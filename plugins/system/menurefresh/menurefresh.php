<?php
/**
 * @copyright	Copyright (C) 2013 Greenleaf Media. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

// no direct access
defined( '_JEXEC' ) or die;

// import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

class plgSystemMenurefresh extends JPlugin {

	/**
	 * Array of paths needed by the installer
	 *
	 * @var    array
	 * @since  12.1
	 */
	protected $paths = array();
		
 	/**
 	 * The installation manifest XML object
	 * @var    object
	 * @since  3.1
	 */
	public $manifest = null;
	
	/**
	 * True if existing files can be overwritten
	 * @var    boolean
	 * @since  12.1
	 */
	protected $overwrite = false;
	
	/**
	 * True if package is an upgrade
	 *
	 * @var    boolean
	 * @since  12.1
	 */
	protected $upgrade = null;
	
	/**
	 * Stack of installation steps
	 * - Used for installation rollback
	 *
	 * @var    array
	 * @since  12.1
	 */
	protected $stepStack = array();
	
	function onBeforeRender() {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		//Check if in right view
		$extension = JRequest::get();
		
		if ($extension['option'] != "com_installer" || !isset($extension['view']) || $extension['view'] != "manage" || $app->isAdmin() === false) {
			return;
		}
		
		JToolbarHelper::custom('menurefresh.refresh', 'refresh', 'refresh', 'Refresh Menu', true);
		
		return;
		
		
	}
	
	function onAfterRoute() {
		$app = JFactory::getApplication();		
		$request = JRequest::get();
		
		if (isset($request['task'])) {
			$task = $request['task'];
		}else {
			return;
		}
		
		if ($task != "menurefresh.refresh" || $app->isAdmin() === false) {
			return;
		}
		
		$extensions = $request['cid'];
		
		if (sizeof($extensions) > 1) {
			JLog::add(JText::_('Can only refresh one menu at a time'), JLog::WARNING, 'jerror');
			return;
		}
		foreach ($extensions as $id) {
			// Time to build the admin menus
			if (!$this->_refreshAdminMenus($id))
			{
				JLog::add(JText::_('JLIB_INSTALLER_ABORT_COMP_BUILDADMINMENUS_FAILED'), JLog::WARNING, 'jerror');
			}		
		}
		
		//Remove task so we get normal routing
		JRequest::setVar("task",null);
	}
	
	 /* Method to build menu database entries for a component
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   3.1
	 */
	protected function _refreshAdminMenus($id)
	{
		$db = JFactory::getDbo();
		$table = JTable::getInstance('menu');

		// Get the ids of the menu items
		$query = $db->getQuery(true)
			->select('id')
			->from('#__menu')
			->where($db->quoteName('client_id') . ' = 1')
			->where($db->quoteName('component_id') . ' = ' . (int) $id);

		$db->setQuery($query);

		$ids = $db->loadColumn();

		// Check for error
		if (!empty($ids))
		{
			// Iterate the items to delete each one.
			foreach ($ids as $menuid)
			{
				if (!$table->delete((int) $menuid))
				{
					$this->setError($table->getError());
				}
			}
			// Rebuild the whole tree
			$table->rebuild();

		}
		
		//Add menu items back in
		$table = JTable::getInstance('menu');
		
		//Get option
		$query = $db->getQuery(true)
			->select(array('element','type'))
			->from('#__extensions')
			->where('extension_id = ' . $db->quote($id));

		$db->setQuery($query);

		$setting = $db->loadObject();
	
		// Ok, now its time to handle the menus.  Start with the component root menu, then handle submenus.
		$mani_check = $this->findManifest($setting->element,$setting->type);
		if ($mani_check === false) {
			JLog::add(JText::_('Manifest Not Set'), JLog::WARNING, 'jerror');
			return false;
		}
		$menuElement = $this->manifest->administration->menu;

		if ($menuElement)
		{
			$data = array();
			$data['menutype'] = 'main';
			$data['client_id'] = 1;
			$data['title'] = (string) trim($menuElement);
			$data['alias'] = (string) $menuElement;
			$data['link'] = 'index.php?option=' . $setting->element;
			$data['type'] = 'component';
			$data['published'] = 0;
			$data['parent_id'] = 1;
			$data['component_id'] = $id;
			$data['img'] = ((string) $menuElement->attributes()->img) ? (string) $menuElement->attributes()->img : 'class:component';
			$data['home'] = 0;

			try
			{
				$table->setLocation(1, 'last-child');
			}
			catch (InvalidArgumentException $e)
			{
				JLog::add($e->getMessage(), JLog::WARNING, 'jerror');

				return false;
			}

			if (!$table->bind($data) || !$table->check() || !$table->store())
			{
				// The menu item already exists. Delete it and retry instead of throwing an error.
				$query->clear()
					->select('id')
					->from('#__menu')
					->where('menutype = ' . $db->quote('main'))
					->where('client_id = 1')
					->where('link = ' . $db->quote('index.php?option=' . $setting->element))
					->where('type = ' . $db->quote('component'))
					->where('parent_id = 1')
					->where('home = 0');

				$db->setQuery($query);
				$menu_id = $db->loadResult();

				if (!$menu_id)
				{
					// Oops! Could not get the menu ID. Go back and rollback changes.
					JError::raiseWarning(1, $table->getError());

					return false;
				}
				else
				{
					// Remove the old menu item
					$query->clear()
						->delete('#__menu')
						->where('id = ' . (int) $menu_id);

					$db->setQuery($query);
					$db->query();

					// Retry creating the menu item
					$table->setLocation(1, 'last-child');

					if (!$table->bind($data) || !$table->check() || !$table->store())
					{
						// Install failed, warn user and rollback changes
						JError::raiseWarning(1, $table->getError());

						return false;
					}
				}
			}

			/*
			 * Since we have created a menu item, we add it to the installation step stack
			 * so that if we have to rollback the changes we can undo it.
			 */
			$this->pushStep(array('type' => 'menu', 'id' => $id));
		}
		// No menu element was specified, Let's make a generic menu item
		else
		{
			$data = array();
			$data['menutype'] = 'main';
			$data['client_id'] = 1;
			$data['title'] = $setting->element;
			$data['alias'] = $setting->element;
			$data['link'] = 'index.php?option=' . $setting->element;
			$data['type'] = 'component';
			$data['published'] = 0;
			$data['parent_id'] = 1;
			$data['component_id'] = id;
			$data['img'] = 'class:component';
			$data['home'] = 0;

			try
			{
				$table->setLocation(1, 'last-child');
			}
			catch (InvalidArgumentException $e)
			{
				JLog::add($e->getMessage(), JLog::WARNING, 'jerror');

				return false;
			}

			if (!$table->bind($data) || !$table->check() || !$table->store())
			{
				// Install failed, warn user and rollback changes
				JLog::add($table->getError(), JLog::WARNING, 'jerror');

				return false;
			}

			/*
			 * Since we have created a menu item, we add it to the installation step stack
			 * so that if we have to rollback the changes we can undo it.
			 */
			$this->parent->pushStep(array('type' => 'menu', 'id' => $id));
		}

		/*
		 * Process SubMenus
		 */

		if (!$this->manifest->administration->submenu)
		{
			return true;
		}

		$parent_id = $table->id;

		foreach ($this->manifest->administration->submenu->menu as $child)
		{
			$data = array();
			$data['menutype'] = 'main';
			$data['client_id'] = 1;
			$data['title'] = (string) trim($child);
			$data['alias'] = (string) $child;
			$data['type'] = 'component';
			$data['published'] = 0;
			$data['parent_id'] = $parent_id;
			$data['component_id'] = $id;
			$data['img'] = ((string) $child->attributes()->img) ? (string) $child->attributes()->img : 'class:component';
			$data['home'] = 0;

			// Set the sub menu link
			if ((string) $child->attributes()->link)
			{
				$data['link'] = 'index.php?' . $child->attributes()->link;
			}
			else
			{
				$request = array();

				if ((string) $child->attributes()->act)
				{
					$request[] = 'act=' . $child->attributes()->act;
				}

				if ((string) $child->attributes()->task)
				{
					$request[] = 'task=' . $child->attributes()->task;
				}

				if ((string) $child->attributes()->controller)
				{
					$request[] = 'controller=' . $child->attributes()->controller;
				}

				if ((string) $child->attributes()->view)
				{
					$request[] = 'view=' . $child->attributes()->view;
				}

				if ((string) $child->attributes()->layout)
				{
					$request[] = 'layout=' . $child->attributes()->layout;
				}

				if ((string) $child->attributes()->sub)
				{
					$request[] = 'sub=' . $child->attributes()->sub;
				}

				$qstring = (count($request)) ? '&' . implode('&', $request) : '';
				$data['link'] = 'index.php?option=' . $option . $qstring;
			}

			$table = JTable::getInstance('menu');

			try
			{
				$table->setLocation($parent_id, 'last-child');
			}
			catch (InvalidArgumentException $e)
			{
				return false;
			}

			if (!$table->bind($data) || !$table->check() || !$table->store())
			{
				// Install failed, rollback changes
				return false;
			}

			/*
			 * Since we have created a menu item, we add it to the installation step stack
			 * so that if we have to rollback the changes we can undo it.
			 */
			$this->pushStep(array('type' => 'menu', 'id' => $id));
		}

		return true;
	}

	/*
	* Tries to find the package manifest file
	 *
	 * @return  boolean  True on success, False on error
	 *
	 * @since 3.1
	 */
	public function findManifest($element,$type)
	{
		$type_path = '';
		if (strcasecmp($type,"component") == 0) {
			$type_path = "components";
		}
		
		$path = JPATH_ADMINISTRATOR . DS . $type_path . DS . $element;
	
		// Main folder manifests (higher priority)
		$parentXmlfiles = JFolder::files($path, '.xml$', false, true);

		// Search for children manifests (lower priority)
		$allXmlFiles    = JFolder::files($path, '.xml$', 1, true);

		// Create an unique array of files ordered by priority
		$xmlfiles = array_unique(array_merge($parentXmlfiles, $allXmlFiles));

		// If at least one XML file exists
		if (!empty($xmlfiles))
		{

			foreach ($xmlfiles as $file)
			{
				// Is it a valid Joomla installation manifest file?
				$manifest = $this->isManifest($file);

				if (!is_null($manifest))
				{
					// If the root method attribute is set to upgrade, allow file overwrite
					if ((string) $manifest->attributes()->method == 'upgrade')
					{
						$this->upgrade = true;
						$this->overwrite = true;
					}

					// If the overwrite option is set, allow file overwriting
					if ((string) $manifest->attributes()->overwrite == 'true')
					{
						$this->overwrite = true;
					}

					// Set the manifest object and path
					$this->manifest = $manifest;
					$this->setPath('manifest', $file);

					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));

					return true;
				}
			}

			// None of the XML files found were valid install files
			JLog::add(JText::_('JLIB_INSTALLER_ERROR_NOTFINDJOOMLAXMLSETUPFILE'), JLog::WARNING, 'jerror');

			return false;
		}
		else
		{
			// No XML files were found in the install folder
			JLog::add(JText::_('JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE'), JLog::WARNING, 'jerror');

			return false;
		}
	}
	
	/**
	 * Is the XML file a valid Joomla installation manifest file.
	 *
	 * @param   string  $file  An xmlfile path to check
	 *
	 * @return  mixed  A SimpleXMLElement, or null if the file failed to parse
	 *
	 * @since   3.1
	 */
	public function isManifest($file)
	{
		$xml = simplexml_load_file($file);

		// If we cannot load the XML file return null
		if (!$xml)
		{
			return null;
		}

		// Check for a valid XML root tag.
		if ($xml->getName() != 'extension')
		{
			return null;
		}

		// Valid manifest file return the object
		return $xml;
	}
		
	/**
	 * Sets an installer path by name
	 *
	 * @param   string  $name   Path name
	 * @param   string  $value  Path
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function setPath($name, $value)
	{
		$this->paths[$name] = $value;
	}
	
	/**
	 * Pushes a step onto the installer stack for rolling back steps
	 *
	 * @param   array  $step  Installer step
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function pushStep($step)
	{
		$this->stepStack[] = $step;
	}	
}
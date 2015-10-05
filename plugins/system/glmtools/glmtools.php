<?php
/**
 * @copyright	Copyright (C) 2014 Greenleaf Media. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

// no direct access
defined( '_JEXEC' ) or die;

// import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

class plgSystemGlmtools extends JPlugin {

	function onAfterInitialise() {
		//reverse compatibility for DS
		if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	}
}
<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'GP Lenses Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editList();
	JToolBarHelper::addNew();
	
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="index.php?option=com_gplens" method="post" name="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->lenses)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="articleList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th width="1%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="3%" align="center" style="whitespace: pre-line;">Name</th>
						<th width="2%" align="center" style="whitespace: pre-line;">Aliases</th>
						<th width="1%" align="center" style="whitespace: pre-line;">Lab</th>
						<th width="4%" align="center" style="whitespace: pre-line;">Material Text</th>
						<th width="4%" align="center" style="whitespace: pre-line;">Category</th>
						<th width="4%" align="center" style="whitespace: pre-line;">Subcat.</th>
						<th width="3%" align="center" style="whitespace: pre-line;">Add Power</th>
						<th width="2%" align="center" style="whitespace: pre-line;">Diameter</th>
						<th width="1%" align="center" style="whitespace: pre-line;">Base Curve</th>
						<th width="2%" align="center" style="whitespace: pre-line;">Power</th>
						<th width="3%" align="center" style="whitespace: pre-line;">Center Thickness</th>
						<th width="3%" align="center" style="whitespace: pre-line;">Optic Zone</th>
						<th width="2%" align="center" style="whitespace: pre-line;">Discont.</th>
						<th width="2%" align="center" style="whitespace: pre-line;">Display</th>
						<th width="5%" align="center" style="whitespace: pre-line;">Updated</th>
						<th width="1%" align="center" style="whitespace: pre-line;">tid</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="13">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->lenses ); $i < $n; $i++)
				{
					$row = &$this->lenses[$i];

					$link = JRoute::_( 'index.php?option=com_gplens&view=lens&task=edit&cid[]='. $row->tid );
					$checked 	= JHTML::_('grid.id', $i, $row->tid );
				?>
					<tr class="<?php echo "row$k"; ?>">
						<td>
							<?php echo $this->pagination->getRowOffset( $i ); ?>
						</td>
						<td align="center">
							<?php echo $checked; ?>
						</td>
						<td>
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Lens' );?>::<?php echo htmlspecialchars($row->name); ?>">
							<a href="<?php echo $link  ?>">
								<strong><?php echo htmlspecialchars($row->name); ?></strong></a></span>
						</td>
						<td align="center">
							<?php echo $row->aliases;?>
						</td>
						<td align="center">
							<?php echo $row->companyname; ?>
						</td>
						<td align="center">
							<?php echo $row->materialText; ?>
						</td>
						<td align="center">
							<?php echo $row->designcategory; ?>
						</td>
						<td align="center">
							<?php echo $row->subcategory; ?>
						</td>
						<td align="center">
							<?php echo $row->addPower; ?>
						</td>
						<td align="center">
							<?php echo $row->diameter; ?>
						</td>
						<td align="center">
							<?php echo $row->baseCurve; ?>
						</td>
						<td align="center">
							<?php echo $row->power; ?>
						</td>
						<td align="center">
							<?php echo $row->centerThickness; ?>
						</td>
						<td align="center">
							<?php echo $row->opticZone; ?>
						</td>
						<td align="center">
							<?php echo $row->discontinued == 1 ? 'Yes' : 'No'; ?>
						</td>
						<td align="center">
							<?php echo $row->display == 1 ? 'Yes' : 'No'; ?>
						</td>
						<td align="center">
							<?php echo $row->updated; ?>
						</td>
						<td align="center">
							<?php echo $row->tid; ?>
						</td>
					</tr>
				<?php
					$k = 1 - $k;
					}
				?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>

	<input type="hidden" name="option" value="com_gplens" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
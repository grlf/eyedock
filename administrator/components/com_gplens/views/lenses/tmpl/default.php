<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'GP Lenses Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
?>

<form action="index.php?option=com_gplens" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
	</tr>
</table>
<div id="tablecell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="1%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->lenses ); ?>);" />
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
			<th width="3%" align="center" style="whitespace: pre-line;">Optic Zone</th><!--
			<th width="4%" align="center" style="whitespace: pre-line;">URL</th>
			<th width="4%" align="center" style="whitespace: pre-line;">PDF</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Image</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Other Info</th>
			<th width="3%" align="center" style="whitespace: pre-line;">Cost</th>-->
			<th width="2%" align="center" style="whitespace: pre-line;">Discont.</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Display</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Updated</th>
			<th width="1%" align="center" style="whitespace: pre-line;">tid</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="18">
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
			</td><!--
			<td align="center" style="whitespace: pre-line;">
				<a href="<?php echo $row->url; ?>"><?php echo substr($row->url, 0, 5) . '...'; ?></a>
			</td>
			<td align="center" style="whitespace: pre-line;">
				<?php echo substr($row->pdf, 0, 5) . '...'; ?>
			</td>
			<td align="center">
				<?php echo substr($row->image, 0, 5) . '...'; ?>
			</td>
			<td align="left">
				<?php echo substr($row->otherInfo, 0, 50) . '...'; ?>
			</td>
			<td align="center">
				<?php echo $row->cost; ?>
			</td>-->
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
</div>

	<input type="hidden" name="option" value="com_gplens" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'GP Materials Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
?>

<form action="index.php?option=com_gpmaterial" method="post" name="adminForm">
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->materials ); ?>);" />
			</th>
			<th width="8%" align="center" style="whitespace: pre-line;">Name</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Material Type</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Material Company</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Dk</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Wetting Angle</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Refractive Index</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Specific Gravity</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Colors</th>
			<th width="5%" align="center" style="whitespace: pre-line;">UV Colors</th>
			<th width="5%" align="center" style="whitespace: pre-line;">URL</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Other Information</th>
			<th width="3%" align="center" style="whitespace: pre-line;">tid</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="14">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->materials ); $i < $n; $i++)
	{
		$row = &$this->materials[$i];

		$link = JRoute::_( 'index.php?option=com_gpmaterial&view=material&task=edit&cid[]='. $row->tid );
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
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Material' );?>::<?php echo htmlspecialchars($row->name); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->name); ?></strong></a></span>
			</td>
			<td align="center">
				<?php echo $row->materialtype;?>
			</td>
			<td align="center">
				<?php echo $row->materialcompany; ?>
			</td>
			<td align="center">
				<?php echo $row->dk; ?>
			</td>
			<td align="center">
				<?php echo $row->wetAngle; ?>
			</td>
			<td align="center">
				<?php echo $row->refractiveIndex; ?>
			</td>
			<td align="center">
				<?php echo $row->specificGravity; ?>
			</td>
			<td align="center">
				<?php echo $row->colors; ?>
			</td>
			<td align="center">
				<?php echo $row->colorsUV; ?>
			</td>
			<td align="center" style="whitespace: pre-line;">
				<a href="<?php echo $row->url; ?>"><?php echo $row->url; ?></a>
			</td>
			<td align="left">
				<?php echo $row->otherInfo; ?>
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

	<input type="hidden" name="option" value="com_gpmaterial" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
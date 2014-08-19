<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'Soft Lenses Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
?>

<form action="index.php?option=com_gpmaterial" method="post" name="adminForm" id='adminForm'>
<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="" class="text_area" onchange="document.adminForm.submit();" />
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
			
			<th width="8%" align="center" style="whitespace: pre-line;">Name</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Company</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Toric</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Bifocal</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Cosmetic</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Updated</th>
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
	for ($i=0, $n=count( $this->lenses ); $i < $n; $i++)
	{
		$row = &$this->lenses[$i];

		$link = JRoute::_( 'index.php?option=com_pnlenses&view=lens&task=edit&cid[]='. $row->pn_tid );
		$checked 	= JHTML::_('grid.id', $i, $row->pn_tid );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Lens' );?>::<?php echo htmlspecialchars($row->pn_name); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->pn_name); ?></strong></a></span>
					<?php if ( $row->discontinued == 1) echo "<br/>(discontinued)"; ?>
					<?php if ( $row->display != 1) echo "<br/>(not displayed)"; ?>
			</td>

			<td align="center">
				<a href="<?php echo $row->pn_website; ?>"><?php echo $row->companyname; ?></a>
			</td>
			<td align="center">
				<?php if ($row->toric == 1) echo "y"; ?>
			</td>
			<td align="center">
				<?php if ( $row->bifocal == 1) echo "y"; ?>
			</td>
			<td align="center">
				<?php if ( $row->cosmetic == 1) echo "y"; ?>
			</td>
			<td align="center">
				<?php echo $row->updated; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_tid; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_pnlenses" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
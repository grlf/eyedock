<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'RX Methods of Action Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
?>

<form action="index.php?option=com_rxmoa" method="post" name="adminForm">
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->moas ); ?>);" />
			</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Name</th>
			<th width="15%" align="center" style="whitespace: pre-line;">Comments</th>
			<th width="1%" align="center" style="whitespace: pre-line;">tid</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->moas ); $i < $n; $i++)
	{
		$row = &$this->moas[$i];

		$link = JRoute::_( 'index.php?option=com_rxmoa&view=moa&task=edit&cid[]='. $row->pn_moa_id );
		$checked 	= JHTML::_('grid.id', $i, $row->pn_moa_id );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit MOA' );?>::<?php echo htmlspecialchars($row->pn_name); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->pn_name); ?></strong></a></span>
			</td>
			<td align="left">
				<?php echo $row->pn_comments; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_moa_id; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_rxmoa" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
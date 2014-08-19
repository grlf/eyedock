<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'Medications Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
?>

<form action="index.php?option=com_rxmeds" method="post" name="adminForm">
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->meds ); ?>);" />
			</th>
			<th width="8%" align="center" style="whitespace: pre-line;">Trade Name</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Company</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Type 1</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Generic?</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Dose</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Comments</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Website</th>
			<th width="3%" align="center" style="whitespace: pre-line;">tid</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->meds ); $i < $n; $i++)
	{
		$row = &$this->meds[$i];

		$link = JRoute::_( 'index.php?option=com_rxmeds&view=med&task=edit&cid[]='. $row->pn_med_id );
		$checked 	= JHTML::_('grid.id', $i, $row->pn_med_id );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Medication' );?>::<?php echo htmlspecialchars($row->pn_trade); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->pn_trade); ?></strong></a></span>
			</td>
			<td align="center">
				<?php echo $row->companyname;?>
			</td>
			<td align="center">
				<?php echo $row->pn_medType1; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_generic; ?>
			</td>
			<td align="center">
				<?php echo substr($row->pn_dose, 0, 100) . '...'; ?>
			</td>
			<td align="center">
				<?php echo substr($row->pn_comments, 0, 100) . '...'; ?>
			</td>
			<td align="center" style="whitespace: pre-line;">
				<a href="<?php echo $row->pn_website; ?>"><?php if($row->pn_med_url != 'http://') { echo substr($row->pn_med_url, 0, 30); } else { echo '-'; }?></a>
			</td>
			<td align="center">
				<?php echo $row->pn_med_id; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_rxmeds" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
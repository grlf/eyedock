<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php

?>


<form action="index.php?option=com_pncompanies" method="post" name="adminForm">
<table>
	<tr>



		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->search);?>" class="text_area" onchange="document.adminForm.submit();" />
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->companies ); ?>);" />
			</th>
			<th width="3%" align="center" style="whitespace: pre-line;">Name</th>
			<th width="1%" align="center" style="whitespace: pre-line;">Logo</th>
			<th width="3%" align="center" style="whitespace: pre-line;">Phone</th>
			<th width="4%" align="center" style="whitespace: pre-line;">Address</th>
			<th width="4%" align="center" style="whitespace: pre-line;">City</th>
			<th width="4%" align="center" style="whitespace: pre-line;">State</th>
			<th width="3%" align="center" style="whitespace: pre-line;">Zip Code</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Website</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Email</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Description</th>
			<th width="2%" align="center" style="whitespace: pre-line;">Hide</th>
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
	for ($i=0, $n=count( $this->companies ); $i < $n; $i++)
	{
		$row = &$this->companies[$i];

		$link = JRoute::_( 'index.php?option=com_pncompanies&view=company&task=edit&cid[]='. $row->pn_comp_tid );
		$checked 	= JHTML::_('grid.id', $i, $row->pn_comp_tid );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Company' );?>::<?php echo htmlspecialchars($row->pn_comp_name); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->pn_comp_name); ?></strong></a></span>
			</td>
			<td align="center">
				<?php echo $row->pn_logo; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_phone; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_address; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_city; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_state; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_zip; ?>
			</td>
			<td align="center">
				<a href="<?php echo $row->pn_url; ?>"><?php echo $row->pn_url; ?></a>
			</td>
			<td align="center">
				<?php echo $row->pn_email; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_comp_desc; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_hide; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_comp_tid; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_pncompanies" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
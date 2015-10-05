<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JSubMenuHelper::addEntry('Soft Lenses', 'index.php?option=com_pnlenses');
	JSubMenuHelper::addEntry('Soft Lenses Companies', 'index.php?option=com_pncompanies');

	JToolBarHelper::title( JText::_( 'Soft Lens Polymers Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editList();
	JToolBarHelper::addNew();

	if(empty($this->lists)){
		$this->lists = array();
	}
?>

<form action="index.php?option=com_gplab" method="post" name="adminForm">
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->polymers ); ?>);" />
			</th>
			<th width="10%" align="center" style="whitespace: pre-line;">Name</th>
			<th width="10%" align="center" style="whitespace: pre-line;">H2O</th>
			<th width="40%" align="center" style="whitespace: pre-line;">FDA</th>
			<th width="40%" align="center" style="whitespace: pre-line;">Dk</th>
			<th width="40%" align="center" style="whitespace: pre-line;">Modulus</th>
			<th width="10%" align="center" style="whitespace: pre-line;">Description</th>
			<th width="1%" align="center" style="whitespace: pre-line;">tid</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="7">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->polymers ); $i < $n; $i++)
	{
		$row = &$this->polymers[$i];

		$link = JRoute::_( 'index.php?option=com_pnpolymers&view=polymer&task=edit&cid[]='. $row->pn_poly_tid );
		$checked 	= JHTML::_('grid.id', $i, $row->pn_poly_tid );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Polymer' );?>::<?php echo htmlspecialchars($row->pn_poly_name); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->pn_poly_name); ?></strong></a></span>
			</td>
			<td align="center">
				<?php echo $row->pn_h2o; ?>
			</td>
			<td align="left">
				<?php echo $row->pn_fda_grp; ?>
			</td>
			<td align="left">
				<?php echo $row->pn_poly_dk; ?>
			</td>
			<td align="left">
				<?php echo $row->pn_poly_modulus; ?>
			</td>
			<td align="left">
				<?php echo $row->pn_poly_desc; ?>
			</td>
			<td align="center">
				<?php echo $row->pn_poly_tid; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_pnpolymers" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
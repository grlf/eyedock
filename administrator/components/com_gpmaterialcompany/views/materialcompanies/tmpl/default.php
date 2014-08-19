<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'GP Material Companies Administration' ), 'generic.png' );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
?>

<form action="index.php?option=com_gpmaterialcompany" method="post" name="adminForm">
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
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->companies ); ?>);" />
			</th>
			<th width="10%" align="center" style="whitespace: pre-line;">Name</th>
			<th width="5%" align="center" style="whitespace: pre-line;">Phone</th>
			<th width="10%" align="center" style="whitespace: pre-line;">Website</th>
			<th width="17%" align="center" style="whitespace: pre-line;">Other Info</th>
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
	for ($i=0, $n=count( $this->companies ); $i < $n; $i++)
	{
		$row = &$this->companies[$i];

		$link = JRoute::_( 'index.php?option=com_gpmaterialcompany&view=materialcompany&task=edit&cid[]='. $row->tid );
		$checked 	= JHTML::_('grid.id', $i, $row->tid );
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Material Company' );?>::<?php echo htmlspecialchars($row->name); ?>">
				<a href="<?php echo $link  ?>">
					<strong><?php echo htmlspecialchars($row->name); ?></strong></a></span>
			</td>
			<td align="center">
				<?php echo $row->phone; ?>
			</td>
			<td align="center">
				<a href="<?php echo $row->url; ?>"><?php echo $row->url; ?></a>
			</td>
			<td align="center">
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

	<input type="hidden" name="option" value="com_gpmaterialcompany" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
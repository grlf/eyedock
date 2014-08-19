<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$id = JRequest::getVar( 'tid', array(0), '', 'array' );
	$edit = JRequest::getVar( 'edit', true );
	JArrayHelper::toInteger($id, array(0));

	$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

	JToolBarHelper::title(  JText::_( 'Polymer' ).': <small><small>[ ' . $text.' ]</small></small>' , 'generic.png' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	if ($edit) {
		// for existing items the button is renamed `close`
		JToolBarHelper::cancel( 'cancel', 'Close' );
	} else {
		JToolBarHelper::cancel();
	}
?>

<?php
JFilterOutput::objectHTMLSafe( $this->polymer, ENT_QUOTES );
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		// do field validation
		/*
		if (form.title.value == "") {
			alert( "<?php echo JText::_( 'Poll must have a title', true ); ?>" );
		} else if (isNaN(parseInt( form.lag.value ) ) || parseInt(form.lag.value) < 1)  {
			alert( "<?php echo JText::_( 'Poll must have a non-zero lag time', true ); ?>" );
		//} else if (form.menu.options.value == ""){
		//	alert( "Poll must have pages." );
		//} else if (form.adminForm.textfieldcheck.value == 0){
		//	alert( "Poll must have options." );*/
		} else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php" method="post" name="adminForm">
<div class="col width-45">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Polymer Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->polymer->pn_poly_name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="h2o">
					<?php echo JText::_( 'H2O' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="h2o" id="h2o" size="15" value="<?php echo $this->polymer->pn_h2o; ?>" />% (only enter numbers in the box)	
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="fda">
					<?php echo JText::_( 'FDA' ); ?>:
				</label>
			</td>
			<td>
			
				<select name="fda" id="fda" width="200">
				
						<option value="1" <?php if($this->polymer->pn_fda_grp == 1) { echo 'SELECTED'; }  ?>>1. Low Water (<50 percent water content), Nonionic Polymers.</option>
				
						<option value="2" <?php if($this->polymer->pn_fda_grp == 2) { echo 'SELECTED'; }  ?>>2. High Water (greater than 50 percent water content), Nonionic Polymers.</option>
				
						<option value="3" <?php if($this->polymer->pn_fda_grp == 3) { echo 'SELECTED'; }  ?>>3. Low Water (less then 50 percent water content), Ionic Polymers.</option>
				
						<option value="4" <?php if($this->polymer->pn_fda_grp == 4) { echo 'SELECTED'; }  ?>>4. High Water (greater then 50 percent water content), Ionic Polymers.</option>
			
				</select>
		
			</td>
		</tr>
		
		<tr>
			<td width="110" class="key">
				<label for="dk">
					<?php echo JText::_( 'Dk' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="dk" id="dk" size="15" value="<?php echo $this->polymer->pn_poly_dk; ?>" />
			</td>
		</tr>
		
		
				<tr>
			<td width="110" class="key">
				<label for="MODULUS">
					<?php echo JText::_( 'Modulus' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="modulus" id="modulus" size="15" value="<?php echo $this->polymer->pn_poly_modulus; ?>" />
			</td>
		</tr>
		
		<tr>
			<td width="110" class="key">
				<label for="description">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="description" id="description" size="60" rows="6" cols="45"><?php echo $this->polymer->pn_poly_desc; ?></textarea>
			</td>
		</tr>	
	</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_pnpolymers" />
	<input type="hidden" name="id" value="<?php echo $this->polymer->pn_poly_tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>
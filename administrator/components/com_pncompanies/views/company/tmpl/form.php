<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$id = JRequest::getVar( 'tid', array(0), '', 'array' );
	$edit = JRequest::getVar( 'edit', true );
	JArrayHelper::toInteger($id, array(0));

	$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

	JToolBarHelper::title(  JText::_( 'Company' ).': <small><small>[ ' . $text.' ]</small></small>' , 'generic.png' );
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
JFilterOutput::objectHTMLSafe( $this->company, ENT_QUOTES );
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
	<legend><?php echo JText::_( 'Company Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->company->pn_comp_name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="name_short">
					<?php echo JText::_( 'Short Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name_short" id="name_short" size="60" value="<?php echo $this->company->pn_comp_name_short; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="logo">
					<?php echo JText::_( 'Logo' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="logo" id="logo" size="60" value="<?php echo $this->company->pn_logo; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="phone">
					<?php echo JText::_( 'Phone' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="phone" id="phone" size="60" value="<?php echo $this->company->pn_phone ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="address">
					<?php echo JText::_( 'Address' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="address" id="address" size="60" value="<?php echo $this->company->pn_address ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="city">
					<?php echo JText::_( 'City' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="city" id="city" size="60" value="<?php echo $this->company->pn_city ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="state">
					<?php echo JText::_( 'State' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="state" id="state" size="60" value="<?php echo $this->company->pn_state ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="zip">
					<?php echo JText::_( 'Zip' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="zip" id="zip" size="60" value="<?php echo $this->company->pn_zip ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="website">
					<?php echo JText::_( 'Website' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="website" id="website" size="60" value="<?php echo $this->company->pn_url; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="email">
					<?php echo JText::_( 'Email' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="email" id="email" size="60" value="<?php echo $this->company->pn_email ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="description">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="description" id="description" size="60" rows="6" cols="45"><?php echo $this->company->pn_comp_desc; ?></textarea>
			</td>
		</tr>	
		
		<tr>
			<td width="110" class="key">
				<label for="contactNameF">
					<?php echo JText::_( 'Contact First Name' ); ?>:
				</label>
			<td>
				<input class="inputbox" type="text" name="contactNameF" id="contactNameF" size="60" value="<?php echo $this->company->pn_contact_nameF ?>" />	
			</td>
		</tr>
		<tr>
			<td width="110" class="key">	
				<label for="contactNameL">
					<?php echo JText::_( 'Contact LastName' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="contactNameL" id="contactNameL" size="60" value="<?php echo $this->company->pn_contact_nameL ?>" />
			</td>
		</tr>
		
		<tr>
			<td width="110" class="key">
				<label for="contactEmail">
					<?php echo JText::_( 'Contact Email' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="contactEmail" id="contactEmail" size="60" value="<?php echo $this->company->pn_contact_email ?>" />
			</td>
		</tr>
		
		
		<tr>
			<td width="110" class="key">
				<label for="lastEmail	">
					<?php echo JText::_( 'Last Report Emailed' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->company->pn_last_email ?> (updates automatically)
			</td>
		</tr>
		
		
		
		<tr>
			<td width="110" class="key">
				<label for="emailInterval">
					<?php echo JText::_( 'Report interval' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="emailInterval" id="emailInterval" size="5" value="<?php echo $this->company->pn_email_interval ?>" />
			</td>
		</tr>
		
		<tr>
			<td width="110" class="key">
				<label for="hide">
					<?php echo JText::_( 'Hide' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="hide">
					<option value="1" <?php if($this->company->pn_hide == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->company->pn_hide == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>
					
		
			</td>
		</tr>	
	</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_pncompanies" />
	<input type="hidden" name="id" value="<?php echo $this->company->pn_comp_tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>
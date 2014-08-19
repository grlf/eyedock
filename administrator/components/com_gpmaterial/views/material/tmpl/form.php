<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$id = JRequest::getVar( 'tid', array(0), '', 'array' );
	$edit = JRequest::getVar( 'edit', true );
	JArrayHelper::toInteger($id, array(0));

	$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

	JToolBarHelper::title(  JText::_( 'Material' ).': <small><small>[ ' . $text.' ]</small></small>' , 'generic.png' );
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
JFilterOutput::objectHTMLSafe( $this->material, ENT_QUOTES );
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		var errors = [];
		var errorstring = '';
		
		if (form.specificgravity.value != '' && (form.specificgravity.value > 1.21 || form.specificgravity.value < 1.0)) {
			errors.include('Specific gravity must be between 1.0 and 1.21');
		}
		
		if (form.refractiveindex.value != '' && (form.refractiveindex.value > 1.56 || form.refractiveindex.value < 1.4)) {
			errors.include('Refractive index must be between 1.4 and 1.56');
		}
		
		if (form.wettingangle.value != '' && (form.wettingangle.value > 50 || form.wettingangle.value < 0)) {
			errors.include('Wetting angle must be between 0 and 50');
		}
		
		if (form.dk.value != '' && (form.dk.value > 50 || form.dk.value < 0)) {
			errors.include('Dk must be between 0 and 100');
		}
		
		errorstring = errors.join('\n');
		
		if(errors.length > 0) {
			alert(errorstring);		
			var errors = [];
			var errorstring = '';
		}
		
		else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php" method="post" name="adminForm">
<div class="col width-45">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Material Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->material->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="materialtype">
					<?php echo JText::_( 'Material Type' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="materialtype">
					<?php foreach($this->materialtypes as $type) { ?>
						<option value="<?php echo $type->tid; ?>" <?php if($this->material->materialTypeID == $type->tid) { echo 'SELECTED'; } ?>><?php echo $type->name; ?></option>
					<?php } ?>
				</select>
					
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="materialcompany">
					<?php echo JText::_( 'Material Company' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="materialcompany">
					<?php foreach($this->materialcompanies as $type){ ?>
						<option value="<?php echo $type->tid;?>"<?php if($this->material->rgpMaterialCompanyID == $type->tid) { echo 'SELECTED'; }  ?>><?php echo $type->name; ?></option>
					<?php } ?>
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
				<input class="inputbox" type="text" name="dk" id="dk" size="60" value="<?php echo $this->material->dk; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="wettingangle">
					<?php echo JText::_( 'Wetting Angle' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="wettingangle" id="wettingangle" size="60" value="<?php echo $this->material->wetAngle; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="refractiveindex">
					<?php echo JText::_( 'Refractive Index' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="refractiveindex" id="refractiveindex" size="60" value="<?php echo $this->material->refractiveIndex; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="specificgravity">
					<?php echo JText::_( 'Specific Gravity' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="specificgravity" id="specificgravity" size="60" value="<?php echo $this->material->specificGravity; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="colors">
					<?php echo JText::_( 'Colors' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="colors" id="colors" size="60" value="<?php echo $this->material->colors; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="uvcolors">
					<?php echo JText::_( 'UV Colors' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="uvcolors" id="uvcolors" size="60" rows="6" cols="45"><?php echo $this->material->colorsUV; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="url">
					<?php echo JText::_( 'URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="url" id="url" size="60" value="<?php echo $this->material->url; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="otherinfo">
					<?php echo JText::_( 'Other Information' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="otherinfo" id="otherinfo" size="60" value="<?php echo $this->material->url; ?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_gpmaterial" />
	<input type="hidden" name="id" value="<?php echo $this->material->tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>

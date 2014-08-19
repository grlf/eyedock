<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$id = JRequest::getVar( 'tid', array(0), '', 'array' );
	$edit = JRequest::getVar( 'edit', true );
	JArrayHelper::toInteger($id, array(0));

	$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

	JToolBarHelper::title(  JText::_( 'Medication' ).': <small><small>[ ' . $text.' ]</small></small>' , 'generic.png' );
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
JFilterOutput::objectHTMLSafe( $this->med, ENT_QUOTES );
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		/*
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
		*/
		else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php" method="post" name="adminForm">
<div class="col width-45">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Medication Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'Trade Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->med->pn_trade; ?>" />
			</td>
		</tr>		
		<tr>
			<td width="110" class="key">
				<label for="company">
					<?php echo JText::_( 'Company' ); ?>:
				</label>
			</td>
			<td>
				<select name="company">
					<?php foreach($this->companies as $type){ ?>
						<option value="<?php echo $type->pn_comp_id;?>"<?php if($this->med->pn_comp_id == $type->pn_comp_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="type1">
					<?php echo JText::_( 'Type 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="type1" id="type1" size="60" value="<?php echo $this->med->pn_medType1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="type2">
					<?php echo JText::_( 'Type 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="type2" id="type2" size="60" value="<?php echo $this->med->pn_medType2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="preg">
					<?php echo JText::_( 'Pregnancy Class' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="preg" id="preg" size="60" value="<?php echo $this->med->pn_preg; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="schedule">
					<?php echo JText::_( 'Schedule' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="schedule" id="schedule" size="60" value="<?php echo $this->med->pn_schedule; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="generic">
					<?php echo JText::_( 'Generic?' ); ?>:
				</label>
			</td>
			<td>	
				<select name="generic">
						<option value="yes" <?php if($this->med->pn_generic == 'yes') { echo 'SELECTED'; } ?>>Yes</option>
						<option value="no" <?php if($this->med->pn_generic == 'no') { echo 'SELECTED'; } ?>>No</option>
						<option value="unknown" <?php if($this->med->pn_generic == 'unknown') { echo 'SELECTED'; } ?>>Unknown</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="image1">
					<?php echo JText::_( 'Image 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="image1" id="image1" size="60" value="<?php echo $this->med->pn_image1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="image2">
					<?php echo JText::_( 'Image 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="image2" id="image2" size="60" value="<?php echo $this->med->pn_image2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="dose">
					<?php echo JText::_( 'Dose' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="dose" id="dose" size="60" rows="6" cols="45"><?php echo $this->med->pn_dose; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="peds">
					<?php echo JText::_( 'Peds' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="peds" id="peds" size="60" value="<?php echo $this->med->pn_peds; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="pedstext">
					<?php echo JText::_( 'Peds Text' ); ?>:
				</label>
			</td>
			<td>	
				<select name="pedstext">
						<option value="months" <?php if($this->med->pn_ped_text == 'months') { echo 'SELECTED'; } ?>>Months</option>
						<option value="years" <?php if($this->med->pn_ped_text == 'years') { echo 'SELECTED'; } ?>>Years</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="nurse">
					<?php echo JText::_( 'Nurse' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="nurse" id="nurse" size="60" value="<?php echo $this->med->pn_nurse; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="preserve1">
					<?php echo JText::_( 'Preserve 1' ); ?>:
				</label>
			</td>
			<td>
				<select name="preserve1">
						<option value=""></option>
					<?php foreach($this->preserves as $type){ ?>
						<option value="<?php echo $type->pn_pres_id;?>"<?php if($this->med->pn_pres_id1 == $type->pn_pres_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="preserve2">
					<?php echo JText::_( 'Preserve 2' ); ?>:
				</label>
			</td>
			<td>
				<select name="preserve2">
						<option value=""></option>
					<?php foreach($this->preserves as $type){ ?>
						<option value="<?php echo $type->pn_pres_id;?>"<?php if($this->med->pn_pres_id2 == $type->pn_pres_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="comments">
					<?php echo JText::_( 'Comments' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="comments" id="comments" size="60" rows="6" cols="45"><?php echo $this->med->pn_comments; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="pdf">
					<?php echo JText::_( 'PDF' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="pdf" id="pdf" size="60" value="<?php echo $this->med->pn_rxInfo; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="url">
					<?php echo JText::_( 'URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="url" id="url" size="60" value="<?php echo $this->med->pn_med_url; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="display">
					<?php echo JText::_( 'Display?' ); ?>:
				</label>
			</td>
			<td>	
				<select name="display">
						<option value="true" <?php if($this->med->pn_display == 'true') { echo 'SELECTED'; } ?>>Yes</option>
						<option value="false" <?php if($this->med->pn_display == 'false') { echo 'SELECTED'; } ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="conc1">
					<?php echo JText::_( 'Concentration 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conc1" id="conc1" size="60" value="<?php echo $this->med->pn_conc1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="chem1">
					<?php echo JText::_( 'Chemical 1' ); ?>:
				</label>
			</td>
			<td>
				<select name="chem1">
					<option value=""></option>
					<?php foreach($this->chemicals as $type){ ?>
						<option value="<?php echo $type->pn_chem_id;?>"<?php if($this->med->pn_chem_id1 == $type->pn_chem_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="moa1">
					<?php echo JText::_( 'Method of Action 1' ); ?>:
				</label>
			</td>
			<td>
				<select name="moa1">
					<option value=""></option>
					<?php foreach($this->moas as $type){ ?>
						<option value="<?php echo $type->pn_moa_id;?>"<?php if($this->med->pn_moa_id1 == $type->pn_moa_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="conc2">
					<?php echo JText::_( 'Concentration 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conc2" id="conc2" size="60" value="<?php echo $this->med->pn_conc2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="chem2">
					<?php echo JText::_( 'Chemical 2' ); ?>:
				</label>
			</td>
			<td>
				<select name="chem2">
					<option value=""></option>
					<?php foreach($this->chemicals as $type){ ?>
						<option value="<?php echo $type->pn_chem_id;?>"<?php if($this->med->pn_chem_id2 == $type->pn_chem_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="moa2">
					<?php echo JText::_( 'Method of Action 2' ); ?>:
				</label>
			</td>
			<td>
				<select name="moa2">
					<option value=""></option>
					<?php foreach($this->moas as $type){ ?>
						<option value="<?php echo $type->pn_moa_id;?>"<?php if($this->med->pn_moa_id2 == $type->pn_moa_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="conc3">
					<?php echo JText::_( 'Concentration 3' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conc3" id="conc3" size="60" value="<?php echo $this->med->pn_conc3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="chem3">
					<?php echo JText::_( 'Chemical 3' ); ?>:
				</label>
			</td>
			<td>
				<select name="chem3">
					<option value=""></option>
					<?php foreach($this->chemicals as $type){ ?>
						<option value="<?php echo $type->pn_chem_id;?>"<?php if($this->med->pn_chem_id3 == $type->pn_chem_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="moa3">
					<?php echo JText::_( 'Method of Action 3' ); ?>:
				</label>
			</td>
			<td>
				<select name="moa3">
					<option value=""></option>
					<?php foreach($this->moas as $type){ ?>
						<option value="<?php echo $type->pn_moa_id;?>"<?php if($this->med->pn_moa_id3 == $type->pn_moa_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="conc4">
					<?php echo JText::_( 'Concentration 4' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conc4" id="conc4" size="60" value="<?php echo $this->med->pn_conc4; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="chem4">
					<?php echo JText::_( 'Chemical 4' ); ?>:
				</label>
			</td>
			<td>
				<select name="chem4">
					<option value=""></option>
					<?php foreach($this->chemicals as $type){ ?>
						<option value="<?php echo $type->pn_chem_id;?>"<?php if($this->med->pn_chem_id4 == $type->pn_chem_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="moa4">
					<?php echo JText::_( 'Method of Action 4' ); ?>:
				</label>
			</td>
			<td>
				<select name="moa4">
					<option value=""></option>
					<?php foreach($this->moas as $type){ ?>
						<option value="<?php echo $type->pn_moa_id;?>"<?php if($this->med->pn_moa_id4 == $type->pn_moa_id) { echo 'SELECTED'; }  ?>><?php echo $type->pn_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="form1">
					<?php echo JText::_( 'Form 1' ); ?>:
				</label>
			</td>
			<td>
				<select name="form1">
						<option value="solution" <?php if($this->med->pn_form1 == 'solution') { echo 'SELECTED'; } ?>>Solution</option>
						<option value="capsule" <?php if($this->med->pn_form1 == 'capsule') { echo 'SELECTED'; } ?>>Capsule</option>
						<option value="intravenous" <?php if($this->med->pn_form1 == 'intravenous') { echo 'SELECTED'; } ?>>Intravenous</option>
						<option value="suspension" <?php if($this->med->pn_form1 == 'suspension') { echo 'SELECTED'; } ?>>Suspension</option>
						<option value="syrup" <?php if($this->med->pn_form1 == 'syrup') { echo 'SELECTED'; } ?>>Syrup</option>
						<option value="tablet" <?php if($this->med->pn_form1 == 'tablet') { echo 'SELECTED'; } ?>>Tablet</option>
						<option value="ointment" <?php if($this->med->pn_form1 == 'ointment') { echo 'SELECTED'; } ?>>Ointment</option>
						<option value="emulsion" <?php if($this->med->pn_form1 == 'emulsion') { echo 'SELECTED'; } ?>>Emulsion</option>
						<option value="gel" <?php if($this->med->pn_form1 == 'gel') { echo 'SELECTED'; } ?>>Gel</option>
						<option value="other" <?php if($this->med->pn_form1 == 'other') { echo 'SELECTED'; } ?>>Other</option>
						<option value="unknown" <?php if($this->med->pn_form1 == 'unknown') { echo 'SELECTED'; } ?>>Unknown</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="size1">
					<?php echo JText::_( 'Size 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="size1" id="size1" size="60" value="<?php echo $this->med->pn_size1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cost1">
					<?php echo JText::_( 'Cost 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cost1" id="cost1" size="60" value="<?php echo $this->med->pn_cost1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="form2">
					<?php echo JText::_( 'Form 2' ); ?>:
				</label>
			</td>
			<td>
				<select name="form2">
						<option value=""></option>
						<option value="solution" <?php if($this->med->pn_form2 == 'solution') { echo 'SELECTED'; } ?>>Solution</option>
						<option value="capsule" <?php if($this->med->pn_form2 == 'capsule') { echo 'SELECTED'; } ?>>Capsule</option>
						<option value="intravenous" <?php if($this->med->pn_form2 == 'intravenous') { echo 'SELECTED'; } ?>>Intravenous</option>
						<option value="suspension" <?php if($this->med->pn_form2 == 'suspension') { echo 'SELECTED'; } ?>>Suspension</option>
						<option value="syrup" <?php if($this->med->pn_form2 == 'syrup') { echo 'SELECTED'; } ?>>Syrup</option>
						<option value="tablet" <?php if($this->med->pn_form2 == 'tablet') { echo 'SELECTED'; } ?>>Tablet</option>
						<option value="ointment" <?php if($this->med->pn_form2 == 'ointment') { echo 'SELECTED'; } ?>>Ointment</option>
						<option value="emulsion" <?php if($this->med->pn_form2 == 'emulsion') { echo 'SELECTED'; } ?>>Emulsion</option>
						<option value="gel" <?php if($this->med->pn_form2 == 'gel') { echo 'SELECTED'; } ?>>Gel</option>
						<option value="other" <?php if($this->med->pn_form2 == 'other') { echo 'SELECTED'; } ?>>Other</option>
						<option value="unknown" <?php if($this->med->pn_form2 == 'unknown') { echo 'SELECTED'; } ?>>Unknown</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="size2">
					<?php echo JText::_( 'Size 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="size2" id="size2" size="60" value="<?php echo $this->med->pn_size2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cost2">
					<?php echo JText::_( 'Cost 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cost2" id="cost2" size="60" value="<?php echo $this->med->pn_cost2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="form3">
					<?php echo JText::_( 'Form 3' ); ?>:
				</label>
			</td>
			<td>
				<select name="form3">
						<option value=""></option>
						<option value="solution" <?php if($this->med->pn_form3 == 'solution') { echo 'SELECTED'; } ?>>Solution</option>
						<option value="capsule" <?php if($this->med->pn_form3 == 'capsule') { echo 'SELECTED'; } ?>>Capsule</option>
						<option value="intravenous" <?php if($this->med->pn_form3 == 'intravenous') { echo 'SELECTED'; } ?>>Intravenous</option>
						<option value="suspension" <?php if($this->med->pn_form3 == 'suspension') { echo 'SELECTED'; } ?>>Suspension</option>
						<option value="syrup" <?php if($this->med->pn_form3 == 'syrup') { echo 'SELECTED'; } ?>>Syrup</option>
						<option value="tablet" <?php if($this->med->pn_form3 == 'tablet') { echo 'SELECTED'; } ?>>Tablet</option>
						<option value="ointment" <?php if($this->med->pn_form3 == 'ointment') { echo 'SELECTED'; } ?>>Ointment</option>
						<option value="emulsion" <?php if($this->med->pn_form3 == 'emulsion') { echo 'SELECTED'; } ?>>Emulsion</option>
						<option value="gel" <?php if($this->med->pn_form3 == 'gel') { echo 'SELECTED'; } ?>>Gel</option>
						<option value="other" <?php if($this->med->pn_form3 == 'other') { echo 'SELECTED'; } ?>>Other</option>
						<option value="unknown" <?php if($this->med->pn_form3 == 'unknown') { echo 'SELECTED'; } ?>>Unknown</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="size3">
					<?php echo JText::_( 'Size 3' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="size3" id="size3" size="60" value="<?php echo $this->med->pn_size3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cost3">
					<?php echo JText::_( 'Cost 3' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cost3" id="cost3" size="60" value="<?php echo $this->med->pn_cost3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="form4">
					<?php echo JText::_( 'Form 4' ); ?>:
				</label>
			</td>
			<td>
				<select name="form4">
						<option value=""></option>
						<option value="solution" <?php if($this->med->pn_form4 == 'solution') { echo 'SELECTED'; } ?>>Solution</option>
						<option value="capsule" <?php if($this->med->pn_form4 == 'capsule') { echo 'SELECTED'; } ?>>Capsule</option>
						<option value="intravenous" <?php if($this->med->pn_form4 == 'intravenous') { echo 'SELECTED'; } ?>>Intravenous</option>
						<option value="suspension" <?php if($this->med->pn_form4 == 'suspension') { echo 'SELECTED'; } ?>>Suspension</option>
						<option value="syrup" <?php if($this->med->pn_form4 == 'syrup') { echo 'SELECTED'; } ?>>Syrup</option>
						<option value="tablet" <?php if($this->med->pn_form4 == 'tablet') { echo 'SELECTED'; } ?>>Tablet</option>
						<option value="ointment" <?php if($this->med->pn_form4 == 'ointment') { echo 'SELECTED'; } ?>>Ointment</option>
						<option value="emulsion" <?php if($this->med->pn_form4 == 'emulsion') { echo 'SELECTED'; } ?>>Emulsion</option>
						<option value="gel" <?php if($this->med->pn_form4 == 'gel') { echo 'SELECTED'; } ?>>Gel</option>
						<option value="other" <?php if($this->med->pn_form4 == 'other') { echo 'SELECTED'; } ?>>Other</option>
						<option value="unknown" <?php if($this->med->pn_form4 == 'unknown') { echo 'SELECTED'; } ?>>Unknown</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="size4">
					<?php echo JText::_( 'Size 4' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="size4" id="size4" size="60" value="<?php echo $this->med->pn_size4; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cost4">
					<?php echo JText::_( 'Cost 4' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cost4" id="cost4" size="60" value="<?php echo $this->med->pn_cost3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="updated">
					<?php echo JText::_( 'Updated (updates automatically)' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->med->pn_updated; ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_rxmeds" />
	<input type="hidden" name="id" value="<?php echo $this->med->pn_med_id; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>

<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$id = JRequest::getVar( 'tid', array(0), '', 'array' );
	$edit = JRequest::getVar( 'edit', true );
	JArrayHelper::toInteger($id, array(0));

	$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

	JToolBarHelper::title(  JText::_( 'Lens' ).': <small><small>[ ' . $text.' ]</small></small>' , 'generic.png' );
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
//JFilterOutput::objectHTMLSafe( $this->lens, ENT_QUOTES, 'pn_other_info' );
JFilterOutput::objectHTMLSafe( $this->lens, ENT_QUOTES, array('pn_cyl_notes','pn_other_info', 'pn_price', 'pn_sph_notes') );
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
	<legend><?php echo JText::_( 'Lens Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->lens->pn_name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="aliases">
					<?php echo JText::_( 'Aliases' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="aliases" id="aliases" size="60" rows="6" cols="45"><?php echo $this->lens->pn_aliases; ?></textarea>
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
						<option value="<?php echo $type->pn_comp_tid;?>"<?php if($this->lens->pn_comp_id == $type->pn_comp_tid) { echo 'SELECTED'; }  ?>><?php echo $type->pn_comp_name; ?></option>
					<?php } ?>
				</select>
					

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="polymer">
					<?php echo JText::_( 'Polymer' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="polymer">
					<?php foreach($this->polymers as $type){ ?>
						<option value="<?php echo $type->pn_poly_tid;?>"<?php if($this->lens->pn_poly_id == $type->pn_poly_tid) { echo 'SELECTED'; }  ?>><?php echo $type->pn_poly_name; ?></option>
					<?php } ?>
				</select>
					

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="visitint">
					<?php echo JText::_( 'Visit INT' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="visitint">
					<option value="1" <?php if($this->lens->pn_visitint == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_visitint == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>
					

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="ew">
					<?php echo JText::_( 'Extended Wear' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="ew">
					<option value="1" <?php if($this->lens->pn_ew == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_ew == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>
					

			</td>
		</tr>		
		<tr>
			<td width="110" class="key">
				<label for="centerthickness">
					<?php echo JText::_( 'Center Thickness' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="centerthickness" id="centerthickness" size="60" value="<?php echo $this->lens->pn_ct; ?>" />
			</td>
		</tr>	
		<tr>
			<td width="110" class="key">
				<label for="dk">
					<?php echo JText::_( 'Dk' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="dk" id="dk" size="60" value="<?php echo $this->lens->pn_dk; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="opticzone">
					<?php echo JText::_( 'Optic Zone' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="opticzone" id="opticzone" size="60" value="<?php echo $this->lens->pn_oz; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="processtext">
					<?php echo JText::_( 'Process Text' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="processtext" id="processtext" size="60" value="<?php echo $this->lens->pn_process_text; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="processsimple">
					<?php echo JText::_( 'Process Simple' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="processsimple">
					<option value="unknown" <?php if($this->lens->pn_process_simple == 'unknown') { echo 'SELECTED'; }  ?>>Unknown</option>
					<option value="lathe-cut" <?php if($this->lens->pn_process_simple == 'lathe-cut') { echo 'SELECTED'; }  ?>>Lathe-cut</option>
					<option value="molded" <?php if($this->lens->pn_process_simple == 'molded') { echo 'SELECTED'; }  ?>>Molded</option>
					<option value="spin-cast" <?php if($this->lens->pn_process_simple == 'spin-cast') { echo 'SELECTED'; }  ?>>spin-cast</option>
				</select>
									
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="qty">
					<?php echo JText::_( 'Qty' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="qty" id="qty" size="60" value="<?php echo $this->lens->pn_qty; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="replacesimple">
					<?php echo JText::_( 'Replace Simple' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="replacesimple" id="replacesimple" size="60" value="<?php echo $this->lens->pn_replace_simple; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="replacetext">
					<?php echo JText::_( 'Replace Text' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="replacetext" id="replacetext" size="60" value="<?php echo $this->lens->pn_replace_text; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="wear">
					<?php echo JText::_( 'Wear' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="wear" id="wear" size="60" value="<?php echo $this->lens->pn_wear; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="price">
					<?php echo JText::_( 'Price' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="price" id="price" size="60" rows="6" cols="45"><?php echo $this->lens->pn_price; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="markings">
					<?php echo JText::_( 'Markings' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="markings" id="markings" size="60" value="<?php echo $this->lens->pn_markings; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="fittingguide">
					<?php echo JText::_( 'Fitting Guide' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="fittingguide" id="fittingguide" size="60" value="<?php echo $this->lens->pn_fitting_guide; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="website">
					<?php echo JText::_( 'Website' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="website" id="website" size="60" value="<?php echo $this->lens->pn_website; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="image">
					<?php echo JText::_( 'Image' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="image" id="image" size="60" value="<?php echo $this->lens->pn_image; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="otherinfo">
					<?php echo JText::_( 'Other Information' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="otherinfo" id="otherinfo" size="60" rows="6" cols="45"><?php echo $this->lens->pn_other_info; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="discontinued">
					<?php echo JText::_( 'Discontinued' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="discontinued">
					<option value="1" <?php if($this->lens->pn_discontinued == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_discontinued == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>	

			</td>
		</tr>	
		<tr>
			<td width="110" class="key">
				<label for="display">
					<?php echo JText::_( 'Display' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="display">
					<option value="1" <?php if($this->lens->pn_display == 1 || is_null($this->lens->pn_display) ) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_display == 0  && !is_null($this->lens->pn_display) ) { echo 'SELECTED'; }  ?>>False</option>
				</select>
					

			</td>
		</tr>	

		<tr>
			<td width="110" class="key">
				<label for="bcsimple">
					<?php echo JText::_( 'BC Simple' ); ?>:
				</label>
			</td>
			<td>
			<select name="bcsimple[]" multiple='multiple'>
				<option value="flat" <?php if(in_array('flat', $this->bcsimples)) { echo 'SELECTED'; }  ?>>Flat</option>
				<option value="median" <?php if(in_array('median', $this->bcsimples) || in_array('med', $this->bcsimples)) { echo 'SELECTED'; }  ?>>Median</option>
				<option value="steep" <?php if(in_array('steep', $this->bcsimples)) { echo 'SELECTED'; }  ?>>Steep</option>
			</select>
				
			</td>
		</tr>	
		<tr>
			<td width="110" class="key">
				<label for="bcall">
					<?php echo JText::_( 'BC All' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="bcall" id="bcall" size="60" value="<?php echo $this->lens->pn_bc_all; ?>" />
			</td>
		</tr>
		
			<tr>
			<td width="110" class="key">
				<label for="diamall">
					<?php echo JText::_( 'Diameter All' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="diamall" id="diamall" size="60" value="<?php echo $this->lens->pn_diam_all; ?>" />
			</td>
		</tr>
		
		
		<tr>
			<td width="110" class="key">
				<label for="maxplus">
					<?php echo JText::_( 'Max Plus' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="maxplus" id="maxplus" size="60" value="<?php echo $this->lens->pn_max_plus; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="maxminus">
					<?php echo JText::_( 'Max Minus' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="maxminus" id="maxminus" size="60" value="<?php echo $this->lens->pn_max_minus; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="maxdiam">
					<?php echo JText::_( 'Max Diameter' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="maxdiam" id="maxdiam" size="60" value="<?php echo $this->lens->pn_max_diam; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="mindiam">
					<?php echo JText::_( 'Min Diameter' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="mindiam" id="mindiam" size="60" value="<?php echo $this->lens->pn_min_diam; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="diameter1">
					<?php echo JText::_( 'Diameter 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="diameter1" id="diameter1" size="60" value="<?php echo $this->lens->pn_diam_1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="basecurves1">
					<?php echo JText::_( 'Base Curves 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="basecurves1" id="basecurves1" size="60" value="<?php echo $this->lens->pn_base_curves_1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="powers1">
					<?php echo JText::_( 'Powers 1' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="powers1" id="powers1" size="60" value="<?php echo $this->lens->pn_powers_1; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="diameter2">
					<?php echo JText::_( 'Diameter 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="diameter2" id="diameter2" size="60" value="<?php echo $this->lens->pn_diam_2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="basecurves2">
					<?php echo JText::_( 'Base Curves 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="basecurves2" id="basecurves2" size="60" value="<?php echo $this->lens->pn_base_curves_2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="powers2">
					<?php echo JText::_( 'Powers 2' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="powers2" id="powers2" size="60" value="<?php echo $this->lens->pn_powers_2; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="diameter3">
					<?php echo JText::_( 'Diameter 3' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="diameter3" id="diameter3" size="60" value="<?php echo $this->lens->pn_diam_3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="basecurves3">
					<?php echo JText::_( 'Base Curves 3' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="basecurves3" id="basecurves3" size="60" value="<?php echo $this->lens->pn_base_curves_3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="powers3">
					<?php echo JText::_( 'Powers 3' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="powers3" id="powers3" size="60" value="<?php echo $this->lens->pn_powers_3; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="sphnotes">
					<?php echo JText::_( 'SPH Notes' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="sphnotes" id="sphnotes" size="60" rows="6" cols="45"><?php echo $this->lens->pn_sph_notes; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="toric">
					<?php echo JText::_( 'Toric' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="toric">
					<option value="1" <?php if($this->lens->pn_toric == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_toric == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>	

			</td>
		</tr>	
		<tr>
			<td width="110" class="key">
				<label for="torictype">
					<?php echo JText::_( 'Toric Type' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="torictype" id="torictype" size="60" value="<?php echo $this->lens->pn_toric_type; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="torictypesimple">
					<?php echo JText::_( 'Toric Type Simple' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="torictypesimple" id="torictypesimple" size="60" value="<?php echo $this->lens->pn_toric_type_simple; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cylpower">
					<?php echo JText::_( 'Cyl Power' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cylpower" id="cylpower" size="60" value="<?php echo $this->lens->pn_cyl_power; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="maxcylpower">
					<?php echo JText::_( 'Max Cyl Power' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="maxcylpower" id="maxcylpower" size="60" value="<?php echo $this->lens->pn_max_cyl_power; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cylaxis">
					<?php echo JText::_( 'Cyl Axis' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cylaxis" id="cylaxis" size="60" value="<?php echo $this->lens->pn_cyl_axis; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cylaxissteps">
					<?php echo JText::_( 'Cyl Axis Steps' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="cylaxissteps" id="cylaxissteps" size="60" value="<?php echo $this->lens->pn_cyl_axis_steps; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="oblique">
					<?php echo JText::_( 'Oblique' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="oblique" id="oblique" size="60" value="<?php echo $this->lens->pn_oblique; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cylnotes">
					<?php echo JText::_( 'Cyl Notes' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="cylnotes" id="cylnotes" size="60" rows="6" cols="45"><?php echo $this->lens->pn_cyl_notes; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="bifocal">
					<?php echo JText::_( 'Bifocal' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="bifocal">
					<option value="1" <?php if($this->lens->pn_bifocal == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_bifocal == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="bifocaltype">
					<?php echo JText::_( 'Bifocal Type' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="bifocaltype" id="bifocaltype" size="60" value="<?php echo $this->lens->pn_bifocal_type; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="addtext">
					<?php echo JText::_( 'Add Text' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="addtext" id="addtext" size="60" value="<?php echo $this->lens->pn_add_text; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="maxadd">
					<?php echo JText::_( 'Max Add' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="maxadd" id="maxadd" size="60" value="<?php echo $this->lens->pn_max_add; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cosmetic">
					<?php echo JText::_( 'Cosmetic' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="cosmetic">
					<option value="1" <?php if($this->lens->pn_cosmetic == 1) { echo 'SELECTED'; }  ?>>True</option>
					<option value="0" <?php if($this->lens->pn_cosmetic == 0) { echo 'SELECTED'; }  ?>>False</option>
				</select>

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="enhnames">
					<?php echo JText::_( 'ENH Names' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="enhnames" id="enhnames" size="60" value="<?php echo $this->lens->pn_enh_names; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="enhsimplenames">
					<?php echo JText::_( 'ENH Simple Names' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="enhsimplenames" id="enhsimplenames" size="60" value="<?php echo $this->lens->pn_enh_names_simple; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="opaquenames">
					<?php echo JText::_( 'Opaque Names' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="opaquenames" id="opaquenames" size="60" value="<?php echo $this->lens->pn_opaque_names; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="opaquesimplenames">
					<?php echo JText::_( 'Simple Opaque Names' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="opaquesimplenames" id="opaquesimplenames" size="60" value="<?php echo $this->lens->pn_opaque_names_simple; ?>" />				
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="updated">
					<?php echo JText::_( 'Updated (updates automatically)' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lens->pn_updated; ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_pnlenses" />
	<input type="hidden" name="id" value="<?php echo $this->lens->pn_tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>

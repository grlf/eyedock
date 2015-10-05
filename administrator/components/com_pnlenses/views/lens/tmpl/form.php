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

	JFilterOutput::objectHTMLSafe( $this->lens, ENT_QUOTES, 'pn_other_info' );

	$document = JFactory::getDocument();
	$document->addScript("/administrator/components/com_pnlenses/js/lensParams.js");
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		else {
			submitform( pressbutton );
		}
	}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-45">
	<fieldset class="adminform">

	<legend><?php echo JText::_( 'Lens Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'LENSID' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lens->pn_tid; ?><br/>
			</td>
		</tr>
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
					<option value="0"> - </option>

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
					<option value="0"> - </option>
					<?php foreach($this->polymers as $type){ ?>
						<option value="<?php echo $type->pn_poly_tid;?>"<?php if($this->lens->pn_poly_id == $type->pn_poly_tid) { echo 'SELECTED'; }  ?>><?php echo $type->pn_poly_name; ?> (  <?php echo $type->pn_h2o; ?>%, <?php echo $type->pn_poly_dk; ?> dk)</option>
					<?php } ?>
				</select>


			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="visitint">
					<?php echo JText::_( 'Visibility tint' ); ?>:
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
				<!--<input class="inputbox" type="text" name="replacesimple" id="replacesimple" size="10" value="<?php echo $this->lens->pn_replace_simple; ?>" /> eg) 1, 7, 14, 30, 90, 180, -->


		<select name="replacesimple" id="replacesimple">
					<?php if($this->lens->pn_replace_simple >0) {
						echo "<option SELECTED>" . $this->lens->pn_replace_simple . "</option>";
					 } ?>
					<option value="365"> - </option>
					<option value="1"> daily </option>
					<option value="14"> two weeks </option>
					<option value="30"> monthly </option>
					<option value="90"> quarterly </option>
					<option value="180"> 6 months </option>
					<option value="365"> conventional </option>

				</select>




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

			<?php
				$editor = JFactory::getEditor();
echo $editor->display('price', $this->lens->pn_price, '350', '70', '60', '4', false);
?>

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
			<?php
				$editor = JFactory::getEditor();
echo $editor->display('otherinfo', $this->lens->pn_other_info, '450', '200', '60', '20', false);
?>


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


	<!-- searching / sorting / summary data -->


   <div class="col width-45">

   	<fieldset class="adminform">
	<legend>Searching / Sorting / Summary data</legend>

	<table class="admintable" style = "float: left;">

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
				<label for="maxadd">
					<?php echo JText::_( 'Max Add' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="maxadd" id="maxadd" size="60" value="<?php echo $this->lens->pn_max_add; ?>" />
			</td>
		</tr>
		<tr>

	</table>
	<label style="float:right" for='autoVars_chk'>Auto-populate
	<input type="checkbox"  id="autoVars_chk"  checked='checked'/>
	</label>
	</fieldset>

</div>


<!-- ~~~~~~~~-  toric params ~~~~~~~~~~~~~~- -->
   <div class="col width-45">

		<fieldset class="adminform">
			<legend>Toric Params</legend>

			<table class="admintable" style = "float: left;">
				<tr>
					<td width="110" class="key">
						<label for="toric">
							<?php echo JText::_( 'Toric' ); ?>:
						</label>
					</td>
					<td>

						<select name="toric" id="toric">
							<option value="1" <?php if($this->lens->pn_toric == 1) { echo 'SELECTED'; }  ?>>True</option>
							<option value="0" <?php if($this->lens->pn_toric == 0) { echo 'SELECTED'; }  ?>>False</option>
						</select>

					</td>
				</tr>
				<tr class="toricClass">
					<td width="110" class="key">
						<label for="torictype">
							<?php echo JText::_( 'Toric Type' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="torictype" id="torictype" size="60" value="<?php echo $this->lens->pn_toric_type; ?>" />
					</td>
				</tr>
				<tr  class="toricClass">
					<td width="110" class="key">
						<label for="torictypesimple">
							<?php echo JText::_( 'Toric Type Simple' ); ?>:
						</label>
					</td>
					<td>

						<select name="torictypesimple">
							<option value="unknown" <?php if($this->lens->pn_bifocal_type == 'unknown') { echo 'SELECTED'; }  ?>>Unknown</option>
							<option value="prism ballast" <?php if($this->lens->pn_toric_type_simple == 'prism ballast') { echo 'SELECTED'; }  ?>>prism ballast</option>
							<option value="thin zones" <?php if($this->lens->pn_toric_type_simple == 'thin zones') { echo 'SELECTED'; }  ?>>thin zones</option>
						</select>

					</td>
				</tr>
				<tr  class="toricClass">
					<td width="110" class="key">
						<label for="cylpower">
							<?php echo JText::_( 'Cyl Power' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="cylpower" id="cylpower" size="60" value="<?php echo $this->lens->pn_cyl_power; ?>" />
					</td>
				</tr>

				<tr  class="toricClass">
					<td width="110" class="key">
						<label for="cylaxis">
							<?php echo JText::_( 'Cyl Axis' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="cylaxis" id="cylaxis" size="60" value="<?php echo $this->lens->pn_cyl_axis; ?>" />
					</td>
				</tr>

				<tr  class="toricClass">
					<td width="110" class="key">
						<label for="cylnotes">
							<?php echo JText::_( 'Cyl Notes' ); ?>:
						</label>
					</td>
					<td>
						<textarea class="inputbox" type="text" name="cylnotes" id="cylnotes" size="60" rows="6" cols="45"><?php echo $this->lens->pn_cyl_notes; ?></textarea>
					</td>
				</tr>


			</table>
		</fieldset>
	</div>


<!--
// ~~~~~~~~~~~~ bifocal params ~~~~~~~~~~~~~~-
 -->
	<div class="col width-45">

		<fieldset class="adminform">
		<legend>Bifocal Params</legend>

			<table class="admintable" style = "float: left;">
				<tr>
					<td width="110" class="key">
						<label for="bifocal">
							<?php echo JText::_( 'Bifocal' ); ?>:
						</label>
					</td>
					<td>

						<select name="bifocal" id="bifocal">
							<option value="1" <?php if($this->lens->pn_bifocal == 1) { echo 'SELECTED'; }  ?>>True</option>
							<option value="0" <?php if($this->lens->pn_bifocal == 0) { echo 'SELECTED'; }  ?>>False</option>
						</select>

					</td>
				</tr>
				<tr   class="bifocalClass">
					<td width="110" class="key">
						<label for="bifocaltype">
							<?php echo JText::_( 'Bifocal Type' ); ?>:
						</label>
					</td>
					<td>

						<select name="bifocaltype">
							<option value="unknown" <?php if($this->lens->pn_bifocal_type == 'unknown') { echo 'SELECTED'; }  ?>>Unknown</option>
							<option value="aspheric back surface" <?php if($this->lens->pn_bifocal_type == 'aspheric back surface') { echo 'SELECTED'; }  ?>>aspheric back surface</option>
							<option value="aspheric front surface" <?php if($this->lens->pn_bifocal_type == 'aspheric front surface') { echo 'SELECTED'; }  ?>>aspheric front surface</option>
							<option value="concentric, distance center" <?php if($this->lens->pn_bifocal_type == 'concentric, distance center') { echo 'SELECTED'; }  ?>>concentric, distance center</option>
							<option value="concentric, near center" <?php if($this->lens->pn_bifocal_type == 'concentric, near center') { echo 'SELECTED'; }  ?>>concentric, near center</option>
							<option value="concentric zones" <?php if($this->lens->pn_bifocal_type == 'concentric zones') { echo 'SELECTED'; }  ?>>concentric zones</option>
							<option value="diffractive optics" <?php if($this->lens->pn_bifocal_type == 'diffractive optics') { echo 'SELECTED'; }  ?>>diffractive optics</option>
							<option value="monovision" <?php if($this->lens->pn_bifocal_type == 'monovision') { echo 'SELECTED'; }  ?>>monovision</option>
							<option value="progressive" <?php if($this->lens->pn_bifocal_type == 'progressive') { echo 'SELECTED'; }  ?>>progressive</option>
							<option value="translating" <?php if($this->lens->pn_bifocal_type == 'translating') { echo 'SELECTED'; }  ?>>translating</option>
							<option value="other" <?php if($this->lens->pn_bifocal_type == 'other') { echo 'SELECTED'; }  ?>>other</option>

						</select>

					</td>
				</tr>
				<tr  class="bifocalClass">
					<td width="110" class="key">
						<label for="addtext">
							<?php echo JText::_( 'Add Text' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="addtext" id="addtext" size="60" value="<?php echo $this->lens->pn_add_text; ?>" />
					</td>
				</tr>


			</table>
		</fieldset>
	</div>


	<div class="col width-45">

		<fieldset class="adminform">
		<legend>Cosmetic Params</legend>

			<table class="admintable" style = "float: left;">
				<tr>
					<td width="110" class="key">
						<label for="cosmetic">
							<?php echo JText::_( 'Cosmetic' ); ?>:
						</label>
					</td>
					<td>

						<select name="cosmetic" id="cosmetic">
							<option value="1" <?php if($this->lens->pn_cosmetic == 1) { echo 'SELECTED'; }  ?>>True</option>
							<option value="0" <?php if($this->lens->pn_cosmetic == 0) { echo 'SELECTED'; }  ?>>False</option>
						</select>

					</td>
				</tr>

					<tr  class="cosmeticClass">
				<td width="110" class="key">
					<label for="enhsimplenames">
						<?php echo JText::_( 'ENH Simple Names' ); ?>:
					</label>
				</td>
				<td>


					<select name="enhsimplenames[]" multiple='multiple' size="8">
						<option value="aqua" <?php if(in_array('aqua', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Aqua</option>
						<option value="amber" <?php if(in_array('amber', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Amber</option>
						<option value="blue" <?php if(in_array('blue', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Blue</option>
						<option value="brown" <?php if(in_array('brown', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Brown</option>
						<option value="gray" <?php if(in_array('gray', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Gray</option>
						<option value="green" <?php if(in_array('green', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Green</option>
						<option value="hazel" <?php if(in_array('hazel', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Amber</option>
						<option value="honey" <?php if(in_array('honey', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Honey</option>
						<option value="yellow" <?php if(in_array('yellow', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Yellow</option>
						<option value="violet" <?php if(in_array('violet', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Violet</option>
						<option value="novelty" <?php if(in_array('novelty', $this->enhsimples)) { echo 'SELECTED'; }  ?>>Novelty</option>

					</select>


				</td>
			</tr>

				<tr  class="cosmeticClass">
					<td width="110" class="key">
						<label for="enhnames">
							<?php echo JText::_( 'ENH Names' ); ?>:
						</label>
					</td>
					<td>
						<textarea class="inputbox" type="text" name="enhnames" id="enhnames" size="60" rows="6" cols="45"><?php echo  $this->lens->pn_enh_names;  ?></textarea>
					</td>
				</tr>


					<tr  class="cosmeticClass">
					<td width="110" class="key">
						<label for="opaquesimplenames">
							<?php echo JText::_( 'Simple Opaque Names' ); ?>:
						</label>
					</td>
					<td>

					<select name="opaquesimplenames[]" multiple='multiple' size="8">
						<option value="aqua" <?php if(in_array('aqua', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Aqua</option>
						<option value="amber" <?php if(in_array('amber', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Amber</option>
						<option value="blue" <?php if(in_array('blue', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Blue</option>
						<option value="brown" <?php if(in_array('brown', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Brown</option>
						<option value="gray" <?php if(in_array('gray', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Gray</option>
						<option value="green" <?php if(in_array('green', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Green</option>
						<option value="hazel" <?php if(in_array('hazel', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Amber</option>
						<option value="honey" <?php if(in_array('honey', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Honey</option>
						<option value="yellow" <?php if(in_array('yellow', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Yellow</option>
						<option value="violet" <?php if(in_array('violet', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Violet</option>
						<option value="novelty" <?php if(in_array('novelty', $this->opaquesimples)) { echo 'SELECTED'; }  ?>>Novelty</option>

					</select>

					</td>
				</tr>

				<tr  class="cosmeticClass">
					<td width="110" class="key">
						<label for="opaquenames">
							<?php echo JText::_( 'Opaque Names' ); ?>:
						</label>
					</td>
					<td>
						<textarea class="inputbox" type="text" name="opaquenames" id="opaquenames" size="60" rows="6" cols="45"><?php echo  $this->lens->pn_opaque_names;  ?></textarea>
					</td>
				</tr>


			</table>
		</fieldset>
	</div>


<!--
	//~~~~~~- power variations ~~~~-
 -->
<?php if($edit) { ?>


	<fieldset class="adminform" style="clear:right; clear:left;">
		<legend>Power Variations</legend>

		<input type="button"  id="addParams" value="Add Row" style="float:right" />

		<table class="admintable" id="powersTable" >
			<thead>
			<tr>
				<th width="60" class="key" >
					<input type="hidden" id="paramTID0" value="x" />
						<?php echo JText::_( 'Base Curve(s)' ); ?>:
				</th>


				<th width="60" class="key">
						<?php echo JText::_( 'Diameters(s)' ); ?>:
				</th>


				<th width="60" class="key">

						<?php echo JText::_( 'Sphere powers' ); ?>:

				</th>


				<th width="60" class="key, toricClass">
						<?php echo JText::_( 'Cyl Pwrs' ); ?>:
				</th>

				<th width="60" class="key, toricClass">
						<?php echo JText::_( 'Cyl Axis' ); ?>:
				</th>

				<th width="40" class="key, bifocalClass">
						<?php echo JText::_( 'Add' ); ?>:
				</th>

				<th width="60" class="key, cosmeticClass">
						<?php echo JText::_( 'Enh Colors' ); ?>:
				</th>

				<th width="60" class="key, cosmeticClass">
						<?php echo JText::_( 'Opq Colors' ); ?>:
				</th>


				<th width="60" class="key">

				</th>

			</tr>


			</thead>


			<tbody>
			<?php for ($i=0, $n=count( $this->lenspowers); $i < $n; $i++ ) { ?>
			<tr size="20">
				<td>

				<input name="lenspowers[<?php echo $i ?>][id]" hidden="hidden" size="4"
				 value="<?php echo $this->lenspowers[$i]->id ?>" />

				 <label><input type="checkbox" <?php echo ($this->lenspowers[$i]->any_baseCurve ==1)?"checked":"" ?> name="lenspowers[<?php echo $i ?>][any_baseCurve]" value="1" onclick="anyboxclick(this)" >Any base cuve</label><br>

					<textarea  type="text"  name="lenspowers[<?php echo $i ?>][baseCurve]" size="40" rows="3" class="numlist"><?php echo $this->lenspowers[$i]->baseCurve ?></textarea>
				</td>

				<td>
					<label><input type="checkbox"  <?php echo ($this->lenspowers[$i]->any_diameter ==1)?"checked":"" ?> name="lenspowers[<?php echo $i ?>][any_diameter]" value="1"  onclick="anyboxclick(this)">Any diameter</label><br>
					<textarea  type="text"  name="lenspowers[<?php echo $i ?>][diameter]" size="40" rows="3" class="numlist"><?php echo $this->lenspowers[$i]->diameter ?></textarea>
				</td>

				<td>
					<label><input type="checkbox" <?php echo ($this->lenspowers[$i]->any_sphere ==1)?"checked":"" ?>  name="lenspowers[<?php echo $i ?>][any_sphere]" value="1" onclick="anyboxclick(this)">Any sphere</label><br>
					<textarea  type="text"  name="lenspowers[<?php echo $i ?>][sphere]" size="60" rows="3" class="numlist"><?php echo $this->lenspowers[$i]->sphere ?></textarea>
				</td>


				<td class="toricClass">
					<label><input type="checkbox" <?php echo ($this->lenspowers[$i]->any_cylinder ==1)?"checked":"" ?>  name="lenspowers[<?php echo $i ?>][any_cylinder]" value="1"  onclick="anyboxclick(this)">Any cyl power</label><br>
					<textarea  type="text"  name="lenspowers[<?php echo $i ?>][cylinder]" size="40" rows="3" class="numlist"><?php echo $this->lenspowers[$i]->cylinder ?></textarea>

				</td>

				<td class="toricClass">
					<label><input type="checkbox" <?php echo ($this->lenspowers[$i]->any_axis ==1)?"checked":"" ?>  name="lenspowers[<?php echo $i ?>][any_axis]" value="1"  onclick="anyboxclick(this)">Any axis</label><br>
					<textarea  type="text"  name="lenspowers[<?php echo $i ?>][axis]" size="40" rows="3" class="numlist"><?php echo $this->lenspowers[$i]->axis ?></textarea>

				</td>

				<td  class="bifocalClass">
					<label><input type="checkbox" <?php echo ($this->lenspowers[$i]->any_addPwr ==1)?"checked":"" ?>   name="lenspowers[<?php echo $i ?>][any_add]" value="1"  onclick="anyboxclick(this)">Any add</label><br>
					<input  type="text"  name="lenspowers[<?php echo $i ?>][add]" size="40" value="<?php echo $this->lenspowers[$i]->addPwr ?>" class="numlist"/>
				</td>

				<td  class="cosmeticClass">
					<textarea type="text"  name="lenspowers[<?php echo $i ?>][colors_enh]" size="60" rows="3" ><?php echo $this->lenspowers[$i]->colors_enh ?></textarea>
				</td>

				<td  class="cosmeticClass">
					<textarea  type="text"  name="lenspowers[<?php echo $i ?>][colors_opq]" size="60" rows="3" ><?php echo $this->lenspowers[$i]->colors_opq ?></textarea>

				</td>

				<td>
					<input type="button"  name="deleteColumn[0]" value="X"  />
					<input type="hidden"  name="lenspowers[<?php echo $i ?>][delete]" value="0"  />
				</td>

			</tr>
			<?php }  ?>
			</tbody>
		</table>

	</fieldset>


	<?php } else {  ?>
		<p style="clear:both" >Save this lens, then edit it to enter lens parameters (BC, diam, powers, colors, etc)</p>
	<?php }  ?>



<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_pnlenses" />
	<input type="hidden" name="id" value="<?php echo $this->lens->pn_tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>

</form>

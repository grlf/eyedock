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
JFilterOutput::objectHTMLSafe( $this->lens, ENT_QUOTES );
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
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
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
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->lens->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="aliases">
					<?php echo JText::_( 'Aliases' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="aliases" id="aliases" size="60" rows="3" cols="45"><?php echo $this->lens->aliases; ?></textarea>
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
					<?php foreach($this->companies as $c) { ?>
						<option value="<?php echo $c->tid; ?>" <?php if($this->lens->rgpCompanyID == $c->tid) { echo 'SELECTED'; } ?>><?php echo $c->name; ?></option>
					<?php } ?>
				</select>
					
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="materialtext">
					<?php echo JText::_( 'Material Text' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="materialtext" id="materialtext" size="60" value="<?php echo $this->lens->materialText; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="designcategory">
					<?php echo JText::_( 'Design Category' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="designcategory" id="designcategory">
					<?php foreach($this->designcategories as $d){ ?>
						<option value="<?php echo $d->tid;?>"<?php if($this->lens->designCategoryID == $d->tid) { echo 'SELECTED'; }  ?>><?php echo $d->name; ?></option>
					<?php } ?>
				</select>
					

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="subcategory">
					<?php echo JText::_( 'Subcategory' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="subcategory" id="subcategory">
						<option value="NULL" <?php if($this->lens->subcategoryID == 'NULL') { echo 'SELECTED'; } ?>>None</option>
					<?php foreach($this->startingsubcategories as $s){ ?>
						<option value="<?php echo $s->tid;?>"<?php if($this->lens->subcategoryID == $s->tid) { echo 'SELECTED'; }  ?>><?php echo $s->name; ?></option>
					<?php } ?>
				</select>
					
					<div id="allsubcategories" style="display: none;">
						<?php echo json_encode($this->allsubcategories); ?>					
					</div>

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="addpower">
					<?php echo JText::_( 'Add Power' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="addpower" id="addpower" size="60" value="<?php echo $this->lens->addPower; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="diameter">
					<?php echo JText::_( 'Diameter' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="diameter" id="diameter" size="60" value="<?php echo $this->lens->diameter; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="basecurve">
					<?php echo JText::_( 'Base Curve' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="basecurve" id="basecurve" size="60" value="<?php echo $this->lens->baseCurve; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="power">
					<?php echo JText::_( 'Power' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="power" id="power" size="60" value="<?php echo $this->lens->power; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="centerthickness">
					<?php echo JText::_( 'Center Thickness' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="centerthickness" id="centerthickness" size="60" value="<?php echo $this->lens->centerThickness; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="opticzone">
					<?php echo JText::_( 'Optic Zone' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="opticzone" id="opticzone" size="60" value="<?php echo $this->lens->opticZone; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="url">
					<?php echo JText::_( 'URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="url" id="url" size="60" value="<?php echo $this->lens->url; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="pdf">
					<?php echo JText::_( 'PDF' ); ?>:
				</label>
			</td>
			<td>
				<?php if($this->lens->pdf != '') { ?> 
				
					<a href="<?php echo $this->filepaths->pdf . $this->lens->pdf; ?>" style="display: block; margin-bottom: 10px;">Click here to view the PDF.</a>
					<p>Replace PDF:</p>
					<p><input type="file" name="newpdf" /></p>
					<input class="inputbox" type="checkbox" name="deletepdf" /> <span style="font-weight: bold; color: red;">Delete PDF</span>
					
				<?php } else { ?>
				
					<input type="file" name="newpdf" />
				
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="image">
					<?php echo JText::_( 'Image' ); ?>:
				</label>
			</td>
			<td>
				<?php if($this->lens->image != '') { ?> 
				
					<img src="<?php echo $this->filepaths->img . $this->lens->image; ?>" style="display: block; margin-bottom: 10px;" />
					<p>Replace Image:</p>
					<p><input type="file" name="newimage" /></p>
					<input class="inputbox" type="checkbox" name="deleteimage" /> <span style="font-weight: bold; color: red;">Delete Image</span>
					
				<?php } else { ?>
				
					<input type="file" name="newimage" />
				
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="otherinfo">
					<?php echo JText::_( 'Other Information' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="otherinfo" id="otherinfo" size="60" rows="6" cols="45"><?php echo $this->lens->otherInfo; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="cost">
					<?php echo JText::_( 'Cost' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" type="text" name="cost" id="cost" size="60" rows="3" cols="45"><?php echo $this->lens->cost; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="discontinued">
					<?php echo JText::_( 'Discontinued?' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="discontinued">
						<option value="1" <?php if($this->lens->discontinued == 1) { echo 'SELECTED'; }  ?>>Yes</option>
						<option value="0" <?php if($this->lens->discontinued == 0) { echo 'SELECTED'; }  ?>>No</option>
				</select>
					

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
						<option value="1" <?php if($this->lens->display == 1 || is_null($this->lens->display) ) { echo 'SELECTED'; }  ?>>Yes</option>
						<option value="0" <?php if($this->lens->display == 0 && !is_null($this->lens->display) ) { echo 'SELECTED'; }  ?>>No</option>
				</select>
					

			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="updated">
					<?php echo JText::_( 'Updated (updates automatically)' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lens->updated; ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<?php if($edit) { ?>

<div class="col width-55">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'EXISTING Lens ~ Material Associations' ); ?></legend>
	<table class="admintable">
	<?php for ($i=0, $n=count( $this->lensmaterials ); $i < $n; $i++ ) { ?>
		<tr>
			<td>
				<label>
					<?php echo ($i+1); ?>
				</label>
			</td>
			<td>

				<select name="existinglensmaterials[<?php echo $i; ?>]['material']">
					<option value="-" <?php if($this->lensmaterials[$i]->materialID == null) { echo 'SELECTED'; } ?>>-</option>
					<?php foreach($this->materials as $d){ ?>
						<option value="<?php echo $d->tid;?> "<?php if($this->lensmaterials[$i]->materialID == $d->tid) { echo 'SELECTED'; }  ?>><?php echo $d->name; ?></option>
					<?php } ?>
				</select>
			
			</td>
			<td>

				<select name="existinglensmaterials[<?php echo $i; ?>]['materialcompany']">
					<option value="-" <?php if($this->lensmaterials[$i]->materialCompanyID == null) { echo 'SELECTED'; } ?>>-</option>
					<?php foreach($this->materialcompany as $d){ ?>
						<option value="<?php echo $d->tid;?>" <?php if($this->lensmaterials[$i]->materialCompanyID == $d->tid) { echo 'SELECTED'; }  ?>><?php echo $d->name; ?></option>
					<?php } ?>
				</select>
			
			</td>
			<td>
			
					<input class="inputbox" type="checkbox" name="existinglensmaterials[<?php echo $i; ?>]['anymaterial']" <?php echo ($this->lensmaterials[$i]->anyMaterial == 1) ? 'CHECKED' : ''; ?>  /> Any Material?
					
			</td>
			<td>
			
					<input class="inputbox" type="checkbox" name="existinglensmaterials[<?php echo $i; ?>]['savechanges']" /> <span style="font-weight: bold; color: green;">Update</span>
					
			</td>
			<td>
			
					<input class="inputbox" type="checkbox" name="existinglensmaterials[<?php echo $i; ?>]['delete']" /> <span style="font-weight: bold; color: red;">Delete</span>
					<input type="hidden" name="existinglensmaterials[<?php echo $i; ?>]['tid']" value="<?php echo $this->lensmaterials[$i]->tid; ?>" />
					
			</td>
		</tr>
		

		<?php } ?>		

	</table>
	</fieldset>
</div>



<div class="col width-55">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'NEW Lens ~ Material Associations' ); ?></legend>
	<table class="admintable" id="new_lms">

		<tr class="lm_row">
			<td>
				<label>
					1
				</label>
			</td>
			<td>

				<select name="newlensmaterials[0]['material']" class="lm_material_select">
					<option value="-">-</option>
					<?php foreach($this->materials as $d){ ?>
						<option value="<?php echo $d->tid;?>"><?php echo $d->name; ?></option>
					<?php } ?>
				</select>
			
			</td>
			<td>

				<select name="newlensmaterials[0]['materialcompany']" class="lm_company_select">
					<option value="-">-</option>
					<?php foreach($this->materialcompany as $d){ ?>
						<option value="<?php echo $d->tid;?>"><?php echo $d->name; ?></option>
					<?php } ?>
				</select>
			
			</td>
			<td>
			
					<input class="inputbox lm_anymaterial" type="checkbox" name="newlensmaterials[0]['anymaterial']" /> Any Material?
					
			</td>
			<td>
			
					<input class="inputbox lm_create" type="checkbox" name="newlensmaterials[0]['create']" /> <span style="font-weight: bold; color: green;">Create</span>
					
			</td>
		</tr>		

	</table>
	</fieldset>
	
	<div style="float: right;"><a href="#" style="" id="row_remover">[-] Remove a row</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="#" style="margin-right: 20px;" id="row_adder">[+] Add a row</a></div>
	
</div>

<?php } ?>

<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_gplens" />
	<input type="hidden" name="id" value="<?php echo $this->lens->tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>

<script language="javascript" type="text/javascript">

window.addEvent('domready', function(){
  
	// populate subcategory depending on category selection
	var subcats = Json.evaluate($('allsubcategories').getText());
	var catselect = $('designcategory');
	var subcatselect = $('subcategory');
	
	catselect.addEvent('change', function() {
	
		var catindex = catselect.selectedIndex;
		var ops = catselect.getElements('option');
		var catid = ops[catindex].getProperty('value');
		
		subcatselect.setHTML('');
		
		subcats.each(function(ob) {
			if(ob.designCategoryID == catid) {
				new Element('option', {
					'value': ob.tid
				}).injectInside(subcatselect).setHTML(ob.name);
			}
		});
		
		new Element('option', {
			'value': 'NULL'
		}).injectTop(subcatselect).setHTML('None');		
	
	});
	
    // add new rows to lens_materials table
	var new_lms = $('new_lms');
	
	$('row_adder').addEvent('mousedown', function() {
	
		var i = new_lms.getLast('.lm_row').getElement('.lm_material_select').getProperty('name').substring(17,18).toInt() + 1;
		console.log(i);
		var newlm = new_lms.getElement('.lm_row').clone().injectInside(new_lms);
		newlm.getElement('.lm_material_select').setProperty('name', 'newlensmaterials[' + i + "]['material']");
		newlm.getElement('.lm_company_select').setProperty('name', 'newlensmaterials[' + i + "]['materialcompany']");
		newlm.getElement('.lm_anymaterial').setProperty('name', 'newlensmaterials[' + i + "]['anymaterial']");
		newlm.getElement('.lm_create').setProperty('name', 'newlensmaterials[' + i + "]['create']");
		
	});

	$('row_remover').addEvent('mousedown', function() {
	
		if(new_lms.getChildren().length > 1) { new_lms.getLast('.lm_row').remove(); }
		
	});
	
});

</script>
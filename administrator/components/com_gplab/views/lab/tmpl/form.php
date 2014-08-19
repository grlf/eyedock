<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$id = JRequest::getVar( 'tid', array(0), '', 'array' );
	$edit = JRequest::getVar( 'edit', true );
	JArrayHelper::toInteger($id, array(0));

	$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

	JToolBarHelper::title(  JText::_( 'Lab' ).': <small><small>[ ' . $text.' ]</small></small>' , 'generic.png' );
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
JFilterOutput::objectHTMLSafe( $this->lab, ENT_QUOTES );
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
	<legend><?php echo JText::_( 'Lab Attributes' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->lab->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="phone">
					<?php echo JText::_( 'Phone' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="phone" id="phone" size="60" value="<?php echo $this->lab->phone; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="url">
					<?php echo JText::_( 'Website' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="url" id="url" size="60" value="<?php echo $this->lab->url; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="email">
					<?php echo JText::_( 'Email' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="email" id="email" size="60" value="<?php echo $this->lab->email; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="address">
					<?php echo JText::_( 'Address' ); ?>:
				</label>
			</td>
			<td>			
				<input class="inputbox" type="text" name="address" id="address" size="60" value="<?php echo $this->lab->address; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="logoimage">
					<?php echo JText::_( 'Logo Image' ); ?>:
				</label>
			</td>
			<td>
				<?php if($this->lab->logoImage != '') { ?> 
				
					<img src="<?php echo $this->filepaths->img . $this->lab->logoImage; ?>" style="display: block; margin-bottom: 10px;" />
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
				<label for="display">
					<?php echo JText::_( 'Display?' ); ?>:
				</label>
			</td>
			<td>
				
				<select name="display">
						<option value="1" <?php if($this->lab->display == 1) { echo 'SELECTED'; }  ?>>Yes</option>
						<option value="0" <?php if($this->lab->display == 0) { echo 'SELECTED'; }  ?>>No</option>
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
				<input class="inputbox" type="text" name="comments" id="comments" size="60" value="<?php echo $this->lab->comments; ?>" />
			</td>
		</tr>		
	</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_gplab" />
	<input type="hidden" name="id" value="<?php echo $this->lab->tid; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
</form>
<?php defined('_JEXEC') or die('Restricted access'); 

	JToolBarHelper::title( JText::_( 'PN Company Report' ), 'generic.png' );
	JToolBarHelper::back("Go back", "index.php?option=com_pncompanies");
	
	$document = JFactory::getDocument();
	$document->addScript("/administrator/components/com_pncompanies/javascript/form.js");
	
	
	echo "<p>The following is a company report. An email may be sent to the contact person by using the form below. To change the company's contact person, hit the back button and edit the company's listing.</p><p><hr></p>";
	
?>
<div style='margin: 3em 0; border: 1px solid black; padding: 2em; width: 600px;'>
	<div style='float:right; width: 100px;'>Last email sent <?= $this->companyData["pn_last_email"] ?></div>
	<p>From: admin@eyedock.com</p>
	<p>To: <input type='text' id='email_to' size='50' value='<?= $this->companyData["pn_contact_email"] ?>'/>
	<p>Subject: <br/>
	<input type='text' id='email_subject' size='70' value='<?= $this->companyData["pn_comp_name_short"] ?> lenses on EyeDock'/></p>
	<p>Message<br/>

	<?php
		$editor =& JFactory::getEditor();
		echo $editor->display('email_body', $this->companyData['content'] , '600', '250', '60', '20', false);
	?>

	<div style='clear:both; margin-left: 400px; width: 150px;'>
		<input type='button' id='submitReport_btn' value='Email this report' onClick='sendEmail();'>
		<img id='email_spinner' src='/images/spinner.gif' style='display:none' >
		<div id='email_confirmation'>&nbsp;</div>
	</div>
	<input type='hidden' id="last_email" value="<?= $this->companyData["pn_last_email"] ?>"/>
	<input type='hidden' id="email_contact" value="<?= $this->companyData['contact'] ?>"/>
	<input type='hidden' id="email_comp_name" value="<?= $this->companyData['pn_comp_name_short'] ?>"/>
	<input type='hidden' id="email_comp_id" value="<?= $this->companyData['pn_comp_tid'] ?>"/>
</div>

<div id='lens_report_content'>
	<?php
	
		echo $this->data['company'] ;
		echo $this->data['lenses'];
	?>
</div>
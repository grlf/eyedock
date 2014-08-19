<?php


// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');

	require_once('emailer.php');
	require_once('companyFunctions.php');
	
	$to = $_REQUEST['to'];
	$subject = $_REQUEST['subject'];
	//$report_content = $_REQUEST['report_content'];
	$intro = $_REQUEST['email_body'];
	$company = $_REQUEST['company'];
	$contact = $_REQUEST['contact'];
	$id = $_REQUEST['id'];
	
	$companyReport =  getCompanyReport($id);
	$lensesReport =   getCompanyLensesReport ($id);
	
	$lensesReport = str_replace("&deg", "", $lensesReport);
	$lensesReport = str_replace("<br/>", "\n", $lensesReport);
	
	$content = $intro . "\n\n\n" . $companyReport . "\n\n\n" . $lensesReport;
	
	//echo $content;
	
	 emailCompanyReport ($to, $subject, $content, $company, $contact);
	 
	 updateLastEmail ($id);
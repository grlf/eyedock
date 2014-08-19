<?php

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');


//sends an email with the given email, subject, content, and from address

function emailCompanyReport ($to, $subject, $content, $company, $contact) {
	
	//$subject .= " (sent to ". $to . ")"; // for testing !!!!!!
	//$subject 
	// multiple recipients (note the commas)
	$sendTo = "EyeDock Admin <todd@eyedock.com>, "; //for testing !!!!!!!!
	$sendTo .= $contact . " <" . $to . "> ";

	// compose message
	$message = "
	<html>
	  <head>
		<title> ". $subject ."</title>
	  </head>
	  <body>" . $content ." 	
	  </body>
	</html>
	";

	// To send HTML mail, the Content-type header must be set
	$headers = "From: Todd M Zarwell OD FAAO <admin@eyedock.com>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "Reply-To: EyeDock Admin <admin@eyedock.com>\r\n"; 
    $headers .= "Return-Path: EyeDock Admin <admin@eyedock.com>\r\n"; 
    $headers .= "Organization: EyeDock\r\n"; 

	//for testing locally
	/*echo "<p>" . $to . "</p>";
	echo "<p>" . $from . "</p>";
	echo "<p>" . $subject . "</p>";
	echo "<p>" . $message . "</p>";
	*/
	// send email
	if ($to == "") {
		mail("admin@eyedock.com", "Email needed for: ". $company , "No email on file for this company", $headers); 
	} else {
		mail($sendTo, $subject, $message, $headers); 
	}
}

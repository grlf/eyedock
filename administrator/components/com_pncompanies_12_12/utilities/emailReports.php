<?php

//not using joomla api. A cron job will call this file directly...

ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
ini_set('html_errors', 'On');


//include_once $_SERVER['DOCUMENT_ROOT']. "/eyedock.com/api_new/dataGetters.php";

include_once "comp_db.php";
include_once "companyFunctions.php";

//get all the companies
$companiesSQL = "SELECT pn_comp_tid from pn_lenses_companies";
$results = $mysqli->query($companiesSQL);
while ($row = $results->fetch_assoc() ){
	//print_r($row['pn_comp_tid']);
	//echo "<br/>";
	$report = getCompanyReport ($row['pn_comp_tid']);
	echo "<br/>" . $report;
	
	$lensReport = getCompanyLensesReport ($row['pn_comp_tid']);
	
}



/*
// multiple recipients (note the commas)
$to = "zarwell@gmail.com.com";


// subject
$subject = "Testing CL company reports";

// compose message
$message = "
<html>
  <head>
    <title>CL company report</title>
  </head>
  <body>
    <h1>Nonsensical Latin</h1>
    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
       Nam iaculis pede ac quam. Etiam placerat suscipit nulla.
       Maecenas id mauris eget tortor facilisis egestas.
       Praesent ac augue sed <a href=\"http://lipsum.com/\">enim</a> aliquam auctor.
       Pellentesque convallis tempor tortor. Nullam nec purus.</p>
  </body>
</html>
";

// To send HTML mail, the Content-type header must be set
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

// send email
mail($to, $subject, $message, $headers);
*/
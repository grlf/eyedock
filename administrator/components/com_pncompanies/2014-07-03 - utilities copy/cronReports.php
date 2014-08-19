<?php

//not using joomla api. A cron job will call this file directly...
// 
// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);
// ini_set('html_errors', 'On');


//include_once $_SERVER['DOCUMENT_ROOT']. "/eyedock.com/api_new/dataGetters.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/utilities/mysqliSingleton.php";
include_once "companyFunctions.php";
include_once "emailer.php";

$maxCount = 2; //how many emails that will be automatically sent when this script is called (how often the script is called is determined by the cron job)

$mysqli = DBAccess::getConnection();

$companiesSQL = "SELECT *
FROM `pn_lenses_companies` 
WHERE pn_hide = 0 
ORDER BY pn_last_email ASC
";

//we don't want to send out too many emails at once - we'll limit it to the value of $count
$count = 0;

$result = $mysqli->selectQuery($companiesSQL);

while ($row = $result->fetch_assoc() ){
	
	if ($count > $maxCount-1) break;
	
	$lastEmail = strtotime($row['pn_last_email']);
	$interval = $row['pn_email_interval'];
	$daysSinceEmail = (time() - $lastEmail)/60/60/24;

	if ($daysSinceEmail < $interval) continue; //it hasn't been long enough
	
	//just a safeguard (don't automatically send anyone more than 1 email in a month)
	if ($daysSinceEmail < 30) continue;
	
	$count ++;
	
	$data['company'] =  getCompanyReport($row['pn_comp_tid']);
	$data['lenses'] =   getCompanyLensesReport ($row['pn_comp_tid']);
	
	//massage the data for the email
	if ($row['pn_contact_email'] == "" ) $row['pn_contact_email'] = $row['pn_email'];
	if ($row['pn_comp_name_short'] == "" ) $row['pn_comp_name_short'] = $row['pn_comp_name'];
			
	//get the email intro content
	$data['intro'] = makeEmailIntro ($row);
	
	$to = $row['pn_contact_email'];
	$subject = $row['pn_comp_name_short'] . " lenses on EyeDock";
	$contact = $row['pn_contact_nameF'] . " " . $row['pn_contact_nameL'];
	
	if ($row['pn_contact_nameF'] == "") $contact = $row['pn_comp_name_short'];
	
	$content = $data['intro'] . "<p><hr></p>" . $data['company'] . $data['lenses'] ;
	
	emailCompanyReport ($to, $subject , $content, $row['pn_comp_name_short'] , $contact) ;
	updateLastEmail ($row['pn_comp_tid']);
}





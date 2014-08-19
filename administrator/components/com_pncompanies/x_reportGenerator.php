<?php

/*ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
ini_set('html_errors', 'On');
*/

include_once $_SERVER['DOCUMENT_ROOT']. "/eyedock.com/api_new/dataGetters.php";



$company = $_REQUEST['id'];  //the company ID

$data = getCompanyReport($company);

//print_r($data);

$companyInfo = $data['info'][0];

//print_r($companyInfo);

$infoText = "<table border='1' cellspacing='0' bordercolor='#cae0eb' ><tr><td colspan='2'><h2>". $companyInfo['comp_name'] ."</h2></td></tr>";
$infoText .= "<tr><td>Phone:</td><td> " . $companyInfo['phone']."</td></tr>";
$infoText .= "<tr><td>Address: </td><td>" .   $companyInfo['address']."</td></tr>";
$infoText .= "<tr><td>Website: </td><td><a href='" . $companyInfo['website']. "'>".   $companyInfo['website']." </a></td></tr>";
$infoText .= "<tr><td>Email address: </td><td><a href='mailto:" . $companyInfo['email']. "'>".   $companyInfo['email']." </a><br/>";
$infoText .= "<tr><td>Description: </td><td>" .   $companyInfo['description']."</td></tr>";
$infoText .= "<tr><td>Contact name*</td><td> " .   $companyInfo['contactName']."</td></tr>";
$infoText .= "<tr><td>Contact email*</td><td> " .   $companyInfo['contactEmail']."</td></tr></table>";
$infoText .= "<br/>* the contact name and email are where we (Eyedock) will direct questions regarding " . $companyInfo['comp_name_short'].  "lenses. This information will not be displayed on the website.";
$infoText .= "</p>";

$infoText .= "<p>";

$allLenses = $data['cls'];

//print_r( $allLenses[0]);

$discontinuedArray = array();

foreach ($allLenses as $lens) {

if ($lens['discontinued'] ==1 ) {
	$discontinuedArray[] = $lens['name'];
	continue;
}


//echo "<hr>";
	//print_r($lens);
	//$infoText .= "<hr>";
	$infoText .= "<p><table border='1' cellspacing='0' width='400px' bordercolor='#cae0eb'>";
	$infoText .= "<tr><td colspan='2'><h2>" .$lens['name'] ."</h2></td></tr>";
	if ($lens['images'] !="") {
		$images = explode(",", $lens['images'] );
		$infoText .= "<tr><td colspan='2'>";
		foreach ($images as $image){
			$image = trim($image, " \s");
			$infoText .= "<img src='http://www.eyedock.com/modules/Lenses/pnimages/lens_images/" . $image ."' />";
		}
		$infoText .= "</td></tr>";
	}
	
	$infoText .= "<tr><td>Material</td><td>". $lens['material'] ."<br/>";
	$infoText .= "&nbsp;&nbsp;&nbsp; - FDA group: ". checkForNull($lens['fda']) ."<br/>";
	$infoText .= "&nbsp;&nbsp;&nbsp; - Water content: ". checkForNull($lens['h2o']) ."<br/>";
	$infoText .= "&nbsp;&nbsp;&nbsp; - Modulus: ". checkForNull($lens['modulus']) ."<br/>";
	if ($lens['polydk']>0) $infoText .= "&nbsp;&nbsp;:&nbsp; - dk: ". checkForNull($lens['polydk']) ."</td></tr>";

	
	
	$infoText .= "<tr><td>Visibility Tint?</td><td> ". yesOrNo($lens['visitint']) ."</td></tr>";
	$infoText .= "<tr><td>OK for extended wear?</td><td> ". yesOrNo($lens['ew']) ."</td></tr>";
	$infoText .= "<tr><td>Center thickness: </td><td>". checkForNull($lens['ct']) ."</td></tr>";
	$infoText .= "<tr><td>DK: </td><td>". checkForNull($lens['dk']) ."</td></tr>";
	$infoText .= "<tr><td>OZ:</td><td> ". checkForNull($lens['oz']) ."</td></tr>";
	$infoText .= "<tr><td>Quantity : </td><td>". checkForNull($lens['qty']) ."</td></tr>";
	$infoText .= "<tr><td>Replacement: </td><td>". checkForNull($lens['replace_text']) ."</td></tr>";
	$infoText .= "<tr><td>Wear type: </td><td>". checkForNull($lens['wear']) ."</td></tr>";
	$infoText .= "<tr><td>Wholesale price :</td><td> ". checkForNull($lens['price']) ."</td></tr>";
	$infoText .= "<tr><td>Appearance: </td><td>". checkForNull($lens['markings']) ."</td></tr>";
	$infoText .= "<tr><td>Fitting guide:</td><td> <a href='" . $lens['fitting_guide']. "'>".   $lens['fitting_guide']." </a></td></tr>";
	$infoText .= "<tr><td>Lens Website:</td><td> <a href='" . $lens['website']. "'>".   $lens['website']." </a></td></tr>";

	$infoText .= "<tr><td>Other info:</td><td> ". checkForNull($lens['other_info']). "</td></tr>" ;

	$infoText .= "<tr><td colspan='2'>Parameters:<br/>";
	
	$powers = $lens['powers'] ;
	$variation = 1;
	$pwrCount = count($powers);
	foreach ($powers as $power){
		$infoText .= "<p><table border='1' cellspacing='0' style='margin:7px; width:90%;' bordercolor='#f1f6fa'>";
		$variationLabel = ($power['variation'] !="")?$power['variation'] :$variation;
		if ($pwrCount>1) $infoText .= "<tr><td>Variation:</td><td>  " . $variationLabel . "</td></tr>";
		$infoText .= "<tr><td width='25%'>Base Curve:</td><td>  " . $power['baseCurve']. "</td></tr>";
		$infoText .= "<tr><td>Diameter:</td><td>  " . $power['diameter']. "</td></tr>";		
		$infoText .= "<tr><td>Sphere:</td><td>  " . $power['sphere']. "</td></tr>";
		
		if ($lens['toric'] == 1) {
			$infoText .= "<tr><td>Cyl Power:</td><td>  " . $power['cylinder']. "</td></tr>";
			$infoText .= "<tr><td>Cyl Axis:</td><td>  " . $power['axis']. "</td></tr>";	
		}
		
		if ($lens['bifocal'] == 1) {
			$infoText .= "<tr><td>Add power:</td><td>  " . $power['addPwr']. "</td></tr>";	
		}
		
		if ($lens['cosmetic'] == 1) {
			$infoText .= "<tr><td>Colors (enhancers):</td><td>  " . $power['colors_enh']. "</td></tr>";	
			$infoText .= "<tr><td>Colors (opaque): </td><td> " . $power['colors_opq']. "</td></tr>";	
		}	
		$infoText .= "</table></p>";
		
		$variation++;
	}
	$infoText .= "</td></tr>";
	$infoText .= "</table></p>";

}

$infoText .= "</p>";

//make a list of discontinued lenses:
if (count($discontinuedArray) > 0)	{
	$infoText .= "<p>&nbsp;</p><p><b>Discontinued lenses:</b> ";
	$infoText .= implode(", ", $discontinuedArray);
	$infoText .= "</p>";
}


echo $infoText;


function yesOrNo ($val) {
	return $val==1?"yes":"no";
}

function checkForNull ($val) {
	//echo $val;
	if ($val == ""|| $val == null || ( is_numeric($val) && $val == 0)) return "";
	return $val;
}
<?php

// -- replaced on 2014-07-03

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'] . "/utilities/mysqliSingleton.php";
include_once  $_SERVER['DOCUMENT_ROOT'] . '/utilities/powerLists/formatNumberText.php';

//updates the "last_email" field in the companies table (call when an email is sent)
function updateLastEmail ($id) {
	$mysqli = DBAccess::getConnection();
	$query = "UPDATE pn_lenses_companies SET pn_last_email = CURDATE() WHERE pn_comp_tid = $id";
 	$result=$mysqli->selectQuery($query);

	//echo $SQL;
	//$result = $mysqli->query($SQL);
	//$result->close();
	//$mysqli->close();
}

function makeEmailIntro ($contactInfo) {
		
	//massage the data for the email
	if ($contactInfo['pn_contact_nameL'] == "" && $contactInfo['pn_contact_nameF'] == "") $contactInfo['pn_contact_nameF'] = "sir or madam";
	if ($contactInfo['pn_comp_name_short'] == "") $contactInfo['pn_comp_name_short'] = $contactInfo['pn_comp_name'] ;
		
	//get the intro text for the email
	$intro = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/administrator/components/com_pncompanies/utilities/email.txt');
	
	//fill the variables
	$intro = str_replace("{companyname}", $contactInfo['pn_comp_name_short'], $intro);
	$intro = str_replace("{fname}", $contactInfo['pn_contact_nameF'], $intro);
	$intro = str_replace("{lname}", $contactInfo['pn_contact_nameL'], $intro);
	
	return $intro;

}

function getCompanyReport ($id) {
	$mysqli = DBAccess::getConnection();
	$SQL = "SELECT * from pn_lenses_companies WHERE pn_comp_tid = $id";	
    $result=$mysqli->selectQuery($SQL);
	$row = $result->fetch_assoc();
	//print_r($row);
	$report = "";
	$report .= "<table border='1' cellspacing='0' bordercolor='#cae0eb' >";
	$report .= "<tr><td colspan='2'><h2> " . $row['pn_comp_name'] . " </h2></td></tr>";
	$report .= "<tr><td>Phone:</td><td>  " . $row['pn_phone'] . " </td></tr>";
	$report .= "<tr><td>Address: </td><td>  " . $row['pn_address'] . "<br/>";
	$report .= $row['pn_city'] . ", " . $row['pn_state'] . " " .$row['pn_zip'];
	$report .= "</td></tr>";
	$report .= "<tr><td>Website: </td><td><a href=\"" . $row['pn_url'] . "\"> " . $row['pn_url'] . "   </a></td></tr>";
	$report .= "<tr><td>Email address: </td><td><a href=\"mailto: " . $row['pn_email'] . " \"> " . $row['pn_email'] . " </a><br/>";
	$report .= "<tr><td>Description: </td><td> " . $row['pn_comp_desc'] . " </td></tr>";
	$report .= "<tr><td>Contact name*</td><td> " . $row['pn_contact_nameF'] . " " . $row['pn_contact_nameL'] . "</td></tr>";
	$report .= "<tr><td>Contact email*</td><td> " . $row['pn_contact_email'] . " </td></tr></table>";
	$report .= "<br/>* the contact name and email are where we (Eyedock) will direct questions regarding  " . $row['pn_comp_name_short'] . " lenses. This information will not be displayed on the website.";
	//$report .= "";
	//$report = "<b> REPORT!</b>";
	
	return $report;

}

function getCompanyLensesReport ($id) {
	$mysqli = DBAccess::getConnection();
	
		$query = "
		SELECT  `pn_tid` AS tid,  `pn_name` AS name,  `pn_comp_id` AS comp_id,  `pn_poly_id` AS poly_id,  pn_fda_grp as fda, pn_h2o as h2o, pn_poly_dk as polydk, pn_poly_name as material, pn_poly_modulus as modulus,
		`pn_visitint` AS visitint,  `pn_ew` AS ew,  `pn_ct` AS ct,  `pn_dk` AS dk,  `pn_oz` AS oz,  `pn_process_text` AS 
process ,  `pn_qty` AS qty,  `pn_replace_text` AS replace_text,  `pn_wear` AS wear,  `pn_price` AS price,  `pn_markings` AS markings, pn_image AS images,
concat ( IF (`pn_fitting_guide` LIKE 'modules/%', 'http://www.eyedock.com/', ''), `pn_fitting_guide`)  AS fitting_guide,
  `pn_website` AS website,  `pn_other_info` AS other_info,  `pn_discontinued` AS discontinued,  `pn_bc_all` AS bc_all,  `pn_diam_all` AS diam_all,  `pn_max_plus` AS max_plus,  `pn_max_minus` AS max_minus,  `pn_max_diam` AS max_diam,  `pn_min_diam` AS min_diam,  `pn_toric` AS toric,  `pn_toric_type` AS toric_type, `pn_max_cyl_power` AS max_cyl_power,  `pn_bifocal` AS bifocal,  `pn_bifocal_type` AS bifocal_type,  `pn_max_add` AS max_add,  `pn_cosmetic` AS cosmetic
FROM pn_lenses left join pn_lenses_polymers on pn_poly_id = pn_poly_tid
WHERE pn_comp_id = $id
AND pn_display =1
	";
	
	$result=$mysqli->selectQuery($query);
	
	$lensListing = "";
	$discontinuedArray = array();
	while ($row = $result->fetch_assoc() ) {
		
		if ($row['discontinued'] ==1 ) {
			$discontinuedArray[] = $row['name'];
			continue;
		}
		
		$lensListing .= getLensParamHTMLforRow($row);
		
	
	}
	
	$report =  $lensListing;
	
	if (count($discontinuedArray) > 0) {
		$discontinuedList = implode ("<br/>", $discontinuedArray);
		$report .=  "<u>Discontinued lenses</u>:<br/>" . $discontinuedList;
	}

	
	return $report;
	
}

function getLensParamHTMLforRow ($lens) {
	$html = "<p>&nbsp;</p>";
	$html .= "<p><table border='1' cellspacing='0' width='400px' bordercolor='#cae0eb'>";
	$html .= "<tr><td colspan='2'><h2>" . $lens['name'] . "</h2></td></tr>";
	
	if ($lens['images'] !="") {
		$images = explode(",", $lens['images'] );
		$html .= "<tr><td colspan='2'>";
		foreach ($images as $image){
			$image = trim($image, " ");
			$imgURL = "http://www.eyedock.com/modules/Lenses/pnimages/lens_images/" . $image;
			$html .= "<img src='" . $imgURL . "'/> ";
		}
		$html .= "</td></tr>";
	} else {
		$html .= "<tr><td colspan='2'>[No images available for this lens]</td></tr>";
	}
	
	
	$html .= "<tr><td>Material</td><td>" . $lens['material']  . "<br/>";
	$html .= "&nbsp;&nbsp;&nbsp; - FDA group: " . checkForNull($lens['fda'])  . "<br/>";
	$html .= "&nbsp;&nbsp;&nbsp;&nbsp; - Water content: " . checkForNull($lens['h2o'])  . "<br/>";
	$html .= "&nbsp;&nbsp;&nbsp; - Modulus: " . checkForNull($lens['modulus'])  . "<br/>";
	$html .= "&nbsp;&nbsp;&nbsp; - dk:";  
	 
	 	if ($lens['polydk']>0) { 
	 		$html .=  checkForNull($lens['polydk']);
	 	} else {
	 		$html .=  checkForNull($lens['dk']);
	 	}
	 	
	  
	 	
	 	
	 	$html .= "</td></tr>";

	
	
	$html .= "<tr><td>Visibility Tint?</td><td>  " . yesOrNo($lens['visitint'])  . "</td></tr> ";
	$html .= "<tr><td>OK for extended wear?</td><td>  " . yesOrNo($lens['ew'])   . "</td></tr> ";
	$html .= "<tr><td>Center thickness: </td><td> " . checkForNull($lens['ct'])   . "</td></tr> ";
	$html .= "<tr><td>DK: </td><td> " . checkForNull($lens['dk'])   . "</td></tr> ";
	$html .= "<tr><td>OZ:</td><td> " . checkForNull($lens['oz'])   . "</td></tr> ";
	$html .= "<tr><td>Quantity : </td><td> " .  checkForNull($lens['qty'])   . "</td></tr> ";
	$html .= "<tr><td>Replacement: </td><td> " .  checkForNull($lens['replace_text'])   . "</td></tr> ";
	$html .= "<tr><td>Wear type: </td><td> " . checkForNull($lens['wear'])   . "</td></tr> ";
	$html .= "<tr><td>Wholesale price :</td><td>  " . checkForNull($lens['price'])   . "</td></tr> ";
	$html .= "<tr><td>Appearance: </td><td> " . checkForNull($lens['markings'])   . "</td></tr> ";
	$html .= "<tr><td>Fitting guide:</td><td> <a href='" .  $lens['fitting_guide']  . "'>" .  $lens['fitting_guide']  . " </a></td></tr> ";
	$html .= "<tr><td>Lens Website:</td><td> <a href='" .  $lens['website']  . "'>" .   $lens['website']  . " </a></td></tr> ";

	$html .= "<tr><td>Other info:</td><td>  " .  checkForNull($lens['other_info'])  . "</td></tr>";

	$html .= "<tr><td colspan='2'>Parameters:<br/>";
	
	$html .= getPowerHTMLforLens ($lens);
	
	$html .= "</td></tr>";
	$html .= "</table></p>";
	
	
	return $html;
}


function getPowerHTMLforLens ($lens) {
	$mysqli = DBAccess::getConnection();

	$powerSQL = "SELECT variation,	baseCurve,	diameter, sphere, cylinder,	axis, addPwr, colors_enh, colors_opq FROM pn_lenses_powers WHERE lensID = " . $lens['tid'] ;
		$result=$mysqli->selectQuery($powerSQL);
		$pwrCount = $result->num_rows;
		$html = "";
		$variation = 1;
		while ($powerRow = $result->fetch_assoc() ) {
			$array[0] = $powerRow; //the formatNumberText function expects a 2D array
			$power= formatNumberText ($array);
			$power = $power[0]; //change it back to a 1D array
			
			$html .= "<p><table border='1' cellspacing='0' style='margin:7px; width:90%;' bordercolor='#f1f6fa'>";
			$variationLabel = ($power['variation'] !="")?$power['variation'] :$variation;
			if ($pwrCount>1) $html .=  "<tr><td>Variation:</td><td>  " . $variationLabel . "</td></tr>";
		
			$html .= "<tr><td width='25%'>Base Curve:</td><td>" . $power['baseCurve'] . "</td></tr>";
			$html .= "<tr><td>Diameter:</td><td>" . $power['diameter'] . "</td></tr>";	
			$html .= "<tr><td>Sphere:</td><td>" . $power['sphere'] . "</td></tr>";
		
			if ($lens['toric'] == 1) {
				$html .= "<tr><td>Cyl Power:</td><td>" . $power['cylinder'] . "</td></tr>";
				$html .= "<tr><td>Cyl Axis:</td><td>  " . $power['axis'] . "</td></tr>";	
			}
		
			if ($lens['bifocal'] == 1) {
				$html .= "<tr><td>Add power:</td><td>  " . $power['addPwr'] ." </td></tr>";	
			}
		
			if ($lens['cosmetic'] == 1) {
				$html .= "<tr><td>Colors (enhancers):</td><td>  " . $power['colors_enh'] . "</td></tr>";	
				$html .= "<tr><td>Colors (opaque): </td><td> " . $power['colors_opq'] . "</td></tr>";	
			}	
			$html .= "</table></p>";
		
			$variation++;
		}
		
		return $html;
}

function yesOrNo ($val) {
	return $val==1?"yes":"no";
}

function checkForNull ($val) {
	//echo $val;
	if ($val == ""|| $val == null || ( is_numeric($val) && $val == 0)) return "";
	return $val;
}
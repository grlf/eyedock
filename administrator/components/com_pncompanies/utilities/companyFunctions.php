<?php

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
	$intro = str_replace("{compID}", $contactInfo['pn_comp_tid'], $intro);
	
	return $intro;

}

function getCompanyReport ($id) {
	//echo "company report --";
	$mysqli = DBAccess::getConnection();
	$SQL = "SELECT * from pn_lenses_companies WHERE pn_comp_tid = $id";	
    $result=$mysqli->selectQuery($SQL);
	$row = $result->fetch_assoc();
	//print_r($row);
	$report = "";
	$report .= $row['pn_comp_name'] . " info: \n";
	$report .= "--------------------\n";
	$report .= "Phone: " . $row['pn_phone'] . "\n";
	$report .= "Address:  " . $row['pn_address'] ;
	$report .= $row['pn_city'] . ", " . $row['pn_state'] . " " .$row['pn_zip'];
	$report .= "\n";
	$report .= "Website: ". $row['pn_url'] . "\n";
	$report .= "Email: " . $row['pn_email'] . "\n";
	$report .= "Description: " . $row['pn_comp_desc'] . "\n";
	$report .= "Contact name* : " . $row['pn_contact_nameF'] . " " . $row['pn_contact_nameL'] . "\n";
	$report .= "Contact email* : " . $row['pn_contact_email'] . "\n";
	$report .= "* the contact name and email are where we (Eyedock) will direct questions regarding  " . $row['pn_comp_name_short'] . " lenses. This information will not be displayed on the website.";
	//$report .= "";
	//$report = "<b> REPORT!</b>";
	$report .= "\n";
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
	
	$report .= "\n\n\nNEW LENSES? Fill in the following form (copy and paste as needed)\n";
	
	$report .="____________________________\n\n";

	$report .= getBlankLens();
	
	if (count($discontinuedArray) > 0) {
		$discontinuedList = implode ("\n", $discontinuedArray);
		$report .= "\n\n\nDISCONTINUED LENSES\n" ;
		$report .=  "________________________\n";

		$report .= $discontinuedList;
	}


	
	return $report;
	
}

function getLensParamHTMLforRow ($lens) {
	$html = "\n\n";
	$html .= "\n_____________________________________________\n\n";
	$html .=  $lens['name'];
	$html .= "\n_____________________________________________\n\n";
	$html .= "EyeDock listing: http://www.eyedock.com/index.php?option=com_pnlenses#view=detail&id=" . $lens['tid'] . "\n";

	$html .= "Fitting guide: " .  $lens['fitting_guide'] . "\n";
	$html .= "Lens Website: " .   $lens['website']  . " \n ";
	$html .= "Image: ";
	if ($lens['images'] !="") {
		$images = explode(",", $lens['images'] );
		$html .= "image on file (see listing)\n";
			} else {
		$html .= "no image - could you provide one?\n";
	}
	
	
	$html .= "Material: " . $lens['material'];
	$html .= "\n --> (FDA group: " . checkForNull($lens['fda']) ;
	$html .= ", Water content: " . checkForNull($lens['h2o']);
	$html .= ", Modulus: " . checkForNull($lens['modulus']);
	$html .= ", dk: ";  
	 
	 	if ($lens['polydk']>0) { 
	 		$html .=  checkForNull($lens['polydk']);
	 	} else {
	 		$html .=  checkForNull($lens['dk']);
	 	}

	 	$html .= ")\n";

	
	
	$html .= "Visibility Tint? " . yesOrNo($lens['visitint'])  . "\n";
	$html .= "Extended wear? " . yesOrNo($lens['ew'])   . "\n";
	$html .= "Center thickness:  " . checkForNull($lens['ct'])   . "\n";
	$html .= "DK: " . checkForNull($lens['dk'])   . "\n";
	$html .= "OZ: " . checkForNull($lens['oz'])   . "\n";
	$html .= "Quantity : " .  checkForNull($lens['qty'])   . "\n";
	$html .= "Replacement: " .  checkForNull($lens['replace_text'])   . "\n";
	$html .= "Wear type:  " . checkForNull($lens['wear'])   . "\n";
	$html .= "Appearance:  " . checkForNull($lens['markings'])   . "\n";
	
	if ($lens['toric'] == 1) $html .= "Toric type: " . checkForNull($lens['toric_type']) . "\n";
	if ($lens['bifocal'] == 1) $html .= "Bifocal type: " . checkForNull($lens['bifocal_type']) . "\n";




	$html .= "\n------Parameters--------\n";
	
	$html .= getPowerHTMLforLens ($lens);
	
	$html .= "\n-- Wholesale price -- \n" . clearHTML(checkForNull($lens['price']) );
	$html .= "\n ->  note: wholesale prices are not visible to the public";
	
	$html .= "\n\n-- Other info: --\n" .  clearHTML(checkForNull($lens['other_info']) )  . "\n";


	//$html .= "\n";
	
	
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
			
			//$variationLabel = ($power['variation'] !="")?$power['variation'] :$variation;
			if ($pwrCount>1) $html .=  "\n -- Variation " . $variation . " --\n";
		
			$html .= " -Base Curve: " . $power['baseCurve'] . "\n";
			$html .= " -Diameter: " . $power['diameter'] . "\n";	
			$html .= " -Sphere: \n" . $power['sphere'] . "\n\n";
		
			if ($lens['toric'] == 1) {
				$html .= " -Cyl Power: " . $power['cylinder'] . "\n";
				$html .= " -Cyl Axis: " . $power['axis'] . "\n";	
			}
		
			if ($lens['bifocal'] == 1) {
				$html .= " -Add power:  " . $power['addPwr'] ." \n";	
			}
		
			if ($lens['cosmetic'] == 1) {
				$html .= " -Colors (enhancers):  " . $power['colors_enh'] . "\n";	
				$html .= " -Colors (opaque):  " . $power['colors_opq'] . "\n";	
			}	
			//$html .= "\n";
		
			$variation++;
		}
		
		return $html;
}

function getBlankLens () {

$html = "NAME: 
Image: please email images\nMaterial:  \n-- (FDA group: , Water content: , Modulus: , dk: )\nVisibility Tint? yes / no \nOK for extended wear? yes / no \nCenter thickness: \nDK: \nOZ: \nQuantity: \nReplacement: \nWear type: \nAppearance: \ntoric type (if applicable):\nbifocal type (if applicable):\n\n------URLs--------\nFitting guide: (please email PDF)\nLens Website: \n\n------Parameters--------\n-- Variation  -- (copy as needed)\n-Base Curve: \n-Diameter: \n-Sphere: \n-Toric Powers\n-Toric axis\n-Add powers\n-Colors (enhancers): \n-Colors (opaque): \n\n-- Wholesale price (note: wholesale prices are not visible to the public) --\n\n-- Other info: --\n";

return $html;


}

function yesOrNo ($val) {
	return $val==1?"yes":"no";
}

function checkForNull ($val) {
	//echo $val;
	if ($val == ""|| $val == null || ( is_numeric($val) && $val == 0)) return " (no data) ";
	return $val;
}

function clearHTML ($des){
	return trim( urldecode(html_entity_decode(strip_tags($des))));

}
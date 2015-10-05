<?php

// ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);


require_once("utilities/mysqliSingleton.php" );

if (!isset($type) ) $type = "companies";

if ($type == "companies") {
	
	
# 	echo "<h2>Soft lens companies</h2>";
# 	echo "Click a company to view more information and its lenses";

	$sql = "SELECT *
		FROM pn_lenses_companies
		WHERE pn_hide = 0
		ORDER BY pn_comp_name"; 
		
	$mysqli = DBAccess::getConnection();
	$result = $mysqli->selectQuery($sql);
	  while($row = $result->fetch_assoc() ) {
	  	echo "<p style='margin-top: 30px;'>";
	  	echo "<a href='/index.php?option=com_jumi&fileid=6&type=lenses&comp_id=" . $row['pn_comp_tid'] . "'>";
		
	  	if ($row['pn_logo'] ){
	  		$logo = "/modules/Lenses/pnimages/comp_logos/". $row['pn_logo'];
	  		echo "<br/><img src='" . $logo . "' style='max-width:100px; max-height: 100px;; margin:.5em; vertical-align:middle'>";
	  	}
	  	
	  echo "  " . $row['pn_comp_name'] . "</a>";
	   	echo "</p>";
	   	
 	}
}

if ($type=="lenses" && $comp_id >0 ) {

	//display the company info
	$comp_sql = "SELECT *
		FROM pn_lenses_companies
		WHERE pn_comp_tid = $comp_id"; 
		
	$mysqli = DBAccess::getConnection();
	$result = $mysqli->selectQuery($comp_sql);
	 while($row = $result->fetch_assoc() ) {
	  	if ($row['pn_logo'] ){
	  		$logo = "/modules/Lenses/pnimages/comp_logos/". $row['pn_logo'];
	  		echo "<p><img src='" . $logo . "'></p>";
	  	}
	  		 
	  	echo "<h2>" . $row['pn_comp_name'] . "</h2>";
	  	
	   echo $row['pn_address'];
	   if ($row['pn_city'] ) {
	   		echo "<br/>";
	   		echo $row['pn_city'] . " ";
	   		echo $row['pn_state'] . " ";
	   		echo $row['pn_zip'] . "<br/>";;
	   }
	   
	   echo $row['pn_phone'] . "<br/>";
	   if ($row['pn_url']) {
	   		echo "<a href='" . $row['pn_url'] . " '>";
	   		echo $row['pn_url'] . "<br/>";
	   		echo "</a>";
	   	}
	   	if ($row['pn_email']) {
	   		echo "<a href='mailto:" . $row['pn_email'] . " '>";
	   		echo $row['pn_email'] . "<br/>";
	   		echo "</a>";
	   	}
 	}
 	
 	echo "<p style='margin: 15px 0'>Click a lens to view its parameters</p>";
 	
	$sql = "SELECT pn_name as name, pn_tid as id, pn_discontinued as dc, pn_image as image
		FROM pn_lenses
		WHERE pn_comp_id = $comp_id and pn_display = 1
		ORDER BY dc, name"; 
		
	//$mysqli = DBAccess::getConnection();
	$result = $mysqli->selectQuery($sql);
	 while($row = $result->fetch_assoc() ) {
	   echo "<a href='/index.php?option=com_pnlenses&lens_id=" . $row['id'] . "'>";
	   if ($row['image']) {
	   		$image = "/modules/Lenses/pnimages/lens_images/". getOneImage($row['image']);
	  		echo "<img src='" . $image . "' style='max-width:50px; max-height: 50px; margin:.5em; vertical-align:middle'>";
	   } else {
	   		echo "<img src='/images/crosses/sphere_lens.png' style='width:50px; height: 50px; margin:.5em; vertical-align:middle'>";
	   }
	   echo $row['name'];
	   echo "</a>";
	   if ($row['dc'] == 1) echo " (discontinued)";
	   echo "<br/>";
 	}
}

function getOneImage ($str) {
	$imgs = explode(",", trim($str));
	return (count($imgs) > 0)?$imgs[0]:$str;
	
}

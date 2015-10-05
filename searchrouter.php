<?php

//  ini_set('display_errors', 1); 
//  ini_set('log_errors', 1); 
//  ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
//  error_reporting(E_ALL);
//  ini_set('html_errors', 'On');

$q = trim($_REQUEST["q"]); 

preg_match("/(.*)\(([^)]*)\)/u", $q, $matches);
//  preg_match_all("/(.*)\(([^)]*)\)/u", $search_text, $matches);
//print_r($matches);

$q = (isset($matches[1]) ) ? $matches[1] : $q;
$q_encoded = urlencode(trim($q) );

$type = (isset($matches[2]) ) ? $matches[2] : "scl";
//possibilities: scl company, meds, gp, gp btn, epocrates, ICD-9, refraction, CL power

$prefix = "http://www.eyedock.com/index.php?";

$types = array("scl", "med", "gp", "gp btn", "ICD-9", "refraction", "CL power", "epocrates");
$locations = array(
	$prefix . "option=com_pnlenses#view=list&phrase%5B%5D=",
	$prefix . "option=com_content&view=article&id=70&Itemid=62&q=",
	$prefix . "option=com_content&view=article&id=126&Itemid=71&q=",
	$prefix . "option=com_content&view=article&id=127&Itemid=72&q=",
	$prefix . "option=com_content&view=article&id=71&Itemid=63&q=",
	$prefix . "option=com_pnlenses#view=list&refraction%5B%5D=",
	$prefix . "option=com_pnlenses#view=list&clRx%5B%5D=",
	"http://online.epocrates.com/public/portkey/?monograph="
	);

//search scls as default
$url = $prefix . "option=com_pnlenses#view=list&phrase%5B%5D=" . $q_encoded; 
$i = 0;
foreach ($types as $module) {
	//truncate epocrates searches for less precise results
	//echo "<br/>check: " . $module;
	if (strpos($type, "epocrates") ) $q_encoded = substr($q_encoded, 0, 6);
	if (strpos($type, $module) !== false) $url = $locations[$i] . $q_encoded;
	//echo "<br/>".$url;
	$i ++;
}

// echo "<p>'$q'</p>";
// echo "<p>'$type'</p>";
// echo "<p>. . . redirecting</p>";
// 
// if ($type == null) echo "<p>no type</p>";
// 
// echo "<p>go to: $url</p>";

header( "Location: $url" );


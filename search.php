<?php
require_once "configuration.php";

if($_POST['mode'] == 'get_med_adv_search'){

  $c = new JConfig();
  $conn = mysql_connect($c->host, $c->user, $c->password) or die ("There appears to be a database error (Error 101), please contact site administrator");
  mysql_select_db("eyedock_data", $conn) or die ("There appears to be a database error (Error 101), please contact site administrator");

	$xml = med_adv_search_values();

	header('Content-type: text/xml');
	echo $xml;

	exit;
}

$q = strtolower($_GET["q"]);
$db = $_GET["db"];

if (strlen($q) >= 2) {

  $c = new JConfig();


  $conn = mysql_connect($c->host, $c->user, $c->password) or die ("There appears to be a database error (Error 101), please contact site administrator");
  mysql_select_db("eyedock_data", $conn) or die ("There appears to be a database error (Error 101), please contact site administrator");


  if (!$db || $db == "1") {
	$sql = "select * from pn_lenses
		where lower(pn_name) like " . db_escape("%$q%") . "
		or lower(pn_aliases) like " . db_escape("%$q%");
#	get_words($sql, "pn_name,pn_aliases", "1");
	get_words_alliases($sql, "pn_name", "1");
	$sql = "select * from pn_lenses_companies
		where lower(pn_comp_name) like " . db_escape("%$q%");
	get_words($sql, "pn_comp_name", "1");
  }

  if (!$db || $db == "2") {
	$sql = "SELECT * FROM pn_icd9
	LEFT JOIN pn_icd9_section
		ON ( pn_icd9_section.pn_high >= pn_code
		AND pn_icd9_section.pn_low <= pn_code
		AND pn_icd9.pn_letter = pn_icd9_section.pn_letter )
	LEFT JOIN pn_icd9_category
		ON (pn_icd9_category .pn_high >= pn_code
		AND   pn_icd9_category .pn_low <= pn_code
		AND pn_icd9.pn_letter = pn_icd9_category.pn_letter)
	WHERE ( (lower(pn_icd9.pn_diagnosis) LIKE " . db_escape("%$q%") . "
		or lower(pn_icd9.pn_comment) LIKE " . db_escape("%$q%") . ")  )
		order by pn_icd9.pn_letter, pn_icd9.pn_code LIMIT 100";

	get_words($sql, "pn_diagnosis,pn_comment", "2");
  }


  if (!$db || $db == "3") {
	$sql = "select * from pn_rx_chem
		where lower(pn_name) like " . db_escape("%$q%");
	get_words($sql, "pn_name", "3");

	$sql = "select * from pn_rx_company
		where lower(pn_name) like " . db_escape("%$q%");
	get_words($sql, "pn_name", "3");

	$sql = "select * from pn_rx_meds
		where lower(pn_trade) like " . db_escape("%$q%");
	get_words($sql, "pn_trade", "3");

	$sql = "select * from pn_rx_moa
		where lower(pn_name) like " . db_escape("%$q%");
	get_words($sql, "pn_name", "3");

	$sql = "select * from pn_rx_preserve
		where lower(pn_name) like " . db_escape("%$q%");
	get_words($sql, "pn_name", "3");

  }


  if(!$db){
  	mysql_select_db('eyedock_data', $conn);

		$sql = 'select l.name as lens_name 
		from 
		rgpLenses l 
		where l.name like ' . db_escape("%$q%") . ' order by lens_name asc';

		get_words($sql, 'lens_name', '4');

		$sql = 'select m.name as material_name 
		from 
		rgpMaterials m 
		where m.name like ' . db_escape("%$q%") . ' order by m.name asc';

		get_words($sql, 'material_name', '5');

  }

  if ($arr) {

		ksort($arr);

		/* Get rid of beginning and trailing non-alphanumeric characters */
		$_arr = array_values($arr);
		foreach($_arr as $k => $v){
			if(strpos($v, '|')){
				list($tmp1, $tmp2) = explode('|', $v);
				while(preg_match('/^[^\d\w]/', $tmp1)){
					$tmp1 = preg_replace('/^[^\d\w]/', '', $tmp1);
				}
				while(preg_match('/[^\d\w]$/', $tmp1)){
					$tmp1 = preg_replace('/[^\d\w]$/', '', $tmp1);
				}
				$_arr[$k] = $tmp1 . '|' . $tmp2;
			}else{
				$_arr[$k] = $v;
			}
		}

		$arr = array_unique($_arr);

		foreach($arr as $w) {
			$out .= "$w\n";
		}

  } else {
		$out = "";
  }

  echo $out;

}


function get_words_alliases($sql, $columns, $search) {
	global $conn;
	global $arr, $q, $db;

	if ($search == 1) {$search_name = " (CLs)";}
	if ($search == 2) {$search_name = " (Icd-9)";}
	if ($search == 3) {$search_name = " (Meds)";}
	if ($search == 4) {$search_name = " (GP Name)";}
	if ($search == 5) {$search_name = " (GP Material)";}
	if ($db) {$search_name = "";}

	if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
	while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$c = explode(",", $columns);

		while (list(, $column) = each($c)) {

			$s = $row["$column"];

			$s = preg_replace("/\(|\)/", "", $s);
			$arr["$s$search_name|$search"] = "$s|$search";
		}
	}
}

function get_words($sql, $columns, $search) {
	global $conn;
	global $arr, $q, $db;

	if ($search == 1) {$search_name = " (CLs)";}
	if ($search == 2) {$search_name = " (Icd-9)";}
	if ($search == 3) {$search_name = " (Meds)";}
	if ($search == 4) {$search_name = " (GP Name)";}
	if ($search == 5) {$search_name = " (GP Material)";}
	if ($db) {$search_name = "";}

	if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
	
	while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$c = explode(",", $columns);

		while (list(, $column) = each($c)) {

			$s = $row["$column"];
			
			if ($search < 4) {
				$s = preg_replace("/\(|\)/", "", $s);
				$w = explode(" ", "$s");
				for ($i = 0; $i<= count($w); $i++) {
					$word = strtolower($w[$i]);
					if (strpos($word, $q) !== false) {$arr["$w[$i]$search_name|$search"] = "$w[$i]|$search";}
				}				
			}
			
			else {
				$word = strtolower($s);
				if (strpos($word, $q) !== false) {
					$arr["$s$search_name|$search"] = "$s|$search";
				}				
			}
			
			
			/*
			
			$s = preg_replace("/\(|\)/", "", $s);
			
			$s = preg_replace("/\(/", "[", $s);
			$s = preg_replace("/\)/", "]", $s);
			
			//echo $s;
			
			$w = explode(" ", "$s");
			
			for ($i = 0; $i<= count($w); $i++) {
				$word = strtolower($w[$i]);
				if (strpos($word, $q) !== false) {$arr["$w[$i]$search_name|$search"] = "$w[$i]|$search";}
			}
			
			
			$word = strtolower($s);
			if (strpos($word, $q) !== false) {
				$arr["$s$search_name|$search"] = "$s|$search";
			}
			*/
			
			//$arr["$s$search_name|$search"] = "$s|$search";
			
		}
	}

}

function db_escape($str) {
 if (!$str) {return "null";} else {
	 //couldn't get this to work with ereg in php 5.3
	//return "'" . ereg_replace("\\\\", "\\\\", ereg_replace("'", "''", $str)) . "'";
	return "'" .  mysql_real_escape_string($str) . "'";
 }
}


function med_adv_search_values(){

	$fields = array(
		'manufacturer' => array(
			'table' => 'pn_rx_company',
			'key' => 'pn_comp_id',
			'name' => 'pn_name'
		),
		'preservative' => array(
			'table' => 'pn_rx_preserve',
			'key' => 'pn_pres_id',
			'name' => 'pn_name'
		),
		'methods' => array(
			'table' => 'pn_rx_moa',
			'key' => 'pn_moa_id',
			'name' => 'pn_name'
		)
	);

	$xml = '<return>';

	foreach($fields as $k => $v){
		$xml .= '<options>';
		$xml .= '<name>' . xml_escape($k) . '</name>';
		$res = mysql_query("select * from " . $v['table'] . " order by " . $v['name'] . " asc");
		$xml .= '<values>';
		while($row = mysql_fetch_assoc($res)){
			$xml .= '<item>';
			$xml .= '<ovalue>' . xml_escape($row[$v['key']]) . '</ovalue>';
			$xml .= '<oname>' . xml_escape($row[$v['name']]) . '</oname>';
			$xml .= '</item>';
		}
		mysql_free_result($res);
		$xml .= '</values>';
		$xml .= '</options>';
	}

	$xml .= '</return>';

	return $xml;
}

function xml_escape($str){

	$str = html_entity_decode($str);

	$chars = array();
	for($x = 0; $x < strlen($str); $x++){
		if(ord($str[$x]) > 127){
			$chars[$str[$x]] = '&#' . ord($str[$x]) . ';';
		}
	}

	if(!empty($chars)){
		$str = str_replace(array_keys($chars), array_values($chars), $str);
	}

	return str_replace(array('&', '"', "'", '<', '>'), array('&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;'), $str); 
}
?>

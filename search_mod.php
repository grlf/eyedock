<?
require_once "configuration.php";

$q = $_REQUEST["q"];
if (!$q) {$q = $_GET["q"];}
if (ereg("^(.*) \(Meds\)$", $q, $m)) {$q = $m[1];}

if ($q) {

$sql = "SELECT pn_med_id, pn_trade, pn_medType1, pn_medType2, pn_comp_id, pn_chem_id1, pn_chem_id2, pn_chem_id3, pn_chem_id4
FROM pn_rx_meds WHERE pn_display = 'true'
AND pn_trade LIKE " . db_escape("%$q%") . "
ORDER BY pn_trade";


$c = new JConfig();


$conn = mysql_connect($c->host, $c->user, $c->password) or die ("There appears to be a database error (Error 101), please contact site administrator");
mysql_select_db("eyedock_data", $conn) or die ("There appears to be a database error (Error 101), please contact site administrator");

$bg_color = "#FFFFFF";

$chem_arr = get_ref_table("pn_rx_chem", "pn_chem_id", "pn_name");
$comp_arr = get_ref_table("pn_rx_company", "pn_comp_id", "pn_name");

if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$found = 1;

	$id = $row["pn_med_id"];
	$trade = $row["pn_trade"];
	$type1 = $row["pn_medType1"];
	$type2 = $row["pn_medType2"];
	$comp_id = $row["pn_comp_id"];
	$chem1 = $row["pn_chem_id1"];
	$chem2 = $row["pn_chem_id2"];
	$chem3 = $row["pn_chem_id3"];
	$chem4 = $row["pn_chem_id4"];

	$out .= "
    <tr style=\"background-color:$bg_color;\">
       <td><a href=\"/module-Meds-display-med_id-$id-search-1.htm\" title=\"$.trade\">$trade</a></td>
       <td>$comp_arr[$comp_id]</td>
       <td>$chem_arr[$chem1]";

	if ($chem2) {$out .= "<br>$chem_arr[$chem2]";}
	if ($chem3) {$out .= "<br>$chem_arr[$chem3]";}
	if ($chem4) {$out .= "<br>$chem_arr[$chem4]";}
	$out .= "
       </td>
       <td>$type1";
	if ($type2) {$out .= "<br>&nbsp;$type2";}
	$out .= "</td>
    </tr>";
    
	if ($bg_color == "#FFFFFF") {$bg_color = "#E5EBFF";} else {$bg_color = "#FFFFFF";}
}

if ($found) {
  $out = "<div style=\"padding:0 25px 0 25px;\">

<style>
td {
	font-family: verdana, helvetica, sans-serif;
	font-size: 11px;
	color: #0069b1;
	vertical-align: top;
}
tr th {
	text-align: center;
	background-color: #D5E7F4;
   font-variant: small-caps
}

</style>

<table border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
   <tr>
       <th>Trade name</th>
       <th>Manufacturer</th>
       <th>Components</th>
       <th>Med type</th>
    </tr>

$out
</table></div>";
} else {
	$out = "No results found.";
}
} else {
	$out = "No search Term specified.";
}

$url = "page_mod_search.txt";
$content = "";

$file = fopen("$url", "r");
if ($file) {
  while(!feof($file)) {
    $content .= fread($file, 4096);
  }
  fclose($file); 
}

$content = ereg_replace("###content###", $out, $content);

if ($content) {
	echo $content;
} else {
	echo $out;
}

function db_escape($str) {
 if (!$str) {return "null";} else {
	return "'" . ereg_replace("\\\\", "\\\\", ereg_replace("'", "''", $str)) . "'";
 }
}

function get_ref_table($table, $field_id, $field) {
	global $conn;

	$result["0"] = "";
	$sql = "select * from $table";

	if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
	while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$id = $row["$field_id"];
		$name = $row["$field"];
#echo "<br>$table: $id - $name";
		$result["$id"] = $name;
	}
	return $result;
}
?>
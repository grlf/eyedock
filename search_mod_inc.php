<?
defined('_JEXEC') OR defined('_VALID_MOS') OR die( "Direct Access Is Not Allowed" );

require_once "configuration.php";
global $chem_arr;

$c = new JConfig();
$conn = mysql_connect($c->host, $c->user, $c->password) or die ("There appears to be a database error (Error 101), please contact site administrator");
mysql_select_db("eyedock_data", $conn) or die ("There appears to be a database error (Error 101), please contact site administrator");

$q = null;
if (isset($_POST['q'])) {
	$q = $_POST['q'];
}elseif(isset($_GET['q'])) {
	$q = $_GET['q'];
}
if (preg_match("/^(.*) \(Meds\)$/", $q, $m)) {$q = $m[1];}

$id = null;
if (isset($_GET["s_id"])) {
	$id = $_GET["s_id"];
}

$adv = (isset($_POST['adv_search']) ? true : false);

if ($q || $id || $adv) {

  if ($id) {
    $sql = "SELECT *
	FROM pn_rx_meds WHERE pn_display = 'true'
	AND pn_med_id = " . db_escape($id) . "
	ORDER BY pn_trade";
  } elseif($adv){

		$adv_search = $_POST['adv_search'];

		$where = array();
		if($adv_search['manufacturer'] > 0){
			$where[] = "m.pn_comp_id = '" . intval($adv_search['manufacturer']) . "'";
		}
		if($adv_search['preservative'] > 0){
			$where[] = "(m.pn_pres_id1 = '" . intval($adv_search['preservative']) . "' or m.pn_pres_id2 = '" . intval($adv_search['preservative']) . "')";
		}
		if($adv_search['type'] != ''){
			$where[] = "(m.pn_medType1 like '%" . addslashes($adv_search['type']) . "%' or m.pn_medType2 like '%" . addslashes($adv_search['type']) . "%')";
		}
		if($adv_search['methods'] > 0){
			$where[] = "(m.pn_moa_id1 = '" . intval($adv_search['methods']) . "' or 
				m.pn_moa_id2 = '" . intval($adv_search['methods']) . "' or 
				m.pn_moa_id3 = '" . intval($adv_search['methods']) . "' or 
				m.pn_moa_id4 = '" . intval($adv_search['methods']) . "')";
		}
		if($adv_search['pregclass'] != ''){
			$adv_search['pregclass'] = substr(preg_replace("/[^a-z]/i", '', $adv_search['pregclass']), 0, 1);
			$where[] = "m.pn_preg = '" . $adv_search['pregclass'] . "'";
		}
		if(isset($adv_search['generic']) && $adv_search['generic'] == 'Y'){
			$where[] = "m.pn_generic = 'yes'";
		}

		if(!empty($where)){
			$where = ' AND ' . implode(' AND ', $where);
		}else{
			$where = '';
		}

		$sql = "SELECT m.* 
			FROM pn_rx_meds m WHERE m.pn_display = 'true' ".
			$where . "
			ORDER BY m.pn_trade";

  } else {
    $sql = "SELECT *
	FROM pn_rx_meds WHERE pn_display = 'true'
	AND pn_trade LIKE " . db_escape("%$q%") . "
	union
	select m.* from pn_rx_meds m join pn_rx_chem c on m.pn_chem_id1 = pn_chem_id
	WHERE pn_display = 'true'
	AND pn_name LIKE " . db_escape("%$q%") . "
	ORDER BY pn_trade";
    
  }

  $url = $_SERVER["REQUEST_URI"];

  $bg_color = "#FFFFFF";

  $chem_arr = get_ref_table("pn_rx_chem", "pn_chem_id", "pn_name", $conn);
  $comp_arr = get_ref_table("pn_rx_company", "pn_comp_id", "pn_name", $conn);

  if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
	$count = 0;
	$out ='';
  while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$found = 1;
	$count++;

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
       <td><!--<a href=\"/module-Meds-display-med_id-$id-search-1.htm\"--><a href=\"$url&s_id=$id\" title=\"$trade\">$trade</a></td>
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
	$last_row = $row;
  }


  if ($count == "1") {
	$out = show_product($last_row, $conn);
  } elseif ($found) {
	$out = "<div style=\"padding:0 25px 0 0;\">

<style>
td {
	font-family: verdana, helvetica, sans-serif;
	font-size: 11px;
	color: #0069b1;
	vertical-align: top;
	padding: 4px 4px 4px 4px;
}
tr th {
	text-align: center;
	background-color: #D5E7F4;
   font-variant: small-caps
}

#overDiv {
	background-color: #FFF;
	border: 1px solid gray;
	padding: 10px;
	}

</style>

<table border=\"1\" cellspacing=\"0\" cellPadding=\"4\" width=\"100%\">
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
  #go back to joomla database:
  mysql_select_db("eyedockjoomla", $conn);
} else {
	$out = "No search Term specified.";
}

if(!empty($adv_search)){
	$out .= '
<script type="text/javascript">
/* <![CDATA[ */
advsearch = {}
';
	foreach($adv_search as $k => $v){
		$out .= 'advsearch[\'' . htmlentities($k) . '\'] = \'' . htmlentities($v) . '\'' . "\n";
	}
	$out .= '
/* ]]> */
</script>';
}

echo "<br><br>$out";


function db_escape($str) {
 if (!$str) {return "null";} else {
	//return "'" . preg_replace("/\\\\/", "/\\\\/", preg_replace("'", "''", $str)) . "'";
	return "'" . mysql_real_escape_string($str) . "'";
 }
}

function get_ref_table($table, $field_id, $field, $conn) {
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

function get_table_row($table, $id_col, $id, $conn) {
	$result["0"] = "";
	$sql = "select * from $table where $id_col = " . db_escape($id);

	if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
	if ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
		return $row;
	} else {
		return array();
	}
}

function show_product($row, $conn) {
	global $chem_arr;

	/****
	 * Greenleaf - MJS - 10/27/14
	 * 
	 * Had to move these to the head.php of T3
	 */
	/*$doc = JFactory::getDocument();
	$doc->addStyleSheet("/modules/Meds/pnstyle/style.css");
	$doc->addStyleSheet("/themes/EyeDock/style/style.css");
	$doc->addScript("/javascript/overlib/overlib.js");*/

	$sched['I'] = "Schedule I drugs have no acceptable medical use in the united states and may not be prescribed.";
	$sched['II'] = "Schedule II drugs have a high potential for abuse and cannot be refilled.  In addition, These drugs cannot be prescribed by telephone.";
	$sched['III'] = "Schedule III drugs have moderate potential for dependence.  They can be refilled, but there is a five-refill maximum and the prescription is invalid after 6 months.";
	$sched['IV'] = "Schedule IV drugs have low potential for dependence.  They can be refilled, but there is a five-refill maximum and the prescription is invalid after 6 months.";
	$sched['V'] = "Schedule V drugs have the lowest abuse potential.  They can be dispensed without a prescription if the patient is 18 years old, distribution is by a pharmacist, and only a limited quantity of drug is purchased.";

	$pregs ['A']= " Class A: <i>Controlled studies show no risk</i>. Adequate, well-controlled studies in pregnant women have failed to demonstrate a risk to the fetus in any trimester of pregnancy.";
	$pregs['B'] = "Class B: <i>No evidence of risk in humans.</i>  Adequate, well-controlled studies in pregnant women have not shown increased risk of fetal abnormalities despite adverse findings in animals, or, in the absence of adequate human studies, animal studies show no fetal risk. The chance of fetal harm is remote, but remains a possibility.";
	$pregs['C']= "Class C: <i>Risk cannot be ruled out</i>.  Adequate, well-controlled human studies are lacking, and animal studies have shown a risk to the fetus or are lacking as well. There is a chance of fetal harm if the drug is administered during pregnancy; but the potential benefits may outweigh the potential risks.";
	$pregs['D'] = "Class D: <i>Positive evidence of risk</i>.  Studies in humans, or investigational or post-marketing data, have demonstrated fetal risk. Nevertheless, potential benefits from the use of the drug may outweigh the potential risk. For example, the drug may be acceptable if needed in a life-threatening situation or serious disease for which safer drugs cannot be used or are ineffective.";
	$pregs['X'] = "Class X: <i>Contraindicated in pregnancy.</i>  Studies in animals or humans, or investigational or post-marketing reports, have demonstrated positive evidence of fetal abnormalities or risks which clearly outweighs any possible benefit to the patient.";

	$moa[1] = get_table_row("pn_rx_moa", "pn_moa_id", $row["pn_moa_id1"], $conn);
	$moa[2] = get_table_row("pn_rx_moa", "pn_moa_id", $row["pn_moa_id2"], $conn);
	$moa[3] = get_table_row("pn_rx_moa", "pn_moa_id", $row["pn_moa_id3"], $conn);
	$moa[4] = get_table_row("pn_rx_moa", "pn_moa_id", $row["pn_moa_id4"], $conn);
	$company = get_table_row("pn_rx_company", "pn_comp_id", $row["pn_comp_id"], $conn);
	$preserve = get_table_row("pn_rx_preserve", "pn_pres_id", $row["pn_pres_id1"], $conn);
	$preserve2 = get_table_row("pn_rx_preserve", "pn_pres_id", $row["pn_pres_id2"], $conn);
	$out = "

<style>
.meds_helpcue {
	cursor: help; 
	border-bottom: 1px solid #7CC1FC;
        padding: 2px 5px;
}
.meds_helpcue:hover{
        background:#E5EEEF;
        
}

</style>
<p>
<table class=\"meds_table_max\" style=\"background:#FFFFFF;\" border=\"0\">
    <tr class=\"meds_table_head\">
        <td colspan=\"4\" class=\"align_left\" style=\"padding:5px;font-size:16pt;\" align=\"left\">
            " . $row["pn_trade"] . " 
        </td>

    </tr>
    <tr  style=\"padding:5px;\">
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Manufacturer:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";

	$comp_desc = $company["pn_phone"] . "<br />" . $company["pn_street"] . "<br />" . $company["pn_city"] . ", " . $company["pn_state"] . " " . $company["pn_zip"] . "<br />";
	if ($company["pn_url"]) {$comp_desc .= "<a href='" . $company["pn_url"] . "' target='_blank'>Their website</a><br />";}

	$out .= print_popup($company["pn_name"], $comp_desc);
	$out .= "
        </td>

        <td colspan=\"2\" rowspan=\"11\" style=\"vertical-align:top;text-align:center; padding:5px;\">
	<img class=\"meds_product_image\" src=\"";

	if ($row["pn_image1"]) {$out .= "/modules/Meds/pnimages/" . $row["pn_image1"] . "\" alt = \"" . $row["pn_image1"] . "\" />";}
	else {$out .= "/modules/Meds/pnimages/No-Picture-Available.jpg\" alt=\"No image available\" />";}
	if ($row["pn_image2"]) {$out .= "<img class=\"meds_product_image\" src=\"/modules/Meds/pnimages/" . $row["pn_image2"] . "\" alt = \"" . $row["pn_image2"] . "\" />";}

 	$out .= " </td>
    </tr>
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Med Type:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_medType1"]) {$out .= $row["pn_medType1"];} else {$out .= "None Specified";}
	if ($row["pn_medType2"]) {$out .= "<br>(" . $row["pn_medType2"]. ")";}
	$out .= "</td>
    </tr>

    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Preservative:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_pres_id1"]) {
		$out .= print_popup($preserve["pn_name"], $preserve["pn_comments"]);
	} else {
		$out .= "Not Specified";
	}
	if ($row["pn_pres_id2"]) {
		$out .= print_popup($preserve2["pn_name"], $preserve2["pn_comments"]);
	}
   	$out .= "        
        </td>
    </tr>

    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Pediatrics:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";

	if ($row["pn_peds"] > 0) {$out .= $row["pn_peds"] . " " . $row["pn_ped_text"] . " and older";} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>

    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Schedule:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_schedule"]) {$out .= print_popup($row["pn_schedule"], $sched[$row["pn_schedule"]]);} else {$out .= "Not Specified";}
	$out .= "</td>
    </tr>
    <tr>

        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Pregnancy Class:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_preg"]) {$out .= print_popup($row["pn_preg"], $pregs[$row["pn_preg"]]);} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>

    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Nursing:</td>
        <td class=\"align_left\" colspan=\"3\" style=\"padding:5px;\">";
	if ($row["pn_nurse"]) {$out .= $row["pn_nurse"];} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Generic:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_generic"]) {$out .= $row["pn_generic"];} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Website:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_med_url"]) {$out .= "<a href=\"" . $row["pn_med_url"] . "\" target=\"_blank\" title=\"" . $row["pn_trade"] . "  on the web\">" .  tr_truncate($row["pn_med_url"], 20) . "</a>";} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>
    </tr>
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Rx Info:</td>
        <td class=\"align_left\" style=\"padding:5px;\">";
	if ($row["pn_rxInfo"]) {$out .= "<a href=\"" . $row["pn_rxInfo"] . "\" target=\"_blank\" title=\"RX Information\">" .  tr_truncate($row["pn_rxInfo"], 20) . "</a>";} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>


    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Dose:</td>
        <td class=\"align_left\" colspan=\"3\" style=\"padding:5px;\">";
	if ($row["pn_dose"]) {$out .= $row["pn_dose"];} else {$out .= "Not Specified";}

	$out .= "</td>
    </tr>
    
    <tr>

        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Components</td>
        <td class=\"meds_center_strong_bg\" style=\"padding:5px;\">Concentration</td>
        <td class=\"meds_center_strong_bg\" style=\"padding:5px;\">Chemical</td>
        <td class=\"meds_center_strong_bg\" style=\"padding:5px;\">Method Of Action</td>
    </tr>";

    for ($i = 1; $i <= 4; $i++) {
	$out .= "
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">#$i:</td>

        <td class=\"align_center\" style=\"padding:5px;\">";

	if ($row["pn_conc$i"]) {$out .= $row["pn_conc$i"];} else {$out .= "--";}
	$out .= "</td> 
        <td class=\"align_center\" style=\"padding:5px;\">";
	if ($row["pn_chem_id$i"]) {$out .= $chem_arr[$row["pn_chem_id$i"]];} else {$out .= "--";}
	$out .= "</td>
        <td class=\"align_center\" style=\"padding:5px;\">";
	if ($row["pn_moa_id$i"]) {$out .= print_popup($moa[$i]["pn_name"], $moa[$i]["pn_comments"]);} else {$out .= "--";}
	$out .= "</td>

    </tr>";
    }
	$out .= "
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Packages</td>
        <td class=\"meds_center_strong_bg\" style=\"padding:5px;\">Form</td>
        <td class=\"meds_center_strong_bg\" style=\"padding:5px;\">Size</td>
        <td class=\"meds_center_strong_bg\" style=\"padding:5px;\"><span class=\"meds_helpcue\" onClick=\"return overlib('The <i>Estimated Retail Cost</i> represents the medicine\'s cost on Drugstore.com (or if not available there, through Walgreens.com).  The cost is recorded at the time of the medicine\'s last update, indicated at the bottom of the corresponding webpage.  Of course, the actual cost of the medicine may differ depending on the dispensing pharmacy and market fluctuations.  The purpose of the estimated retail cost is to give you an idea of how much your patient might pay for the medicine.',STICKY,CAPTION,'Where does cost info come from?' );\" >Cost</span></td>
    </tr>";
    for ($i = 1; $i <= 4; $i++) {
	$out .= "
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">#$i:</td>
        <td class=\"align_center\" style=\"padding:5px;\">";
	if ($row["pn_form$i"]) {$out .= $row["pn_form$i"];} else {$out .= "--";}
	$out .= "</td> 
        <td class=\"align_center\" style=\"padding:5px;\">";
	if ($row["pn_size$i"]) {$out .= $row["pn_size$i"];} else {$out .= "--";}
	$out .= "</td> 
        <td class=\"align_center\" style=\"padding:5px;\">";
	if ($row["pn_cost$i"]) {$out .= $row["pn_cost$i"];} else {$out .= "--";}
	$out .= "</td> 
    </tr>";
    }
	$out .= "
    <tr>
        <td class=\"meds_right_strong_bg\" style=\"padding:5px;\">Comments:</td>
        <td class=\"align_left\" colspan=\"3\" style=\"padding:5px;\">";
	if ($row["pn_comments"]) {$out .= $row["pn_comments"];} else {$out .= "Not Specified";}
	$out .= "
	</td>
    </tr>
        <tr style=\"background:#e5e5e5;\">
        <td class=\"meds_width_25\">&nbsp;</td>
        <td class=\"meds_width_25\">&nbsp;</td>
        <td class=\"meds_width_25\">&nbsp;</td>
        <td class=\"meds_width_25\">&nbsp;</td>
    </tr>
</table>";
	return $out;

}

function print_popup($name, $comments) {
	$comments = preg_replace("/(\r)?\n/", "<br>", $comments);
	return "<span class=\"meds_helpcue\" onClick=\"return overlib('" . str_replace("'", "\'", $comments) . "',STICKY,CAPTION,'" . str_replace("'", "\\\'", $name) . "');\" >$name</span>";
}
function tr_truncate($str, $len) {
	if (strlen($str) > $len) {
		$str = substr($str, 0, $len - 2) . "...";
	}
	return $str;
}
?>

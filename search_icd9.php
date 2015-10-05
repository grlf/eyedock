<?
require_once "configuration.php";

$q = $_POST["q"];
if (!$q) {$q = $_GET["q"];}
if (preg_match("/^(.*) \(Icd-9\)$/", $q, $m)) {$q = $m[1];}

$show = $_GET["show"];

if ($q) {

#$tid, $code, $letter, $tab, $diagnosis, $comment,
#                     $sec_id, $sec, $sec_lo, $sec_hi, $sec_letter, $sec_desc,
#                     $cat_id, $cat, $cat_lo, $cat_hi, $cat_letter, $cat_desc


$sql = "SELECT pn_icd9.*,
	pn_icd9_section.pn_tid as sec_id, pn_icd9_section.pn_section as sec, pn_icd9_section.pn_low as sec_lo, pn_icd9_section.pn_high as sec_hi,  pn_icd9_section.pn_desc as sec_desc,
	pn_icd9_category.pn_tid as cat_id, pn_icd9_category.pn_section as cat, pn_icd9_category.pn_low as cat_lo, pn_icd9_category.pn_high as cat_hi,  pn_icd9_category.pn_desc as cat_desc
	FROM pn_icd9
             LEFT JOIN pn_icd9_section
                  ON ( pn_icd9_section.pn_high >= pn_code
                  AND pn_icd9_section.pn_low <= pn_code
                  AND pn_icd9.pn_letter = pn_icd9_section.pn_letter )
             LEFT JOIN pn_icd9_category
                  ON (pn_icd9_category .pn_high >= pn_code
                  AND   pn_icd9_category .pn_low <= pn_code
                  AND pn_icd9.pn_letter = pn_icd9_category.pn_letter)
	WHERE pn_icd9.pn_diagnosis LIKE " . db_escape("%$q%") . " || pn_icd9.pn_comment LIKE " . db_escape("%$q%") . "
	order by pn_icd9.pn_letter, pn_icd9.pn_code LIMIT 100";


$c = new JConfig();


$conn = mysql_connect($c->host, $c->user, $c->password) or die ("There appears to be a database error (Error 101), please contact site administrator");
mysql_select_db("eyedock_data", $conn) or die ("There appears to be a database error (Error 101), please contact site administrator");

$bg_color = "#FFFFFF";

$found = 0;
$end = 0;

if (!$res = mysql_query($sql, $conn)) {echo "error: " . mysql_errno($conn) . " : " . mysql_error($conn);}
while (($row = mysql_fetch_array($res, MYSQL_ASSOC)) && !$end) {
  $found++;

#if ($found == 1) {print_r($row);}
  if ($found < 100 || $show = "all") {
	$id = $row["pn_tid"];
	$code = $row["pn_code"];
	$letter = $row["pn_letter"];
	$tab = $row["pn_tab"];
	$diagnosis = $row["pn_diagnosis"];

	$codes[$id][sec_id] = $row["sec_id"];
	$codes[$id][sec] = $row["sec"];
	$codes[$id][sec_range] = $letter.$row[sec_lo]."-".$letter.$row[sec_hi];
	$codes[$id][sec_desc] = $row["sec_desc"];
	$codes[$id][cat_id] = $row["cat_id"];
	$codes[$id][cat] = $row["cat"];
	$codes[$id][cat_range] = $letter.$row[cat_lo]."-".$letter.$row[cat_hi];
	$codes[$id][cat_desc] = $row["cat_desc"];
	$codes[$id][searched] = 1;
	$codes[$id][tid] = $id;
	$codes[$id][diagnosis] = $diagnosis;
	$codes[$id][letter] = $letter;
	$codes[$id][code] = $code;
	$codes[$id][tab] = $tab;
  } else {
	$end = 1;
  }
}

if ($found) {$class = "found_highlight";} else {$class = "notfound_highlight";}

$out = "<script>
   function toggleHiddenDiv(icd9Div){
         if (document.getElementById(icd9Div).style.display=='none'){
              document.getElementById(icd9Div).style.width='350px'
              document.getElementById(icd9Div).style.display='block'
         } else {
              document.getElementById(icd9Div).style.display='none'
         }
   }

</script>
<style>
  .tmz_gray {
	background-color: #F0F0F0;
	border: 1px solid gray;
	width: 90%;
	position:relative;
	padding:1em;
}
</style>

  <div class=\"$class\">";

if ($tab_limit == 1) {
	$out .= "There were a lot of results for your search!  To conserve resources we've <strong>only displayed the zero-digit codes.</strong>  You can browse more specific codes by choosing amongst these options.";
} elseif ($found > 99) {
	$out .= "There were a lot of results for your search!  To conserve resources we've <strong>only displayed the first 100 codes</strong>.  Please use more specific search criterion to get a smaller result set.";
} elseif (!$found) {
	$out .= "<strong>Sorry, no results were found.</strong>  Consider searching by a different phrase or using the \"Browse Codes\" feature.";
} else {
	$out .= "<strong>$found results were found</strong>.  Click the codes to see more information about that diagnosis.";
}
$out .= "</div>";

if ($codes) {    
  while (list($id, $code) = each($codes)) {
       	if ($sec_id != $code[sec_id] && $code[sec_id]) {
		$out .= "
	    <div style=\"margin: 2.5em 1em 1em; color: #000;\">
               <a href=\"javascript:toggleHiddenDiv('icd9_sec$code[sec_id]');\" style=\"color: #000; background-color: #ddffee; border: 1px solid black; padding: 3px; \" title=\"$code[sec]\"> " . substr($code[sec], 0, 50) . " ($code[sec_range])</a>

               <div class=\"tmz_gray\" style=\"display: none;\" id=\"icd9_sec$code[sec_id]\">
                    $code[sec]<br />
                    $code[sec_desc]<br />
                    <div style=\"text-align:right; margin-top: .6em;\"><a href='index.php?module=icd9&func=view&browse=1&q=$code[sec_range]'  style=\"text-align:right; margin-top: .6em;\">Search for all codes in this category</a></div>
               </div>
           </div>
"; 
	}
	$sec_id = $code[sec_id];

	if ($cat_id != $code[cat_id] && $code[cat_id]) {
		$out .= "
	     <div style=\"margin: 1.5em 1em 1em 2em; color: #000;\">
                 <a href=\"javascript:toggleHiddenDiv('icd9_cat$code[cat_id]');\"  style=\"color: #000; background-color: #ffffcc; border: 1px solid black; padding: 3px;\" title=\"\$code[cat]\"> " . substr($code[cat], 0, 50) . " ($code[cat_range])</a>

               <div class=\"tmz_gray\" style=\"display: none;\" id=\"icd9_cat$code[cat_id]\">
                    $code[cat]<br />
                    $code[cat_desc]<br />
                    <div style=\"text-align:right; margin-top: .6em;\"><a href='index.php?module=icd9&func=view&browse=1&q=$code[cat_range]'>Search for all codes in this sub-category</a></div>
               </div>
            </div>
";
	}
	$cat_id = $code[cat_id];

	$tab = $code[tab];
	$letter = $code[letter];


	if ($tab == 0) {$margin = "3em"; $bg_color = "orange";}
	if ($tab == 1) {$margin = "5em"; $bg_color = "yellow";}
	if ($tab == 2) {$margin = "7em"; $bg_color = "cyan";}
	if ($letter) {$margin = "3em";}

	if ($code[searched] == 0) {$color = "gray"; $bg_color = "#CCCCCC";} else {$color = "#000";}

	if ($letter == 'E') {$bg_color = "#66ff33";}
	if ($letter == 'V') {$bg_color = "#bb77ff";}

	$out .= "    
            <div style=\"margin:.6em; margin-left: $margin; color: $color;\">
      
      <a href=\"javascript:toggleHiddenDiv('icd9_$code[tid]');\"
      style=\"margin-right: 1em; color: $color; padding: 1px; border: 1px solid gray; background-color: $bg_color;\">$letter";

	if ($tab == "1" || $tab == "3") {$out .= sprintf("%04.1f", $code[code]);}
	if ($tab == "2") {$out .= sprintf("%05.2f", $code[code]);}
	if ($tab == "0") {$out .= sprintf("%02.0f", $code[code]);}

	$out .= " </a> " . substr($code[diagnosis], 0, 60) . "
        <div class=\"tmz_gray\" style=\"padding-top: 0px;  display: none;\" id=\"icd9_$code[tid]\">
             " . process_str($code[diagnosis]) . "
             
             <div style=\"text-align:right; margin-top: .6em;\">";
		$rounddown = preg_replace( "/\.[0-9]?[0-9]?/", "", $code[code]);
	if ($letter == "") {
		$out .= " <a href='index.php?module=icd9&func=view&browse=1&q=$rounddown:" . (0.99 + $rounddown) . "'  title='$code[cat]'>Search for all the \"$rounddown\" codes </a> |";
	}

	$out .= "<a href=\"pnmodurl modname='icd9' type='user' func='get' tid=$code[tid]\">more info</a></div>
	</div>
    </div>";

  }
  $out .= '
  <p>&nbsp;</p>
  <p> <fieldset style="width:400px;">
  <legend><strong>Key</strong></legend>
     <table style="padding:5px;" >
       <tr>
           <td style="padding:5px;"><span style="color: #000; background-color: #ddffee; border: 1px solid black; padding: 3px; ">category</span></td>
           <td style="padding:5px;"><span style="color: #000; background-color: #ffffcc; border: 1px solid black; padding: 3px; ">sub-category</span></td>
           <td></td>
      </tr><tr>
           <td style="padding:5px;"><span style="color: #000; background-color: orange; padding: 1px; border: 1px solid gray; ">zero-digit code</span></td>
           <td style="padding:5px;"><span style="color: #000; background-color: yellow; padding: 1px; border: 1px solid gray;">one-digit code</span></td>
           <td style="padding:5px;"><span style="color: #000; background-color: cyan; padding: 1px; border: 1px solid gray;">two-digit code</span></td>
      </tr><tr>
           <td style="padding:5px;"><span style="color: #000; background-color: #bb77ff; padding: 1px; border: 1px solid gray;">V codes</span></td>
           <td style="padding:5px;"><span style="color: #000; background-color: #66ff33; padding: 1px; border: 1px solid gray;">E codes</span></td>
           <td style="padding:5px;"></td>
      </tr><tr>
               <td colspan="3"><span style="color: gray; background-color: #CCCCCC; padding: 1px; border: 1px solid gray;" >Unsearched parent</span><span style="font-size:smaller"> (a code that does not contain the phrase you searched for, but is a parent of a code that does)</span></td>
       </tr>
     </table>
</fieldset>
';

}
    
if ($found) {
#	$out .= "<br><ul>$out</ul>";
# 
#	if ($found > 14) {$out .= "<p><a href=\"search_icd9.php?q=" . urlencode($q) . "&show=all\">...See ALL ICD-9 search results</a></p>";
} else {
#	$out .= "No lenses were found.";
}
} else {
#	$out .= "No search Term specified.";
}

$url = "page_idc9_search.txt";
$content = "";

$file = fopen("$url", "r");
if ($file) {
  while(!feof($file)) {
    $content .= fread($file, 4096);
  }
  fclose($file); 
}

$content = str_replace("###title###", "", $content);
$content = str_replace("###content###", $out, $content);

$content = str_replace("</head>",'<link rel="stylesheet" href="themes/EyeDock/style/style.css" type="text/css" /></head>', $content);


if ($content) {
	echo $content;
} else {
	echo $out;
}

function process_str($str) {
	$words = explode(" ", $str);
	while (list(, $word) = each($words)) {
	#	$letter = substr($word, 0, 1);
		if (preg_match("/^[A-Z]/", $word)) {$out .= "<br>";}
	#	if (strtoupper($letter) == $letter) {$out .= "<br>";}
		$out .= " $word";
	}
	return $out;
}

function db_escape($str) {
 if (!$str) {return "null";} else {
	//return "'" . preg_replace("\\\\", "\\\\", ereg_replace("'", "''", $str)) . "'";
	return "'" . mysql_real_escape_string($str) . "'";
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

		$result["$id"] = $name;
	}
	return $result;
}
?>

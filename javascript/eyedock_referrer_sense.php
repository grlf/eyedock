<?
/*		2009_07_21 20:26 PDT (GMT -0700) Greenleaf IL
		 referrer sensitive auto login
		see also: http://www.eyedock.com/amember/admin/eyedock_referrer_login.php
		
*/
include_once($_SERVER['DOCUMENT_ROOT']."/amember/config.inc.php");
$js = '';

// check if referrer is in our list of auto login allowed referrers
$referrer = $_SERVER['HTTP_REFERER'];
$sql = "SELECT * from eyedock_referrer where url = '$referrer'";
$r = mysql_query($sql) or die (mysql_error());
if(mysql_num_rows($r) >0){
	$valid_referrer = TRUE;
	while($row = mysql_fetch_assoc($r)){
		$user_data = $row;
		}
//	echo "<pre>";print_r($user_data);echo "</pre>\n";//debug
	
	// Retrieve password for this user
	$sql = "SELECT pass from amember_members where login ='".$user_data['login']."'";
	$r = mysql_query($sql) or die (mysql_error());
	if(mysql_num_rows($r) >0){
	while($row = mysql_fetch_assoc($r)){
		$user_data['pass'] = $row['pass'];
		}
	}
//	echo "<pre>";print_r($user_data);echo "</pre>\n";//debug
	
	
$js = <<<EOD
<script type="text/javascript">
// when page is loaded set login
$(document).ready(function(){

	var ed_un =  '{$user_data['login']}';
	var ed_pw =  '{$user_data['pass']}';


	$("#username").val(ed_un);
	$("#password").val(ed_pw);
	$("form:first").submit();

});
</script>
EOD;
		
	}else{
$js = <<<EOD

<!-- ref: $referrer -->

EOD;

	}

echo "<!--  ";//debug
echo "\n\n\n\n\n\n\n";//debug
echo " _referrer_sense ";
echo "\n\n -->";//debug

echo $js;

?>
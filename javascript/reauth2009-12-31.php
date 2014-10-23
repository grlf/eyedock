<?
//ini_set('include_path',ini_get( 'include_path' ) . PATH_SEPARATOR . "/home/eyedockuser/pear/php");
//set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/local/lib/php');
//include("Net/Dig.php");

// see  http://manual.amember.com/AMember_Session_Variables
//	http://wiki.dreamhost.com/PEAR

include($_SERVER['DOCUMENT_ROOT'].'/config_session.php');
session_start();

header("Content-Type: application/javascript");

include($_SERVER['DOCUMENT_ROOT']."/amember/config.inc.php");

if (count($_SESSION['_amember_product_ids']) > 0){
	// Valid user
	echo "// valid user";//debug
	}else{
	// Not Valid User
	// What domains are allowed?
//	$allowed_domains = explode(',',"navy.gov,pacificu.edu,sb.sd.cox.net,dsl.mdsnwi.ameritech.net");  // This includes mary and Todd

	$remote_addr = $_SERVER['REMOTE_ADDR'];
	
	//	What is name associated with Remote_ADDR?  Do a reverse lookup
	$remote_host = gethostbyaddr($remote_addr);
	
	// is this host in the allowed domains?
	$host_parts = explode('.',$remote_host);
	$junk= array_shift($host_parts);
	$remote_domain = implode('.',$host_parts);

	// Check if request is from an allowed domain.
	$sql = "select * from eyedock_autologin where domain = '$remote_domain'";
	$query_result  = mysql_query($sql) or die(mysql_error());
	$r = (bool) mysql_num_rows($query_result);

	if($r){
		// remote host is in allowed domains so we deliver payload JS
$js = <<<EOD
// hello
// when page is loaded set login
$(document).ready(function(){
		var ed_un =  'SpecialCmnMilEdu';
		var ed_pw =  'wP8n#xc0xre';
		$("#username").val(ed_un);
		$("#password").val(ed_pw);
		$("form:first").submit();
	});
EOD;
		echo $js;
		}else{
		// perhaps domain reverse lookup failed so we'll check IP address ranges too
		$passed = FALSE;
		$sql = "select * from eyedock_autologin where beginIP != ''";
		$query_result  = mysql_query($sql) or die(mysql_error());
		if($query_result){
			while($row = mysql_fetch_assoc($query_result)){
				if(trim($row['endIP']) != ''){
					$passed = in_ip_range($row['beginIP'],$row['endIP']);
					}else{
					$passed = in_ip_range($row['beginIP']);
					}
				}
			}
		
		if($passed){
		// remote host is in allowed domains so we deliver payload JS
$js = <<<EOD
// hello
// when page is loaded set login
$(document).ready(function(){
		var ed_un =  'SpecialCmnMilEdu';
		var ed_pw =  'wP8n#xc0xre';
		$("#username").val(ed_un);
		$("#password").val(ed_pw);
		$("form:first").submit();
	});
EOD;
		echo $js;
		
			}
		
		}
/*		2009_07_21 20:26 PDT (GMT -0700) Greenleaf IL
		 referrer sensitive auto login
		see also: http://www.eyedock.com/amember/admin/eyedock_referrer_login.php
		
*/
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
// when page is loaded set login
$(document).ready(function(){

		var ed_un =  '{$user_data['login']}';
		var ed_pw =  '{$user_data['pass']}';


		$("#username").val(ed_un);
		$("#password").val(ed_pw);
		$("form:first").submit();

	});

EOD;
			echo $js;exit;  //debug
			
		}else{
		echo "// nothing to see here ".$referrer;
		}
	

	
	}










// -----------------------------------------------------------------------------
function in_ip_range($ip_one, $ip_two=false){ 
//http://us.php.net/manual/en/function.ip2long.php
//mhakopian at gmail dot com 11-Feb-2008 07:46
//Just a little function to check visitor's ip if it is in given range or not 
//usage :echo in_ip_range('192.168.0.0','192.168.1.254'); 
    if($ip_two===false){ 
        if($ip_one==$_SERVER['REMOTE_ADDR']){ 
            $ip=true; 
        }else{ 
            $ip=false; 
        } 
    }else{ 
        if(ip2long($ip_one)<=ip2long($_SERVER['REMOTE_ADDR']) && ip2long($ip_two)>=ip2long($_SERVER['REMOTE_ADDR'])){ 
            $ip=true; 
        }else{ 
            $ip=false; 
        } 
    } 
    return $ip; 
} 

?>
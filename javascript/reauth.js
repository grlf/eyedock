// when page is loaded set login
$(document).ready(function(){

	var ed_un =  'USmilitary';
	var ed_pw =  '****************';
	var msg = "This is just a test page.  This alert will not appear during normal operation.  \n\n After clicking OK, you will be logged in.";

	if(confirm(msg)){
		$("#username").val(ed_un);
		$("#password").val(ed_pw);
		$("form:first").submit();
	}else{
	}

	});

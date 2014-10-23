
	jQuery().ready(function(){
		jQuery("#epocrates_form").submit(function(){
		url = 'https://online.epocrates.com/public/portkey/?monograph=' + jQuery("#epocrates_url").val()
		window.open(url, '_blank' );
		return false;
		
		});
	});
 
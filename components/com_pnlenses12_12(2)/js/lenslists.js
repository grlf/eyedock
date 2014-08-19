jQuery(document).ready(function(){
	jQuery(function() {
		jQuery( "#ed-lenses-settings" ).dialog({autoOpen:false, modal: true, title: "Lens Search Settings"});
	});
		
	
	jQuery( "#ed-lenses-settings input" ).change(function() {
		setLensListColumns ();
  		var settings = jQuery("#ed-lenses-settings :input").serialize() ;
		jQuery.post("/index.php?option=com_pnlenses&task=userPrefs&format=raw",{prefs:settings} );
		return false;
	});
	
        
		
});

function showLensListSettings () {
	jQuery( "#ed-lenses-settings" ).dialog( "open" );
	return false;
}

function printLensList () {
	jQuery('#ed-lenses-lists').printElement();
		return false;
}


function showLensList (result, listType) {
	
	jQuery('#adv-searches-loader').hide();


	jQuery("#ed-lenses-comparetable").html(result);
	
	jQuery('#ed-lenses-lists').fadeIn();
		
	jQuery(".tablesorter").tablesorter(); 
	
	//reset the columns with the user preferences (needed?)
	setLensListColumns ();

}

function setLensListColumns () {
	
	jQuery('#ed-lenses-settings input').each(function() {
		var thisId = this.id;
		var el = "." + thisId.replace("Cbx","Col");
		if (jQuery(this).is(':checked') ) {
			jQuery(el).show();
		} else {
			jQuery(el).hide();
		}
	});
	
}
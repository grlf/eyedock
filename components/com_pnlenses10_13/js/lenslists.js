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

	jQuery('#ed-lenses-search').show(); 	
	jQuery("#ed-lenses-comparetable").html(result);
	
	jQuery('#ed-lenses-lists').fadeIn();
	jQuery('#ed-lenses-display').fadeOut("slow");
		
	jQuery(".tablesorter").tablesorter({
		theme     : 'ice', 
    	widgets: ["stickyHeaders", "uitheme", "zebra"],
    	widgetOptions : {
      	// css class name applied to the sticky header
     	 stickyHeaders : "tablesorter-stickyHeader",
     	 zebra : [ "normal-row", "alt-row" ]
    	}
  }); 
  
  
	//reset the columns with the user preferences (needed?)
	setLensListColumns ();
	
	//if only one result returned, click it to display the details
	var rows = (jQuery("#ed-lenses-paramsList tr").length);  //why minus 2?
	//alert (rows);
	if (rows == 1) jQuery("#ed-lenses-paramsList tr:last").trigger("click");
	
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
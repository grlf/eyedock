var dkText = "Dk/t is calculated from listed dk and center thickness values. Actual Dk/t will vary.";

var ctText = "Center thickness varies by power. For the majority of manufacturers the listed value indicates the CT at -3.00 D.";

var fdaGroups = new Array();
fdaGroups[0] = "";
fdaGroups[1] = "Low Water (<50 percent water content), Nonionic Polymers.<br />This group has the greatest resistance to protein deposition due to its lower water content and nonionic nature. Heat, chemical, and hydrogen peroxide care regimens can be used.";
fdaGroups[2] = "High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in greater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be avoided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.";
fdaGroups[3] = "High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in greater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be avoided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.";
fdaGroups[4] = "High Water (greater then 50 percent water content), Ionic Polymers.<br />Because of the high water content and ionic nature of these polymers they attract more proteins than any other group. It is best to avoid heat disinfection and sorbic acid preservatives.";

jQuery(document).ready(function() {  

	/*
	jQuery(document).on("keyup.autocomplete", '#edas_mr', function(){ 
		jQuery(this).autocomplete({
			source: "/search_lenses.php?onlyRx=1"
		}); 
	})
	*/
	
	//autocomplete functions moved to lenses.js do_display_lens(id)
	
	/*
	jQuery(document).ready(function(){
		jQuery("#edas_mr").autocomplete({
			//TODO: add a flag for subscribers --> . . .&subscriber=1
			source: "/search2.php?mr=1&clRx=1"  
			
		});
	});
	

	jQuery( "#edas_mr" ).on( "autocompleteselect", function( event, ui ) {
			if (ui.item.type == "refraction") trialLensSearch (ui.item.rx);
			if (ui.item.type == "clRx") trialLensSearch (ui.item.rx); // todo make this a cl power

		} );
	*/	
    
});

function show_details_popup(which, theTitle){
	var theText = "";
	switch (which){
		case "company":
			theText = jQuery("#ed-ld-companyInfo").html();
			break;
		case "fda":
			theText = fdaGroups[parseInt(theTitle)];
			theTitle = "FDA Group " + theTitle;
			break;
		case "dk":
			theText = dkText;
			theTitle = "Regarding Dk";
			break;
		case "ct":
			theText = ctText;
			theTitle = "Regarding Center Thickness";
			break;
		case "images":
			theText = jQuery("#ed-ld-lensImages").html();
			theTitle = theTitle + " Images";
			break;
	}
	
	jQuery("<div>"+theText+"</div>").dialog({ title: theTitle, modal: true });
}

function detail_wholesale_toggle() {
	jQuery('#edas-ld-wholesale').toggle('slow');
	return false;
}

function printLensDetails() {
	jQuery('#ed-lenses-display').printElement( );
		return false;
}

function closeDetails(){
	//window.back doesn't work if arrived at the details view directly from an URL (an id in the query string)
	if (fromURL == 1) {
		fromURL = 0;
		var lensName = new Array(jQuery("#hiddenLensName").val() );
		window.location = 	"/index.php?option=com_pnlenses#view=list&phrase%5B%5D=" + lensName;
	} else {
		window.history.back();
	}
}

//triggers the trial lens searches when enter or tab are pressed
function findTrialLens(e) {
	var key = e.keyCode || e.which;
    if (key != 13) return;
    trialLensSearch ();
}
    
//the actual trial lens searches
function trialLensSearch (rx){  //todo allow for clRx too . . .
	var rx = jQuery('#edas_mr').val();
	//check if the rx is a CL - will default to it being a refraction otherwise

	var isCLRx = (rx.indexOf('(CL power)') == -1)?0:1;

	jQuery("#ed-suggest-loader").show();
	//var mr = jQuery('#edas_mr').val();
	jQuery.ajax({
		type: "POST",
		url: "/index.php?option=com_pnlenses&task=bestPowerForLens&search=lensPowers&format=raw",
		data: {lensID: currentLensID, refraction: rx, isCL: isCLRx},
		dataType: "html",
		success: function(result){
			jQuery('#ed-details-suggest-lenses').html( result);
			jQuery("#ed-suggest-loader").hide();
		}
    });
}

    

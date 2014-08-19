var dkText = "Dk/t is calculated from listed dk and center thickness values. Actual Dk/t will vary.";

var ctText = "Center thickness varies by power. For the majority of manufacturers the listed value indicates the CT at -3.00 D.";

var fdaGroups = new Array();
fdaGroups[0] = "";
fdaGroups[1] = "Low Water (<50 percent water content), Nonionic Polymers.<br />This group has the greatest resistance to protein deposition due to its lower water content and nonionic nature. Heat, chemical, and hydrogen peroxide care regimens can be used.";
fdaGroups[2] = "High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in greater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be avoided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.";
fdaGroups[3] = "High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in greater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be avoided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.";
fdaGroups[4] = "High Water (greater then 50 percent water content), Ionic Polymers.<br />Because of the high water content and ionic nature of these polymers they attract more proteins than any other group. It is best to avoid heat disinfection and sorbic acid preservatives.";

jQuery(document).ready(function() {        

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

function printLensDetails() {
	jQuery('#ed-lenses-display').printElement( );
		return false;
}
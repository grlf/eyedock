//var lastScreen;

//var rootURL = "http://localhost:8888/eyedock.com";
//var componentURL = "http://localhost:8888/eyedock.com/index.php?option=com_pnlenses";

var rootURL = "http://www.eyedock.com"
var componentURL = "http://www.eyedock.com/index.php?option=com_pnlenses";

//keeps track of which lens is displayed (it might be hidden). Then, if the user tries to view it again it can just be made visible instead of being pulled from the database again.
var currentLensID;

jQuery(document).ready(function(){
    
    jQuery('#ed-lenses-name_mr_input').formatRxField({round: .25, strict:1});

})

function do_close_lens(){
	 jQuery('#ed-lenses-display').fadeOut("slow");
	 jQuery('#ed-lenses-lists').fadeIn("slow");
	 jQuery('#ed-lenses-search').fadeIn("slow");
}


function do_display_lens(id){
		//jQuery('#ed-lenses-display-content').html('waiting');
		jQuery('#ed-lenses-lists').fadeOut(300);
		jQuery('#ed-lenses-search').fadeOut(300);
		
		//see if that lens is already displayed but hidden
		if (id == currentLensID) {
			jQuery('#ed-lenses-display').fadeIn(100);
		} else {
			jQuery.ajax({
				type: "POST",
				url: componentURL + "&task=details&format=raw",
				data: {'id': id},
				dataType: "html",
				success: function(result){
					jQuery('#ed-lenses-display-content').html(result);
					//don't show he content until the images are ready
						jQuery('#ed-lenses-display').fadeIn(300);					
					//jQuery(".ed-ld-lensImage").on("load", function() {

					//});
				}
			});
			currentLensID = id;
    	} 
    	
    	
}

function favStarClick (e, userID){
	var starLink = e.currentTarget; 
	var starID = jQuery(starLink).attr("id");
	var whichStar = starID.substr(starID.indexOf("-") + 1)
	var lensID = whichStar.replace(/\D/g, '');
	jQuery(starLink).toggleClass('on');
	var isOn = jQuery(starLink).hasClass('on')?1:0;
	//change the star in all locations (lens lists and lens details pages) as they my be hidden
	if (isOn == 1) {
		//alert ("#param-list-star-" + starID);
		jQuery("a[id$='" + whichStar +"']").addClass("on");
	} else {
		jQuery("a[id$='" + whichStar +"']").removeClass("on");
	}
	

	jQuery.post("/ratings/changeFavorite.php", {itemID: lensID, userID: userID, table: 'lenses', rating: isOn} );			
	
	e.cancelBubble = true;
	e.stopPropagation();
}
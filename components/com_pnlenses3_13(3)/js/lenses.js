//var lastScreen;

//var rootURL = "http://localhost:8888";
//var componentURL = "http://localhost:8888/index.php?option=com_pnlenses";

//var rootURL = "http://www.eyedock.com"
//var componentURL = "http://www.eyedock.com/index.php?option=com_pnlenses";

//keeps track of which lens is displayed (it might be hidden). Then, if the user tries to view it again it can just be made visible instead of being pulled from the database again.

//var lensesURL = "http://www.eyedock.com/index.php?option=com_pnlenses";

var searchData = "";
var lensIDhash = ""
var urlCache;
//var dontSearch = 0; // a flag - when it's "1" it won't search - used when populating the search form
var currentLensID;
var optionsPopulated = 0; //will be changed to "1" after the page is loaded and the select boxes are populated - needed because he hashchanged triggers on pageload (before these boxes are populated)


  //window.addEventListener('hashchange', eatHash, false);

jQuery(document).ready(function(){
    
    jQuery('#ed-lenses-name_mr_input').formatRxField({round: .25, strict:1});
    
    //if (window.location.hash.length) eatHash();
    
    //Grab hash off URL (default to first tab) and update
	jQuery(window).bind("hashchange", function(e) {
		//if (dontSearch == 1) return;
		var view = jQuery.bbq.getState("view");
	   //var anchor = jQuery(window.location.hash);
 		//alert (view);
	   if (view == "list") {
	   		var paramObj = jQuery.deparam.fragment();
			var data = new Object();
			data["params"]  = paramObj;
			if (paramObj == searchData) {
				showSearchResultsDivs ();
			} else {
				doCLsearch(data);
				populateSearchForm(paramObj);
			}
			searchData = paramObj;
		} else if (view == "detail") {
		 	var id = jQuery.bbq.getState("id");
		 	if (id == lensIDhash) {
				showLensDetailsDivs ();
			} else {
				do_display_lens(id);
				
			}
			lensIDhash = id;  		
	   }
	});
	
	jQuery(window).trigger('hashchange');

})

function setDetailsHash (lensID){
	jQuery.bbq.pushState({ view: "detail", id: lensID },2);
}

/*
function eatHash (){
	//var hash = window.location.hash.replace('#!','');
	//alert (hash.substring(0, 2));
	var paramObj = jQuery.deparam.fragment();
	alert (paramObj);
	
	if (paramObj.id) {
		//alert (paramObj.id);
		do_display_lens(paramObj.id); 
	} else {
		var data = new Object();
		data["params"]  = paramObj;
		doCLsearch(data);
	}
}
*/


// TODO: PUT THIS IN THE BUTTON SCRIPT 
/*function do_close_lens(){
	history.back();
	// jQuery('#ed-lenses-display').fadeOut("slow");
	 //jQuery('#ed-lenses-lists').fadeIn("slow");
	 //location.hash = searchHash;
	 //jQuery('#ed-lenses-search').fadeIn("slow");
}*/

function showSearchResultsDivs (){
	 jQuery('#ed-lenses-display').hide();
	 jQuery('#ed-lenses-lists').show();
}

function showLensDetailsDivs() {
	jQuery('#ed-lenses-lists').hide();
	jQuery('#ed-lenses-display').show();
}


function do_display_lens(id){
		//jQuery('#ed-lenses-display-content').html('waiting');
		jQuery('#ed-lenses-lists').fadeOut(300);
		//jQuery('#ed-lenses-search').fadeOut(300);
		
		//see if that lens is already displayed but hidden
		/*if (id == currentLensID) {
			jQuery('#ed-lenses-display').fadeIn(100);
		} else {*/
			jQuery.ajax({
				type: "POST",
				url: "/index.php?option=com_pnlenses&task=details&format=raw",
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
			//currentLensID = id;
			//var lensIDhash = "id=" + currentLensID; 
			//var newUrl = $.param.fragment( lensesURL, lensIDhash );
			//location.hash = lensIDhash;
			//jQuery.bbq.pushState({ view: "detail", id: currentLensID },2);
    	/*} */
    	
    	
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
	window.event.cancelBubble = true;
}

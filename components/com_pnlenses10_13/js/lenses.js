
var searchData = "";
//var lensIDhash = ""
var urlCache;
//var dontSearch = 0; // a flag - when it's "1" it won't search - used when populating the search form
var currentLensID = 7;
var optionsPopulated = 0; //will be changed to "1" after the page is loaded and the select boxes are populated - needed because hashchanged triggers on pageload (before these boxes are populated)
var currentView = "list";

var xhr = null; // global object for the ajax requests (this will help us cancel a request if a second one is made before it is finished)
var subscriber = 0; //a flag to identify subscribers (to only show subscriber related content). Is put into a hidden input by com_pnlenses/views/pnlenses/tmpl/default.php and then assigned this variable in the ready function below
var fromURL = 0; //if a search id is passed from the query string. needed for the details back button to function correctly

jQuery(document).ready(function(){
    
    //jQuery('#ed-lenses-name_mr_input').formatRxField({round: .25, strict:1});
    
    //if (window.location.hash.length) eatHash();
    
    
    
    //Grab hash off URL (default to first tab) and update
	jQuery(window).bind("hashchange", function(e) {
		
		
		
		//if lensID or compID are passed in the query string, and the "view" is not set, use those params for searches. This is used for sending searches in the URL and for SEO. This only works when the "view" so the hash vars can override the query string vars.
		if (!jQuery.bbq.getState("view") ) {
			var params = jQuery.deparam.querystring();
			var qLensID = params.lens_id;
			var qCompID = params.comp_id;
			var qLensName = params.lens_name;
			if (qLensID) {
				fromURL = 1;
				jQuery.bbq.pushState({ view: "detail", id: qLensID } );
			} else if (qLensName){
				var nameArr = new Array(qLensName); //needs to be in array form
				jQuery.bbq.pushState({ view: "list", phrase: nameArr } );
			} else if (qCompID) {
				var compArr = new Array(qCompID); //needs to be in array form
				jQuery.bbq.pushState({ view: "list", company: compArr } );
			}
		}
		
		var id = jQuery.bbq.getState("id");
		
		var oldView = currentView;
		currentView = jQuery.bbq.getState("view");
		
		//don't do more work than we have to - if the information is already loaded, just display it
		if (oldView == "detail" && currentView == "list") {
			showSearchResultsDivs ();
			return;
		}
		if (oldView == "list" && currentView == "detail" && id == currentLensID) {
			showLensDetailsDivs ();
			//however, we'll update the refraction suggestion as it may have changed
			populateDetailsSuggest ();
			
			return;
		}
		

	   if (currentView == "list") {
	   		var paramObj = jQuery.deparam.fragment();
			var data = new Object();
			data["params"]  = paramObj;
			jQuery("#ed-lenses-cross").html("&nbsp;");
			doCLsearch(data);
			populateSearchForm(paramObj);
			searchData = paramObj;
		} else if (currentView == "detail") {
			do_display_lens(id);
			if (optionsPopulated == 0) populateSearchForm(paramObj);
			currentLensID = id;  		
	   }

	});
	
	subscriber = jQuery("#lenses_subscriber").val();
	
	jQuery(window).trigger('hashchange');

})

function setDetailsHash (lensID){
	jQuery.bbq.pushState({ view: "detail", id: lensID } );
}



function showSearchResultsDivs (){
	jQuery('#ed-lenses-search').show();
	 jQuery('#ed-lenses-display').hide();
	 jQuery('#ed-lenses-lists').show();
}

function showLensDetailsDivs() {
	jQuery('#ed-lenses-search').hide();
	jQuery('#ed-lenses-lists').hide();
	jQuery('#ed-lenses-display').show();
}


function do_display_lens(id){
		
		//jQuery('#ed-lenses-display-content').html('waiting');
		
		//abort any unfinished ajax requests
		var fn = function(){
			if(xhr && xhr.readystate != 4){
				xhr.abort();
			}
		}
       
		jQuery('#ed-lenses-lists').fadeOut(300);
		jQuery('#adv-searches-loader').show();

			xhr = jQuery.ajax({
				type: "POST",
				url: "/index.php?option=com_pnlenses&task=details&format=raw",
				data: {'id': id},
				dataType: "html",
				success: function(result){
					jQuery('#adv-searches-loader').hide();
					jQuery('#ed-lenses-search').hide();
					jQuery('#ed-lenses-directions').hide();
					jQuery('#ed-lenses-display-content').html(result);
					//don't show he content until the images are ready
					jQuery('#ed-lenses-display').fadeIn(300);
					populateDetailsSuggest();
					//gapi.plusone.go(); //--> disable for testing
				}
			});
}

function populateDetailsSuggest () {	
	jQuery("#edas_mr").autocomplete({
			//TODO: add a flag for subscribers --> &subscriber=1
			source: "/search2.php?mr=1&clRx=1"  
			
		});
	jQuery( "#edas_mr" ).on( "autocompleteselect", function( event, ui ) {
			if (ui.item.type == "refraction") trialLensSearch (ui.item.rx);
			if (ui.item.type == "clRx") trialLensSearch (ui.item.rx); // todo make this a cl power

		} );
        
	var mr = jQuery.bbq.getState("refraction");
	var clRx = jQuery.bbq.getState("clRx");
	if (mr) jQuery('#edas_mr').val(mr[0]);
	if (clRx) jQuery('#edas_mr').val(clRx[0] + " (CL power)");
	if (mr || clRx) trialLensSearch();
}

function favStarClick (e, lensID, userID){
	
	var starLink = (e.currentTarget) ? e.currentTarget : e.srcElement; 
	var starID = jQuery(starLink).attr("id");
	var whichStar = starID.substr(starID.indexOf("-") + 1)
	//var lensID = whichStar.replace(/\D/g, '');
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
	
	stopEvent(e);
	
	//e.cancelBubble = true;
	//e.stopPropagation();
	//window.event.cancelBubble = true;
}

function stopEvent(e) {
 
	if(!e) var e = window.event;
 
	//e.cancelBubble is supported by IE -
        // this will kill the bubbling process.
	e.cancelBubble = true;
	e.returnValue = false;
 
	//e.stopPropagation works only in Firefox.
	if ( e.stopPropagation ) e.stopPropagation();
	if ( e.preventDefault ) e.preventDefault();		
 
       return false;
}


/*
operates the search filters. each filter has a:
- left hand side (LHS) select box, 
- an operator ( is, more/less, etc), 
- and most have a right-hand side (RHS) that will be a text box or a select box.
the operator and RHS are changed based on the LHS selection
*/

//store the queries - if a new query is done, but the parameters don't change, won't repeat the search
var oldQuery = new Array();
var hiddenQuery = "";
var advSearchesVisible = false;
//var inputRxString = "";
//var wantSpherical = 0;
var hiDkValue = 45; //it's best if this value is NOT in the dk_options array

jQuery(document).ready(function() {

	//makes the checkboxes buttons
	jQuery(function() {
		//jQuery( "#adv-search-type-checkboxes" ).buttonset();
		jQuery( "#adv-search-replace" ).buttonset();
		jQuery( "#adv-search-toric-radio").buttonset();
		jQuery( "#adv-search-bifocal-radio").buttonset();		
		jQuery( "#adv-search-cosmetic-radio").buttonset();
		jQuery( "#adv-search-hiDk" ).buttonset();
	});

	//initSearchOptions();
		
	//add a new row of filter options when the plus button is pressed
	jQuery(".clonefilter_btn").on("click", function(event){
		var row = jQuery(event.target).closest(".filterRow");	
	});
	
	//remove a row of filter options when the "x" button is pressed
	jQuery(".removeFilterRow_btn").on("click", function(event){
		removeFilterRow(event.target);
		//hideShowCloneRemove ();
		//submitSearch();
	});

	//when the search type option changes update the operator and RHS element
    jQuery(".searchType").on("change", function(event){
		selectChange(event.target);
    });
    
    jQuery('#more-options').click(function(){
    	if (!advSearchesVisible) {
    		advSearchesVisible = true;
    		jQuery('#more-options').html('<b>less options &uarr;</b>');
    		jQuery('#adv-search-div').slideDown('fast', submitSearch() );
    	} else {
    		advSearchesVisible = false;
    		jQuery('#more-options').html('<b>more options &darr;</b>');
    		jQuery('#adv-search-div').slideUp('fast', submitSearch() );
    	}

    });
    

//      jQuery('#adv-search-radios input').click(function(){
//     	submitSearch();
//     });
    
    jQuery('#adv-search-replace input').click(function(e){
    	//uncheck "any" if anything else is clicked, uncheck everything else if "any" is clicked
    	var clicked = jQuery(e.target).attr("id") ;
    	if(clicked == "replace0") {
    		//first, uncheck all "replace" buttons
    		jQuery('#adv-search-replace input').attr('checked', false).button('refresh');
    		jQuery('#adv-search-replace #replace0').attr('checked', true).button('refresh');
    	} else {
    		jQuery('#adv-search-replace #replace0').attr('checked', false).button('refresh');

    	}
    	//submitSearch();
    });
     
    jQuery('#ed-lenses-searchForm ').submit(function(e){
    	 e.preventDefault(); 
		 submitSearch();
    });
    
    jQuery(".defaultText").focus(function(srcc) {
        if (jQuery(this).val() == jQuery(this).attr('title') ) {
            jQuery(this).removeClass("defaultTextActive");
            jQuery(this).val("");
        }
    });

	
	jQuery('input.deletable').clearSearch({ 
		callback: function() { 
			submitSearch();
		} 
	});
	
	//this may be a duplicate, but I want to make sure it gets done...
	//subscriber = jQuery("#lenses_subscriber").val();
	
	jQuery("#ed-lenses-name_mr_input").autocomplete({
		//source: "/search_lenses.php?powers=" + subscriber
		source: "/search2.php?mr=1&clRx=1&scl=1&noLabels=1"
    });
    
    jQuery( "#ed-lenses-name_mr_input" ).on( "autocompleteselect", function( event, ui ) {
    	var lensID = ui.item.id;
    	if (lensID > 0) { 
    		//var lensName = (ui.item.label)?ui.item.label:"";
    		//set the hash so the back button works from the details screen
    		//if (lensName != "") jQuery.bbq.pushState({ view: "list", phrase: lensName } );
    		setDetailsHash (lensID);
    	} else {
			jQuery("#ed-lenses-searchForm").submit();
		}
	});
    
    
    

            

    

});

function clearAdvForm (){
	jQuery("#ed-lenses-name_mr_input").val("");
	clearAllFilterRows();
	clearReplaceButtons ();
	clearTBCradios();
	
	advSearchesVisible = false;
    jQuery('#more-options').html('<b>more options &darr;</b>');
    jQuery('#adv-search-div').hide();
	
	submitSearch ();
}

function clearAllFilterRows() {
	jQuery(".removeFilterRow_btn").each(function(i, obj) {
    	removeFilterRow(obj);
	});
}

//reset the "replace" buttons to any
function clearReplaceButtons() {		

	jQuery('#adv-search-replace input').attr('checked', false).button('refresh');
	jQuery('#adv-search-replace #replace0').attr('checked', true).button('refresh');
	

}

//uncheck all the toric/bifocal/cosmetic radio buttons
function clearTBCradios() {
	//uncheck all the toric/bifocal/cosmetic radio buttons
	jQuery("#adv-search-bifocal-radio radio").attr('checked', false).button('refresh');
	jQuery("#adv-search-cosmetic-radio radio").attr('checked', false).button('refresh');
	jQuery("#adv-search-toric-radio radio").attr('checked', false).button('refresh');
	
}




function removeFilterRow (row) {
	var rowCount = jQuery(".filterRow").length;
	if (rowCount == 1) {
		//alert(jQuery(event.target).closest(".filterRow").find(".filterLHS_div").find(".searchType").attr("class") );
		jQuery(row).closest(".filterRow").find(".filterLHS_div").find(".searchType")[0].selectedIndex=0; 
		jQuery(row).closest(".filterRow").find(".operator_div").html("");
		jQuery(row).closest(".filterRow").find(".filterRHS_div").html("");
	} else {
		jQuery(row).closest(".filterRow").remove();
	}
}

function initSearchOptions() {
    var options = "";
    for (var i = 0; i < searchOptionsObj.length; i++) {
        var obj = searchOptionsObj[i];
        options += "<option value='" + obj.id + "'>" + obj.name + "</option>";
    }
    jQuery('.searchType').html(options);
    //alert(options);
    //hideShowCloneRemove ();
	optionsPopulated = 1;
    populateSearchForm();
}

function cloneClicked (el) {
	var row = jQuery(el.target || el.srcElement).closest(".filterRow");	
	cloneFilterRow (row);
}

function cloneFilterRow (row) {
	var clone = jQuery(row).clone(true).insertAfter(jQuery(row) );
		
	//needed to keep the selected options the same
	 var selects = jQuery(row).find("select");
	jQuery(selects).each(function(i) {
			var select = this;
			jQuery(clone).find("select").eq(i).val(jQuery(select).val());
	});
	//hideShowCloneRemove ()
}

function selectChange (el) {
	//alert("changed");
	var selectedValue = jQuery(el).find( "option:selected").attr('value');
	var index = jQuery.inArray(selectedValue, searchOptionIDs);
	//for (var i = 0; i < searchOptionsObj.length; i++) {
		var obj = searchOptionsObj[index];
		//if (selectedValue == obj.id) {
			//alert (obj.operator);
			updateOperator (el, obj);
			var rhsSpan = jQuery(el).parent().nextAll(".filterRHS_div");
			jQuery(rhsSpan).html(updateRHS (obj) );
		//}
	//}
}


function updateOperator (el, obj){
	var operator = ""; 
	if (obj.operator != "none" && obj.operator != undefined) operator = makeSelect(obj.operator);
	var operatorSpan = jQuery(el).parent().nextAll(".operator_div");
	var rhsSpan = jQuery(el).parent().nextAll(".filterRHS_div");
	jQuery(operatorSpan).html(operator);
	jQuery(rhsSpan).html(updateRHS(obj));

}

function updateRHS (obj){
	if (obj.input == "bool") return "<select class='rhsVal' ' ><option value='1'>yes</option><option value='0'>no</option></select>";
	//onchange='submitSearch();
	if (obj.input == "text") return "<input class='rhsVal' type='text'  />"; //onkeyup='checkRHSkeypress(event)'
	if (obj.input == "html") return eval(obj.id + "_html");
	if (obj.input != "select") return "error";
	
	try { 
		var list = eval(obj.id + "_options"); 
		} 
	catch (e) { 
		return "error";
	}; 
	
	try { 
		var selected_index = eval(obj.id + "_selected"); 
		} 
	catch (e) {	}; 
	
	
	//var list = eval(obj.id + "_options");
	//var selected_index = eval(obj.id + "_selected");
	if (!selected_index) var selected_index = 0;
	if (typeof(list) == "undefined") return "error";
	
	var select = "<select class='rhsVal'  ";
	//onchange='submitSearch();'
	
	if (obj.multiple) select += "multiple='multiple' style='height:80px'";
	
	select += ">";
	for (var i = 0; i < list.length; i++) {
		select += "<option ";
		if (selected_index == i) select += " selected='selected' ";
		select += " value='"+ list[i] + "'>"+ list[i]+"</option>";
	}
	select += "</select>";
	
	if (obj.suffix != undefined) select += " " + obj.suffix;
	
	return select;
}


function makeSelect (valObj) {
	var list = eval(valObj);
	if (list.length == 1 ) {
		return "<input type='hidden' value='" + list[0].value + "'/>"+ list[0].name;
	}
	var select = "<select class='operatorVal' >"; //onChange='submitSearch()'
	for (var i = 0; i < list.length; i++) {
		select += "<option  value='"+ list[i].value + "'>"+ list[i].name+"</option>";
	}
	select += "</select>";
	return select;
}

function checkRHSkeypress(e){
   if(e && (e.keyCode == 13 || e.keyCode == 9 ) ){
      	submitSearch();
   }
} 

function submitSearch (){
	jQuery("#ed-lenses-name_mr_input").autocomplete("close");		
	var data = new Object();
	data["params"]  = getParamsToSearch();
	data["params"].view = "list";
	data["params"].id = currentLensID;
	jQuery.bbq.pushState( data["params"],2);
	//doCLsearch(data);

}

function doCLsearch (data) {

	//we don't need this info, so we'll remove it to make the next step easier
	delete data.params.id;
	delete data.params.view;
	
	
	//don't do the search if no search data is provided	
	if (countObjectParts(data.params) == 0 ) {
		jQuery('#ed-lenses-lists').hide(100);
		jQuery('#ed-lenses-directions').show();
		return;
	}
	
	jQuery('#ed-lenses-directions').hide();

	var layout = "";
	
	//don't do refraction or cl searches if not a subscriber
	if ( (data.params.refraction || data.params.clRx) && subscriber != 1) {
		delete data.params.refraction;
		delete data.params.clRx;
		alert ("Sorry - Only logged in subscribers are allowed to search by refraction or CL power");
	}
	
	if (data.params.refraction || data.params.clRx) {
		layout = "&layout=powers";
		if (data.params.bifocal == 1) {
			alert ("Sorry - Many multifocal lenses use unconventional methods for calculating power. We cannot search for this type of lens by power");
			jQuery.bbq.removeState("bifocal");
		}

	}
	
	jQuery('#adv-searches-loader').show();
	jQuery('#ed-lenses-lists').fadeOut();
	
	//abort any unfinished ajax requests
	var fn = function(){
		if(xhr && xhr.readystate != 4){
			xhr.abort();
		}
	}
	
	xhr = jQuery.ajax({
        type: "POST",
        url: "/index.php?option=com_pnlenses&view=paramlist&format=raw&task=paramSearch" + layout,
        data: data,
        dataType: "html",
        success: function(result){
        	showLensList(result, layout);
        }
    });
	
}

function getParamsToSearch (){

	var params = new Object();
	
	if (advSearchesVisible ) {

		if (jQuery('#toric-yes').prop('checked')) params['toric'] = 1;
		if (jQuery('#toric-no').prop('checked')) params['toric'] = 0;
		if (jQuery('#bifocal-yes').prop('checked')) params['bifocal'] = 1;
		if (jQuery('#bifocal-no').prop('checked')) params['bifocal'] = 0;
		if (jQuery('#cosmetic-yes').prop('checked')) params['cosmetic'] = 1;
		if (jQuery('#cosmetic-no').prop('checked')) params['cosmetic'] = 0;
		if (jQuery('#hiDk-yes').prop('checked')) params['dk'] = new Array("+"+  hiDkValue);
		
		//wantSpherical = (params['toric'] == 0 )?1:0;

		//don't worry about replacement if "any" is checked
		if (jQuery('#replace0').attr('checked') != "checked" ) {
			var replaceArr = [];
			jQuery('#adv-search-replace input:checked').each(function() {
					replaceArr.push(jQuery(this).val() );
				});
			params['replace'] = replaceArr;
		}
		
		//alert(jQuery('.filterRow').html() );
		jQuery('.filterRow').each(function() {
			var q1 = jQuery(this).find('.filterLHS_div .searchType option:selected').val();
			var q2 = findValue( jQuery(this).find('.operator_div') );
			var q3 = findValue(jQuery(this).find('.filterRHS_div') );
			if (q3 && q3 != "any" ) {
				if (params[q1] == undefined) params[q1] = new Array();
				params[q1].push(q2 + q3);
			}
		});
		
		//if cosmetic color choice is any make sure we still do a search for opaque or enhancer lenses
		//if (q1 == "colors_enh") params["enhance"] = 1;
		//if (q1 == "colors_opq") params["opaque"] = 1;
		

	}
	
	//alert(params);
	
	var phraseOrName = jQuery('#ed-lenses-name_mr_input').val().replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	var isMR = (phraseOrName.indexOf('(refraction)') == -1)?0:1;
	var isCLRx = (phraseOrName.indexOf('(CL power)') == -1)?0:1;
		
	//see what the first character is - if it's a "+" or "-" we'll assume it's a refraction
	//we'll only do this if it's not already a known mr or clrx 
	var char1 = phraseOrName.charAt(0);
	if 	( (char1 == "+" || char1 == "-") && isMR == 0 && isCLRx ==0 && subscriber == 1) {
		phraseOrName = makeMRstring(phraseOrName);
		isMR = 1;
	}
	
	if 	( (char1 == "+" || char1 == "-") && subscriber != 1) {
		alert ("It looks like you might be trying to search by refraction or lens power. This feature is only available to members! Consider subscribing to take advantage of this wonderful, wonderful feature.  (if you are a subscriber, log in!)");
	}

	
	if (phraseOrName != "" ) {
		var rx = phraseOrName;
		rx = rx.replace (" (refraction)", "");
		rx = rx.replace (" (CL power)", "");
		if (isMR == 1  && subscriber == 1) {
			params['refraction'] = new Array(rx);
			showOpticalCross (rx, 1);
		} else if (isCLRx == 1 && subscriber == 1) {
			params['clRx'] = new Array(rx);
			showOpticalCross (rx, 0);
		} else {
			params['phrase'] = new Array (jQuery('#ed-lenses-name_mr_input').val() );
		}
	} 
	
	

	return params;
}


function showOpticalCross (rx, vertex){
	rx = rx.replace(/\+/g, "%2B"); // need to escape the "+" sign in the URL
	
	var str = "?doVertex=" + vertex + "&rxString=" + rx;
	
	var toric = jQuery.bbq.getState("toric");
	//var view = jQuery.bbq.getState("view");
	
	if (toric == 0) str += "&doSphEquiv=1";
	
	jQuery.ajax({
        type: "POST",
        url: "/utilities/utilities/lenscrosses.php" + str,
        dataType: "html",
        success: function(result){
        	jQuery("#ed-lenses-cross").html(result);
			//if (view=="detail") jQuery("#ed-details-suggest-cross").html(result);
        }
    });
}




function findValue (div) {
	var input = jQuery(div).children(":first");
	if (jQuery(input).is("select") ) return jQuery(input).find('option:selected').val();
	if (jQuery(input).is("input") ) return jQuery(input).val();
	return "";
}

//compare two arrays (in this case the search parameters) 
function equalArray(a, b) {
    return JSON.stringify(a) == JSON.stringify(b);
}

//count the number of elements in an object (in this case, the elements that come from the hash)
function countObjectParts(a) {
	var count = 0;
	for (i in a) {
		if (a.hasOwnProperty(i)) {
			count++;
		}
	}
	return count;
}

//use the hash or query string to put put together a populated search form
function populateSearchForm (params) {

	if (optionsPopulated == 0) 
		return; //don't try to populate the search form before the select boxes are populated!	
	
	
	var params = jQuery.deparam.fragment();
	if (params.phrase) jQuery("#ed-lenses-name_mr_input").val(params.phrase);
	if (params.refraction) jQuery("#ed-lenses-name_mr_input").val(params.refraction + " (refraction)" );
	if (params.clRx) jQuery("#ed-lenses-name_mr_input").val(params.clRx + " (CL power)");
	
	
	if ( params.refraction || params.clRx) {
		if (subscriber == 1) {
			var rx = (params.refraction)?params.refraction[0]:params.clRx[0];
			var vertex = (params.refraction)?1:0;
			showOpticalCross (rx, vertex);
		} else 	{
			delete params["refraction"];
			delete params["clRx"];
		}
	}
	
	//clear all the filter rows (we'll start from scratch)
	clearAllFilterRows();

	var needMoreOptions = 0; // if >1 show the "more options" div
	
	if (params.toric || params.bifocal || params.cosmetic || params.replace ) needMoreOptions++;
		
	//get the radiobuttons properly checked
	setParamsRadios ("toric", params.toric || -1);
	setParamsRadios ("bifocal", params.bifocal || -1);
	setParamsRadios ("cosmetic", params.cosmetic || -1);
	
	//alert (params.dk[0]);
	
	//reset the "replace" buttons to any
	clearReplaceButtons();

	
	//next, iterate through the replace array and set the proper buttons
	if (params.replace) {
		for (var item in params.replace){
			var days = params.replace[item];
			if (days != 0 ) jQuery('#adv-search-replace #replace0').attr('checked', false).button('refresh');
			jQuery("#replace" + days).attr('checked', true).button('refresh');
		}
	}	


	for (var prop in params){
		if (jQuery.inArray(prop, searchOptionIDs) > -1  ) {
			
			// get the prop's value. the propVal will be an array, eg diameter[]=14.0
			var propVal = jQuery.bbq.getState(prop); 
			if (!propVal) continue;
			

			//check if the dk is equal to the "only hi dk" checkbox value (hiDkValue). if so, set the checkbox too true and continue so that the dk dropdown isn't also set below
			//uncheck all values first
			jQuery("#adv-search-hiDk radio").attr('checked', false).button('refresh');
			if (prop == "dk" && propVal[0] == hiDkValue) {
				jQuery("#hiDk-yes").attr('checked', true).button('refresh');
				needMoreOptions++;
				continue;
			} else {
				jQuery("#hiDk-any").attr('checked', true).button('refresh');
			}
			
			
			
			//eliminate duplicates in the propVal's array
			var uniqueProps = [];
			jQuery.each(propVal, function(i, el){
    			if(jQuery.inArray(el, uniqueProps) === -1) uniqueProps.push(el);
			});
			
			for (var item in uniqueProps) { 
				var pVal = propVal[item];
				needMoreOptions++;
				var el = jQuery(".searchType").last();
				//make another filter row, if needed
				if (needMoreOptions >1) {
					cloneFilterRow (jQuery(el).closest(".filterRow") );
				}
				jQuery(el).val(prop);  //changes the first select box to the prop title
				selectChange(jQuery(el) ); //creates the operator and RHS inputs
			
				//find the operator select, if it exists
				var operatorSelect = jQuery(el).parent().nextAll(".operator_div").find("select");	
				if (operatorSelect.is('*') ) jQuery(operatorSelect).val( (pVal.indexOf("-") == -1 )?"+":"-" );
				var rhs = jQuery(el).parent().nextAll(".filterRHS_div").find("select");
				if (operatorSelect.is('*') ) pVal = pVal.substr(1);
				if (rhs.is('*')) jQuery(rhs).val(pVal);
			}
		}
	}
	

	
	if (needMoreOptions > 0) {
		    jQuery('#more-options').html('<b>less options &uarr;</b>');
    		jQuery('#adv-search-div').show();
    		advSearchesVisible = true;
	}
	//dontSearch = 0;
}

function setParamsRadios (item, value) {
	//uncheck all values first
	jQuery("#adv-search-" + item + "-radio radio").attr('checked', false).button('refresh');
	if (value == 1) jQuery("#" + item + "-yes").attr('checked', true).button('refresh');
	if (value == 0) jQuery("#" + item + "-no").attr('checked', true).button('refresh');
	if (value == -1) jQuery("#" + item + "-any").attr('checked', true).button('refresh');
}

function makeMRstring (str) {
	var rxObj = rxStringBreaker(str);
	var newStr = rxObj.prettyString();
	jQuery('#ed-lenses-name_mr_input').val( newStr + " (refraction)" );
	return newStr;
}
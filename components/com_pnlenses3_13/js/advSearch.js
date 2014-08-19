
/*
operates the search filters. each filter has a:
- left hand side (LHS) select box, 
- an operator ( is, more/less, etc), 
- and most have a right-hand side (RHS) that will be a text box or a select box.
the operator and RHS are changed based on the LHS selection
*/

//store the queries - if a new query is done, but the parameters don't change, won't repeat the search
var oldQuery = new Array();

jQuery(document).ready(function() {

	initSearchOptions();
	
	//makes the checkboxes buttons
	jQuery(function() {
		//jQuery( "#adv-search-type-checkboxes" ).buttonset();
		jQuery( "#adv-search-replace" ).buttonset();
		jQuery( "#adv-search-toric-radio").buttonset();
		jQuery( "#adv-search-bifocal-radio").buttonset();		
		jQuery( "#adv-search-cosmetic-radio").buttonset();
	});
	
	//add a new row of filter options when the plus button is pressed
	jQuery(".clonefilter_btn").on("click", function(event){
		var row = jQuery(event.target).closest(".filterRow");
		var clone = jQuery(row).clone(true).insertAfter(jQuery(row) );
		
		//needed to keep the selected options the same
		 var selects = jQuery(row).find("select");
        jQuery(selects).each(function(i) {
                var select = this;
                jQuery(clone).find("select").eq(i).val(jQuery(select).val());
        });
		//hideShowCloneRemove ()
	});
	
	//remove a row of filter options when the "x" button is pressed
	jQuery(".removeFilterRow_btn").on("click", function(event){
		var rowCount = jQuery(".filterRow").length;
		if (rowCount == 1) {
			//alert(jQuery(event.target).closest(".filterRow").find(".filterLHS_div").find(".searchType").attr("class") );
			jQuery(event.target).closest(".filterRow").find(".filterLHS_div").find(".searchType")[0].selectedIndex=0; 
			jQuery(event.target).closest(".filterRow").find(".operator_div").html("");
			jQuery(event.target).closest(".filterRow").find(".filterRHS_div").html("");
		} else {
			jQuery(event.target).closest(".filterRow").remove();
		}
		//hideShowCloneRemove ();
		submitSearch();
	});

	//when the search type option changes update the operator and RHS element
    jQuery(".searchType").on("change", function(event){
        var selectedValue = jQuery(event.target).find( "option:selected").attr('value');
		for (var i = 0; i < searchOptionsObj.length; i++) {
        	var obj = searchOptionsObj[i];
        	if (selectedValue == obj.id) {
        		//alert (obj.operator);
        		updateOperator (event.target, obj);
        		updateRHS (event.target, obj);
        	}
    	}
    });
    
    jQuery('#more-options').click(function(){
    	if (jQuery('#adv-search-div').css('display') == 'none' ) {
    		jQuery('#more-options').html('<b>less options &uarr;</b>');
    		jQuery('#adv-search-div').slideDown('fast', submitSearch() );
    	} else {
    		jQuery('#more-options').html('<b>more options &darr;</b>');
    		jQuery('#adv-search-div').slideUp('fast', submitSearch() );
    	}

    });
    

     jQuery('#adv-search-radios input').click(function(){
    	submitSearch();
    });
    
    jQuery('#adv-search-replace input').click(function(e){
    	//uncheck "any" if anything else is clicked, uncheck everything else if "any" is clicked
    	var clicked = jQuery(e.target).attr("id") ;
    	if(clicked == "replace0") {
    		jQuery('#adv-search-replace input').attr('checked', false).button('refresh');
    		jQuery('#adv-search-replace #replace0').attr('checked', true).button('refresh');
    	} else {
    		jQuery('#adv-search-replace #replace0').attr('checked', false).button('refresh');

    	}
    	submitSearch();
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
    
    jQuery(".defaultText").blur(function() {
        if (jQuery(this).val() == "") {
            jQuery(this).addClass("defaultTextActive");
            jQuery(this).val(jQuery(this).attr('title') );
        }
    });
    jQuery(".defaultText").blur();     
    
     
	jQuery('input.deletable').wrap('<span class="deleteicon" />').after(jQuery('<span/>').click(function() {
		jQuery(this).prev('input').val('').focus();
	}));
            

	//jQuery("#ed_lens_params_table").tablesorter(); 
	//jQuery("#ed_lens_power_table").tablesorter();
    

});




function initSearchOptions() {
    var options = "";
    for (var i = 0; i < searchOptionsObj.length; i++) {
        var obj = searchOptionsObj[i];
        options += "<option value='" + obj.id + "'>" + obj.name + "</option>";
    }
    jQuery('.searchType').html(options);
    //alert(options);
    //hideShowCloneRemove ();
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
	if (obj.input == "bool") return "<select class='rhsVal' onChange='submitSearch()' ><option value='1'>yes</option><option value='0'>no</option></select>";
	if (obj.input == "text") return "<input class='rhsVal' type='text' onkeyup='checkRHSkeypress(event)' />";
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
	
	var select = "<select class='rhsVal' onChange='submitSearch()' ";
	
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
	var select = "<select class='operatorVal' onChange='submitSearch()'>";
	for (var i = 0; i < list.length; i++) {
		select += "<option  value='"+ list[i].value + "'>"+ list[i].name+"</option>";
	}
	select += "</select>";
	return select;
}

function resetFilterrow() {
	
}

function checkRHSkeypress(e){
   if(e && (e.keyCode == 13 || e.keyCode == 9 ) ){
      	submitSearch();
   }
}


function submitSearch (){

	var data = new Object();
	data["params"]  = getParamsToSearch();
	data["params"].view = "list";
	jQuery.bbq.pushState( data["params"],2);
	//doCLsearch(data);

}

function doCLsearch (data) {
	//alert ("count: " + countObjectParts(data.params) );
	//don't do the search if no search data is provided
	if (countObjectParts(data.params)<2 ) {
		jQuery('#ed-lenses-lists').hide(100);
		return;
	}
	//don't do the search if the search params haven't changed
	/*if (oldQuery.params) {
		if (equalArray (oldQuery.params, data.params)  ) return;
	}*/
	
	//oldQuery = data;
	
	var layout = (data.params.refraction)?"&layout=powers":"";
	
	jQuery('#adv-searches-loader').show();
	jQuery('#ed-lenses-lists').fadeOut();
	
	jQuery.ajax({
        type: "POST",
        url: "/index.php?option=com_pnlenses&view=paramlist&format=raw&task=paramSearch" + layout,
        data: data,
        dataType: "html",
        success: function(result){
        	showLensList(result, layout);

        }
    });
	
	//searchHash = jQuery.param(data["params"]);
	//var paramFrag = jQuery.param.fragment( data["params"] );
	//data["params"].view = "list";
	//jQuery.bbq.pushState( data["params"],2);
	//location.hash = searchHash;
}

function getParamsToSearch (){

	var params = new Object();
	
	if (jQuery('#adv-search-div').css('display') != 'none' ) {

		if (jQuery('#toric-yes').prop('checked')) params['toric'] = 1;
		if (jQuery('#toric-no').prop('checked')) params['toric'] = 0;
		if (jQuery('#bifocal-yes').prop('checked')) params['bifocal'] = 1;
		if (jQuery('#bifocal-no').prop('checked')) params['bifocal'] = 0;
		if (jQuery('#cosmetic-yes').prop('checked')) params['cosmetic'] = 1;
		if (jQuery('#cosmetic-no').prop('checked')) params['cosmetic'] = 0;
		

		//don't worry about replacement if "any" is checked
		if (jQuery('#replace0').attr('checked') != "checked" ) {
			var replaceArr = [];
			jQuery('#adv-search-replace input:checked').each(function() {
					replaceArr.push(jQuery(this).val() );
				});
			params['replace'] = replaceArr;
		}
		
		jQuery('.filterRow').each(function(index) {
			var q1 = jQuery(this).find('.filterLHS_div .searchType option:selected').val();
			var q2 = findValue( jQuery(this).find('.operator_div') );
			var q3 = findValue(jQuery(this).find('.filterRHS_div') );
			if (q3 && q3 != "any" ) {
				if (params[q1] == undefined) params[q1] = new Array();
				params[q1].push(q2 + q3);
			}
		});

	}
	
	var phraseOrName = jQuery('#ed-lenses-name_mr_input').val().replace(/^\s\s*/, '').replace(/\s\s*$/, '');;
	
	//don't worry about the phrase if the default text is present	
	if (phraseOrName != jQuery('#ed-lenses-name_mr_input').attr('title') ) {
		//if phraseOrName starts with a number it might be a refraction - we'll go with that
		//some logic to eliminate lens names that start with a number (eg 1-day)
		if (/^[\+\-\.\d]/.test(phraseOrName)  ) {
			params['refraction'] = new Array(jQuery('#ed-lenses-name_mr_input').val() );
		} else {
			params['phrase'] = new Array (jQuery('#ed-lenses-name_mr_input').val() );

		}
	}

	return params;
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
	//dontSearch = 1;
	if (params.phrase) jQuery("#ed-lenses-name_mr_input").val(params.phrase);
	if (params.refraction) jQuery("#ed-lenses-name_mr_input").val(params.refraction);	
	var temp = searchOptionIDs;
	var needMoreOptions = 0; // if >1 show the "more options" div
	//jQuery(".searchType:not(:first)").remove();
	for (var prop in params){
		if (jQuery.inArray(prop, searchOptionIDs) > -1  ) {
			needMoreOptions++;
			var el = jQuery(".searchType").last();
			alert (jQuery(el).html() );
			alert (jQuery(el).val());
			jQuery(el).val(prop);
		}
	}
	if (needMoreOptions > 0) {
		    jQuery('#more-options').html('<b>less options &uarr;</b>');
    		jQuery('#adv-search-div').show();
	}
	//dontSearch = 0;
}

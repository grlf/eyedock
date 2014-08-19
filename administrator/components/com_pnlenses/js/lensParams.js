
jQuery(document).ready(function() {	 		
		// show/hide tables and columns related to toric/cosmetic/bifocal
		jQuery( "#cosmetic, #bifocal, #toric").change(function() {
			managePowerColumns();
		});	
		 
		 //button to delete power row
		 jQuery("#powersTable  input[name^='delete']").click(function() {
			deleteRow (jQuery(this).parents("tr"));
			autoUpdateVars();
		});
		
		// -- clone the row of power parameters
		var rowCount = jQuery("#powersTable tr").length - 1;
		jQuery("#addParams").click(function() {
			jQuery("#powersTable tr:last").clone().find("input, textarea").each(function() {
				var i = jQuery("#powersTable tr").length-1;
				jQuery(this).attr('name', function(x, id) { return id.replace(/[0-9]/g, rowCount)  });
			 }).end().appendTo("#powersTable");
			 rowCount++;
			 jQuery("#powersTable tr:last td:last input[name^='delete']").click(function() {
				deleteRow (jQuery(this).parents("tr"));
			});
			jQuery("#powersTable tr:last input[name$='[id]']").val('new');
			jQuery("#powersTable tr:last").show('slow').find("input[name$='[delete]']").val('0');
			assignValidate ();
		});
		
		//-if auto update vars is checked or unchecked
		jQuery('#autoVars_chk').change(function(){
			autoUpdateVars();
		});

		
		jQuery('.numlist').each(function(index) {
    		validatePowers(jQuery(this));
		});
		

		
		
		
		
		
		managePowerColumns();
		assignValidate ();
});


function confirmDeleteRow(el) {
	if (jQuery("#powersTable tr").length == 2) {
		alert ("sorry - you must keep at least one row");
		return;
	}
}

function deleteRow (el) {
		var answer = confirm("Are you sure you want to delete this row? \nNote that changes will not be made to the database until you 'save' or 'apply' this page.");
		if (answer){
			jQuery(el).hide('slow');
			jQuery(el).find("input[name$='[delete]']").val('1');
		}

}


function managePowerColumns () {
	if (jQuery("#toric").prop("selectedIndex") == 1) {
		jQuery(".toricClass").hide('slow');
	} else {
		jQuery(".toricClass").show('slow');
	}
	
	if (jQuery("#bifocal").prop("selectedIndex") == 1) {
			jQuery(".bifocalClass").hide('slow');
	} else {
		jQuery(".bifocalClass").show('slow');
	}
	
	if (jQuery("#cosmetic").prop("selectedIndex") == 1) {
		jQuery(".cosmeticClass").hide('slow');
	} else {
		jQuery(".cosmeticClass").show('slow');
	}
	
}


function assignValidate () {

	jQuery('.numlist').each(function(index) {
		jQuery(this).unbind("blur").bind("blur", function(){ 
			cleanPowers (jQuery(this))
    		validatePowers(jQuery(this));
		});
	});

	
}


function cleanPowers (el) {
		var val = el.val();
		val = jQuery.trim( val );	
		val = val.replace(/^,|,$/g,''); //remove commas from beginning and end
		el.val(val);
		val = val.replace(/\s/g,''); //remove all spaces (to simplify regex testing)
	    if(jQuery('#autoVars_chk').is(':checked') ) autoUpdateVars();

}

function validatePowers (el){
		var val = el.val();
		val = val.replace(/\s/g,''); //remove all spaces (to simplify regex testing)
		var regularEx = /^(([-\+]?\d*\.?\d*to[-\+]?\d*\.?\d*in[-\+]?\d*\.?\d*steps)|[-\+]?\d*\.?\d*)(,(([-\+]?\d*\.?\d*to[-\+]?\d*\.?\d*in[-\+]?\d*\.?\d*steps)|[-\+]?\d*\.?\d*))*$/g;
	   var valid = regularEx.test(val);
	   var color = (valid)?"#d3eabb":"#f7d2db";
	   el.css("background-color", color);

}



function autoUpdateVars(){
	if(! jQuery('#autoVars_chk').is(':checked') ) return;
	//alert ('autoUpdateVars');

	
	var spheres = jQuery('textarea[name $="[sphere]"]').map(function() {
  		return jQuery(this).val();
}).get().join(', ');
	var diameters = jQuery('textarea[name $="[diameter]"]').map(function() {
  		return jQuery(this).val();
}).get().join(', ');
	var baseCurves = jQuery('textarea[name $="[baseCurve]"]').map(function() {
  		return jQuery(this).val();
}).get().join(', ');
	var cylinders = jQuery('textarea[name $="[cylinder]"]').map(function() {
  		return jQuery(this).val();
}).get().join(', ');
	var axes = jQuery('textarea[name $="[axis]"]').map(function() {
  		return jQuery(this).val();
}).get().join(', ');
	var addPwrs = jQuery('input[name $="[add]"]').map(function() {
  		return jQuery(this).val();
}).get().join(', ');
	
	
	 jQuery.ajax({
			type : 'POST',
			url : '/api/utilities/clParamExtraction.php',
			dataType : 'json',
			
			data: {
			"sphData": spheres, 
			"diamData": diameters, 
			"bcData": baseCurves, 
			"cylPwrData": cylinders, 
			"cylAxisData": axes, 
			"addData": addPwrs, 			
			"format": "json"},

			success : function(data){
				setPwrVars (data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				//alert("error");
			}
		});

	
}

function setPwrVars(data) {

	if (! data) return;
	
	var params = data['body'];
	
	for (var index in params) {
		 jQuery("#" + index).val(params[index] );
	}
	//alert (params['numBCs'] );
	if (params['numBCs'] > 0 ) {
		var numBCs = params['numBCs'];
		var flatOption = jQuery('select[name ="bcsimple[]"] option[value="flat"]');
		var medianOption = jQuery('select[name ="bcsimple[]"] option[value="median"]');
		var steepOption = jQuery('select[name ="bcsimple[]"] option[value="steep"]');
		flatOption.prop('selected',false); 
		medianOption.prop('selected',false); 
		steepOption.prop('selected',false); 
		if (numBCs == 1 || numBCs >= 3) medianOption.prop('selected',true); 
		if (numBCs == 2 || numBCs >= 3) flatOption.prop('selected',true); 
		if (numBCs == 2 || numBCs >= 3) steepOption.prop('selected',true); 
	}
	
	//if the lens is not a toric then erase the appropriate fields
	if (jQuery("#toric").prop("selectedIndex") == 1) {
		jQuery("#maxcylpower").val("");
		jQuery("#cylaxissteps").val("");
		jQuery("#oblique").val("");
	}

		//if the lens is not a bifocal then erase the add field
	if (jQuery("#bifocal").prop("selectedIndex") == 1) jQuery("#maxadd").val("");
	
	//iterate through all the "any" power boxes
	jQuery("[name*='any_']:checked").each(function( index ) {
  		var name = jQuery( this ).prop("name");
  		var textAreaName = name.replace("any_","");
  		if (name.indexOf("diameter") != -1) {
  			jQuery("#maxdiam").val("24.0");
  			jQuery("#mindiam").val("10.0");
  			jQuery("#diamall").val("10.0 to 24.0");
  			jQuery('textarea[name ="' + textAreaName + '"]').val("10.0 to 24.0 in 0.1 steps");
  		}
  		if (name.indexOf("baseCurve") != -1) {
  			jQuery('select[name ="bcsimple[]"] option[value="flat"]').prop('selected',true); 
			jQuery('select[name ="bcsimple[]"] option[value="median"]').prop('selected',true); 
			jQuery('select[name ="bcsimple[]"] option[value="steep"]').prop('selected',true); 
			jQuery("#bcall").val("6.0 to 10.0");
			jQuery('textarea[name ="' + textAreaName + '"]').val("6.0 to 10.0 in 0.1 steps");
  		}
  		 if (name.indexOf("sphere") != -1) {
  			jQuery("#maxplus").val("+50.00");
  			jQuery("#maxminus").val("-50.0");
  			jQuery('textarea[name ="' + textAreaName + '"]').val("+50.00 to -50.00 in 0.25 steps");
  		}
  		 if (name.indexOf("cylinder") != -1) {
  			jQuery("#maxcylpower").val("-16.00");
  			jQuery('textarea[name ="' + textAreaName + '"]').val("-0.75 to -16.00 in 0.25 steps");
  		}
  		 if (name.indexOf("axis") != -1) {
  			jQuery("#cylaxissteps").val("1");
  			jQuery("#oblique").val("45");
  			 jQuery('textarea[name ="' + textAreaName + '"]').val("1 to 180 in 1 steps");

  		}
  		  if (name.indexOf("add") != -1) {
  			jQuery("#maxadd").val("+6.00");
  			jQuery('input[name ="' + textAreaName + '"]').val("+1.00 to +6.00 in 0.25 steps");

  		}
	});	
}

function anyboxclick(el) {
	var isChecked = jQuery(el).prop('checked') ;
	var name = jQuery(el).prop('name');
	var textAreaName = name.replace("any_","");
	//alert (textAreaName);
	/*if (isChecked) {
		jQuery('textarea[name ="' + textAreaName + '"]').val("").prop("disabled", true);;
	} else {
		jQuery('textarea[name ="' + textAreaName + '"]').prop("disabled", false);;

	}*/
	autoUpdateVars();
}
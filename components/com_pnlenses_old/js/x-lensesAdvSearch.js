
//initial values
//multiply is used to put the minus powers on the left side of the slider (sphere power only)
//prefix is used to add a "-" sign to the cyl powers, but still keep the numbers ascending from left to right
var sphereRange = {min:-20, max:20, start: 55, steps:1, multiply:-1};
var diameterRange = {min:9, max:20, steps:.1, unit: " mm", decimal: 1};
var dkRange = {min:1, max:100};
var ctRange = {min:.01, max:.15, steps:.01, unit: " mm", decimal: 2};
var ozRange = {min:4, max:10, steps:.01, unit: " mm", decimal: 1};
var h2oRange = {min:25, max:90, unit: "%"};
var cylRange = {min:.75, max:10, steps:.25, prefix:"-", decimal: 2};
var addRange = {min:1, max:5, steps:.25, decimal: 2};

jQuery(function() {
		//these sliders will say "x or more" on one side and "x or less on the other"
		jQuery('.moreLessSlider').each(function(index, value){
			var el = jQuery(this).attr('id');
			var elValue = "#" + el.replace("slider", "value");
			var elRange = el.replace("_slider", "Range");
			jQuery( "#" + el).slider({
				slide: function( event, ui ) {
					jQuery( elValue).html( moreLessLabel(ui.value, eval(elRange) ));
				},
				change: function(event, ui) { lenses_advsearch_change();}
			});

		});
		
		//these sliders will just say "at least x"
		jQuery('.atLeastSlider').each(function(index, value){
			var el = jQuery(this).attr('id');
			var elValue = "#" + el.replace("slider", "value");
			var elRange = eval(el.replace("_slider", "Range"));
			if (!elRange.start) elRange.start = 0;
				jQuery( "#" + el).slider({
				value: elRange.start,
				slide: function( event, ui ) {
					jQuery( elValue).html( atLeastLabel(ui.value, elRange ));
				},
				change: function(event, ui) { lenses_advsearch_change();}
			});

		});
		
		
		jQuery( "#replace_slider" ).slider({
			range: true,
			min: 0,
			max: 5,
			values: [ 0, 6 ],
			step: 1,
			slide: function( event, ui ) {
				jQuery( "#replace_value" ).html( replaceLabel(ui.values) );
			},
				change: function(event, ui) { lenses_advsearch_change();}
		});	
		

		
		
	jQuery( "#ed-lenses-bc_options label:first" ).click(function() {
			jQuery("#ed-lenses-bc_options input:checkbox").prop('checked', false);
			jQuery( "#basecurve-any" ).prop('checked', true);
		});
		
	jQuery( "#ed-lenses-bc_options label:not(:first)").click(function() {
			jQuery( "#basecurve-any"  ).prop('checked', false);
			var count = jQuery("#ed-lenses-bc_options input:checkbox:checked").length;
			if (count==0) jQuery( "#basecurve-any" ).prop('checked', true);
		});
		

	jQuery( "#ed-lenses-toric_chk").change(function() {
		 if (this.checked) {
			jQuery("#ed-lenses-axis_steps_chk").removeAttr("disabled");
			jQuery("#ed-lenses-oblique_chk").removeAttr("disabled");
			jQuery( "#cyl_slider" ).slider( "option", "disabled", false );
			jQuery(".toric-label").removeClass("text-disabled");
		  } else {
			jQuery("#ed-lenses-axis_steps_chk").attr("disabled", true);
			jQuery("#ed-lenses-oblique_chk").attr("disabled", true);
			jQuery( "#cyl_slider" ).slider( "option", "disabled", true );
			jQuery(".toric-label").addClass("text-disabled");

		  }
		});
		
	jQuery( "#ed-lenses-bifocal_chk").change(function() {
		 if (this.checked) {
			jQuery( "#add_slider" ).slider( "option", "disabled", false );
			jQuery(".bifocal-label").removeClass("text-disabled");
			jQuery("#ed-lenses-bifocal_type").attr("disabled", false);
		  } else {
			jQuery( "#add_slider" ).slider( "option", "disabled", true );
			jQuery(".bifocal-label").addClass("text-disabled");
			jQuery("#ed-lenses-bifocal_type").attr("disabled", true);
		  }
		});
		
	jQuery( "#ed-lenses-cosmetic_chk").change(function() {
		 if (this.checked) {
			jQuery("#ed-lenses-cosmetic_color").attr("disabled", false);
		  } else {
			jQuery("#ed-lenses-cosmetic_color").attr("disabled", true);
		  }
		});
		
		jQuery("#ed-lenses-advsearch_reset").click(function(){
			lenses_setDefaults();
		});
		
		jQuery("#ed-lenses-tab_advanced input:checkbox").change(function(){
			lenses_advsearch_change();
		});
		
		
		jQuery("#ed-lenses-tab_advanced select").change(function(){
			lenses_advsearch_change();
		});
		
		
		lenses_setDefaults();
			
});

function lenses_advsearch_change(){
	//alert("change!");
}

function lenses_setDefaults () {
	//set (or reset) the labels, initial values, and disabled 
	
	jQuery('.moreLessSlider').each(function(index, value){
		var el =  jQuery(this).attr('id');
		var elValue = "#" + el.replace("slider", "value");
		var elRange = eval(el.replace("_slider", "Range") );
		jQuery( "#"+el ).slider( "option", "value", 50 );
		jQuery( elValue ).html( moreLessLabel(jQuery( "#"+el ).slider("value") , elRange ));
	});
	
		jQuery('.atLeastSlider').each(function(index, value){
		var el =  jQuery(this).attr('id');
		var elValue = "#" + el.replace("slider", "value");
		var elRange = eval(el.replace("_slider", "Range"));
		if (!elRange.start) elRange.start = 0;
		jQuery( "#"+el ).slider( "option", "value", elRange.start );
		jQuery( elValue ).html( atLeastLabel(jQuery( "#"+el ).slider("value") , elRange ));
	});
	
	jQuery( "#replace_slider").slider( "option", "values", [0,6] );
	jQuery( "#replace_value" ).html("daily to yearly");
	
	//uncheck checkboxes, disable appropriate controllers and labels
	jQuery("#ed-lenses-tab_advanced input:checkbox").prop('checked', false);
	jQuery( "#basecurve-any" ).prop('checked', true);
	jQuery( "#cyl_slider" ).slider( "option", "disabled", true );
	jQuery(".toric-label").addClass("text-disabled");	
	jQuery( "#add_slider" ).slider( "option", "disabled", true );
	jQuery(".bifocal-label").addClass("text-disabled");
	jQuery("#ed-lenses-bifocal_type").attr("disabled", true);
	jQuery("#ed-lenses-cosmetic_color").attr("disabled", true);
	jQuery("#ed-lenses-bifocal_type")[0].selectedIndex = 0;
	jQuery("#ed-lenses-cosmetic_color")[0].selectedIndex = 0;
	
	//set the sliders
}

//pct is the slider's value (0-100)
function moreLessLabel (pct, item ){
	if (!item.decimal) item.decimal = 0;
	if (!item.steps) item.steps = 1;
	if (!item.unit) item.unit = "";
	var suffix = (pct>=50)?" or more":" or less";
	var val = (item.max - item.min) * 2 * Math.abs(pct/100 - .50) + item.min;
	val = Math.round(val/item.steps)*item.steps;
	return val.toFixed(item.decimal) + item.unit + suffix;
}

//all these will be diopter numbers so we won't worry about the units or decimals
function atLeastLabel (pct, item){
	if (!item.steps) item.steps = 1;
	if (!item.unit) item.unit = "";
	if (!item.prefix) item.prefix = "";
	if (!item.multiply) item.multiply = 1;
	var val = (item.max - item.min) * pct/100  + item.min;
	val = Math.round(val/item.steps)*item.steps;
	val = parseFloat(item.prefix + val * item.multiply);
	var sign = (val >= 0)?"+":"";
	if (val == 0) return "at least plano";
	return "at least " + sign + val.toFixed(2) + " D";
}

function replaceLabel(values){
	var val1 = replaceText(values[0]);
	var val2 = replaceText(values[1]);
	if (val1 == val2) return val1;
	return val1 + " to " +val2;
}

function replaceText(val){
	if (val==0) return "daily";
	if (val==1) return "2 weeks";
	if (val==2) return "monthly";
	if (val==3) return "quarterly";
	if (val==4) return "6 months";
	if (val==5) return "yearly";
	return "?";
}
	
function moreOrLess (val){
	return (val<0)?" or less":" or more";
}	
	
	
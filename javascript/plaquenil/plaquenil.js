var height_m;
var height_inches = 64;
var weight_kg = 54; 
var weight_lbs = 120; 
var minWeight =  70;
var maxWeight = 300;
var minHeight = 48;
var maxHeight = 86;
  
jQuery(document).ready(function(){
   
    jQuery( "#sex" ).buttonset();
  
    jQuery( "#slider-weight" ).slider({
      range: "min",
      min: minWeight,
      max: maxWeight,
      value: weight_lbs,
      slide: weightChange
    });
    
    jQuery( "#slider-height" ).slider({
      range: "min",
      min: minHeight,
      max: maxHeight,
      value: height_inches,
      slide: heightChange
    });
    
    jQuery("#weight_input").change(function() {
    	var val = parseInt(jQuery( this ).val() );
    	weight_lbs = (val >= minWeight && val <= maxWeight) ? val : weight_lbs;
		jQuery("#slider-weight").slider( "value", weight_lbs );
		weightChange ();
	});
	
	jQuery("#height_input").change(function() {
    	var val = parseInt(jQuery( this ).val() );
    	height_inches = (val >= minHeight && val <= maxHeight) ? val : height_inches;
		jQuery("#slider-height").slider( "value", height_inches );
		heightChange () ;
	});
    
    
	weightChange ();
	heightChange () ;
  
});

function weightChange (){
	weight_lbs = jQuery( "#slider-weight" ).slider( "value" );
	weight_kg = parseFloat(calcKg (weight_lbs));
	jQuery( "#weight_input" ).val(weight_lbs ) ;
	jQuery("#extraweight").html(weight_lbs + " lbs = " + weight_kg.toFixed(1) + " Kg");
	doDailyCalc ();
}

function heightChange () {
	height_inches = jQuery( "#slider-height" ).slider( "value" ); 
	height_m =  calcMeters(height_inches) ;
	var meter_str = height_m.toFixed(3) + " meters";
	var ft_in = calcFeetInches (height_inches);
	jQuery( "#height_input" ).val(height_inches); 
	jQuery("#extraheight").html(height_inches + "\" = " + ft_in + " = " + meter_str );
	doDailyCalc ();
}

function doDailyCalc () {
	var dose = safeDose (weight_kg);
	var bmi = calcBMI();
	jQuery("#bmi").html(bmi + " (" + bmiString() + ")" );
	jQuery("#weightResult").html(weight_kg.toFixed(1));
	jQuery("#dailyResult").html( dose.toFixed(0) );
	if (dose >= 400) {
		jQuery("#dailyResult").addClass("green");
		jQuery("#dailyResult").removeClass("red");
	} else {
		jQuery("#dailyResult").removeClass("green");
		jQuery("#dailyResult").addClass("red");
	}
	var text = "<p>";
	if (dose > 400) text += "  Taking the typical Plaquenil dosage (two 200 mg tablets or 400 mg/day) would be considered appropriate (in the absence of other risk factors).";
	if (dose < 400) text += "  Taking the typical Plaquenil dosage (two 200 mg tablets or 400 mg/day), <i>may</i> place your patient at higher risk for retinotoxicity.";
	text += "</p>";

		if (bmi > 24.9) {
			text += "  <b>However</b>, as the BMI is " + bmiString() +", it may be more appropriate to use the <i>ideal</i> body weight to calculate the generally regarded \"safe\" dose (see accompanying explanation on this page).";
			text += "  For this height this patient's ideal weight would be " + calcPounds(idealWeight() ).toFixed(1) + " lbs (" + idealWeight().toFixed(1) + " kg).";
			text += "  Using this weight, the \"safe\" dose would be <b>" + safeDose(idealWeight() ).toFixed() + " mg / day</b>.";
		} else {
			text += "  As the BMI is <i>not</i> classified as overweight, using the patient's true weight should be appropriate for dosage calculations.";
		}
		text += "</p><p></p>"
		
		jQuery("#daily_discussion").html(text);
	}

function calcMeters(inches) {
	return inches * 0.0254;
}

function calcFeetInches (inches){
	var ft = parseInt(inches/12);
	var inc = inches % 12;
	return ft + "' " + inc + "\"";
}

function calcInches() {
	var inches = 0;
	var ft = jQuery("#height_ft").val();
	var inch = jQuery("#height_in").val();
	inches = ft *12 + inch *1;
	return inches;
}


function calcKg(lbs) {
	return lbs * 0.45359;
}

function calcPounds (kg) {
 return kg / 0.45359	;
}

function safeDose (kg) {
	//uses formula 
	// weight * 6.5 for HCQ
	// weight * 3 for chloroquine
	//var val1 = (jQuery('#cq').is(':checked') )?3:6.5; 
	return  6.5 * kg;
}

function calcBMI() {
	var ans = weight_kg/ (height_m * height_m);
	return ans.toFixed(1);
}

function bmiString () {
	var string = "underweight";
	var bmi = calcBMI();
	if (bmi>=18.5) string = "normal weight";
	if (bmi>24.9) string = "overweight";
	if (bmi>29.9) string = "class I obesity";
	if (bmi>34.9) string = "class II obesity";
	if (bmi>=40) string = "class III obesity";
	return string;
}

function idealWeight () {
	
	//uses formula
	// for men =50 + 2.3 ( Height(in) - 60 )
	//for woman= 45.5 + 2.3 ( Height(in) - 60 )
	//form.sex[0] = women, [1] = men

	var var1 = (jQuery('#female').is(':checked') )?45.5:50; 
	ans = var1 + (2.3 * ( height_inches - 60 ) );

	return ans;
}

//------------- cumulative dose calcs ---------

jQuery(document).ready(function(){
	jQuery(".cumRow input, .cumRow select").change(function(){
		doCumCalc();
	});
	doCumCalc();
});

function cloneClicked (el) {
	var row = jQuery(el.target || el.srcElement).closest(".cumRow");	
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
	doCumCalc();
}

function removeFilterRow (el) {
	var row = jQuery(el.target || el.srcElement).closest(".cumRow");	
	var rowCount = jQuery(".cumRow").length;
	//don't remove the row if only one row visible!
	if (rowCount > 1) jQuery(row).closest(".cumRow").remove();
	doCumCalc();	
}

function doCumCalc(){
	var totalDose = 0;
	jQuery(".cumRow").each(function(){
		var dose = jQuery(this).find(".dose").val();
		var freq = jQuery(this).find(".freq option:selected").val();
		var time = jQuery(this).find(".time option:selected").val();
		var months_years = jQuery(this).find(".months_years option:selected").val();
		
		var rowDose = (dose * freq *  365 * time * (months_years / 12) ) / 1000;
		var rowDoseGrams = "("+ rowDose.toFixed(0) +"g)";
		jQuery(this).find(".rowTotal").html(rowDoseGrams);
		totalDose += rowDose;
		jQuery("#cumAnswer").html( totalDose.toFixed(0) + "g" );
		
		if (totalDose >= 1000) {
			jQuery("#cumAnswer").addClass("red");
			jQuery("#cumAnswer").removeClass("green");
		} else {
			jQuery("#cumAnswer").removeClass("red");
			jQuery("#cumAnswer").addClass("green");
		}
		
	});
}
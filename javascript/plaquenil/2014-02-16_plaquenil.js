
	jQuery().ready(function(){
		
		jQuery("#plaquenilDaily").submit(function(){
			doCalcs()
			return false;
		});
		
		jQuery("#plaquenilCum").submit(function(){
			doCumCalc()		
			return false;
		});
		
		jQuery("input.plaqInput").blur(function(){
			formatInput();
			return false;
		});
		
		jQuery("input.plaqInput").keydown(function(){
			jQuery("#results").hide("slow").html("");
			return true;
		});
		
		jQuery("input[type='radio']").click(function(){
			jQuery("#results").hide("slow").html("");
			return true;
		});
		
		
		jQuery("input.cumInput").blur(function(){
			formatCumText();
			return false;
		});
		
		jQuery("input.cumInput").keydown(function(){
			jQuery("#cum_results").hide("slow").html("");
			return true;
		});
		
		jQuery("#dailyDiscussionA").click(function(){
			jQuery("#dailyDiscussionP").toggle('fast');
			return false;
		});
		
		jQuery("#cumDiscussionA").click(function(){
			jQuery("#cumDiscussionP").toggle('fast');
			return false;
		});
		
		
		jQuery("#results").hide();
		jQuery("#cum_results").hide();
		jQuery("#dailyDiscussionP").hide();
		jQuery("#cumDiscussionP").hide();
		
		
	});
 

function calcMeters() {
	var m = 0;
	var ft = jQuery("#height_ft").val();
	var inch = jQuery("#height_in").val();
	m = ft *0.3048 + inch *0.0254
	return m;
}

function calcInches() {
	var inches = 0;
	var ft = jQuery("#height_ft").val();
	var inch = jQuery("#height_in").val();
	inches = ft *12 + inch *1;
	return inches;
}


function calcKg() {
	var kg = 0;
	var lbs = jQuery("#weight").val();
	kg = lbs * 0.45359;
	return kg;
}

function calcPounds (kg) {
 return kg / 0.45359	;
}

function formatInput () {
//alert ("The value entered must be between 35-65 Diopters.  ");
			var m = calcMeters ();
			var kg = calcKg ();
			if (!weightBad() ) jQuery("#kilos").html("(" + kg.toFixed(1) + " kg)");
			if (!heightBad() ) jQuery("#meters").html("(" +  m.toFixed(2) + " meters)");

}

function weightBad () {
	var retVar = false;
	var kg = calcKg();
	if (kg>185 || kg < 22) retVar = true;
	return retVar;
}

function heightBad () {
	var retVar = false;
	var m = calcMeters();
	if ( m > 2.43 || m < .91) retVar = true;
	return retVar;
}

function calcBMI() {
	var m = calcMeters ();
	var kg = calcKg ();
	var ans = kg/ (m*m);
	return ans;
}

function bmiString () {
	var string = "underweight";
	var bmi = calcBMI();
	if (bmi>=18.5) string = "a normal weight";
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
	
	var w = calcKg();
	var h = calcInches();
	var var1 = (jQuery('#female').is(':checked') )?45.5:50; 
	ans = var1 + (2.3 * ( h - 60 ) );

	return ans;
	
	
}

function safeDose (weight) {
	//uses formula 
	// weight * 6.5 for HCQ
	// weight * 3 for chloroquine
	var val1 = (jQuery('#cq').is(':checked') )?3:6.5; 
	var ans = val1 * weight;
	return ans;
}

function doCalcs () {
	if (weightBad() || heightBad() ) {
		alert ("Please enter a weight between 50 and 400 lbs and a height between 3 and 8 feet.");
		return;
	}
	
			//alert ("doin' calcs");

	var dose = safeDose(calcKg() );
	var text = "<b><u>Result</u></b><br/>";
	text += "<p> 6.5 mg/kg x " + calcKg().toFixed(1) + " kg = <b> " + dose.toFixed() + "mg/day.</b><br />";
	text += " This dosage (and less) would be generally regarded as a safe daily dose.";
	if (dose > 400) text += "  Taking the typical Plaquenil dosage (two 200 mg tablets or 400 mg/day) would be considered appropriate (in the absence of other risk factors).";
	if (dose < 400) text += "  Taking the typical Plaquenil dosage (two 200 mg tablets or 400 mg/day), <i>may</i> place your patient at higher risk for retinotoxicity.";
	text += "</p>";
	if (!weightBad() && ! heightBad() ) {
		var bmi = calcBMI();
		text += "<p>  This patient's BMI is " + bmi.toFixed(1) + ", which would be classified as " + bmiString() +".";
		if (bmi > 24.9) {
			text += "  As a consequence, it may be more appropriate to use the <i>ideal</i> body weight to calculate the generally regarded \"safe\" dose (see explanation below).";
			text += "  For this height this patient's ideal weight would be " + calcPounds(idealWeight() ).toFixed(1) + " lbs (" + idealWeight().toFixed(1) + " kg).";
			text += "  Using this weight, the \"safe\" dose would be <b>" + safeDose(idealWeight() ).toFixed() + " mg / day</b>.";
		} else {
			text += "  As a consequence using the patient's true weight should be appropriate for dosage calculations.";
		}
		text += "</p><p></p>"
	}
	
	jQuery("#results").html(text).slideDown("slow");;
}

function formatCumText () {
	var dose = jQuery("#dosage").val();
	var years = jQuery("#years").val();
	var months = jQuery("#months").val();
	var days =  (years * 1 +  (months / 12) ) * 365;
	if (dose > 0 ) jQuery("#dose_grams").html(" (" + (dose / 1000).toFixed(3) +"g)");
	if (days > 0 ) jQuery("#days").html(" (" + days.toFixed() +" days)");
}

function doCumCalc() {
	var dose = jQuery("#dosage").val();
	var doseGrams = dose /1000;
	var years = jQuery("#years").val();
	var months =  jQuery("#months").val();
	var days =  (years * 1 +  (months / 12) ) * 365;
	if ( isNaN (years) || isNaN (dose) ) {
		alert ("Please enter numeric values");
			   return;
	}

	var cum = days * doseGrams;
	if ( !isNaN (cum) ) {
		var text = "<b><u>Result</u></b><br/>Cumulative Dose: ";
		text += days.toFixed() + " days x " + doseGrams.toFixed(3) + " g = <b>" + cum.toFixed() + " g</b>"; 
		jQuery("#cum_results").html(text).show("slow");  
	}
}

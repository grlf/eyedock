// -------RxHelper utilities---------



function numToDiopterString(aNum, showSign, round) {

	var numberString;
	
	if (isNaN(aNum)  || !aNum ) return undefined;
	
	if (aNum > 100) aNum = aNum / 100;

	roundTo = 1 / round;

	aNum = Math.round(roundTo * aNum) / roundTo;

	var sign = "";
	if(showSign) {
		if(aNum > 0) sign = "+";
		numberString = sign + aNum.toFixed(2);
	} else {
		numberString = aNum.toFixed(2);

	}

	return numberString;
}


//make sure that this receives a positive value - it doesn't check

function numToAxisString(aNum) {

	if (isNaN(aNum)  || !aNum) return undefined;
	var s = Math.round (aNum) + "";
	if (s == 0) s = 180;
    while (s.length < 3) s = "0" + s;

	return s;

}

function roundToAxis(aNum, round) {

	roundTo = 1 / round;
	var newAxis = fixAxis(aNum);

	//round to the closest 90 or 180 degrees
	if((newAxis < 45 && newAxis > 0) || (newAxis > 90 && newAxis < 135)) {
		newAxis = Math.round(roundTo * newAxis - .1) / roundTo;
	} else {
		newAxis = Math.round(roundTo * newAxis + .1) / roundTo;

	}
	return newAxis;
}



function checkSphereRange(aNum) {
	if(aNum < -30 || aNum > +30) return false;
	return true;
}

function checkCylinderRange(aNum) {
	if(aNum < -12 || aNum > +12) return false;
	return true;
}

function checkAxisRange(aNum) {
	if(isNaN(aNum)) return false;
	if(aNum < 0 || aNum > 180) return false;
	return true;
}

function checkMMRange(aNum) {
	if(aNum > 11.3 || aNum < 4.8) return false;
	return true;
}

function checkKRange(aNum) {
	if(aNum > 70 || aNum < 25) return false;
	return true;
}




function cleanRxString(rxString) {

	//remove whitespace

	rxString = rxString.replace(/\s+/g, ' ');

	//replace words with numbers:
	rxString = rxString.replace(/plano/i, "0");
	rxString = rxString.replace(/pl/i, "0");
	rxString = rxString.replace(/sphere/i, "0");
	rxString = rxString.replace(/sph/i, "0");

	//make sure the first char is a + or - sign
	if(rxString.charAt(0) != "+" && rxString.charAt(0) != "-") rxString = "+" + rxString;

	return rxString;

}

function splitRxString(rxString) {
	//put breakpoints (:) between the sphere, cyl, and axis
	rxString = rxString.replace(/\+/g, ":+");
	rxString = rxString.replace(/\-/g, ":-");
	rxString = rxString.replace(/x/i, ":x");
	rxString = rxString.replace(/\*/i, ":x");


	//remove the breakpoint from the front of the string
	rxString = rxString.substr(1);


	return rxString;

}


function removeLetters(numString) {

	var digits = numString.replace(/[^0-9\.-]/g, '');

	return Number(digits);
}

function removeAllButNumbers(numString) {

	var digits = numString.replace(/[^0-9\.]/g, '');


	return Number(digits);
}

function removeAllButFullKeratoChars(keratoString) {

	var digits = numString.replace(/[^0-9\.-\/\@]/g, '');


	return keratoClean;
}



function rxStringBreaker(rxString) {

	rxString = cleanRxString(rxString);

	rxString = splitRxString(rxString);

	var rxChunks = rxString.split(":")

	var returnRx = new RxObject();

	if(rxChunks.length > 0) {
		returnRx.sph = removeLetters(rxChunks[0]);
		if (isNaN(returnRx.sph ) ) returnRx.sph  = 0;
	} else {
		returnRx.sph = 0;
	}

	if(rxChunks.length > 1) {
		returnRx.cyl = removeLetters(rxChunks[1]);
		if (isNaN(returnRx.cyl ) ) returnRx.cyl  = 0;
	} else {
		returnRx.cyl = 0;
	}

	if(rxChunks.length > 2) {
		returnRx.axis = removeLetters(rxChunks[2]);
		if (isNaN(returnRx.axis) ) returnRx.axis  = 0;
	} else {
		returnRx.axis = 0;
	}

	checkSph = checkSphereRange(returnRx.sph);
	checkCyl = checkSphereRange(returnRx.cyl);
	checkAxis = checkAxisRange(returnRx.axis);

	if(!checkSph || !checkCyl || !checkAxis) return null;

	return returnRx;
}

function fixAxis(axis) {
	while(axis <= 0) {
		axis += 180;
	}
	while(axis > 180) {
		axis -= 180;
	}
	return axis;
}

function prettyRxString(sph, cyl, axis, round) {

	axis = fixAxis(axis);

	var sphString = numToDiopterString(sph, 1, round);
	var cylString = numToDiopterString(cyl, 1, round);
	var axisString = " x " + numToAxisString(axis);

	if(Math.abs(sph) < round / 2) sphString = "plano";
	if(Math.abs(sph) < round / 2 && Math.abs(cyl) < round / 2) return "plano";
	if(isNaN(sph)) sphString = "plano";
	if(Math.abs(cyl) < round / 2) {
		if (sphString == "plano") return "plano";
		cylString = "sph";
		axisString = "";
	}

	return sphString + " " + cylString + axisString;
}


function isNumeric(aNum) {
	var numbers = /^[0-9]./;
	if(aNum.match(numbers)) {
		return true;
	} else {
		return false;
	}
}

function kStringBreaker(kString) {

	var kNum = removeAllButNumbers(kString);
	var mmOK = checkMMRange(kNum);
	var dOK = checkKRange(kNum);

	var keratoObject = new KeratoObject();

	if(mmOK || dOK) {
		keratoObject.kValue = kNum;
		return keratoObject;
	}
	// if the entered value isn't a number within the diopter or mm range...
	return null;
}


function fullKStringBreaker(fullKString) {

	var fullKString = removeAllButFullKeratoChars(fullKString);

	var returnKeratoObj = new FullKeratoObject();
	var keratoObject1 = new KeratoObject();
	var keratoObject2 = new KeratoObject();

	//check if one number is entered eg) 43.00 may be a spherical cornea.  also check for "/" and "" signs
	if(isNumeric(fullKString)) {
		returnKeratoObj.k1 = kStringBreaker(fullKString);
		returnKeratoObj.k2 = kStringBreaker(fullKString);
		returnKeratoObj.k2meridian = 90;
		return returnKeratoObj;

	}


	//string should be in this format: 42.00/43.0090 ->: split string at the "/"
	var kchunks = fullKString.split("/")

	//must have 2 parts
	if(kChunks.length != 2) return null;

	keratoObject1 = kStringBreaker(kChunks[0]);
	returnKeratoObj.k1 = keratoObject1;

	//now split the second half at the ""
	var kChunks2 = kChunks[1].split("@");


	//must have 2 parts
	if(kChunks2.length != 2) return null;

	keratoObject2 = kStringBreaker(kChunks2[0]);
	returnKeratoObj.k2 = keratoObject2;
	returnKeratoObj.k2meridian = kChunks2[1];


	checkK1 = checkKRange(keratoObject1.kValue);
	checkK2 = checkKRange(keratoObject2.kValue);
	checkAxis = checkAxisRange(returnKeratoObj.k2meridian);

	if(!checkK1 || !checkK2 || !checkAxis) return null;



	return returnKeratoObj;
}


function radians(degrees) {
	return degrees * Math.PI / 180;
}


function degrees(radians) {
	return radians * 180 / Math.PI;
}

// ------- end  RxHelper utilities---------



// ----------- kerato object---------------------------------

//
//  KeratoObject.m
//  Rxtest
//
//  Created by Todd Zarwell on 5/10/10.
//  Copyright 2010 EyeDock. All rights reserved.
//

function KeratoObject() {

	this.round = .25;
	this.isMM = false;
	this.isDiopters = true;
	this.kValue = 0;

	this.setKValue = function(kVal) {
		this.isMM = checkMMRange(kVal);
		this.isDiopters = (isMM) ? false : true;
		this.kValue = kVal;
	}


	this.kValueMM = function() {
		return(this.isMM) ? this.kValue : 337.5 / this.kValue;
	}

	this.kValueD = function() {
		return(isDiopters) ? this.kValue : 337.5 / this.kValue;;
	}

	this.prettyString = function() {
		return(this.isDiopters) ? this.prettyStringK() : this.prettyStringMM();
	}

	//if K value is in diopters, returns a string in mm (and vice versa)
	this.prettyStringConverted = function() {
		return(this.isDiopters) ? this.prettyStringMM() : this.prettyStringK();
	}

	this.prettyStringK = function() {

		return numToDiopterString(this.kValueD(), 0, this.round) + " D";
	}

	this.prettyStringKNoD = function() {
		return numToDiopterString(this.kValueD(), 0, this.round);
	}


	this.prettyStringMM = function() {

		return numToDiopterString(this.kValueMM(), 0, .01) + " mm";
	}

	this.prettyStringMMNoMM = function() {

		return numToDiopterString(this.kValueMM(), 0, .01);
	}


}

// ----------- end kerato object----------------------------



// ------------- full kerato object---------------------------


function FullKeratoObject() {

	this.k1 = new KeratoObject();
	this.k2 = new KeratoObject();
	this.k2meridian = 0;

	this.prettyStringD = function() {
		var returnString;

		//if spherical cornea:
		if(this.isSphericalCornea()) {
			returnString = k1.prettyStringKNoD() + " sph";
		} else {
			returnString = k1.prettyStringKNoD() + " / " + k2.prettyStringKNoD() + " @ " + numToAxisString(k2meridian);
		}
		return returnString;
	}

	this.prettyStringMM = function() {
		var returnString;

		//if spherical cornea:
		if(isSphericalCornea) {
			returnString = k1.prettyStringMMNoMM() + " sph";

		} else {
			returnString = k1.prettyStringMMNoMM() + " / " + k2.prettyStringMMNoMM() + " @ " + numToAxisString(k2meridian);
		}

		return returnString;
	}

	this.flatK = function() {
		return(k1.kValue < k2.kValue) ? k1.kValue : k2.kValue;
	}

	this.steepK = function() {
		return(k1.kValue > k2.kValue) ? k1.kValue : k2.kValue;
	}

	this.flatKMeridian = function() {
		return(k1.kValue < k2.kValue) ? fixAxis(k2meridian + 90) : k2meridian;
	}

	this.steepKMeridian = function() {
		return(k1.kValue < k2.kValue) ? k2meridian : fixAxis(k2meridian + 90);
	}


	this.horizK = function() {
		return(k2meridian <= 45 && k2meridian > 135) ? k2.kValue : k1.kValue;
	}

	this.vertK = function() {
		return(k2meridian > 45 && k2meridian <= 135) ? k2.kValue : k1.kValue;
	}

	this.horizMeridian = function() {
		return(k2meridian <= 45 || k2meridian > 135) ? k2meridian : fixAxis(k2meridian + 90);

	}

	this.vertMeridian = function() {
		return(k2meridian > 45 && k2meridian <= 135) ? k2meridian : fixAxis(k2meridian + 90);
	}

	this.midK = function() {
		return(k1.kValue + k2.kValue) / 2;
	}

	this.cornealCyl = function() {
		return Math.abs(k1.kValue - k2.kValue);
	}

	this.cornealRx = function() {
		var returnRx = new RxObject();
		returnRx.sph = 0;
		returnRx.cyl = (k1.kValue - k2.kValue);
		returnRx.axis = k2meridian;

		return returnRx;

	}

	this.isSphericalCornea = function() {
		return(cornealCyl < .12) ? true : false;
	}

	this.isWTR = function() {
		return(flatKMeridian <= 30 || flatKMeridian >= 150) ? true : false;
	}

	this.isATR = function() {
		return(flatKMeridian >= 60 && flatKMeridian <= 120) ? true : false;
	}

	this.isOblique = function() {
		return((flatKMeridian > 30 && flatKMeridian < 60) || (flatKMeridian > 120 && flatKMeridian < 150)) ? true : false;
	}



}

// function ------------ end full kerato object---------------------------


// --------- CLHelper -------------

function softBaseCurveAdvice(midK) {

	//if midK = 0;
	if(midK < 25) return "Keratometry needed to determine";

	if(midK <= 42) return "flat";

	if(midK > 42 && midK <= 43) return "flat to median";

	if(midK > 43 && midK <= 44) return "median";

	if(midK > 44 && midK <= 45) return "median to steep";

	if(midK > 45) return "steep";

	return "Unable to determine withour keratometry";
}




function softBaseCurveInt(midK) {
	//for searching: 0 = any, 1=flat, 2=med, 3=steep
	//int #'s correspond with segment index in adv lens search

	//if midK = 0;
	if(midK < 25) return 0;

	if(midK <= 42) return 1;

	if(midK > 44.5) return 3;

	return 2;
}


function softDiameterAdvice() {
	return "Usually 13.5 to 15.0 mm *";
}

function reduceToricCylPower(theRx, maxCyl) {

	//check to see if cyl needs to be reduced (0 change if cyl is already below max cyl)
	if(Math.abs(maxCyl) > Math.abs(theRx.cylM())) return theRx;

	var returnRx = new RxObject();
	returnRx.cyl = -Math.abs(maxCyl);
	returnRx.sph = theRx.sphM() + (theRx.cylM() - maxCyl) / 2;
	returnRx.axis = theRx.axisM();

	return returnRx;
}

function rgpBCfitFactor(cornealCyl) {
	//make sure it's a positive #
	cornealCyl = Math.abs(cornealCyl);
	if(cornealCyl <= .5) return -0.75;
	if(cornealCyl > .5 && cornealCyl <= 1) return -.5;
	if(cornealCyl > 1 && cornealCyl <= 1.25) return -.25;
	if(cornealCyl > 1.75 && cornealCyl <= 2) return .25;
	if(cornealCyl > 2 && cornealCyl <= 3) return .5;
	if(cornealCyl > 3) return .75;
	return 0;


}


function rgpFrontToricfitFactor(cornealCyl) {
	//make sure it's a positive #
	cornealCyl = Math.abs(cornealCyl);
	if(cornealCyl <= .12) return -0.50;
	if(cornealCyl > .12 && cornealCyl <= .37) return -.25;
	if(cornealCyl > .37 && cornealCyl <= .67) return 0;
	if(cornealCyl > .67 && cornealCyl <= 1.12) return .25;
	if(cornealCyl > 1.12 && cornealCyl <= 1.67) return .45;
	if(cornealCyl > 1.67) return .62;

	if(cornealCyl > 3) return .75;
	return 0;


}

function rembraFitfactorFlat(cornealCyl) {
	//make sure it's a positive #
	cornealCyl = Math.abs(cornealCyl);
	fitFactor = 0;
	if(cornealCyl > 1.5 && cornealCyl <= 4.5) fitFactor = -.25;
	if(cornealCyl > 4.5) fitFactor = -.50;
	return fitFactor;
}

function rembraFitFactorSteep(cornealCyl) {
	//make sure it's a positive #
	cornealCyl = Math.abs(cornealCyl);
	fitFactor = 0;
	if(cornealCyl > 1.5 && cornealCyl <= 2.5) fitFactor = -.25;
	if(cornealCyl > 2.5 && cornealCyl <= 3.5) fitFactor = -.50;
	if(cornealCyl > 3.5) fitFactor = -.75;
	return fitFactor;

}

function mandellMooreFitfactorFlat(cornealCyl) {
	//make sure it's a positive #
	cornealCyl = Math.abs(cornealCyl);
	fitFactor = 0;
	if(cornealCyl > 2.25) fitFactor = -.25;
	return fitFactor;
}

function mandellMooreFitFactorSteep(cornealCyl) {
	//make sure it's a positive #
	cornealCyl = Math.abs(cornealCyl);
	fitFactor = -0.50;
	if(cornealCyl > 2.75 && cornealCyl < 3.75) fitFactor = -.75;
	if(cornealCyl > 3.75 && cornealCyl <= 4.50) fitFactor = -1;
	if(cornealCyl > 4.50) fitFactor = -1.25;

	return fitFactor;
}

//compares axis of corneal cyl to axis of refractive cyl
// in theory,the flat k meridian and the mr axis should match up pretty closely
// keratometry is fullkerato object

function compareKandMrAxis(keratometry, mr) {
	var kAxis = keratometry.flatKMeridian();
	var mrAxis = mr.axisM();

	if(kAxis == 180) kAxis = 0;
	if(mrAxis == 180) mrAxis = 0;
	diff = Math.abs(kAxis - mrAxis);
	if(kAxis > 135 && mrAxis < 45) {
		diff = Math.abs((180 - kAxis) - mrAxis);
	}
	if(mrAxis > 135 && kAxis < 45) {
		diff = Math.abs((180 - mrAxis) - kAxis);
	}
	return diff;
}

/*

function  adviceHTMLtext (advice) {
		NSString *someHTML = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html><head><style type='text/css'>body{background-color:transparent; font: 14px Arial; } .headr {font-weight: bold;background-color:#7dbee3;text-align:center; padding: .5em;} li {padding-bottom:.75em;}</style></head><body >%</body></html>", advice;
	return someHTML;
}

*/


// --------- end  CLHelper -------------





//   -------------    RxObject  ----------------

function RxObject() {

	this.sph = 0;
	this.cyl = 0;
	this.axis = 0;
	this.round = 0.25;
	this.isPlusCyl = 0;
	this.isMinusCyl = 1;
	this.vertex = .012;



	this.setCyl = function(cl) {
		this.cyl = cl;
		this.isPlusCyl = (cl > 0) ? true : false;
		this.isMinusCyl = (cl < 0) ? true : false;
	}


	this.prettyString = function() {

		return prettyRxString(this.sph, this.cyl, this.axis, this.round);

	}


	this.prettyStringPlus = function() {

		return prettyRxString(this.sphP(), this.cylP(), this.axisP(), this.round);
	}

	this.prettyStringMinus = function() {
		return prettyRxString(this.sphM(), this.cylM(), this.axisM(), this.round);
	}



	this.diffVertex = function(vertexTo) {


		var oldSph = this.sph;
		var oldCyl = parseFloat(this.sph) + parseFloat(this.cyl);
		var newSph = 1 / (1 / oldSph - (this.vertex - vertexTo));
		var newCyl = 1 / (1 / oldCyl - (this.vertex - vertexTo));

		var vertexedRx = new RxObject();


		vertexedRx.sph = newSph;
		vertexedRx.cyl = newCyl - newSph;
		vertexedRx.axis = this.axis;
		vertexedRx.vertex = vertexTo;
		return vertexedRx;

	}

	this.sphericalEquivalent = function() {
		return parseFloat(sph) + .5 * cyl;
	}

	//numbers in minus sphere
	this.sphM = function() {
		var returnNum = this.sph;
		if(this.cyl > 0) returnNum = this.sph + this.cyl;
		return returnNum;
	}

	this.cylM = function() {
		var returnNum = this.cyl;
		if(this.cyl > 0) returnNum = -this.cyl;
		return returnNum;

	}
	this.axisM = function() {
		var returnNum = this.axis;
		if(this.cyl > 0) returnNum = this.axis - 90;
		returnNum = fixAxis(returnNum);
		return returnNum;
	}

	//number in plus cyl
	this.sphP = function() {
		var returnNum = this.sph;
		if(this.cyl < 0) returnNum = parseFloat(this.sph) + parseFloat(this.cyl);
		return returnNum;
	}

	this.cylP = function() {
		var returnNum = this.cyl;
		if(this.cyl < 0) returnNum = -this.cyl;
		return returnNum;
	}

	this.axisP = function() {
		var returnNum = this.axis;
		if(this.cyl < 0) returnNum = this.axis - 90;
		returnNum = fixAxis(returnNum);
		return returnNum;
	}


	this.isWTR = function() {
		return(this.axisP() <= 30 || this.axisP() >= 150) ? true : false;
	}

	this.isATR = function() {
		return(this.axisP() >= 60 && this.axisP() <= 120) ? true : false;
	}

	this.isOblique = function() {
		return((this.axisP() > 30 && this.axisP() < 60) || (this.axisP() > 120 && this.axisP() < 150)) ? true : false;
	}
	
	this.verticalMeridian = function(){
		var axis = fixAxis(this.axisP());
		var axis2 = fixAxis(axis -= 90);
		if (axis >= 45 && axis < 135 ) return axis;
		return axis2;
	}
	
	this.horizontalMeridian = function(){
		return fixAxis (this.verticalMeridian() - 90);
	}

	this.verticalPower = function(){
		var axis = fixAxis(this.axisP());
		if (axis >= 45 && axis < 135) return this.sphP();
		return Number(this.sphP()) + Number(this.cylP());
	}

	this.horizontalPower = function(){
		var axis = fixAxis(this.axisP());
		if (axis >= 45 && axis < 135) return Number(this.sphP()) + Number(this.cylP());
		return this.sphP();
	}


	this.addRx = function(lensRx2) {

		var ansObj = new RxObject();
		var clSph = this.sphP();
		var clCyl = this.cylP();
		var clAxis = this.axisP();
		var orSph = lensRx2.sphP();
		var orCyl = lensRx2.cylP();
		var orAxis = lensRx2.axisP();

		var resSph = 0;
		var resCyl = 0;
		var resAxis = 0;

		//the following was added 12/04/2011
		//if either lens has 0 cyl (is spherical) set the axis to match the other lens
		if(clCyl == 0) clAxis = orAxis;
		if(orCyl == 0) orAxis = clAxis;

		var axisDiff = orAxis - clAxis;



		var resAxis = (Math.atan((Math.sin(radians(axisDiff * 2)) * orCyl) / (Number(clCyl) + (orCyl * Math.cos(radians(axisDiff * 2)))))) / 2;
		resAxis = degrees(resAxis);

		if(axisDiff !== 0) {

			resCyl = (orCyl * Math.sin(radians(2 * axisDiff))) / (Math.sin(radians(2 * resAxis)));
			resSph = (Number(clCyl) + Number(orCyl) - (resCyl)) / 2;


			resAxis += Number(clAxis);


			resSph += Number(clSph) + Number(orSph);

			if(orCyl == 0 || clCyl == 0) {
				ansObj.sph = orSph + Number(clSph);
				ansObj.cyl = orCyl + Number(clCyl);
				if(orCyl == 0) {
					ansObj.axis = clAxis;

				}
				if(clCyl == 0) {
					ansObj.axis = orAxis;
				}

			} else {
				ansObj.sph = resSph;
				ansObj.cyl = resCyl;
				ansObj.axis = resAxis;


			}
 
		} else { //axisDiff = 0 (cl rx and overrefraction were at same axis)
			ansObj.sph = Number(clSph) + Number(orSph);
			ansObj.cyl = Number(clCyl) + Number(orCyl);
			ansObj.axis = clAxis;
		}

		return ansObj;
	}



	this.subtractRx = function(lensRx2) {

		//self & lensRx2 are Rx objects.  "self" is usually the MR, lensRx2 is the spectacles

		var newAxis = 0;
		var newSph = 0;
		var gamma = 0;
		var F2 = 0;
		var xSph = this.sphP();
		var xCyl = this.cylP();
		var xAxis = this.axisP();


		if(lensRx2.cylP() > .375) {

			var theta = xAxis - lensRx2.axisP();
			if(theta > 90) {
				theta = 180 - theta;
			}
			if(theta < -90) {
				theta = -180 - theta;
			}

			var a = xCyl * Math.sin(radians(2 * theta));
			var b = xCyl * (Math.cos(radians(2 * theta))) - lensRx2.cylP();
			F2 = Math.sqrt(a * a + b * b);

			if(F2 != 0) {
				gamma = degrees(Math.asin(a / F2) / 2);
			}


			newAxis = lensRx2.axisP() + parseFloat(gamma);

			newSph = xSph - lensRx2.sphP() - (lensRx2.cylP() + F2 - xCyl) / 2;
		} else {
			newSph = xSph - lensRx2.sphP();
			F2 = xCyl;
			newAxis = xAxis;
		}

		var tempObj = new RxObject();
		tempObj.sph = newSph;
		tempObj.cyl = F2;
		tempObj.axis = newAxis;

		return tempObj;


	}


}

// ----------- end RxObject ---------------

// ---------contact lens------------------------------



function ContactLens() {

	this.keratometry = new FullKeratoObject();
	this.vertexedRx = new RxObject();
	this.round = .01;

	this.power = function() {
		return "power not defined";
	}

	this.baseCurve = function() {
		return "base curve not defined";
	}

	this.diameter = function() {
		return "diameter not defined";

	}

	this.isGoodChoiceShort = function() {
		return "thoughts!";
	}

	this.isGoodChoiceLong = function() {
		return "advice!";
	}

}


// ---------end contact lens------------------------------

// ----- soft sphere ---------

function CLSoftSphere() {

	this.power = function() {
		var spherePower = this.vertexedRx.sphericalEquivalent();
		return numToDiopterString(spherePower, 1, this.round);
	}


	this.baseCurve = function() {
		return softBaseCurveAdvice(this.keratometry.midK());
	}


	this.diameter = function() {
		return softDiameterAdvice();

	}


	this.isGoodChoiceShort = function() {

		var advice = "";
		var cylAdvice = "";
		var powerAdvice = "";

		if(this.vertexedRx.cylP() < .75) cylAdvice = "<li>With only " + vertexedRx.cylP().toFixed(2) + " D of refractive cyl (vertexed) a spherical soft lens may be a good choice.</li>";
		if(this.vertexedRx.cylP() >= .75) cylAdvice = "<li>With " + vertexedRx.cylP().toFixed(2) + " D of refractive cyl (vertexed) a toric  lens may be a better choice.</li>";
		if(this.vertexedRx.cylP() >= 1.25) cylAdvice = "<li>With " + this.vertexedRx.cylP().toFixed(2) + " D of refractive cyl (vertexed) a toric lens would be a better choice.</li>";
		if(this.vertexedRx.cylP() < .25) cylAdvice = "<li>With no significant refractive cylinder a spherical soft lens would be a good choice.</li>";
		if(Math.abs(this.vertexedRx.sphM()) > 12) powerAdvice = "<li>With this magnitude of power a more customized soft lens might be needed (I can help you find one)</li>";

		advice = cylAdvice + " " + powerAdvice;
		return advice;
	}



	this.isGoodChoiceLong = function() {
		var thoughts = "";

		thoughts = "<li>* Of course, when determining diameter you need to take into account lid aperature, position, tension, etc . . .</li>";


		var advice = this.isGoodChoiceShort() + " " + thoughts;
		return advice;
	}


}

CLSoftSphere.prototype = new ContactLens();


// ----- end soft sphere ---------


// ----- soft toric ---------



function CLSoftToric() {

	this.maxCyl = -2.25;
	this.roundToAxis = 10;


	this.power = function() {
		returnRx = new RxObject();

		returnRx = reduceToricCylPower(vertexedRx, maxCyl);
		returnRx.axis = roundToAxis(vertexedRx.axis, roundToAxis);

		returnRx.round = round;
		return returnRx.prettyStringMinus();
	}

	this.baseCurve = function() {
		return softBaseCurveAdvice(this.keratometry.midK());
	}

	this.diameter = function() {
		return softDiameterAdvice();

	}

	this.isGoodChoiceShort = function() {

		var advice = "";
		var cylAdvice = "";
		var powerAdvice = "";


		if(this.vertexedRx.cylP() < .75) cylAdvice = "<li>With only " + this.vertexedRx.cylP().toFixed(2) + " D of refractive cyl (vertexed) a toric lens may not be necessary.</li>";
		if(this.vertexedRx.cylP() >= .75) cylAdvice = "<li>With " + vertexedRx.cylP().toFixed(2) + " D of refractive cyl (vertexed) a toric lens may be a good choice.</li>";
		if(this.vertexedRx.cylP() >= 1.25) cylAdvice = "<li>With " + vertexedRx.cylP().toFixed(2) + " D of refractive cyl (vertexed) a toric lens is a good choice.</li>";

		if(Math.abs(this.vertexedRx.sphM()) > 12) powerAdvice = "<li>With this magnitude of power a more customized soft lens might be needed (I can help you find one)</li>";

		advice = cylAdvice + " " + powerAdvice;

		return advice;
	}



	this.isGoodChoiceLong = function() {

		return this.isGoodChoiceShort();
	}

}

CLSoftToric.prototype = new ContactLens();



// -----end  soft toric ---------


// -----  gp  sphere ---------



function CLRGPSphere() {

	this.bcKeratoObject = function() {
		var keratoObject = new KeratoObject();
		keratoObject.round = this.round;
		keratoObject.kValue = keratometry.flatK() + rgpBCfitFactor(keratometry.cornealCyl());
		return keratoObject;
	}

	this.peripheralCurve = function() {
		var bc = this.bcKeratoObject(); //base curve
		//KeratoObject * sc = [[KeratoObject();  //secondary curve
		//KeratoObject * ic = [[KeratoObject(); //intermediate curve
		//KeratoObject * pc = [[KeratoObject(); // peripheral curve

		var sc = bc.kValueMM() + .8;
		var ic = sc + 1;
		var pc = ic + 1.4;


		return "SC/W " + sc.toFixed(1) + "/0.3; IC/W " + ic.toFixed(1) + "/0.2; PC/W " + pc.toFixed(1) + "/0.2";

	}

	this.power = function() {
		power = vertexedRx.sphM() - rgpBCfitFactor(this.keratometry.cornealCyl());

		return numToDiopterString(power, 1, this.round);
	}


	this.baseCurve = function() {

		return bcKeratoObject.prettyStringK();
	}

	function baseCurveMM() {
		return bcKeratoObject.prettyStringMM();
	}


	this.diameter = function() {
		return "OAD: 9.0 - 9.2 *";
	}

	this.isGoodChoiceShort = function() {

		if(!this.keratometry.k2meridian) return "Cannot offer advice without keratometry measurements.";

		var advice = "";
		var corneaAdvice = "";
		var cylAdvice = "";
		var axisAdvice = "";
		var cylCompare = Math.abs(this.vertexedRx.cylP() - this.keratometry.cornealCyl());
		var axisCompare = compareKandMrAxis(this.keratometry, this.vertexedRx);

		if(cylCompare <= .50) cylAdvice = "<li>As the corneal cylinder and refractive cylinder are similar a spherical lens should work well for this patient.</li>";

		if(cylCompare > .50 && cylCompare < 1.25) cylAdvice = "<li>The difference in corneal cyl vs. refractive cyl will leave about " + cylCompare.toFixed(2) + " D (vertexed) of residual astigmatism.  You may consider some sort of toric lens instead.</li>";
		if(cylCompare >= 1.25) cylAdvice = "<li>The difference in corneal cyl vs. refractive cyl will leave about " + cylCompare.toFixed(2) + " D (vertexed) of residual astigmatism.  You may consider some sort of toric lens instead.</li>";

		if(this.keratometry.cornealCyl() < 2.5 && cylCompare <= .5) corneaAdvice = "<li>With only " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder a spherical lens should work well.</li>";
		if(this.keratometry.cornealCyl() < .25 && cylCompare <= .5) corneaAdvice = "<li>With no significant corneal cylinder a spherical lens should work well.</li>";
		if(this.keratometry.cornealCyl() >= 2.5 && this.vertexedRx.cylP() > .75) {
			corneaAdvice = "<li>With " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder a better choice may be a soft toric, back surface toric RGP, or bitoric RGP</li>";
			cylAdvice = "";
		} else if(this.keratometry.cornealCyl() >= 2.5 && this.vertexedRx.cylP() < .75) {
			corneaAdvice = "<li>With " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder and only " + this.vertexedRx.cylP().toFixed(2) + " D of refractive cyl a better choice may be a soft lens, a back toric, or bitoric RGP.</li>";
		}
		if(axisCompare > 20 && this.vertexedRx.cylP() > 1) {
			axisAdvice = "<li>One potential concern is that the refractive cyl doesn't appear to line up with the corneal cyl, which might negate some of the advantages an RGP has when it comes to correcting astigmatism.</li>";
		}


		advice = cylAdvice + " " + corneaAdvice + " " + axisAdvice;
		return advice;
	}

	this.isGoodChoiceLong = function() {
		var thoughts = "<li>If you're changing the diameter of the optic zone and you want to maintain the fitting relationship of the lens, >flatten the BC 0.25 D for every 0.5 mm increase in optic zone. Conversely, steepen the BC by .25D for every 0.5mm decrease in optic zone.</li>";


		var advice = this.isGoodChoiceShort() + " " + thoughts;
		return advice;
	}


}




CLRGPSphere.prototype = new ContactLens();


// ----- end gp  sphere ---------



// ----- gp bitoric ---------


function CLRGPbitoric() {

	//the base curve of the flat meridian
	this.bc1 = function() {
		var keratoObject = new KeratoObject();
		keratoObject.round = this.round;
		keratoObject.kValue = this.keratometry.flatK() + mandellMooreFitfactorFlat(this.keratometry.cornealCyl());
		return keratoObject;
	}

	//the base curve of the steep meridian
	function bc2() {
		var keratoObject = new KeratoObject();
		keratoObject.round = round;
		keratoObject.kValue = this.keratometry.steepK() + mandellMooreFitFactorSteep(this.keratometry.cornealCyl());
		return keratoObject;
	}

	//the less minus or more plus power (correspnds to flat meridian).  
	function power1() {
		return vertexedRx.sphM() - mandellMooreFitfactorFlat(this.keratometry.cornealCyl());
	}

	//the more minus or less plus power (correspnds to steep meridian)
	function power2() {
		return vertexedRx.sphP() - mandellMooreFitFactorSteep(this.keratometry.cornealCyl());
	}



	this.power = function() {
		var power1 = this.power1();
		var power2 = this.power2();

		return numToDiopterString(this.power1(), 1, this.round) + " / " + numToDiopterString(this.power2(), 1, this.round);
	}


	this.baseCurve = function() {
		var bc1 = this.bc1();
		var bc2 = this.bc2();

		return bc1.prettyStringK() + " / " + bc2.prettyStringK();
	}

	function baseCurveMM() {
		var bc1 = this.bc1();
		var bc2 = this.bc2();

		return bc1.prettyStringMM() + " / " + bc2.prettyStringMM();
	}


	this.diameter = function() {
		return "OAD: 8.8 - 9.8 *";
	}

	this.isGoodChoiceShort = function() {

		if(!this.keratometry.k2meridian()) return "Cannot offer advice without keratometry measurements.";


		var advice = "";
		var corneaAdvice = "";
		var axisAdvice = "";
		var axisCompare = compareKandMrAxis(this.keratometry, this.vertexedRx);

		if(this.keratometry.cornealCyl() < 2) {
			corneaAdvice = "<li>With only " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder a spherical lens may work just as well or better than a bitoric lens. </li><li>A bitoric lens usually requires at <i>least</i> 2D of corneal toricity so the back surface of the lens can align properly on the eye. </li>";
		} else if(this.keratometry.cornealCyl() < 3) {
			corneaAdvice = "<li>With " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder a bitoric lens <i>may</i> be an acceptable choice for this patient. <li>However, some practitioners would argue that a bitoric lens shouldn't be considered until the corneal cylinder is at least 2.50 to 3.00 D.</li></li>";
		} else {
			corneaAdvice = "<li>With " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder a bitoric lens would appear to be a good choice for this patient.</li><li>In general, a bitoric lens is usually the best choice for high corneal astigmatism.</li>";
		}

		if(axisCompare > 20 && vertexedRx.cylP() > 1) {
			axisAdvice = "<li>One potential concern is that the refractive cyl doesn't appear to line up with the corneal cyl, which might negate some of the advantages an RGP has when it comes to correcting astigmatism (of course, this <i>could</i> be due inprecise keratometry or MR axis measurement).</li>";
		}


		advice = corneaAdvice + axisAdvice;
		return advice;
	}

	function isGoodChoiceLong() {
		var thoughts = "<li>* As far as lens diameter is concerned, ";

		if(this.keratometry.flatK() >= 45.5) {
			thoughts += "this steep of a cornea may benefit from a small diameter lens.  Try 8.5 OAD, 7.3 OZ. ";
		} else if(this.keratometry.flatK() <= 41.5) {
			thoughts += "this flat of a cornea may benefit from a large diameter lens.  Try 9.5 OAD, 8.3 OZ.  ";;
		} else {
			thoughts += "this cornea of average curvature may do well with an average sized lens.  Try 9.0 OAD, 7.8 OZ.  ";
		}

		thoughts += " Of course, when determining diameter you need to take into account lid aperature, position, tension, etc . . .</li>";


		var advice = this.isGoodChoiceShort() + " " + thoughts;
		return advice;
	}


}

CLRGPbitoric.prototype = new ContactLens();






// -----end gp bitoric ---------



// ----- gp front toric ---------
//

function CLRGPfrontToric() {


	//the base curve of the flat meridian
	this.bc = function() {
		var keratoObject = new KeratoObject();
		keratoObject.round = round;
		keratoObject.kValue = this.keratometry.flatK() + rgpFrontToricfitFactor(this.keratometry.cornealCyl());
		return keratoObject;
	}

	//need to adjust axis by 12º to compensate for nasal rotation. 
	this.powerObject = function(axisAdjust) {
		var returnRx = new RxObject();
		var powerSteep = this.keratometry.steepK() - this.bc().kValue + this.vertexedRx.sphP();
		var powerFlat = this.keratometry.flatK() - this.bc().kValue + this.vertexedRx.sphM();

		returnRx.sph = powerSteep;
		returnRx.cyl = powerFlat - powerSteep;
		returnRx.axis = this.vertexedRx.axisP() + axisAdjust;

		return returnRx;

	}

	this.peripheralCurve = function() {
		sc = this.bc.kValueMM() + 1;
		pc = this.bc.kValueMM() + 3;

		return "SCR " + sc.toRFixed(2) + " / .3; PCR " + pc.toRFixed(2) + " / .3";
	}

	//really shouldn't call the power function as axis will vary depending on which eye.  Use powerOD and powerOS instead
	this.power = function() {
		return "OD power: " + this.powerObject(-12).prettyStringPlus();
	}

	this.powerOD = function() {
		return this.powerObject(-12).prettyStringPlus();
	}

	this.powerOS = function() {
		return this.powerObject(12).prettyStringPlus();
	}

	this.baseCurve = function() {
		return this.bc.prettyStringK();
	}

	this.baseCurveMM = function() {
		return this.bc.prettyStringMM();

	}


	this.diameter = function() {
		return "OAD: 8.8 - 9.8 *";
	}

	this.isGoodChoiceShort = function() {

		if(!this.keratometry.k2meridian) return "Cannot offer advice without keratometry measurements.";


		var advice = "";
		var otherAdvice = "";
		var residAstig = Math.abs(this.vertexedRx.cylP() - this.keratometry.cornealCyl());


		if(residAstig >= 1) {
			if(this.keratometry.cornealCyl() <= 1) {
				advice += "<li>Since this eye would have about " + residAstig.toFixed(2) + " D of residual astigmatism with a <i>spherical</i> RGP and because there's only about " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal cylinder, a front toric RGP is an appropriate choice for this patient.</li>";
			} else {
				advice += "<li>This cornea has " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal toricity, which is usually considered too much for a front toric lens.</li>";

			}
		} else { //resid astig <1
			advice += "<li>Because this eye would only have about " + residAstig.toFixed(2) + " D of residual astigmatism with a <i>spherical</i> lens, I wouldn't suggest using a front toric lens in this situation.</li>";

			if(this.keratometry.cornealCyl() > 1) advice += "<li>Also, " + this.keratometry.cornealCyl().toFixed(2) + " D is usually too much corneal toricity for this type of lens.</li>";
		}

		otherAdvice = "<li>In general, a front toric lens may be an option if soft toric lenses do not provide adequate vision.  However, there are a number of issues that greatly limit the use of this type of lens.</li>";

		advice = advice + " " + otherAdvice;
		return advice;

	}

	this.isGoodChoiceLong = function() {
		var thoughts = "<li>Cylinder axis is altered by 12&deg; to account for predicted nasal rotation</li><li>Issues that limit the use of front toric lenses include: <ul><li>Blur from lens rotation</li><li>Discomfort from prism or truncation</li><li>Inferior decentration</li><li>Inability to modify the front surface</li><li>Edema if low Dk lens is used</li></ul></li><li>This lens is designed to be stabilized with a prism ballast.  If it does not rotate correctly, the prism can be increased or it can be truncated.</li><li>Because it is difficult to achieve rotational stability with these lenses, it is usually recommended that you try a soft toric lens first (unless it's contraindicated).</li><li>A material with a dk >50 is recommended as the prism makes these lenses thicker.  Also, avoid smaller diameter lenses as they tend to decenter inferiorly.</li><li>Although this calculator assumes diagnostic lenses are unavailable, a trial fitting is highly recommended.  As ballasted diagnostic lenses are rare the next best alternative would be a spherical RGP fit and incorporating the overrefraction and lens rotation.</li>";

		var advice = this.isGoodChoiceShort() + " " + thoughts;
		return advice;

	}

}

CLRGPfrontToric.prototype = new ContactLens();








// -----end gp front toric ---------



// ----- gp back toric ---------


function CLRGPbackToric() {

	//the base curve of the flat meridian
	this.bc1 = function() {
		var keratoObject = new KeratoObject();
		keratoObject.round = this.round;
		keratoObject.kValue = this.keratometry.flatK() + rembraFitfactorFlat(this.keratometry.cornealCyl());
		return keratoObject;
	}

	//the base curve of the steep meridian
	this.bc2 = function() {
		var keratoObject = new KeratoObject();
		keratoObject.round = this.round;
		keratoObject.kValue = this.keratometry.steepK() + rembraFitFactorSteep(this.keratometry.cornealCyl());
		return keratoObject;
	}

	this.peripheralCurve = function() {
		var sc = ((this.bc1.kValueMM() + this.bc2.kValueMM()) / 2) + 1;
		var pc = ((this.bc1.kValueMM() + this.bc2.kValueMM()) / 2) + 3;

		return "SCR " + sc.toFixed(2) + " / .3; PCR " + pc.toFixed(2) + " / .3";
	}

	this.peripheralCurveToric = function() {
		var sc1 = this.bc1.kValueMM() + 1;
		var sc2 = this.bc2.kValueMM() + 1;
		var pc1 = this.bc1.kValueMM() + 3;
		var pc2 = this.bc2.kValueMM() + 3;

		return "SCR " + sc1.toFixed(2) + " / " + sc2.toFixed(2) + "; PCR " + pc1.toFixed(2) + " / " + pc2.toFixed(2) + ", both with a .3mm width.";
	}

	//the less minus or more plus power (correspnds to flat meridian).  
	this.power1 = function() {
		return this.vertexedRx.sphM() - rembraFitfactorFlat(this.keratometry.cornealCyl());
	}

	//the more minus or less plus power (correspnds to steep meridian)
	this.power2 = function() {
		return this.vertexedRx.sphP() - rembraFitFactorSteep(this.keratometry.cornealCyl());
	}



	this.power = function() {
		var power1 = this.power1();

		return numToDiopterString(power1, 1, this.round) + " D";
	}


	this.baseCurve = function() {
		return this.bc1().prettyStringK() + " / " + this.bc2().prettyStringK();
	}

	this.baseCurveMM = function() {
		return this.bc1().prettyStringMM() + " / " + this.bc2().prettyStringMM();

	}


	this.diameter = function() {
		var diam = "";
		if(this.keratometry.flatK() >= 45.50) {
			diam = "8.5 OAD; 7.3 OZ";
		} else if(this.keratometry.flatK() <= 41.50) {
			diam = "9.5 OAD; 8.3 OZ";
		} else {
			diam = "9.0 OAD; 7.8 OZ";
		}

		return diam;
	}

	this.isGoodChoiceShort = function() {

		if(!this.keratometry.k2meridian) return "Cannot offer advice without keratometry measurements.";

		var advice = "";
		var otherAdvice = "";
		var axisCompare = compareKandMrAxis(this.keratometry, this.vertexedRx);

		var corneaType = (this.keratometry.isWTR()) ? "with-the-rule" : "against-the-rule";
		if(this.keratometry.isOblique()) corneaType = "oblique";

		if(this.keratometry.isWTR() || this.keratometry.isOblique()) advice += "<li>Back toric lenses work best on against-the-rule corneas, which may be a consideration as this cornea is " + corneaType + ".</li>";



		if(this.keratometry.isATR()) {

			if(this.keratometry.cornealCyl() > 1.50) {
				advice += "<li>With an ATR cornea and " + this.keratometry.cornealCyl().toFixed(2) + " D of corneal astigmatism, a back toric may be an acceptable choice for this patient.</li>";

				if(axisCompare > 20 && this.vertexedRx.cylP() > 1) advice += "<li>One potential problem is that the refractive axis is keratometric cyl axis is quite different than the refractive cyl axis.  These lenses usually work best when the axes are closer.";


			} else { //corneal cyl <=1.50

				advice += "<li> " + this.keratometry.cornealCyl().toFixed(2) + " D is usually too little corneal astigmatism for a back toric lens.</li>";
			}


		}



		if(this.keratometry.cornealCyl() < 1.5) advice = "<li>Back toric lenses are usually reserved for cases where there <i>at least</i> 1.50 D of corneal cyl.  This cornea only has " + this.keratometry.cornealCyl().toFixed(2) + " D.</li>";


		otherAdvice = "<li>The biggest issue with back toric lenses, however, is that the back surface toric design will induce astigmatism on the eye that is very difficult to control except in very special circumstances.  As a result, a bitoric lens is almost always a better choice.</li>";


		advice = advice + " " + otherAdvice;
		return advice;
	}

	this.isGoodChoiceLong = function() {
		var diam = "<li>Regarding lens diameter: Of course, lid aperature, lid position, and lid tension need to be taken into account.</li>";

		var toricPC = "<li>If the lens is not rotationally stable on the eye, toric peripheral curves may be indicated.  In this case these curves would be suggested: " + this.peripheralCurveToric() + "</li>";

		var thoughts = "<li>Remember that a back toric RGP induces cylinder in situ. The power on the eye will not be the same as the power in air (as measured by a lensometer). The induced cylinder will vary by the refractive index of the lens material.</li>";


		var advice = this.isGoodChoiceShort() + " " + diam + " " + toricPC + " " + thoughts;

		return advice;

	}




}

CLRGPbackToric.prototype = new ContactLens();





// -----end gp back toric ---------
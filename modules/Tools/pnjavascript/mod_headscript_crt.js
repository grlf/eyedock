error1="";
error2="";

function rounder (number){
	return (Math.round(number));
}

function resetter(){
	error1="";
	error2="";
	var newTarget=document.getElementById("info");
	newTarget.innerHTML="&nbsp;";
	var newTarget=document.getElementById("answer");
	newTarget.innerHTML="&nbsp;";
	newTarget.style.backgroundColor="transparent";

}

function kFocus(){
	document.theForm.kdiopters.style.backgroundColor="lightBlue";
	var newTarget=document.getElementById("info");

	if (error1=="" && error2=="") newTarget.innerHTML="Insert the patient's flatter keratometry measurement in Diopters or millimeters.";

}

function MRSFocus(){
	document.theForm.MRS.style.backgroundColor="lightBlue";
	var newTarget=document.getElementById("info");

	if (error1=="" && error2=="") newTarget.innerHTML="Enter the Manifest Refraction Sphere (the spherical amount of myopia you want to correct).<p><font size='2'>Note that this is the spherical component in minus-cyl format.  It is not the spherical equivalent nor should it be vertexed to the corneal plane.</font></p>";
}

function fixDiopters(aNum){

	//alert(aNum);
	 aNum=rounder(aNum.replace(/[^\d\.]/g,"")*100)/100;
	 if (aNum>1000)aNum=aNum/100;
	 var units="";

	 if ((aNum<=50 && aNum>=39)|| (aNum<=8.65 && aNum>=6.75) ) {
	 	error1="";
	 	//var newTarget=document.getElementById("info");
	 	//	newTarget.innerHTML="&nbsp;";
		 if (aNum<10) {units=" mm";} else units=" D";

	} else {
		var newTarget=document.getElementById("info");
		document.theForm.kdiopters=focus();
		error1="<font color='red'>Sorry, the flattest K must be between 39.00 and 50.00 Diopters (or 6.75 and 8.65mm).</font>";
		newTarget.innerHTML=error1;



	}


		return aNum.toFixed(2)+units;


}

function fixRx(aNum){
	var sign="";
	var units="";
	 aNum=rounder(aNum.replace(/[^\d\.\-]/g,"")*100)/100;

	 if (aNum>0) {
	 	sign="+";
	 	//var newTarget=document.getElementById("info");
		//newTarget.innerHTML="<font color='red'>I assume you wanted this to be a negative number as CRT can't done on hyperopes!</font>";
	 }

	 if (aNum>6.5 || aNum<-6.5) {
	 	error2="<font color='red'>CRT is only intended for up to -6.50 D myopes!</font>";
		var newTarget=document.getElementById("info");
		newTarget.innerHTML=error2;
	 } else {
	 	error2="";
	 	units=" D";
	 }

	
	 return sign+aNum.toFixed(2)+units;
}

function doCalc(){

	var positive=false;
	var included=false;
	var extended=false;
	var extraText="";

	var kd=document.theForm.kdiopters.value.replace(/[^\d\.]/g,"");
	var MRS=document.theForm.MRS.value.replace(/[^\d\.\-]/g,"");

//alert (MRS);
	
	if (MRS>0) {
		positive=true;
		MRS=-MRS;  //converts MRS to a minus number for the following calcs
	}

	if (error1=="" && error2=="" && kd!="" && MRS!=""){

		if (kd<10)kd=337.5/kd;
 
			if (kd<=42.75) {
				var BC=165.87-2*kd-2*MRS;
			} else {
				var BC=165.49-2*kd-2*MRS+.05;
			}
		


		var RZD=207.12+7.143*(kd-MRS);

		if (kd<=39.93) var LZ=31;
		if (kd>39.93 && kd<=42.06) var LZ=32;
		if (kd>42.06 && kd<=44.18) var LZ=33;
		if (kd>44.18 && kd<=46.31) var LZ=34;
		if (kd>46.31 && kd<=48.43) var LZ=35;
		if (kd>48.43) var LZ=36;

	//adjustments for -1.25 sph powers
		//RZD
			if (MRS==-1.25){
				if (kd<=40.62){RZD-=25;}
				if (kd>=42 && kd<=44.12) {RZD-=25;}
				if (kd>=45.12 && kd<=47.50){RZD-=25;}
				if (kd>=48.62){RZD-=25;}
			}


		var BC_txt=rounder(BC);
		var RZD_txt=(rounder(RZD*.04))*25;
		var LZ_txt=rounder(LZ);

	//check if lens in 100 lens set
		if (LZ_txt==31 && BC_txt==87 && RZD_txt==500) inlcuded=true;
		if (LZ_txt==32){
			if (BC_txt>=82 && BC_txt<=87 && RZD_txt==500) included=true;
			if (BC_txt>=84 && BC_txt<=88 && RZD_txt==525) included=true;
		}
		if (LZ_txt==33){
			if (BC_txt>=80 && BC_txt<=84 && RZD_txt==500) included=true;
			if (BC_txt>=79 && BC_txt<=88 && RZD_txt==525) included=true;
			if (BC_txt>=82 && BC_txt<=88 && RZD_txt==550) included=true;
		}
		if (LZ_txt==34){
			if (BC_txt>=79 && BC_txt<=81 && RZD_txt==525) included=true;
			if (BC_txt>=79 && BC_txt<=88 && RZD_txt==550) included=true;
			if (BC_txt>=80 && BC_txt<=88 && RZD_txt==575) included=true;
		}
		if (LZ_txt==35 && BC_txt==79 && RZD_txt==550) included=true;
		if (LZ_txt==35 && BC_txt==85 && RZD_txt==575) included=true;
		if (LZ_txt==35 && BC_txt==79 && RZD_txt==575) included=true;

	//check if lens in Extended diag. set
		if (LZ_txt==31 && BC_txt>=89 && BC_txt<=92 && RZD_txt==500) extended=true;
		if (LZ_txt==32){
			if (BC_txt>=89 && BC_txt<=91 && RZD_txt==500) extended=true;
			if (BC_txt>=89 && BC_txt<=92 && RZD_txt==525) extended=true;
		}
		if (LZ_txt==33){
			if (BC_txt>=89 && BC_txt<=90 && RZD_txt==525) extended=true;
			if (BC_txt>=89 && BC_txt<=92 && RZD_txt==550) extended=true;
		}

//alert (BC);


		//do calcs for plus powers - remember MRS was converted to a minus number!
		if (positive==true){
		//alert ("plus");
			BC=337.5/(kd-MRS+.50);
			BC_txt=(rounder(BC*10))/10;
			RZD-=50;
			RZD_txt=(rounder(RZD*.04))*25;
			
		}

		
		var newTarget=document.getElementById("answer");
			newTarget.innerHTML="<font size='5'><b>"+BC_txt+" - "+RZD_txt+" - "+LZ_txt+"</b></font>";

		newTarget.style.backgroundColor="transparent";

		if (included && positive==false) {
			extraText="<br/><br/>This lens is included in the 100 lens Diagnostic Dispensing System.";
			newTarget.style.backgroundColor="#FFFF99";
		}


		if (extended && positive==false) {
			extraText="<br/><br/>This lens is included in the Extended Diagnostic Dispensing System.";
			newTarget.style.backgroundColor="#FFCCFF";
		}



			var newTarget=document.getElementById("info");
			newTarget.innerHTML="The suggested initial diagnostic lens is listed below."+extraText;
	} else {
		var newTarget=document.getElementById("info");
		newTarget.innerHTML=error1+"<br/><br/>"+error2;
	}
}

avgGross=0;
	totalGrowth=0;
	g1="";g2="";g3="";


	months=["January","February","March","April","May","June","July","August","September","October","November","December"];



	function fixMonth(num){
		if (num<0) num+=12;
		if (num>11) num-=12;
		return num;
	}

	function makeDate(m,y){
		y=parseInt (y);
		if (m<0) {
			m+=12;
			y-=1
		}
		if (m>11) {
			m-=12;
			y+=1
		}

		return months[m]+" "+y;

	}

	function changeDate(){
		var month=document.theForm.month.selectedIndex+1;
		var yearObj = document.theForm.year
		var yearIndex = yearObj.selectedIndex;

		var year = yearObj.options[yearIndex].text;


		//document.theForm.year.selectedIndex[0].text;

		nowDate=new Date(month+"/15/"+year);
		//alert (nowDate.toDateString());

		var myTarget=document.getElementById("month14");
		myTarget.innerHTML=makeDate(nowDate.getMonth()-2,nowDate.getFullYear()-1);
		//myTarget.innerHTML=months[fixMonth(nowDate.getMonth()-2)] +" "+(parseInt(nowDate.getFullYear())-1);

		var myTarget=document.getElementById("month13");
		myTarget.innerHTML=makeDate(nowDate.getMonth()-1,nowDate.getFullYear()-1);
		//myTarget.innerHTML=months[fixMonth(nowDate.getMonth()-1)] +" "+(parseInt(nowDate.getFullYear())-1);

		var myTarget=document.getElementById("month12");
		myTarget.innerHTML=makeDate(nowDate.getMonth(),nowDate.getFullYear()-1);
		//myTarget.innerHTML=months[nowDate.getMonth()] +" "+(parseInt(nowDate.getFullYear())-1);

		var myTarget=document.getElementById("month2");
		myTarget.innerHTML=makeDate(nowDate.getMonth()-2,nowDate.getFullYear());
		//myTarget.innerHTML=months[fixMonth(nowDate.getMonth()-2)] +" "+(parseInt(nowDate.getFullYear()));

		var myTarget=document.getElementById("month1");
		myTarget.innerHTML=makeDate(nowDate.getMonth()-1,nowDate.getFullYear());
		//myTarget.innerHTML=months[fixMonth(nowDate.getMonth()-1)] +" "+(parseInt(nowDate.getFullYear()));

		var myTarget=document.getElementById("month0");
		myTarget.innerHTML=makeDate(nowDate.getMonth(),nowDate.getFullYear());
		//myTarget.innerHTML=months[nowDate.getMonth()] +" "+(parseInt(nowDate.getFullYear()));

		//document.theForm.calc1.value="Calculate "+months[nowDate.getMonth()] +"'s goal";

		document.theForm.calc2.value="Calculate "+months[nowDate.getMonth()] +"'s incentives";

	}

	function makeMoney(amt){

		comma1="";comma2="";hundreds="",thousands="",theRest=""
		amt=new String(Math.round(amt*100));

		amtLength=amt.length;

		cents=amt.substring(amtLength-2,amtLength);
		//alert(cents);
		if (amtLength>5){
				hundreds=amt.substring(amtLength-5,amtLength-2);
				comma1=",";
				if (amtLength>8){
					thousands=amt.substring(amtLength-8,amtLength-5);
					theRest=amt.substring(0,amtLength-8);
					comma2=","
				} else {
				 	thousands=amt.substring(0,amtLength-5);
				}
			} else {
				hundreds=amt.substring(0,amtLength-2);
			}

		amtString="$"+theRest+comma2+thousands+comma1+hundreds+"."+cents;
		return amtString;
	}

	function verifyMoney(aNum){
		 aNum=aNum.replace(/[^\d\.]/g,"");
		 //alert(aNum);
		 return aNum;
	}


	function oldGrossChange(what){

		what.value=makeMoney(verifyMoney(what.value));
		resetted();
	}



	function infORgrowth(){

		g1=verifyMoney(document.theForm.gross14.value);
		g2=verifyMoney(document.theForm.gross13.value);
		g3=verifyMoney(document.theForm.gross12.value);
		totGross =1*g1+1*g2+1*g3;

		growth=document.theForm.growth.value;
		inflation=document.theForm.inflation.value;

		var newTarget=document.getElementById("begins");
		if (growth!="" && inflation!="" && totGross!=0){
			totalGrowth=(totGross*(1+(inflation/100))*(1+(growth/100)))/3;
			newTarget.innerHTML="<p><font color='darkblue'>Profit sharing begins when three month average gross surpasses "+ makeMoney(totalGrowth)+ ".</font></p>";

		} else {
			totalGrowth=0;
			resetted();
			alert("You need to fill in all the blanks on the left side of this form before this calculation can be done!");

		}

	}

	function newGrossChange(what){
		what.value=makeMoney(verifyMoney(what.value));
		var answerTarget=document.getElementById("answer");
		answerTarget.innerHTML="";
		resetted();
	}


	function doCalc(){
		gross0=verifyMoney(document.theForm.gross0.value);
		gross1=verifyMoney(document.theForm.gross1.value);
		gross2=verifyMoney(document.theForm.gross2.value);

		gross =(1*gross0+1*gross1+1*gross2)/3;


		percentComp=verifyMoney(document.theForm.percentComp.value);
		staffNum=verifyMoney(document.theForm.staffNum.value);
		profitShare=gross-totalGrowth;
		//alert (gross+"(gross)-"+totalGrowth+"(totalGrowth)="+profitShare+"(profitShare)");
 
		var answerTarget=document.getElementById("answer");
		if (gross!="" && percentComp!="" && staffNum!=""){

			if (profitShare<0){
				theAnswer="<font color='darkblue'>There will be no profit sharing this period as the three month average gross did not surpass the goal.</font>";
			} else {
				theAnswer="Increased gross collections (after taking into account inflation and inherent growth) is "+makeMoney(profitShare)+", of which "+makeMoney(profitShare*percentComp/100)+" goes to staff compensation.  Each of your "+staffNum+" full-time equivalent staff members will each receive "+makeMoney((profitShare*percentComp/100)/staffNum)+".";

			}

			answerTarget.innerHTML="<font color='darkblue'>"+theAnswer+"</font>";
		} else {

			answerTarget.innerHTML="";
			alert("You need to fill in all the blanks on this page before this calculation can be done!");
		}
	}

	function resetted(){
		var newTarget=document.getElementById("begins");
		newTarget.innerHTML="";
		var answerTarget=document.getElementById("answer");
		answerTarget.innerHTML="";

	}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

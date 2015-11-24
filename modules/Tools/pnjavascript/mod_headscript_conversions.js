var sRx= new rx;
var theK=new kerato;
var vertexDist=12;
var rxArray=new Array(0,0,0);
var ansArray=new Array(0,0,0);
var kCurve=0;
var kCurveAns=0;
var ready=0;

function replaceImage (anImgName, anImageFile)
{
   document.images[anImgName].src=anImageFile;
}


function submitRx(prescription)
{
   var rxNumsOnly=sRx.onlyNums(prescription);

   if (!sRx.validateFormat(rxNumsOnly)){
      alert("Please enter the prescription in this format: -10.25+2.50x130.  Plus or minus cyl is accepable");

      ready=0;
   } else {



      rxArray=sRx.stringBreaker(rxNumsOnly);
      ansArray=rxArray;

      var comment =sRx.validateNumbers(rxArray);
      if (comment !="")
      {
         alert (comment);
      } else {

         ready=1;

         isChecked=document.prescriptions.round.checked;

         document.prescriptions.refraction.value=sRx.prettyString(rxArray,isChecked);
      }
   }
}

function transpose()
{
  ansArray=sRx.transposer(ansArray);
  answerChanged();
}

function vertex()
{
   if (ready==1)
   {
     //the next line is goofy but it's the only way i could get it to work!
     newArray=[rxArray[0],rxArray[1],rxArray[2]];
     ansArray=sRx.corneaPlane(newArray,vertexDist);
     answerChanged();
   } else {
      alert ("You still need to enter a valid manifest refraction!");
   }
}

function vertexChanged(theVertex)
{
   vertexDist=theVertex;
   anythingChanged();
}

function anythingChanged(theEvent)
{

   document.prescriptions.answer.value="";
   ansArray=[0,0,0];
   if (theEvent==13)
   {
      submitRx(document.prescriptions.refraction.value);
      vertex();
   }

}

function answerChanged()
{
   isChecked=document.prescriptions.round.checked;
   document.prescriptions.answer.value=sRx.prettyString(ansArray,isChecked);
   transbtn=document.prescriptions.switchCyl;
   answertext=document.prescriptions.answer.value
   if (answertext=="") transbtn.value="transpose";
   if (ansArray[1]>0) transbtn.value="minus cyl";
   if (ansArray[1]<0) transbtn.value="plus cyl";
}

function ksubmit (k)
{
   k=theK.onlyNums(k);
   var newK=0;
   if (!theK.validateFormat(k))
   {
      alert("The keratometry reading must be a value in mm or Diopters.  For example, 7.64 or 44.25.");
   }  else {
      if ( (k<4 || k>70) || (k>11 && k<30) )
      {
         alert ("The value entered must be between 4-11 mm or 30-70 Diopters");
      } else {
      isChecked=document.keratometry.krounder.checked;
      document.keratometry.kvalue.value=theK.prettyK(k,isChecked);
      newK=theK.switcher(k);
      document.keratometry.kans.value=theK.prettyK(newK,isChecked);
      }
   }
}

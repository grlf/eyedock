function fullK(){}

fullK.prototype.onlyNums=function(theValue)
{
   theValue=theValue.replace(/[^\d\.@\*\/]/ig,"");
   theValue=theValue.replace(/[\*]/ig,"@");
   return theValue;
}


fullK.prototype.validateFormat=function(theValue)
{
  
  var kRegExp=/^\d\d(\.\d{0,3})?\/\d\d(\.\d{0,3})?@\d{1,3}$/;
  return kRegExp.test(theValue);
}


fullK.prototype.stringBreaker=function(theValue)
{
   var anArray=new Array (0,0,0);
   var slashLoc=theValue.indexOf("/");
   var circleLoc=theValue.indexOf("@");
   
   anArray[0]=theValue.substring(0,slashLoc);
   anArray[1]=theValue.substring(slashLoc+1,circleLoc);
   anArray[2]=theValue.substring(circleLoc+1,theValue.length);
   
   return anArray;
}

fullK.prototype.verifyRange=function(theArray)
{
   var itsOK=false;
   if (theArray[0]>32 && theArray[0]<75 && theArray[1]>32 && theArray[1]<75 && theArray[2]<=180 && theArray[2]>=0) itsOK=true;
   return itsOK;
}

fullK.prototype.prettyString=function (theArray,rounded)
{
   var k1=0;
   var k2=0;
   
   var axis=""+Math.round(theArray[2]);
   if (axis.length==1) axis="00"+axis;
   if (axis.length==2) axis="0" +axis;

   if (rounded == true )
   {
      k1=new Number (rounder(theArray[0]));
      k2=new Number (rounder(theArray[1]));
   } else {
      k1=new Number (theArray[0]);
      k2=new Number (theArray[1]);
   }
   
     k1=k1.toFixed(2);
     k2=k2.toFixed(2);
     
     return k1 + " / " + k2 + " @ " + axis;

}

fullK.prototype.cylType=function (theArray)
{
   var theType="";
   var k1=theArray[0];
   var k2=theArray[1];
   var axis=theArray[2];
   
   if (k2>k1) 
   {
      if (axis>59 && axis<151) theType="WTR";
      if (axis<61 || axis>149) theType="ATR";
   } else {
      if (axis>59 && axis<151) theType="ATR";
      if (axis<61 || axis>149) theType="WTR";
   }
   
   
   if ((axis>30 && axis <60)||(axis>120 && axis<150)) theType="oblique";
   
   if (Math.abs(k1-k2)<.25 ) theType= "spherical";
   
   return theType;
   
}





//_________________
function rounder (number)
{
return (Math.round(number*4))/4;
}
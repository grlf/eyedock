 function rx() {}

rx.prototype.onlyNums=function(string)
{
   string=string.replace(/[^\d.+*x-]/ig,"");
   string=string.replace(/p(l|lano)?/ig,"0");
   return string.replace(/[X\*]/,"x");
}

rx.prototype.validateFormat=function(string)
{
   var RxRegExp=/^[+-]?\d{0,2}\.?\d{0,2}([+-]\d{0,2}\.?\d{0,2}[x\*]\d{1,3})?$/;
   return RxRegExp.test(string);
}

rx.prototype.validateNumbers=function(anArray)
{
   var comment="";
   var absSph=Math.abs(anArray[0]);
   var absCyl=Math.abs(anArray[1]);
   if (absSph>30) comment="The sphere power has to be less than +/- 30.00 D.  ";
   if (absCyl>15) comment+="The cylinder power needs to be within +/- 15.00 D.  ";
   if (anArray[2]>180 || anArray[2]<0) comment +="The axis needs to be between 0 and 180 degrees.  ";
   return comment;
}

rx.prototype.stringBreaker=function(rxString)
{
   var anArray=new Array (0,0,0);
   var plusLoc=rxString.lastIndexOf("+");
   var minusLoc=rxString.lastIndexOf("-");
   var secondSign=(plusLoc>minusLoc)?plusLoc:minusLoc;
   var xLoc=rxString.indexOf("x");
   
   //if a spherical rx
   if (xLoc<0)
   {
      anArray[0]=rxString;
      anArray[1]=0;
      anArray[2]=0;
      return anArray;
   }

   // find sphere
   anArray[0]=rxString.substring(0,secondSign);
   
   //find cyl
   anArray[1]= rxString.substring (secondSign, xLoc);
   
   //find axis
   anArray[2]= rxString.substring (xLoc+1, rxString.length);

   return anArray;
}


rx.prototype.prettyString=function(anArray,rounded)
{
   var sphType=""; 
   var cylType=" ";
   
   if (anArray[2]>=0 && anArray[2]<=.5) anArray[2]=180;
   var myAxis=""+Math.round(anArray[2]);
   if (anArray[0]>0) sphType="+";
   if (anArray[1]>0) cylType=" +";

 

   if (myAxis.length==1) myAxis="00"+myAxis;
   if (myAxis.length==2) myAxis="0" +myAxis;
  
   if (rounded==1 || rounded=="yes" || rounded == "true" )
   {
      mysph=new Number (rounder(anArray[0]));
      mycyl=new Number (rounder(anArray[1]));
   } else {
      mysph=new Number (anArray[0]);
      mycyl=new Number (anArray[1]);
   }

   stringSph=mysph.toFixed(2);
   stringCyl=mycyl.toFixed(2);
   
   //alert (Math.abs(anArray[0]));

   if (Math.abs(anArray[0])<.125 && Math.abs(anArray[1])<.125) return "plano";
   
   if (Math.abs(anArray[0])<.125){
   	stringSph="plano";
   	sphType="";
   }
   if (Math.abs(anArray[1])<.001) 
      { 
      return sphType+stringSph+" sph";
      } else {
      return sphType+stringSph+cylType+stringCyl+" x "+myAxis;
      }
}


rx.prototype.transposer=function(anArray)
{
   anArray[0]=(anArray[0]*1)+(anArray[1]*1);
   anArray[1]=-anArray[1];

   if (anArray[2]>90)
   {
      anArray[2]=anArray[2]-90;
   } else {
      anArray[2]=(anArray[2]*1)+90;
   }
   return anArray;
}

rx.prototype.corneaPlane=function(anArray,vertex)
{
   vertex=vertex/1000
   s=anArray[0];
   c=(anArray[1]*1)+(anArray[0]*1);

   s= s/(1-(vertex*s));
   if (anArray[1]!=0) c=c/(1-(vertex*c)) ;
   if (anArray[1]!=0) anArray[1]=c-s;

   anArray[0]=s;
   return anArray;
   
}



//------------------------
function rounder (number)
{
return (Math.round(number*4))/4;
}
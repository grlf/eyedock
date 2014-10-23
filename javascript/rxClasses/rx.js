alert("rx!");

function Rx() {
 
 }

Rx.prototype.onlyNums=function(string)
{
   string=string.replace(/[^\d.+*x-]/ig,"");
   string=string.replace(/p(l|lano)?/ig,"0");
   return string.replace(/[X\*]/,"x");
}

Rx.prototype.validateFormat=function(string)
{
   var RxRegExp=/^[+-]?\d{0,2}\.?\d{0,2}([+-]\d{0,2}\.?\d{0,2}[x\*]\d{1,3})?$/;
   return RxRegExp.test(string);
}

Rx.prototype.validateNumbers=function()
{
   var comment="";
   var absSph=Math.abs(this.sph);
   var absCyl=Math.abs(this.cyl);
   if (absSph>30) comment="The sphere power has to be less than +/- 30.00 D.  ";
   if (absCyl>15) comment+="The cylinder power needs to be within +/- 15.00 D.  ";
   if (this.axis>180 || this.axis<0) comment +="The axis needs to be between 0 and 180 degrees.  ";
   return comment;
}

Rx.prototype.stringBreaker=function(rxString)
{
   var plusLoc=rxString.lastIndexOf("+");
   var minusLoc=rxString.lastIndexOf("-");
   var secondSign=(plusLoc>minusLoc)?plusLoc:minusLoc;
   var xLoc=rxString.indexOf("x");
   
   //if a spherical rx
   if (xLoc<0)
   {
      this.sph=rxString;
      this.cyl=0;
      this.axis=0;
      return this;
   }

   // find sphere
   this.sph=rxString.substring(0,secondSign);
   
   //find cyl
   this.cyl= rxString.substring (secondSign, xLoc);
   
   //find axis
   this.axis= rxString.substring (xLoc+1, rxString.length);

   return this;
}


Rx.prototype.prettyString=function(rounded)
{
   var sphType=""; 
   var cylType=" ";
   
   if (this.axis>=0 && this.axis<=.5) this.axis=180;
   var myAxis=""+Math.round(this.axis);
   if (this.sph>0) sphType="+";
   if (this.cyl>0) cylType=" +";

 

   if (myAxis.length==1) myAxis="00"+myAxis;
   if (myAxis.length==2) myAxis="0" +myAxis;
  
   if (rounded==1 || rounded=="yes" || rounded == "true" )
   {
      mysph=new Number (rounder(this.sph));
      mycyl=new Number (rounder(this.cyl));
   } else {
      mysph=new Number (this.sph);
      mycyl=new Number (this.cyl);
   }

   stringSph=mysph.toFixed(2);
   stringCyl=mycyl.toFixed(2);
   
   //alert (Math.abs(this.sph));

   if (Math.abs(this.sph)<.125 && Math.abs(this.cyl)<.125) return "plano";
   
   if (Math.abs(this.sph)<.125){
   	stringSph="plano";
   	sphType="";
   }
   if (Math.abs(this.cyl)<.001) 
      { 
      return sphType+stringSph+" sph";
      } else {
      return sphType+stringSph+cylType+stringCyl+" x "+myAxis;
      }
}


Rx.prototype.transposer=function()
{
	var tempRx = newRx();
   tempRx.sph=(this.sph*1)+(this.cyl*1);
   tempRx.cyl=-this.cyl;

   if (this.axis>90)
   {
      tempRx.axis=this.axis-90;
   } else {
      tempRx.axis=(this.axis*1)+90;
   }
   return tempRx;
}

Rx.prototype.corneaPlane=function(vertex)
{
   var tempRx = newRx();
   vertex=vertex/1000
   s=this.sph;
   c=(this.cyl*1)+(this.sph*1);

   s= s/(1-(vertex*s));
   if (this.cyl!=0) c=c/(1-(vertex*c)) ;
   if (this.cyl!=0) tempRx.cyl = c-s;

   tempRx.sph=s;
   tempRx.axis = this.axis;
   return tempRx;
   
}



//------------------------
function rounder (number)
{
return (Math.round(number*4))/4;
}
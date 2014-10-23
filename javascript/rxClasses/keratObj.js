function Kcurve(){}

Kcurve.prototype.k1=0;
Kcurve.prototype.k2=0;
Kcurve.prototype.k2meridian=0;

Kcurve.prototype.k1meridian=function()
{
   if (this.k2meridian>90) return this.k2meridian-90;
   return (1*this.k2meridian)+90;
}

Kcurve.prototype.prettyString=function (rounded)
{

   axisString=""+Math.round(this.k2meridian);
   if (axisString.length==1) axisString="00"+axisString;
   if (axisString.length==2) axisString="0" +axisString;

   if (rounded == true )
   {
      k1string=new Number (rounder(this.k1));
      k2string=new Number (rounder(this.k2));
   } else {
      k1string=new Number (this.k1);
      k2string=new Number (this.k2);
   }
   
     k1mm=337.5/k1string;
     k2mm=337.5/k2string;
   
     k1string=k1string.toFixed(2)+" ("+k1mm.toFixed(2)+") ";
     k2string=k2string.toFixed(2)+" ("+k2mm.toFixed(2)+") ";
     
     return k1string + " / " + k2string + " @ " + axisString;
     
     
     //_________________
     function rounder (number)
     {
        return (Math.round(number*4))/4;
     }
     //_________________

}

Kcurve.prototype.cylType=function ()
{
   var theType="";
   
   if (this.k2>this.k1) 
   {
      if (this.k2meridian>=60 && this.k2meridian<=150) theType="WTR";
      if (this.k2meridian<60 || this.k2meridian>150) theType="ATR";
   } else {
      if (this.k2meridian>=60 && this.k2meridian<=150) theType="ATR";
      if (this.k2meridian<60 || this.k2meridian>150) theType="WTR";
   }
   
   
   if ((this.k2meridian>30 && this.k2meridian <60)||(this.k2meridian>120 && this.k2meridian<150)) theType="oblique";
   
   if (Math.abs(this.k1-this.k2)<.25 ) theType= "spherical";
   
   return theType;
   
}

Kcurve.prototype.cornealCyl=function()
{
   return Math.abs(this.k1-this.k2);
}


Kcurve.prototype.steepK=function()
{
   if (this.k1<this.k2) return this.k2;
   return this.k1;
}


Kcurve.prototype.flatK=function()
{
   if (this.k1<this.k2) return this.k1;
   return this.k2;
}

Kcurve.prototype.flatKmeridian=function()
{
   if (this.k1<this.k2) return this.k1meridian();
   return this.k2meridian;
}

Kcurve.prototype.steepKmeridian=function()
{
    if (this.k1>this.k2) return this.k1meridian();
    return this.k2meridian;
}


Kcurve.prototype.horizK=function()
{
   if (this.k2meridian>45 && this.k2meridian<=135) return this.k1meridian();
   return this.k2meridian;
}

Kcurve.prototype.vertK=function()
{
   if (this.k2meridian<=45 || this.k2meridian>135) return this.k1meridian();
   return this.k2meridian;
}

Kcurve.prototype.hPwr=function()
{
   if (this.k2meridian>45 && this.k2meridian<=135) return this.k1;
   return this.k2;
   if (this.k2meridian==45) return this.k1;
}

Kcurve.prototype.vPwr=function()
{
   if (this.k2meridian>=45 && this.k2meridian<135) return this.k2;
   return this.k1;
   if (this.k2meridian==45) return this.k2;
}

Kcurve.prototype.midK=function()
{
   return (this.k1/2)+(this.k2/2);
}
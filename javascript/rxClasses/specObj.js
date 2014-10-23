function Specs() {}

Specs.prototype.sph=0;
Specs.prototype.cyl=0;
Specs.prototype.axis=0;

Specs.prototype.vPwr=function()
{
   if (this.axis>=45 && this.axis<135) return this.sph;
   return 1*this.sph+this.cyl*1;
}

Specs.prototype.hPwr=function()
{
   if (this.axis<45 || this.axis>=135) return this.sph;
   return 1*this.sph+this.cyl*1;
}


Specs.prototype.vAxis=function()
{
   if (this.axis>=45 && this.axis<135) return this.axis;
   if (this.axis>90)return this.axis-90;
   return (this.axis*1)+90;
}

Specs.prototype.hAxis=function()
{
   if (this.axis<45 || this.axis>=135) return this.axis;
   if (this.axis>90) return this.axis-90;
   return (this.axis*1)+90;
}



Specs.prototype.prettyString=function(pORm,rounded)
{
   var sphType=""; 
   var cylType=" ";

   if (pORm=="+")
   {
     sphere=Math.min(this.vPwr(),this.hPwr());
     cylinder=Math.max(this.vPwr(),this.hPwr());
     cylType=" +";
   
   } else {
      sphere=Math.max(this.vPwr(),this.hPwr());
      cylinder=Math.min(this.vPwr(),this.hPwr());
   }
   
   if (sphere==this.vPwr())
   {   
      myAxis=this.vAxis();
   } else {
      myAxis=this.hAxis();
   }
   
   cylinder=cylinder-sphere;
   
   myAxis=""+Math.round(myAxis);
   if (sphere>=.1 ) sphType="+";


   if (myAxis.length==1) myAxis="00"+myAxis;
   if (myAxis.length==2) myAxis="0" +myAxis;
   
   if (myAxis=="000") myAxis="180";
  
   if (rounded==1 || rounded==true)
   {
   
      mysph=new Number (rounder(sphere));
      mycyl=new Number (rounder(cylinder));
   } else {
      mysph=new Number (sphere);
      mycyl=new Number (cylinder);
   }

   stringSph=mysph.toFixed(2);
   stringCyl=mycyl.toFixed(2);

   if (Math.abs(sphere)<.1 && Math.abs(cylinder)<.1) return "plano";
   if (Math.abs(sphere)<.1) stringSph="plano";
   if (Math.abs(cylinder)<.001) 
      { 
      return sphType+stringSph+" sph";
      } else {
      return sphType+stringSph+cylType+stringCyl+" x "+myAxis;
      }
      
      
        //------------------------
	function rounder (number)
	{
	   return (Math.round(number*4))/4;
        }
      
}


Specs.prototype.corneaPlane=function(vertex)
{
   vertex=vertex/1000
   s=this.sph;
   c=(this.cyl*1)+(this.sph*1);

   s= s/(1-(vertex*s));
   if (this.cyl!=0) c=c/(1-(vertex*c)) ;
   if (this.cyl!=0) this.cyl=c-s;

   this.sph=s;
   
   
}

Specs.prototype.sphEquiv=function()
{
   return (.5*this.cyl)+(1*this.sph);
}


Specs.prototype.axisType=function()
{
   var axis=this.axis;
   var theType="";
   
   if (Math.abs(this.cyl)<.25) return "spherical";
   
   if (this.cyl<0)  //___if minus cyl___
   
   {
      if (axis<=30 || axis>=150) 
      { 
         theType= "WTR";
      } else { 
         if (axis>=60 && axis<=120)
         {
            theType= "ATR";
         } else {
            theType="oblique";
         }
      }
     
   } else {  //___if plus cyl___ 
      
      if (axis<=30 || axis>=150) 
      { 
         theType= "ATR";
      } else { 
         if (axis>=60 && axis<=120)
         {
            theType= "WTR";
         } else {
            theType="oblique";
         }
      }
   }
         
   return theType;
}

Specs.prototype.refractiveCyl=function()
{
   return Math.abs(this.cyl);
}

Specs.prototype.minusCylsph=function()
{
   if (this.cyl<0) return this.sph;
   if (this.cyl>0) return (this.sph*1)+(1*this.cyl);
}

Specs.prototype.minusCylcyl=function()
{
   if (this.cyl<0) return this.cyl;
   if (this.cyl>0) return -this.cyl;
}

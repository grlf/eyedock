// these functions were used for single dioptric measurements (eg 45.00 or 6.02)

function kerato(){}   

kerato.prototype.onlyNums=function(string)
{
   string=string.replace(/[^\d.]/ig,"");
   return string;
}

kerato.prototype.validateFormat=function(string)
{
   var RxRegExp=/^[+-]?\d{0,2}\.?\d{0,2}([+-]\d{0,2}\.?\d{0,2}[x\*]\d{1,3})?$/;
   var RxRegExp=/^\d\d?.?\d?\d?$/;
   return RxRegExp.test(string);
}

kerato.prototype.prettyK=function(kvalue,rounded)
{
   knum=new Number (kvalue);
   
   if (knum<20) return knum.toFixed(2) + " mm";
   if (rounded==1 || rounded=="yes" || rounded == "true" )
   { 
      answer=new Number (kRounder(kvalue));
   } else {
      answer=new Number (kvalue);
   }
   
   return answer.toFixed(2)+" D";
}


kerato.prototype.switcher=function(k)
{
   return 337.5/k;
}

kerato.prototype.diopters=function(k)
{
   if (k>20) return k;
   return 337.5/k;
}

kerato.prototype.mm=function(k)
{
   if (k<20) return k;
   return 337.5/k;
}


//the following is the markup conversion for steep k readings with a +1.25 lens on the keratometer

kerato.prototype.steepConvert=function(k)
{
   return k*1.165906;
}

//the following is the markup conversion for flat k readings with a -1.00 lens on the keratometer

kerato.prototype.flatConvert=function(k)
{
   return k*0.85702;
}




//---------------------
function flipper (k)
{
   return 337.5/k;
}   

function kRounder (number)
{
return (Math.round(number*4))/4;
}
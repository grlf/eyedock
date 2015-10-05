<?php
//accepts one or two numbers, a unit ("D" or "mm"), an option to display units.  If it is not set, returns "?".  If 99 or -99, returns "any".

/*
function smarty_modifier_displaynum($string,$unit){
       if ($string=="") return "?";
       if ($string==0 && $unit=="D") return "pl";
       if ($string==0 && $unit=="mm") return "?";
       if (abs($string)==99) return "any";
       if ($unit=="D")return sprintf("%+.2f", $string);
       if ($unit=="mm")return sprintf("%.1f", $string);
}
*/

function smarty_function_lenses_nums($params, &$smarty)
{
    $return="";
    $num1=$params['num1'];
    $num2=$params['num2'];
    $unit = (isset($params['unit'])) ? $params['unit'] : "mm";
    $display_unit = (isset($params['display_unit'])) ? $unit : "";
   
   if (abs($num1)==99 || abs($num2)==99) return "any";
   
   if ($num1==0 && $unit=="D") $return.="pl";
   if ($num1==0 && $unit=="mm") $return.="?";
   if (abs($num1)>0 && $unit=="D") $return.=sprintf("%+.2f", $num1);
   if (abs($num1)>0 && $unit=="mm") $return.=sprintf("%.1f", $num1);
   if ($num1==$num2||$num2=="") return $return.$display_unit;
   
   $return.=" to ";
   
   if ($num2==0 && $unit=="D") $return.="pl";
   if ($num2==0 && $unit=="mm") $return.="?";
   if (abs($num2)>0 && $unit=="D") $return.=sprintf("%+.2f", $num2);
   if (abs($num2)>0 && $unit=="mm") $return.=sprintf("%.1f", $num2);
   
   return $return.$display_unit;


}


?>
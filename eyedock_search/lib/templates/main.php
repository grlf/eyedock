<?php

?>

<div id="eyedock-search">

 <div class="edas-top">

  <div class="edas-tabs">

   <div class="edas-tab" id="edas-tab-1">
    <div class="edas-tab-left">
     <div class="edas-tab-right">
      <div class="edas-tab-title">
Company Search
      </div>
     </div>
    </div>
   </div>

   <div class="edas-tab-inactive" id="edas-tab-2">
    <div class="edas-tab-left">
     <div class="edas-tab-right">
      <div class="edas-tab-title">
Parameter Search
      </div>
     </div>
    </div>
   </div>

  

        <input id="edas-top-search-box" type="text" value="Search everything..." onkeyup="eyedock_main_enter_search('edas-top-search-box', event);" />

	   
       <div id="search-compare-lenses" class="edas-tsc-button">
        <span class="edas-button">
         <div class="edas-button-left">
          <div class="edas-button-right">
           <div class="edas-button-content">
Compare All Lenses
           </div>
          </div>
         </div>
        </span>
       </div>
	

     <div class="edas-search-dropdown" id="edas-top-search-box-display"></div>

    </div></div>



   <div class="edas-clear"></div>



 <div class="edas-content">
  <div class="edas-tab-content" id="edas-tab-content-1"><?eyedock_search_display('company_search', false);?></div>
  <div class="edas-tab-content" id="edas-tab-content-2" style="display: none;"><?eyedock_search_display('parameter_search', false);?></div>
 </div>
 
<!--
 <div class="edas-bottom" style="visibility: hidden;"><div class="edas-bottom-left"><div class="edas-bottom-right"><div class="edas-bottom-content">

    <div class="edas-compare-lenses">

     <span class="edas-gray-button" id="search-compare-lenses"><div class="edas-gray-button-left"><div class="edas-gray-button-right"><div class="edas-gray-button-content"><div class="edas-gray-button-content-rt">
Compare all lenses
     </div></div></div></div></span>

    </div>

 </div></div></div></div>
-->

</div>

<div id="eyedock-lens-detail-load">
 <div id="eyedock-lens-detail-load-overlay"></div>
 <div id="eyedock-lens-detail-load-img"></div>
</div>

<div id="eyedock-advanced-search" style="display: none;"></div>

<div id="eyedock-compare-lenses" style="display: none;"></div>

<?
if(is_array($detailed_popups)){
	foreach($detailed_popups as $k => $v){
?>
<div class="eyedock-detailed-popup" id="edp-<?=$k?>" onclick="eld_popup_close('<?=$k?>');">
 <div class="eyedock-detailed-popup-content">
  <div class="eyedock-detailed-popup-text"><?=$v?></div>
  <div class="eyedock-detailed-popup-close" onclick="eld_popup_close('<?=$k?>');">X</div>
 </div>
</div>
<?
	}
}

$fda_descriptions = eyedock_fda_descriptions();
if(!empty($fda_descriptions)){
	foreach($fda_descriptions as $k => $v){
?>
<div class="eyedock-detailed-popup" id="edp-fda-<?=$k?>" onclick="eld_popup_close('fda-<?=$k?>');">
 <div class="eyedock-detailed-popup-content">
  <div class="eyedock-detailed-popup-text"><?=$v?></div>
  <div class="eyedock-detailed-popup-close" onclick="eld_popup_close('fda-<?=$k?>');">X</div>
 </div>
</div>
<?
	}
}
?>
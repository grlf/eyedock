<?php

?>

<div class="edas-ld-content">

 <div class="edas-ld-top">
 	<div class="edas-ld-top-left"><div class="edas-ld-top-right"><div class="edas-ld-top-content">

   <div class="edas-ld-title"><?=$lens['name']?></div>

   <div class="edas-ld-close" onclick="eyedock_lens_detail_close('<?=$lens['id']?>');"></div>

  </div></div></div>
 </div>

 <div class="edas-ld-main">
  <div class="edas-ld-main-content">

   <div class="edas-ld-main-left">
    <div class="edas-ld-main-left-content">
     <!--<div class="edas-ld-company<?=($lens['company_description'] != ''?' edas-ld-company-active':'')?>"<?=($lens['company_description'] != ''?' onclick="eld_popup_open(\'' . $lens['id'] . '-comp\',  \'' . $lens['id'] . '\');"':'')?>><?=$lens['company']?></div>-->
     <div class="edas-ld-company"><?=$lens['company']?></div>
     <span class="edas-popup-link" onclick="eld_companypopup_open(<?=$lens['id']?>);">[?]</span>
     <div class="edas-company-details eyedock-detailed-popup" id="company_details_popup<?=$lens['id']?>">
     	<div class="eyedock-detailed-popup-close" onclick="eld_companypopup_open(<?=$lens['id']?>);">X</div>
     	<div class="edas-company-details-companyname"><?=$lens['company']?></div>
     	<div class="edas-company-details-description edas-company-details-block"><?=$lens['company_description']?></div>
     	<div class="edas-company-details-block">
     		<div class="edas-company-details-address"><?=$lens['company_address']?></div>
     		<div class="edas-company-details-city edas-address-inline"><?=$lens['company_city']?></div>
     		<div class="edas-company-details-state edas-address-inline"><?=$lens['company_state']?></div>
     		<div class="edas-company-details-zip edas-address-inline"><?=$lens['company_zip']?></div>
     	</div>
     	<div class="edas-company-details-block">
     	<div class="edas-company-details-url edas-popup-interior-link"><a href="<?=$lens['company_url']?>" style="color: #327180;" target = "_blank"><?=$lens['company_url']?></a></div>
     	<div class="edas-company-details-email edas-popup-interior-link"><a href="mailto:<?=$lens['company_email']?>" style="color: #FA0;"><?=$lens['company_email']?></a></div>
     	</div>
     </div>
     
<?
if($lens['discontinued']){
?>
     <div class="edas-ld-discontinued">(Discontinued)</div>
<?
}

if($lens['image'] != ''){
?>
     <div class="edas-ld-image"><img src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $lens['image']);?>"<?=($lens['image_width'] > 190?' style="width: 100%;"':'')?> /></div>
<?
}

if(is_array($lens['images'])){
?>
     <div class="edas-ld-show-images"><a href="#" onclick="return eyedock_lens_detail_images_toggle('<?=$lens['id']?>');">See All Images</a></div>

     <div id="<?=$lens['id']?>-images" class="edas-ld-images" style="display: none;">
<?foreach($lens['images'] as $i){?>
      <div><img src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $i);?>" /></div>
<?}?>
     </div>
<?
}
?>

    </div>
   </div>

   <div class="edas-ld-main-right">
    <div class="edas-ld-main-right-content">

     <div class="edas-ld-right-container">
     	<div class="edas-ld-content-title">Spherical Powers</div>
     	<div class="edas-ld-container-content">
     	 <div class="edas-ld-sphere-line">
        <div class="edas-ld-sphere-diam">
         <div class="edas-ld-container-label">Diameter</div>
         <? if($lens['diameter1'] > 0) { ?><div class="edas-ld-container-value"><?=($lens['diameter1'] != ''?$lens['diameter1']:'&nbsp;')?></div><? } ?>
         <? if($lens['diameter2'] > 0) { ?><div class="edas-ld-container-value"><?=($lens['diameter2'] != ''?$lens['diameter2']:'&nbsp;')?></div><? } ?>
         <? if($lens['diameter3'] > 0) { ?><div class="edas-ld-container-value"><?=($lens['diameter3'] != ''?$lens['diameter3']:'&nbsp;')?></div><? } ?>
        </div>
        <div class="edas-ld-sphere-bc">
         <div class="edas-ld-container-label">Base Curves</div>
         <? if($lens['basecurves1'] != '') { ?><div class="edas-ld-container-value"><?=($lens['basecurves1'] != ''?$lens['basecurves1']:'&nbsp;')?></div><? } ?>
         <? if($lens['basecurves2'] != '') { ?><div class="edas-ld-container-value"><?=($lens['basecurves2'] != ''?$lens['basecurves2']:'&nbsp;')?></div><? } ?>
         <? if($lens['basecurves3'] != '') { ?><div class="edas-ld-container-value"><?=($lens['basecurves3'] != ''?$lens['basecurves3']:'&nbsp;')?></div><? } ?>
        </div>
        <div class="edas-ld-sphere-powers">
         <div class="edas-ld-container-label">Powers<span class="edas-popup-link" onclick="eld_popup_open('power', '<?=$lens['id']?>');">[?]</span></div>
         <? if($lens['powers1'] != '') { ?><div class="edas-ld-container-value"><?=($lens['powers1'] != ''?$lens['powers1']:'&nbsp;')?></div><? } ?>
         <? if($lens['powers2'] != '') { ?><div class="edas-ld-container-value"><?=($lens['powers2'] != ''?$lens['powers2']:'&nbsp;')?></div><? } ?>
         <? if($lens['powers3'] != '') { ?><div class="edas-ld-container-value"><?=($lens['powers3'] != ''?$lens['powers3']:'&nbsp;')?></div><? } ?>
        </div>
     	 </div>
      </div>
     </div>

<?
if($lens['sph_notes'] != ''){
?>
     <div class="edas-ld-right-note"><?=$lens['sph_notes']?></div>
<?
}

if($lens['toric'] == 1){
?>

     <div class="edas-ld-right-container edas-ld-right-separator">
     	<div class="edas-ld-content-title">Toric Powers</div>
     	<div class="edas-ld-container-content">
     	 <div class="edas-ld-sphere-line">
        <div class="edas-ld-sphere-diam">
         <div class="edas-ld-container-label">Toric Type</div>
         <div class="edas-ld-container-value"><?=($lens['toric_type'] != ''?$lens['toric_type']:'&nbsp;')?></div>
        </div>
        <div class="edas-ld-sphere-bc">
         <div class="edas-ld-container-label">Cylinder Powers</div>
         <div class="edas-ld-container-value"><?=($lens['cylinder_powers'] != ''?$lens['cylinder_powers']:'&nbsp;')?></div>
        </div>
        <div class="edas-ld-sphere-powers">
         <div class="edas-ld-container-label">Axis</div>
         <div class="edas-ld-container-value"><?=($lens['axis'] != ''?$lens['axis']:'&nbsp;')?></div>
        </div>
     	 </div>
      </div>
     </div>

<?
	if($lens['cyl_notes'] != ''){
?>
     <div class="edas-ld-right-note"><?=$lens['cyl_notes']?></div>
<?
	}

}

if($lens['bifocal'] == 1){
?>

     <div class="edas-ld-right-container edas-ld-right-separator">
      <div class="edas-ld-content-title">Bifocal Powers</div>
      <div class="edas-ld-container-content">
       <div class="edas-ld-sphere-line">
        <div class="edas-ld-sphere-diam">
         <div class="edas-ld-container-label">Bifocal Type</div>
         <div class="edas-ld-container-value"><?=($lens['bifocal_type'] != ''?$lens['bifocal_type']:'&nbsp;')?></div>
        </div>
        <div class="edas-ld-sphere-bc">
         <div class="edas-ld-container-label">Add Powers</div>
         <div class="edas-ld-container-value"><?=($lens['add_powers'] != ''?$lens['add_powers']:'&nbsp;')?></div>
        </div>
        <div class="edas-ld-sphere-powers">
         <div class="edas-ld-container-label">&nbsp;</div>
         <div class="edas-ld-container-value">&nbsp;</div>
        </div>
       </div>
      </div>
     </div>

<?
}

if($lens['cosmetic'] == 1){
?>

     <div class="edas-ld-right-container edas-ld-right-separator">
      <div class="edas-ld-content-title">Cosmetic Options</div>
      <div class="edas-ld-container-content">
       <div class="edas-ld-colors-line">
        <div class="edas-ld-colors-label">Enhancer Colors</div>
        <div class="edas-ld-colors-value"><?=($lens['enhancer_colors'] != ''?$lens['enhancer_colors']:'&nbsp;')?></div>
       </div>
       <div class="edas-ld-colors-line">
        <div class="edas-ld-colors-label">Opaque Colors</div>
        <div class="edas-ld-colors-value"><?=($lens['opaque_colors'] != ''?$lens['opaque_colors']:'&nbsp;')?></div>
       </div>
      </div>
     </div>

<?
}
?>

     <div class="edas-ld-right-center">
      <div class="edas-ld-rc-left">
       <div class="edas-ld-right-container">
        <div class="edas-ld-content-title">Materials &amp; Measurements</div>
        <div class="edas-ld-container-content">
         <div class="edas-ld-rc-list">
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Material</div>
           <div class="edas-ld-rc-value"><?=($lens['polymer'] != ''?$lens['polymer']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">dK</div>
           <div class="edas-ld-rc-value"><?=($lens['dk'] != ''?$lens['dk'] . ($lens['dkt'] != ''?' (dk/t: ' . $lens['dkt'] . ')':''):'&nbsp;')?><span class="edas-popup-link" onclick="eld_popup_open('dk', '<?=$lens['id']?>');">[?]</span></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">FDA Group</div>
           <div class="edas-ld-rc-value">
            <?=($lens['fda_group'] != ''?$lens['fda_group']:'&nbsp;')?>
<?
if($lens['fda_group'] >=1 || $lens['fda_group'] <= 4){
?>
            <span class="edas-popup-link" onclick="eld_popup_open('fda-<?=$lens['fda_group']?>', '<?=$lens['id']?>');">[?]</span>
<?
}
?>
           </div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">H20 Content</div>
           <div class="edas-ld-rc-value"><?=($lens['water'] != ''?($lens['water'] . '%'):'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">CT</div>
           <div class="edas-ld-rc-value"><?=(abs(doubleval($lens['ct'])) > 0?($lens['ct'] . ' mm'):'unknown')?><span class="edas-popup-link" onclick="eld_popup_open('ct', '<?=$lens['id']?>');">[?]</span></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">OZ</div>
           <div class="edas-ld-rc-value"><?=(abs(doubleval($lens['oz'])) > 0?($lens['oz'] . ' mm'):'unknown')?></div>
          </div>
         </div>
        </div>
       </div>
      </div>
      <div class="edas-ld-rc-right">
       <div class="edas-ld-right-container">
        <div class="edas-ld-content-title">Other Parameters</div>
        <div class="edas-ld-container-content">
         <div class="edas-ld-rc-list">
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Manuf. Process</div>
           <div class="edas-ld-rc-value"><?=($lens['man_process'] != ''?$lens['man_process']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Wear Type</div>
           <div class="edas-ld-rc-value"><?=($lens['wear'] != ''?$lens['wear']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">EW</div>
           <div class="edas-ld-rc-value"><?=($lens['ew'] != ''?$lens['ew']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Quantity</div>
           <div class="edas-ld-rc-value"><?=($lens['quantity'] != ''?$lens['quantity']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Replacement</div>
           <div class="edas-ld-rc-value"><?=($lens['replacement'] != ''?$lens['replacement']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Appearance</div>
           <div class="edas-ld-rc-value"><?=($lens['appearance'] != ''?$lens['appearance']:'&nbsp;')?></div>
          </div>
          <div class="edas-ld-rc-item">
           <div class="edas-ld-rc-label">Visitint</div>
           <div class="edas-ld-rc-value"><?=($lens['visitint'] != ''?$lens['visitint']:'&nbsp;')?></div>
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>

     <div class="edas-ld-right-container">
     	<div class="edas-ld-content-title">Comments</div>
     	<div class="edas-ld-container-content"><?=($lens['comments'] != ''?$lens['comments']:'&nbsp;');?></div>
     </div>

<?
if(trim($lens['prices']) != '' && trim($lens['prices']) != '0.00'){
?>
     <div class="edas-ld-wholesale-view"><a href="#" onclick="return eyedock_lens_detail_wholesale_toggle('<?=$lens['id']?>')">View Wholesale Prices</a></div>
     <div class="edas-ld-wholesale" id="<?=$lens['id']?>-wholesale" style="display: none;">
      <div><?=$lens['prices']?></div>
      <div style="padding-top: 2px;">*cost may vary - please contact manufacturer to verify</div>
     </div>
<?
}

if($lens['website'] != ''){
?>
     <div class="edas-ld-link"><a href="<?=$lens['website']?>" target="_blank">View Website</a></div>
<?
}

if($lens['fitting_guide'] != ''){
?>
     <div class="edas-ld-link"><a href="<?=$lens['fitting_guide']?>" target="_blank">View Fitting Guide or Package Insert</a></div>
<?
}
?>

    </div>
   </div>

  </div>
 </div>

 <div class="edas-ld-bottom">
 	<div class="edas-ld-bottom-left"><div class="edas-ld-bottom-right"><div class="edas-ld-bottom-content">

  </div></div></div>
 </div>

</div>

<?
if($lens['company_description'] != ''){
?>
<div class="eyedock-detailed-popup" id="edp-<?=$lens['id'] . '-comp'?>" onclick="eld_popup_close('<?=$lens['id'] . '-comp'?>');">
 <div class="eyedock-detailed-popup-content">
  <div class="eyedock-detailed-popup-text"><?=$lens['company_description']?></div>
  <div class="eyedock-detailed-popup-close" onclick="eld_popup_close('<?=$lens['id'] . '-comp'?>');">X</div>
 </div>
</div>
<?
}
?>

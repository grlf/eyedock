<?php

?>

<?
if(is_array($company_lenses) && !empty($company_lenses)){
?>

<div class="edas-tab-search-lenses-list">

 <div class="edas-tab-search-lenses-content edas-relative">
<?foreach($company_lenses as $l){?>
  <div class="edas-tab-search-lens edas-relative" id="company-lens-<?=$l['pn_tid']?>" onclick="eyedock_lens_detail_display('<?=$l['pn_tid']?>');">
   <div class="edas-tab-search-lens-image"><?if($l['image'] != ''){?><img src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $l['image']);?>" /><?}else{echo '&nbsp;';}?></div>
   <div class="edas-tab-search-lens-name<?=($l['discontinued']?' edas-discontinued':'')?>"><?=$l['name'];?></div>
<?eyedock_display_lens_popup($l);?>
  </div>
<?}?>
 </div>

 <div class="edas-tab-search-lenses-title"><span>Lenses from this company</span></div>

</div>

<?
}else{
?>
<div class="edas-no-lenses-search">
There are no available lenses from this company
</div>
<?
}
?>
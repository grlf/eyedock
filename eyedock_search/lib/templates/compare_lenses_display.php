<?php

?>

<?
if(is_array($lenses) && !empty($lenses)){
?>

<div class="edas-compare-lens-display">

 <div class="edas-compare-lens-head">
  <div class="edas-compare-lens-cell1">Lens</div>
  <div class="edas-compare-lens-cell2">Company</div>
  <div class="edas-compare-lens-cell3">Most+</div>
  <div class="edas-compare-lens-cell4">Most-</div>
  <div class="edas-compare-lens-cell5">sm Diam</div>
  <div class="edas-compare-lens-cell6">lg Diam</div>
  <div class="edas-compare-lens-cell7">BC</div>
  <div class="edas-compare-lens-cell8">Toric</div>
  <div class="edas-compare-lens-cell9">Bifocal</div>
  <div class="edas-compare-lens-cell10">Cosmetic</div>
 </div>

<?
if(!empty($lenses)){
	foreach($lenses as $l){
?>
 <div class="edas-compare-lens-line">
  <div class="edas-compare-lens-cell1 edas-cell-valign<?=($l['discontinued']?' edas-discontinued':'')?>" onclick="eyedock_lens_detail_display('<?=$l['pn_tid']?>');">
<?
		if($l['image'] != ''){
?>
   <img src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $l['image']);?>" />
<?
		}
?>
   <?=$l['name']?>
  </div>
  <div class="edas-compare-lens-cell2 edas-cell-valign"><span><?=$l['company']?></span></div>
  <div class="edas-compare-lens-cell3">+<?=$l['pn_max_plus']?></div>
  <div class="edas-compare-lens-cell4"><?=$l['pn_max_minus']?></div>
  <div class="edas-compare-lens-cell5"><?=$l['pn_min_diam']?></div>
  <div class="edas-compare-lens-cell6"><?=$l['pn_max_diam']?></div>
  <div class="edas-compare-lens-cell7 edas-cell-valign"><span><?=$l['pn_bc_all']?></span></div>
  <div class="edas-compare-lens-cell8"><?=($l['pn_max_cyl_power'] < 0?$l['pn_max_cyl_power']:'')?></div>
  <div class="edas-compare-lens-cell9"><?=($l['pn_max_add'] > 0?$l['pn_max_add']:'')?></div>
  <div class="edas-compare-lens-cell10"><?=($l['pn_cosmetic'] > 0?'Yes':'')?></div>
 </div>
<?
	}
}
?>

</div>

<?
}else{
?>
<div class="edas-no-lenses-search">
There are no available lenses matching your search
</div>
<?
}
?>
                                                                
<?php
	defined('_JEXEC') or die('Restricted access'); 
?>

<?
if(is_array($lenses) && !empty($lenses)){



?>
<div class="edas-compare-lens-display">

<table class="tablesorter" id="ed_lens_compare_table" >
	<thead>
		<tr>
			
			<th>Fav</th>
			<th>Popular</th>
			<th>Pic</th>
			  <th style="width: 80px;">Lens</th>
			  <th>Company</th>
			  <th>Most+</th>
			  <th>Most-</th>
			  <th>BC</th>
			  <th class="allDiamCol">Diam.</th>
			  <th class="sDiamCol">S Diam</th>
			  <th class="lDiamCol">L Diam</th>
			  <th class="dkCol">Dk</th>
			  <th class="ctCol">CT</th>
			  <th class="ozCol">OZ</th>
			  <th class="h2oCol">H2O</th>
			  <th>Toric</th>
			  <th>Bifocal</th>
			  <th>Cosmetic</th>
		</tr>
	</thead>
	<tbody>
		<?
		if(!empty($lenses)){
			foreach($lenses as $l){
		?>
		 <tr  class="<?=($l['discontinued']?'discontinuedCol':'')?>" onclick="eyedock_lens_detail_display('<?=$l['pn_tid']?>');">	
			
			<td class="userFavCol" id="starTD<?=$l['pn_tid']?>">
			<span style="display:none"><?=$l['userFav']?></span> 
			<img src='<?=EYEDOCK_LENS_IMG_URL?>/star1.png' style="display: <?=($l['userFav']==1)?"block":" none"; ?>" /></td>
		  
		  <td class="favPctCol"> <?php if(isset($l['favPercent'])) $l['favPercent'];?></td>
			
			<td class="imageCol">
			<? if($l['image'] != ''){ ?>
			   <img style="float: left; width: 35px; height: 35px; margin: 2px;" src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $l['image']);?>" />
			   <? } ?>
		   </td>
		

		  
		  <td> <?=$l['name']?></td>
		  <td><?=$l['company']?></td>
		  <td><?= sprintf("%+d.00",$l['pn_max_plus'])?></td>
		  <td><?= sprintf("%+d.00",$l['pn_max_minus'])?></td>
		  <td><?=$l['pn_bc_all']?></td>		  
		  <td class="allDiamCol"><?=$l['pn_diam_all']?></td>
		  <td class="sDiamCol"><?=$l['pn_min_diam']?></td>
		  <td class="lDiamCol"><?=$l['pn_max_diam']?></td>
		  <td class="dkCol"><?=($l['pn_dk'] >1?$l['pn_dk']:'?')?></td>
		  <td class="ctCol"><?=($l['pn_ct'] >0?$l['pn_ct']:'?')?></td>
		  <td class="ozCol"><?=($l['pn_oz'] >1?$l['pn_oz']:'?')?></td>
		  <td class="h2oCol"><?=($l['pn_h2o'] >1?$l['pn_h2o']:'?')?></td>
		  <td><?=($l['pn_max_cyl_power'] < 0?'up to ' . $l['pn_max_cyl_power']:'')?></td>
		  <td><?=($l['pn_max_add'] > 0? 'up to +'.$l['pn_max_add']:'')?></td>
		  <td><?=($l['pn_cosmetic'] > 0?'Yes':'')?></td>
		 </tr>
		<?
			}
		}
		?>
	</tbody>
</table>
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

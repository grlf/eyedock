<?php
	defined('_JEXEC') or die('Restricted access'); 
	$user =& JFactory::getUser();

if(is_array($this->lenses) && !empty($this->lenses)){

include_once "header.php"; 





?>
  <div class="lensListTable">
	<table class="tablesorter" id="ed_lens_params_table">
				<thead>
					<tr class="tablesorter-stickyHeader">
					
					<?  //subscriber only
						if ($subscriber == 1) { ?>
						
						<th class="userFavCol">Fav</th>
						<th class ="favPctCol">Popular</th>
						
					<? } // end subscriber only ?>
					
					
						<th class="imageCol">Pic</th>
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
				<tbody id='ed-lenses-paramsList'>

		<?
		if(!empty($this->lenses)){
			foreach($this->lenses as $l){

		?>
		
		 <tr  class="<?=($l['discontinued']?'discontinuedCol':'')?>" onclick="setDetailsHash(<?=$l['pn_tid']?>);">	
			
			<?  //subscriber only ------------------------------------------
				if ($subscriber == 1) { ?>
				
			<td class="userFavCol">
				<div style="display:none"><? echo ($l['favorite']==1)?1:0 ?></div>
				<a class="rating<? if ($l['favorite'] == 1) echo ' on'; ?>" href="#1" title="favorite" id="paramlist-star<?=$l['pn_tid']?>" onClick="favStarClick(event, <? echo $l['pn_tid'] . ", " . $user->id?>);">Star</a>
				
			</td>
		  
		  <td class="favPctCol"> <div style="display:none"><?=$l['score'] ?> </div>
		  	<div class="popularityBar" style=" width:<? echo  $l['score'] / 2.5; ?>px;">&nbsp;</div>
		  
		  </td>
		  
		  <? } // end subscriber only ----------------- ?> 
					
			
			<td class="imageCol">
			<? if($l['image'] != ''){ ?>
			   <img style="float: left; width: 35px; height: 35px; margin: 2px;" src="<?=('http://www.eyedock.com/modules/Lenses/pnimages/lens_images/' . $l['image']);?>" />
			   <? } ?>
		   </td>
		

		  
		  <td> <?=$l['name']?></td>
		  <td><?=$l['company']?></td>
		  <td><?= sprintf("%+d.00",$l['pn_max_plus'])?></td>
		  <td><?= sprintf("%+d.00",$l['pn_max_minus'])?></td>
		  <td><?=$l['pn_bc_all']?></td>		  
		  <td class="allDiamCol"><?=$l['pn_diam_all']?></td>
		  <td class="sDiamCol"><?= ($l['pn_min_diam'] == 99)?"any":$l['pn_min_diam']  ?></td>
		  <td class="lDiamCol"><?= ($l['pn_max_diam']== 99)?"any":$l['pn_max_diam'] ?></td>
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
		</div> <!-- end lensListTable -->
			<?
		
	
} else {
?>
Sorry - no lenses found . . .
<?

}
?>

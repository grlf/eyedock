<?php
	defined('_JEXEC') or die('Restricted access'); 
	
	$user =& JFactory::getUser();
	
if(is_array($this->lenses) && !empty($this->lenses)){



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

			<th class="dkCol">Dk</th>
			<th>BC</th>
			<th>Diam.</th>
			<th>Suggested Power</th>
			<th>VA*</th>
		</tr>
	</thead>
	<tbody>
		<?
		if(!empty($this->lenses)){
			foreach($this->lenses as $l){
		?>
		 <tr  class="<?=($l['discontinued']?'discontinuedCol':'')?>" onclick="do_display_lens('<?=$l['pn_tid']?>');">	
			
			<td>
					<a class="rating" href="#1" title="favorite" id="paramlist-star<?=$l['pn_tid']?>" onClick="favStarClick(event, <?=$user->id?>);">Star</a>

			</td>
		  
		  <td class="favPctCol"> <?php if(isset($l['favPercent'])) $l['favPercent'];?></td>
			
			<td class="imageCol">
			<? if($l['image'] != ''){ ?>
			   <img style="float: left; width: 35px; height: 35px; margin: 2px;" src="<?=('http://www.eyedock.com/modules/Lenses/pnimages/lens_images/' . $l['image']);?>" />
			   <? } ?>
		   </td>
		

		  
		  <td> <?=$l['name']?></td>
		  <td><?=$l['company']?></td>
		  
		  
		  <td class="dkCol"><?=($l['pn_dk'] >1?$l['pn_dk']:'?')?></td>

		  
		  <td><?=$l['baseCurve']?></td>
		  <td><?=$l['diameter']?></td>
		  <td><?=$l['fullRx']?></td>
		  <td><div style='display:none'><?=$l['va']?></div><?=$l['chartVA']?></td>
		  
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

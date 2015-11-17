<?php
	defined('_JEXEC') or die('Restricted access'); 
	
	$user =& JFactory::getUser();
	
if(is_array($this->lenses) && !empty($this->lenses)){

	include_once "header.php"; 
	
include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/is_subscriber.php' );

//only show certain features to users
//$subscriber = isUserASubscriber();


?>
<div class="lensListTable">
<table class="tablesorter" id="ed_lens_power_table" >
	<thead>
		<tr class="tablesorter-stickyHeader">
		
		<?  //subscriber only
			if (isUserASubscriber() == 1) { ?>
							
				<th class="userFavCol">Fav</th>
				<th class ="favPctCol">Popular</th>
				
			 <? } // end subscriber only ?> 
			 
			 
			<th class="imageCol">Pic</th>
			<th style="width: 80px;">Lens</th>
			<th>Company</th>
			<th class="dkCol">Dk</th>
			<th>BC</th>
			<th>Diam.</th>
			<th>Power</th>
			<th>VA*</th>
		</tr>
	</thead>
	<tbody id='ed-lenses-powerList'>


		<?
		if(!empty($this->lenses)){
			foreach($this->lenses as $l){
		?>
		 <tr  class="<?=($l['discontinued']?'discontinuedCol':'')?>"  onclick="setDetailsHash(<?=$l['pn_tid']?>);">	
			
		<?  //subscriber only--------
			if (isUserASubscriber() == 1) { ?>	
			
			<td class="userFavCol">
				<div style="display:none"><? echo ($l['favorite']==1)?1:0 ?></div>
				<a class="rating<? if ($l['favorite'] == 1) echo ' on'; ?>" href="#1" title="favorite" id="paramlist-star<?=$l['pn_tid']?>" onClick="favStarClick(event, <? echo $l['pn_tid'] . ", " . $user->id?>);">Star</a>
				

			</td>
		  
		  <td class="favPctCol"> <div style="display:none"><?=$l['score'] ?> </div>
		  	<div class="popularityBar" style=" width:<? echo  $l['score'] / 2.5; ?>px;">&nbsp;</div>
		  
		  </td>

		<? } // end subscriber only ----- ?> 
			 		  
		  
			
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

<div >* "Search by refraction" results are limited to a maximum of 50 lenses. Favorite lenses, popular lenses, and disposable lenses are prioritized. To see more options consider narrowing your search criteria (for example, only 2 weeks lenses, only high Dk lenses, etc).

</div> <!-- end lensListTable -->
<?

	
} else {
?>
Sorry - no lenses found . . .
<?

}
?>
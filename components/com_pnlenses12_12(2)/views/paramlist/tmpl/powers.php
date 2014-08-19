<?php
	defined('_JEXEC') or die('Restricted access'); 
	
	$user =& JFactory::getUser();
	
if(is_array($this->lenses) && !empty($this->lenses)){

	include_once "header.php"; 

?>

<table class="tablesorter" id="ed_lens_power_table" >
	<thead>
		<tr>
			
			<th class="userFavCol">Fav</th>
			<th class ="favPctCol">Popular</th>
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
		 <tr  class="<?=($l['discontinued']?'discontinuedCol':'')?>" onclick="do_display_lens('<?=$l['pn_tid']?>');">	
			
			<td class="userFavCol">
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

<?

	
} else {
?>
Sorry - no lenses found . . .
<?

}
?>
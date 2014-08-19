<?php 

defined('_JEXEC') or die('Restricted access'); 

require_once( JPATH_ROOT.DS.'utilities/utilities/opticalcrossfunc.php' );
//print_r ($this->dataList);

$crossRx['rxString'] = $this->dataList[0]['bestRx'];
$crossRx['label'] = 1;
$crossRx['bgImage'] = "lens";
$count = min(2, count($this->dataList) );

?>


	<div style='text-align: center; border: 1px solid gray; padding: 1em; background-color: #f2f2f2;'>
	<h3>Closest available <b><?= $this->dataList[0]['name'] ?></b> lens(es)</h3>
	
	
	<div style='width:150px;text-align:center; float: left;'> <u>The ideal lens</u>
	<div>
		<?= (getCrossFromParams($crossRx) ); ?>
	</div>
	<br/>(vertexed from 12mm)
	</div>
	
		<?php 
		$i = 0;
		
		foreach ($this->dataList as $lens) { 
			$i++;
			$lensCross['rxString'] =  $lens['fullRx'];
			$lensCross['label'] = 1;
			$lensCross['bgImage'] = "lens";
?>
	<div style='width:150px;text-align:center;float: left;'> 
		<u>Option <?php if($count>1) echo $i; ?></u>
		<div>
			<?= (getCrossFromParams($lensCross) ); ?>
		</div>
		<div>
			<br>Base curve(s): <?= $lens['baseCurve'] ?><br>
			Diameter(s): <?= $lens['diameter'] ?><br>
			Calculated VA: <?= $lens['chartVA'] ?>
		</div>
	</div>


		
	<?php } ?>
	
	
	
	<table style='margin-left:auto; margin-right:auto; margin-top: 15px;' > <tr><th  style='padding:4px;text-align:center; '><u>Base Curve</u></th><th style='padding:4px; text-align:center;'><u>Diameter</u></th><th style='padding:4px; text-align:center;'><u>Power</u></th><th style='padding:4px; text-align:center;'><u>VA *</u></th></tr>
	
	<?php foreach ($this->dataList as $lens) { ?>
	
		<tr><td style='padding:4px;'><?= $lens['baseCurve'] ?></td><td style='padding:4px;'><?= $lens['diameter'] ?> </td><td style='padding:4px;'><?= $lens['fullRx'] ?></td><td style='padding:4px;'><?= $lens['chartVA'] ?></td></tr>
		
	<?php } ?>
	
	</table></p></p>
	
	<div style='font-size: smaller; margin-top:30px;'>* This value is provided to allow comparison between suggested trial lenses.  It does not take into account the patient's BCVA, lens rotation, lens fit, or even accommodation. In other words, there is no guarantee that the lens will provide the visual acuity that is predicted!</div>
	
	</div>

	
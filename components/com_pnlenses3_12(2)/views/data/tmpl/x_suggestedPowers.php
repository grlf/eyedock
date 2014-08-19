<?php 

//defined('_JEXEC') or die('Restricted access'); 

print_r ($this->dataList);

?>

	<div style='text-align: center'>
	<br/>The ideal lens: <?= $this->dataList[0]['bestRx']?>
	
	<p><br/>Closest available <b><?= $this->dataList[0]['name'] ?></b> lens(es):<br/>
	
	<table style='margin-left:auto; margin-right:auto; margin-top: 15px;' > <tr><th  style='padding:4px;text-align:center; '><u>Base Curve</u></th><th style='padding:4px; text-align:center;'><u>Diameter</u></th><th style='padding:4px; text-align:center;'><u>Power</u></th><th style='padding:4px; text-align:center;'><u>VA *</u></th></tr>
	
	<?php foreach ($this->dataList as $lens) { ?>
	
		<tr><td style='padding:4px;'><?= $lens['baseCurve'] ?></td><td style='padding:4px;'><?= $lens['diameter'] ?> </td><td style='padding:4px;'><?= $lens['fullRx'] ?></td><td style='padding:4px;'><?= $lens['chartVA'] ?></td></tr>
		
	<?php } ?>
	
	</table></p></p>
	
	<div style='font-size: smaller; margin-top:30px;'>* This value is provided to allow comparison between suggested trial lenses.  It does not take into account the patient's BCVA, lens rotation, or accommodation. Needless to say (hopefully), there is no guarantee that the lens will provide the visual acuity that is predicted</div>
	
	</div>

	
<?php defined('_JEXEC') or die('Restricted access'); 

	JToolBarHelper::title( JText::_( 'PN Company Report' ), 'generic.png' );
	JToolBarHelper::back();


$info = $this->companyInfo['info'];
$allLenses = $this->companyInfo['cls'];


?>



<table border='1' cellspacing='0' bordercolor='#cae0eb' >
<tr><td colspan='2'><h2><?php  echo $info[0]['comp_name'] ?></h2></td></tr>
<tr><td>Phone:</td><td> <?= $info[0]['phone'] ?></td></tr>
<tr><td>Address: </td><td><?=  $info[0]['address']?></td></tr>
<tr><td>Website: </td><td><a href="<?php echo $info[0]['website'] ?>"><?php echo $info[0]['website'] ?>  </a></td></tr>
<tr><td>Email address: </td><td><a href="mailto:<?php echo $info[0]['email'] ?>"><?php echo $info[0]['email'] ?></a><br/>
<tr><td>Description: </td><td><?php echo $info[0]['description'] ?></td></tr>
<tr><td>Contact name*</td><td><?php echo $info[0]['contactName'] ?></td></tr>
<tr><td>Contact email*</td><td><?php echo $info[0]['contactEmail'] ?></td></tr></table>
<br/>* the contact name and email are where we (Eyedock) will direct questions regarding <?php echo $info[0]['comp_name_short'] ?> lenses. This information will not be displayed on the website.
</p>

<p>

<?php


//print_r( $allLenses[0]);

$discontinuedArray = array();

foreach ($allLenses as $lens) {

if ($lens['discontinued'] ==1 ) {
	$discontinuedArray[] = $lens['name'];
	continue;
}
	//print_r($lens);

?>
<p>&nbsp;</p>
	<p><table border='1' cellspacing='0' width='400px' bordercolor='#cae0eb'>
	<tr><td colspan='2'><h2><?= $lens['name'] ?></h2></td></tr>
	<?php
	if ($lens['images'] !="") {
		$images = explode(",", $lens['images'] );
		echo "<tr><td colspan='2'>";
		foreach ($images as $image){
			$image = trim($image, " \s");
			echo "<img src='http://www.eyedock.com/modules/Lenses/pnimages/lens_images/" . $image ."' />";
		}
		echo "</td></tr>";
	}
	?>
	
	<tr><td>Material</td><td><?php echo $lens['material'] ?><br/>
	&nbsp;&nbsp;&nbsp; - FDA group: <?php echo checkForNull($lens['fda']) ?><br/>
	&nbsp;&nbsp;&nbsp; - Water content: <?php echo checkForNull($lens['h2o']) ?><br/>
	&nbsp;&nbsp;&nbsp; - Modulus: <?php echo checkForNull($lens['modulus']) ?><br/>
	 &nbsp;&nbsp;&nbsp; - dk:  
	 <?php 
	 	if ($lens['polydk']>0) { 
	 		echo checkForNull($lens['polydk']);
	 	} else {
	 		echo checkForNull($lens['dk']);
	 	}
	 	
	  ?>
	 	
	 	
	 	</td></tr>

	
	
	<tr><td>Visibility Tint?</td><td>  <?php echo yesOrNo($lens['visitint']) ?></td></tr> 
	<tr><td>OK for extended wear?</td><td>  <?php echo yesOrNo($lens['ew'])  ?></td></tr> 
	<tr><td>Center thickness: </td><td> <?php echo checkForNull($lens['ct'])  ?></td></tr> 
	<tr><td>DK: </td><td> <?php echo checkForNull($lens['dk'])  ?></td></tr> 
	<tr><td>OZ:</td><td> <?php echo checkForNull($lens['oz'])  ?></td></tr> 
	<tr><td>Quantity : </td><td> <?php echo  checkForNull($lens['qty'])  ?></td></tr> 
	<tr><td>Replacement: </td><td> <?php echo  checkForNull($lens['replace_text'])  ?></td></tr> 
	<tr><td>Wear type: </td><td> <?php echo checkForNull($lens['wear'])  ?></td></tr> 
	<tr><td>Wholesale price :</td><td>  <?php echo checkForNull($lens['price'])  ?></td></tr> 
	<tr><td>Appearance: </td><td> <?php echo checkForNull($lens['markings'])  ?></td></tr> 
	<tr><td>Fitting guide:</td><td> <a href="<?php echo  $lens['fitting_guide'] ?>"><?php echo  $lens['fitting_guide'] ?> </a></td></tr> 
	<tr><td>Lens Website:</td><td> <a href="<?php echo  $lens['website'] ?>"><?php echo   $lens['website'] ?> </a></td></tr> 

	<tr><td>Other info:</td><td>  <?php echo  checkForNull($lens['other_info']) ?></td></tr>

	<tr><td colspan='2'>Parameters:<br/>
	
	<?php 
	$powers = $lens['powers'] ;
	$variation = 1;
	$pwrCount = count($powers);
	foreach ($powers as $power){
		
		echo "<p><table border='1' cellspacing='0' style='margin:7px; width:90%;' bordercolor='#f1f6fa'>";
		$variationLabel = ($power['variation'] !="")?$power['variation'] :$variation;
		if ($pwrCount>1) echo "<tr><td>Variation:</td><td>  " . $variationLabel . "</td></tr>";
		?>
		<tr><td width='25%'>Base Curve:</td><td><?php echo $power['baseCurve'] ?></td></tr>
		<tr><td>Diameter:</td><td><?= $power['diameter'] ?></td></tr>	
		<tr><td>Sphere:</td><td><?= $power['sphere'] ?></td></tr>
		
		<?php 
		if ($lens['toric'] == 1) {
			echo "<tr><td>Cyl Power:</td><td>  " . $power['cylinder'] . "</td></tr>";
			echo "<tr><td>Cyl Axis:</td><td>  " . $power['axis'] . "</td></tr>";	
		}
		
		if ($lens['bifocal'] == 1) {
			echo "<tr><td>Add power:</td><td>  " . $power['addPwr'] ." </td></tr>";	
		}
		
		if ($lens['cosmetic'] == 1) {
			echo "<tr><td>Colors (enhancers):</td><td>  " . $power['colors_enh'] . "</td></tr>";	
			echo "<tr><td>Colors (opaque): </td><td> " . $power['colors_opq'] . "</td></tr>";	
		}	
		echo "</table></p>";
		
		$variation++;
	}
	?>
	</td></tr>
	</table></p>
<?php
}
?>

</p>

<?php
//make a list of discontinued lenses:
if (count($discontinuedArray) > 0)	{
	echo "<p>&nbsp;</p><p><b>Discontinued lenses:</b> ";
	$infoText .= implode("<br/> ", $discontinuedArray);
	echo "</p>";
}

echo $infoText;


function yesOrNo ($val) {
	return $val==1?"yes":"no";
}

function checkForNull ($val) {
	//echo $val;
	if ($val == ""|| $val == null || ( is_numeric($val) && $val == 0)) return "";
	return $val;
}
?>
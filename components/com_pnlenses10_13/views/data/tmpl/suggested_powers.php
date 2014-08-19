<?php 

defined('_JEXEC') or die('Restricted access'); 

require_once( JPATH_ROOT.DS.'utilities/utilities/opticalcrossfunc.php' );
//require_once( JPATH_ROOT.DS.'utilities/utilities/lenscrosses.php' );
//print_r ($this->dataList);

$numLenses = count ($this->dataList);
$refraction    = JRequest::getVar( 'refraction', null );
$isCL  = JRequest::getVar( 'isCL', 0 );
 	


$sphEquiv = 1;
if ( abs($this->dataList[0]["cylinder"]) > 0) $sphEquiv = 0;
	//echo "sph: " . abs($this->dataList[0]["cylinder"]);
	$mrCross['rxString'] = $refraction;
	$mrCross['label'] = 3;
	$mrCross['bgImage'] = "lens";
	$mrCross['doVertex'] = null;
	$mrCross['header'] = ($isCL == 0)?"refraction":"the desired CL";
	echo "<div class='crossDiv'>" . getCrossFromParams ($mrCross) . "</div>";
	

	if (isset($refraction) && $isCL == 0) {
		$lensCross['rxString'] = $refraction;
		$lensCross['label'] = 1;
		$lensCross['bgImage'] = "lens";
		$lensCross['doVertex'] = 1;
		$lensCross['header'] = "the ideal CL";
		echo "<div class='crossDiv'>" .getCrossFromParams ($lensCross)  . "</div>";
	}
	
	if ($sphEquiv == 1) {
		$seLens['rxString'] = $refraction;
		$seLens['label'] = 1;
		$seLens['bgImage'] = "lens";
		$seLens['doVertex'] = 1;
		$seLens['header'] = "best spherical CL";
		$seLens['doSphEquiv'] = 1;
		echo "<div class='crossDiv'>" .getCrossFromParams ($seLens)  . "</div>";
	}
	

	$bestLens["rxString"] = $this->dataList[0]['fullRx'] ;
		$bestLens['label'] = 1;
		$bestLens['bgImage'] = "lens";
		$bestLens['header'] = "best " . $this->dataList[0]["name"] ;
		echo "<div class='crossDiv'>" .getCrossFromParams($bestLens) . "</div>";





?>


	<div style='text-align: center;padding: 1em; overflow: auto;'>



		<p style="margin: 35px 0 10px;"><u>Best <i><?= $this->dataList[0]["name"] ?></i> option<? if ($numLenses > 1) echo "s"?> </u></p>


	
	<? 
	 foreach ($this->dataList as $lens) {

		 ?>
			<p style="margin: 20px;">
				<b>Power:  &nbsp;</b><?= $lens['fullRx'] ?></br>	
				<b>Base Curve(s):  &nbsp;</b><?= $lens['baseCurve'] ?></br>			
				<b>Diameter(s):  &nbsp;</b><?= $lens['diameter'] ?></br>			

				<b>Calculated VA*: &nbsp;</b><?= $lens['chartVA'] ?></br>	
			</p>

	<?php } ?>
	

		</div>	
	<div style='font-size: smaller; margin-top:30px;'>* This value is provided to allow comparison between suggested trial lenses.  It does not take into account the patient's BCVA, lens rotation, lens fit, or even accommodation. In other words, there is no guarantee that the lens will provide the visual acuity that is predicted!</div>
	
	</div>

	
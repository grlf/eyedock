<?php

/**
 *
 * RxObject class
 * store refraction data and perform calculations 
 *
 * @author TOdd Zarwell
 * @copyright 2011 eyedock.com
 *
 */
 
 include_once "RxHelper.php";
 
 
class RxObject 
{

  		public $round = 0.25;
		//private $plusCyl = 0;
		//private $minusCyl = 1;
		public $vertex = .012;
		public $sph;
		public $cyl;
		public $axis;
		
 	public function __construct($s = 0, $c = 0, $a = 0) 
  { 
    if (is_numeric($s) ) $this->sph = $s;
    if (is_numeric($c) ) $this->cyl = $c;
    if (is_numeric($a) ) $this->axis = $a;
    //$this->determineCylType();
    
  } 
 
 	//getters and setters
	public function getSph() { return $this->sph; } 
	public function getCyl() { return $this->cyl; } 
	public function getAxis() { return $this->axis; } 
	public function setSph($x) { $this->sph = $x; } 
	public function setAxis($x) { $this->axis = $x; } 
	public function setCyl($x) { 
		$this->cyl = $x; 
		//$this->determineCylType();
	} 
 	public function isPlusCyl() { return ($this->cyl>0)?1:0; } 
	public function isMinusCyl() { return ($this->cyl<0)?1:0;} 
	public function setRound($x) { 
		if ($x > 0 && $x < 1) $this->round = $x; 
	} 

	public function setVertex($x) { 
		if (is_numeric ($x) ) $this->vertex = $x; 
	} 
 	
 	//when the cyl is set, set these boolean values right away
 /*	private function determineCylType () {
 		$this->plusCyl = ($this->cyl>0)?1:0;
		$this->minusCyl = ($this->cyl<0)?1:0;
	} */


	public function isValidRx () {
		//make sure each of these things is true
		$valid = checkSphereRange ($this->sph);
		$valid += checkCylinderRange ($this->cyl);
		$valid += checkAxisRange ($this->axis);
																									
		if ($valid < 3) return 0;
		
		return 1;
	
	}


	public function prettyString () {
		//echo "<p>prettystring rxobj</p>";

		return prettyRxString($this->sph, $this->cyl, $this->axis, $this->round);

	}


	 public function prettyStringPlus () {
		
		return prettyRxString ($this->sphP(), $this->cylP(), $this->axisP(), $this->round);
	}
	
	public function prettyStringMinus () {
		return prettyRxString ($this->sphM(), $this->cylM(), $this->axisM(), $this->round);
	}



	public function diffVertex ($vertexTo = 0) {

		$vertexChange = $this->vertex - $vertexTo;
		$oldSph = $this->sph;
		$oldCyl = $this->sph + $this->cyl;
		//check for 0 to avoid deividing by it!
		$newSph = ($oldSph == 0)? $oldSph : 1/( 1/ $oldSph - $vertexChange );
		$newCyl = ($oldCyl == 0) ? $oldCyl : 1/( 1/ $oldCyl - $vertexChange );
		$vertexedRx = new RxObject();
		$vertexedRx ->sph = $newSph;
		$vertexedRx ->cyl = $newCyl - $newSph;
		$vertexedRx ->axis = $this->axis;
		$vertexedRx ->vertex = $vertexTo;
		return $vertexedRx;

	}
	
	 public function sphericalEquivalent () {
		return $this->sph + .5 * $this->cyl;
	}
	
	//numbers in minus sphere
	public function sphM () { 
		$returnNum = $this->sph;
		if ($this->cyl >0) $returnNum = $this->sph + $this->cyl;
		return $returnNum; 
	}
	public function cylM () {
		 $returnNum = $this->cyl;
		if ($this->cyl >0) $returnNum = - $this->cyl;
		return $returnNum; 
	
	}
	public function axisM () {
		$returnNum = $this->axis;
		if ($this->cyl>0) $returnNum = $this->axis - 90;
		$returnNum = fixAxis ($returnNum);
		return $returnNum; 
	}
	
	//number in plus cyl
	public function sphP () {
		$returnNum = $this->sph;
		if ($this->cyl <0) $returnNum = $this->sph + $this->cyl;
		return $returnNum; 
	}
	public function cylP () {
		$returnNum = $this->cyl;
		if ($this->cyl <0) $returnNum = - $this->cyl;
		return $returnNum; 
	}
	public function 	axisP () { 
		$returnNum = $this->axis;
		if ($this->cyl <0) $returnNum = $this->axis - 90;
		$returnNum = fixAxis ($returnNum);
		return $returnNum; 
	}
	
	public function isToric () {
		return ( $this->cylP() > 0)?1:0;
	}
	
	
	public function isWTR () {
		////NSLog("@fullkeratobj flat k: %f", [self flatKMeridian] );
		return ( $this->axisM() <= 30 || $this->axisM() >= 150)?1:0;
	}
	
	public function isATR () {
		return ( $this->axisM() >= 60 && $this->axisM() <= 120)?1:0;
	}
	
	public function isOblique() {
		return ( ($this->axisP() >30 && $this->axisP() <60) || ($this->axisP() >120 && $this->axisP() <150) )?1:0;
		//return returnVal;
	}
	
	public function toricType() {
		if ($this->isWTR() == 1) return "WTR";
		if ($this->isATR() == 1) return "ATR";
		if ($this->isOblique() == 1) return "Oblique";
		
	}
	
	
	public function addRx ($lensRx2) {
	
		$ansObj = new RxObject();
		$clSph = $this->sphP();
		$clCyl = $this->cylP();
		$clAxis = $this->axisP();
		$orSph = $lensRx2->sphP();
		$orCyl = $lensRx2->cylP();
		$orAxis = $lensRx2->axisP();
		
		 $resSph = 0;
		 $resCyl = 0;
		 $resAxis = 0;
		
		//if either lens has no cyl (is spherical) set the axis to match the other lens
		if ($clCyl == 0) $clAxis = $orAxis;
		if ($orCyl == 0) $orAxis = $clAxis;
		
		 $axisDiff =  $orAxis - $clAxis;
	
	
	
		//	var resAxis = (atan((sin(deg2rads(axisDiff*2))*orCyl)/(Number(clCyl)+(orCyl*cos(deg2rads(axisDiff*2))))))/2;
		
		
		 $resAxis = (atan( (sin(deg2rad($axisDiff*2)) * $orCyl) / ( $clCyl + ($orCyl * cos(deg2rad($axisDiff*2))))))/2;
		$resAxis = rad2deg($resAxis);
		
		
		if ($axisDiff != 0) {
			
			$resCyl = ($orCyl*sin(deg2rad(2*$axisDiff)))/(sin(deg2rad(2*$resAxis)));
			$resSph = ( ($clCyl)+ ($orCyl)- ($resCyl))/2;
			
			
			$resAxis+= $clAxis;
			
			
			$resSph += ($clSph)+ ($orSph);
			
			if ($orCyl == 0||$clCyl == 0) {
				$ansObj->sph = $orSph + $clSph;
				$ansObj->cyl = $orCyl + $clCyl;
				if ($orCyl == 0) {
					$ansObj->axis = $clAxis;
					
				}
				if (clCyl == 0) {
					$ansObj->axis = $orAxis;
				}
				
			} else {
				$ansObj->sph = $resSph;
				$ansObj->cyl = $resCyl;
				$ansObj->axis = $resAxis;
				
			
			}
			
		} else { //axisDiff = 0 (cl rx and overrefraction were at same axis)
			$ansObj->sph = ($clSph)+($orSph);
			$ansObj->cyl = ($clCyl)+($orCyl);
			$ansObj->axis = $clAxis;
		}
		
		return $ansObj;
	}
	
	
	
	
	
			//______________________________________________


		public function subtractRx($rx2) {
			//this & rx2 are Rx objects. "This" is usually the MR, rx2 is the spectacles
			$newAxis = 0;
			$newSph = 0;
			$gamma = 0;
			$F2 = 0;
			$xSph =  $this->sphP();
			$xCyl =  $this->cylP();
			$xAxis =  $this->axisP();
			
			//echo $this->prettyString();
			//echo "<br/>";
			//echo $rx2->prettyString();
			//echo "<br/>";

			if ($rx2->cylP() >.375) {    //if the cyl is significant 

				$theta = $xAxis - $rx2->axisP();
				
				//find the smallest diff btwn the axes
				if ($theta>90) {
					$theta=180-$theta;
				}
				if ($theta < -90) {
					$theta = -180-$theta;
				}
				
				//echo "<p>theta: " . $theta . "</p>";

				$a = $xCyl*sin(deg2rad(2*$theta));
				//trace ("a:"+a);
				$b = $xCyl*(cos(deg2rad(2*$theta)))- $rx2->cylP();
				//trace ("b:"+b);
				$F2 = sqrt($a*$a+$b*$b);
				
				if ($b<0){$F2 =-$F2;} //this line added 4/7/04 
				
				if ($F2!= 0) {
					$gamma = rad2deg(asin($a/$F2)/2);
				}
				//echo "<p>gamma: " . $gamma . "</p>";
				
				$newAxis = $rx2->axisP()+$gamma;
		
				//echo "<p>newAxis : " . $newAxis  . "</p>";

				$newSph = $xSph-$rx2->sphP()-($rx2->cylP() + $F2-$xCyl)/2;
			} else {
				// rx2 is essentially spherical
				$newSph = $xSph-$rx2->sphP();
				$F2 = $xCyl;
				$newAxis = $xAxis;
			}
			//trace("newAxis ",newAxis);
			//trace("a = "+a);trace("b = "+b);trace ("$F2 = "+$F2);trace("gamma:"+gamma);trace("new axis:"+newAxis);trace("new sph:"+newSph);
			$tempObj = new RxObject();
			$tempObj->sph = $newSph;
			$tempObj->cyl = $F2;
			$tempObj->axis = fixAxis($newAxis);
			//trace(eye+" residual Rx:"+tempObj.prettyString("+", false));
			return $tempObj;
		}




} // end of class

?>
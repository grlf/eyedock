<?php

/**
 *
 * KeratoObject class
 * store keratometry data and perform calculations 
 *
 * @author Todd Zarwell
 * @copyright 2011 eyedock.com
 *
 */
 
 include_once "KvalObject.php";
 include_once "RxHelper.php";
 
class KeratoObject 
{
		protected $num1;
		protected $meridian1;
		protected $num2;
		protected $meridian2;
		protected $roundD = .125;
		protected $roundMM = .01;
		
	// assume vals will be: $n1/$n2 @ $m2	
 	public function __construct($n1 = 0, $n2 = 0, $m2 = 0) { 
 	if ($n1 == 0) return null;
    if ($n1 > 0) $this->num1 =  new KvalObject($n1);
    if ($n2 > 0 ) $this->num2 = new KvalObject($n2);
    if ($n2 == 0 ) $n2 = $n1;
    $this->meridian2 = fixAxis($m2);
    $this->meridian1 = fixAxis($m2-90);
    //return $this->isValidK() ? $this : null;
    return $this;
  } 
 
 	//getters and setters
 
 public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
  }
  
	public function __set($name, $x) {
        switch ($name) {
        	case "num1":
        		 $this->num1 = new KvalObject($x);
        		 break;
    		case "num2":
    			 $this->num2 = new KvalObject($x);
    			 break;
    	 	case"meridian1": 
				$this->meridian1 = fixAxis($x); 
				$this->meridian2 = fixAxis($x-90); 
				break;   		

    		case "meridian2": 
				$this->meridian2 = fixAxis($x); 
				$this->meridian1 = fixAxis($x-90);    		
    			break;
            default:
            	$this->$property = $value;
        }
    }
 	
 	
	public function pwr1() { return $this->num1->val; } 
	public function pwr2() { return $this->num2->val; } 
	public function pwr1mm() { return $this->num1->mmVal(); } 
	public function pwr2mm() { return $this->num2->mmVal(); } 
	public function pwr1D() { return $this->num1->dVal(); } 
	public function pwr2D() { return $this->num2->dVal(); } 

	/*
	public function setNum1($x) { $this->num1 = new KvalObject($x); } 
	public function setNum2($x) { $this->num2 = new KvalObject($x); } 
	public function setMeridian1($x) { 
		$this->meridian1 = fixAxis($x); 
		$this->meridian2 = fixAxis($x-90);
	} 
	public function setMeridian2($x) { 
		echo "x: " . $x;
		$this->meridian2 = fixAxis($x); 
		$this->meridian1 = fixAxis($x-90);
	} 
	*/
 	public function isMM() { 
 		return $this->num1->isMM();
 	} 
 	
 	public function isD() { 
 		return $this->num1->isD();
 	} 


		
	public function setFromString ($str) {
		if (is_numeric($str) ) {
			//echo "one num";
			self::__construct($str, $str, 0);
		}
		// both meridians: 42 @ 90 / 43 @ 180
		$regEx1 = "/\s*([.\d]{1,5})\s*(?:@\s*(\d{1,3}))\s*\/\s*([.\d]{1,5})\s*@\s*(\d{1,3})\s*/u";
		// one meridian: 42 / 42 @ 180
		$regEx2 = "/\s*([.\d]{1,5})\s*\/\s*([.\d]{1,5})\s*@\s*(\d{1,3})\s*/u";
		
		if (preg_match($regEx1, $str, $matches) ) {
			self::__construct($matches[1], $matches[3], $matches[4]);
		} else if (preg_match($regEx2, $str, $matches) ) {
			self::__construct($matches[1], $matches[2], $matches[3]);
		}
	}

	public function isValidK () {
		//make sure each of these things is true
		if (! $this->num1 || ! $this->num2) return 0;
		$n1OK = $this->num1->isValidK();
		$n2OK = $this->num2->isValidK();
		$m1OK = checkAxisRange($this->meridian1);
		$m2OK = checkAxisRange($this->meridian2);
		return ($n1OK && $n2OK && $m1OK && $m2OK) ? 1 : 0;	
	}



// $both means show both axis, eg) 43 @ 90 / 42 @ 180. Otherwise will return 43 / 42 @ 180
	public function prettyString ($both = 0) {
		if ($this->isMM() ) return $this->prettyStringMM($both);
		if ($this->isD() ) return $this->prettyStringD($both);

	}


	 public function prettyStringMM ($both = 0) {
	 	//if (
		if ($this->num1) $this->num1->roundMM = $this->roundMM;
		if ($this->num2) $this->num2->roundMM = $this->roundMM;
		$meridian1 = $both ? " @ " . numToAxisString($this->meridian1) : "";
		return $this->num1->prettyStringMM() . $meridian1 . " / " . $this->num2->prettyStringMM() . " @ " .  numToAxisString($this->meridian2) ;
	}
	
	
	public function prettyStringD ($both = 0) {
		if ($this->num1) $this->num1->roundD = $this->roundD;
		if ($this->num2) $this->num2->roundD = $this->roundD;
		$meridian1 = $both ? " @ " . numToAxisString($this->meridian1) : "";
		return $this->num1->prettyStringD() . $meridian1 . " / " . $this->num2->prettyStringD() . " @ " .  numToAxisString($this->meridian2) ;
	}

	//assume mid-k in diopters unless otherwise specified
	public function midK ($pretty = 0, $unit = 0, $mm = 0){
	 	$r = new KvalObject ( ($this->num1->dVal() + $this->num2->dVal() ) / 2 );
	 	return $r->toString($pretty, $unit, $mm); 
	}
	
	
	public function isToric () {
		return (abs ($this->num1->dVal() - $this->num2->dVal() ) > .125) ? 1: 0;
	}
	
	public function vertPwr ($pretty = 0, $unit = 0, $mm = 0) {
		if (isVerticalAxis($this->meridian2) ) {
			$r = $this->num2;
		} else {
			$r = $this->num1;
		}
		return $r->toString($pretty, $unit, $mm); //($mm)?$r->mmVal():$r->dVal();
	}
	
	public function horizPwr ($pretty = 0, $unit = 0, $mm = 0) {
		if (isVerticalAxis($this->meridian2) ) {
			$r = $this->num1;
		} else {
			$r = $this->num2;
		}
		return $r->toString($pretty, $unit, $mm);
	}
	
	
	public function vertMeridian () {
		if (isVerticalAxis($this->meridian2) ) return $this->meridian2;
		return $this->meridian1;
		
	}
	
	public function horizMeridian () {
		if (isVerticalAxis($this->meridian2) ) return $this->meridian1;
		return $this->meridian2;
	}
	
	public function flatK ($pretty = 0, $unit = 0, $mm = 0) {
		$r = ($this->num1->dVal() > $this->num2->dVal()) ? $this->num2 : $this->num1;
		return $r->toString($pretty, $unit, $mm);
	}
	
	public function steepK ($pretty = 0, $unit = 0, $mm = 0) {
		$r = ($this->num1->dVal() < $this->num2->dVal()) ? $this->num2 : $this->num1;
		return $r->toString($pretty, $unit, $mm);
	}
	
	public function flatKMeridian () {
		$r = ($this->num1->dVal() > $this->num2->dVal()) ? $this->meridian2 : $this->meridian1;
		return numToAxisString($r);
	}
	
	public function steepKMeridian () {
		$r = ($this->num1->dVal() < $this->num2->dVal()) ? $this->meridian2 : $this->meridian1;
		return numToAxisString($r);
	}
	
	
	public function isWTR () {
		if ($this->isSpherical() || $this->isOblique() ) return 0;
		return ( ($this->horizPwr() - $this->vertPwr()) < 0 )?1:0;
	}
	
	public function isATR () {
		if ($this->isSpherical() || $this->isOblique() )  return 0;
		return ( ($this->horizPwr() - $this->vertPwr()) > 0 )?1:0;
	}
	
	public function isOblique() {
		$minM = min(fixAxis($this->meridian1), fixAxis($this->meridian2) );
		return ($minM >= 30 && $minM < 60) ? 1:0 ;
	}
	
	public function isSpherical () {
		return (abs ($this->num1->dVal() - $this->num2->dVal() ) < .125) ? 1: 0;
	}
	
	public function toricType() {
		if ($this->isWTR() == 1) return "WTR";
		if ($this->isATR() == 1) return "ATR";
		if ($this->isOblique() == 1) return "Oblique";
		if ($this->isSpherical() == 1) return "Spherical";
	}
	
	//just the dioptric value
	public function cornealCyl ($pretty = 0, $unit = 0) {
		$r = abs($this->num1->dVal() - $this->num2->dVal() );
		$post = $unit ? " D": "";
		return $pretty ? numToDiopterString($r, 0, $this->roundD) . $post :$r;
	}
	
	//just the axis - should correspond with the minus cyl Rx axis
	public function cornealCylAxis () {
		if ($this->isSpherical() ) return null;
		if ($this->vertPwr() > $this->horizPwr() ) return $this->horizMeridian();
		return $this->vertMeridian();
	}
	
	
	public function cornealCylAndAxis ($plus = 0) {
		$pwr = $this->cornealCyl (1, 1);
		if ($plus) {
			return  "+" . $pwr . " x " . numToAxisString (fixAxis($this->cornealCylAxis() +90) );
		}
		return "-" . $pwr . " x " . numToAxisString ($this->cornealCylAxis() );
	}
		
	

} // end of class

?>
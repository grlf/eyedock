<?php

/**
 *
 * KvalObj class
 * store keratometry value data and perform calculations 
 *
 * @author Todd Zarwell
 * @copyright 2011 eyedock.com
 *
 */
 
// ini_set('display_errors', 1); 
//ini_set('log_errors', 1); 
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
//error_reporting(E_ALL);
 
 include_once "RxHelper.php";
 
 
class KvalObject {
	public $val;
	public $roundD = 0.125;
	public $roundMM = 0.01;
	
 	public function __construct($v = 0) { 
    	if (is_numeric($v) && $v > 0) {
    		$this->val = $v;
    	} else {
    		$this->val = null;
    	}
    } 
 	
 	//getters and setters

	public function setRoundD($x) { 
		if ($x > 0 && $x < 1) $this->round = $x; 
	} 
	public function setRoundMM($x) { 
		if ($x > 0 && $x <= .001) $this->round = $x; 
	} 

	public function mmVal() { 
		if ($this->isMM() ) return $this->val;
		if ($this->isD() ) return (337.5 / ($this->val) );
		return null;
	 } 
	public function dVal() { 		
		if ($this->isD() ) return $this->val;
		if ($this->isMM() ) return (337.5 / ($this->val) );
		return null;
	 } 

	public function cylType() {
		$v = $this->val;
		if ( $v < 16  && $v > 0) return "mm";
		if ($v >20 && $v < 75) return "D";
		return null; 
	}
	public function isD() { 
		if ($this->cylType()  == "D") return 1;
		return 0;
	} 

	public function isMM() {
		if ($this->cylType() == "mm") return 1;
		return 0; 
	} 
	
	
	public function isValidK () {
		if (!$this->val) return 0;
		if ($this->isD() || $this->isMM() ) return 1;
		return 0;
	}
	
	//flexible. can return d val, mm val, pretty or now, unit or not
	public function toString ($pretty = 0, $unit = 0, $mm = 0) {
		if (!$pretty) {
			return $mm ? $this->mmval() : $this->dVal();
		} else {
			if ($mm) return $this->prettyStringMM($unit);
			return $this->prettyStringD($unit);
		}
		return null;
	}


	public function prettyString ($unit = 0) {
		if ($this->isD() ) return $this->prettyStringD($unit);
		if ($this->isMM() ) return $this->prettyStringMM($unit);
		return null;
	}


	 public function prettyStringD ($unit = 0) {
		$unit = ($unit)?" D":"";
		return numToDiopterString ($this->dVal(), 0, $this->roundD) . $unit;
	}
	
	public function prettyStringMM ($unit = 0) {
		$unit = ($unit)?" mm":"";
		$numberString =  "";
	
		$roundTo = 1 / $this->roundMM;
	
		$aNum = round($roundTo * $this->mmVal() ) / $roundTo;
	
		$numberString = sprintf("%.2f", $aNum);
	
		return $numberString . $unit;
	}

	
	

} // end of class

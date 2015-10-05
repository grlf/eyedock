<?php 

	// takes a refraction and returns and optical cross
	// also, if vertexing, returns a vertexed cross
	// also, if spherical lens desired, return spherical equivalent
	

// 	ini_set('display_errors', 1); 
// ini_set('log_errors', 1); 
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
// error_reporting(E_ALL);


	require_once("opticalcrossfunc.php");
	
	if (! isset($_REQUEST['rxString']) ) echo "Please supply a valid refraction";

	$sphEquiv = !isset($_REQUEST['doSphEquiv'] )?0:$_REQUEST['doSphEquiv'];
	$doVertex = !isset($_REQUEST['doVertex'] )?1:$_REQUEST['doVertex'];
	
	
	//if not requesting vertexing then this is a contact
	if ($doVertex == 0) {
		$clCross['rxString'] = $_REQUEST['rxString'];
		$clCross['label'] = 3;
		$clCross['bgImage'] = !isset($_REQUEST['bgImage']) ? "lens" : $_REQUEST['bgImage'];
		$clCross['doVertex'] = null;
		//$clCross['doMinusCyl'] = 1;
		$clCross['header'] = "the desired CL";
		echo makeCrossDiv (getCrossFromParams ($clCross) );
	}

	//if requestion vertexing show the refraction and the vertexed power
	if ($doVertex == 1) {
		
		//the refraction
		$mrCross['rxString'] = $_REQUEST['rxString'];
		$mrCross['label'] = 3;
		$mrCross['bgImage'] = !isset($_REQUEST['bgImage']) ? "spherelens" : $_REQUEST['bgImage'];
		$mrCross['doVertex'] = null;
		$mrCross['header'] = "refraction";
		echo makeCrossDiv (getCrossFromParams ($mrCross) );
	
		//the vertexted power
		$lensCross['rxString'] = $_REQUEST['rxString'];
		$lensCross['label'] = 1;
		//$lensCross['doMinusCyl'] = 1;
		$lensCross['bgImage'] = !isset($_REQUEST['bgImage']) ? "lens" : $_REQUEST['bgImage'];
		$lensCross['doVertex'] = 1;
		$lensCross['header'] = "the ideal CL";
		echo makeCrossDiv (getCrossFromParams ($lensCross) );
	}
	
	//if the sph equiv is wanted show that too...
	if ($sphEquiv == 1) {
		$seLens['rxString'] = $_REQUEST['rxString'];
		$seLens['label'] = 1;
		$seLens['bgImage'] = !isset($_REQUEST['bgImage']) ? "lens" : $_REQUEST['bgImage'];
		$seLens['doVertex'] = $doVertex;
		$seLens['header'] = "best spherical CL";
		$seLens['doSphEquiv'] = 1;
		echo makeCrossDiv (getCrossFromParams ($seLens) );
	}
	

function makeCrossDiv ($crosshtml) {
	$html = "<div style='display:inline-block; margin: 0 35px 0 0; vertical-align: top '>" . $crosshtml . "</div>";
	return $html;
}
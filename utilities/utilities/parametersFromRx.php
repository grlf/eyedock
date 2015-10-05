<?php


require_once(JPATH_ROOT . DS . '/utilities/phpClasses/RxObject.php' );
require_once(JPATH_ROOT . DS . '/utilities/phpClasses/CLHelper.php' );
require_once(JPATH_ROOT . DS . '/utilities/phpClasses/RxHelper.php' );

// takes a refraction and a toric value and returns a sph, cyl, & oblique params
//if toric is set to "false" it is assumed the user wanted a spherical lens and one is the sph equiv is calculated

function getParametersFromRx ($refraction, $toric, $vertex = null){
	
	$rx = rxStringBreaker($refraction);
	
	if ($rx->isValidRx() !=1 ) return null;
	
	if ($vertex === 1) $rx = $rx->diffVertex(0);
	
	$params[sph]  = $rx->sphM();
	$params[cylinder] = $rx->cylM();

	
	if ($toric === 0) { 
		$params[sph] = $params[sph] + (.5 * $params[cylinder]);
		//$params[toric] = 0;
	} else if ($params[cylinder] < -0.75) {
		if ($rx->isOblique() == 1) $params[oblique] = 1;
		 $params[toric] = 1;		
	}

	
	return $params;
	
	
}

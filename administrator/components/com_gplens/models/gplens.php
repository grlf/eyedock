<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class GplensModelGplens extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '../../../components/com_gplens/imgpdf/images/';
	const LENSPDFURL = 'http://www.eyedock.com/components/com_gplens/imgpdf/pdfs/';

	function addNewLens($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['aliases']))
			$data['aliases'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['materialtext']))
			$data['materialtext'] = '';
		if(empty($data['designcategory']))
			$data['designcategory'] = 'NULL';
		if(empty($data['subcategory']))
			$data['subcategory'] = 'NULL';
		if(empty($data['addpower']))
			$data['addpower'] = '';
		if(empty($data['diameter']))
			$data['diameter'] = '';
		if(empty($data['basecurve']))
			$data['basecurve'] = '';
		if(empty($data['power']))
			$data['power'] = '';
		if(empty($data['centerthickness']))
			$data['centerthickness'] = '';
		if(empty($data['opticzone']))
			$data['opticzone'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		if(empty($data['cost']))
			$data['cost'] = '';/*
		if(empty($data['discontinued']))
			$data['discontinued'] = 'NULL';
		if(empty($data['display']))
			$data['display'] = 'NULL';	*/
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO rgpLenses (name, aliases, rgpCompanyID, materialText, designCategoryID, subcategoryID, addPower, diameter, baseCurve, power, centerThickness, opticZone, url, pdf, image, otherInfo, cost, discontinued, display, updated) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['aliases']) . '", 
			' . $data['company'] . ', 
			"' . addslashes($data['materialtext']) . '", 
			' . $data['designcategory'] . ', 
			' . $data['subcategory'] . ', 
			"' . addslashes($data['addpower']) . '", 
			"' . addslashes($data['diameter']) . '", 
			"' . addslashes($data['basecurve']) . '", 
			"' . addslashes($data['power']) . '", 
			"' . addslashes($data['centerthickness']) . '", 
			"' . addslashes($data['opticzone']) . '", 
			"' . addslashes($data['url']) . '", 
			"",
			"", 
			"' . addslashes($data['otherinfo']) . '", 
			"' . addslashes($data['cost']) . '", 
			' . $data['discontinued'] . ', 
			' . $data['display'] . ', 
			(SELECT NOW())
			)';
				
		$resultarr[] = $mysqli->query($query);
		$newid = $mysqli->insert_id;
		
			if(!empty($_FILES['newimage']['name'])) {

				$info = pathinfo($_FILES['newimage']['name']);
				$extension = '.' . $info['extension'];
				$newname = $newid . $extension;
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
				$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'components/com_gplens/models/images/';
				$target = $uploadsDirectory . $newname;
				if(move_uploaded_file($_FILES['newimage']['tmp_name'], $target)) {
					$resultarr[] = true;
				}
				
				$query = 'UPDATE rgpLenses SET image = "' . $newname . '" WHERE tid = ' . $newid; 
				$resultarr[] = $mysqli->query($query);
					
			}
			
			if(!empty($_FILES['newpdf']['name'])) {

				$info = pathinfo($_FILES['newpdf']['name']);
				$extension = '.' . $info['extension'];
				$newname = $newid . $extension;
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
				$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'components/com_gplens/models/pdfs/';
				$target = $uploadsDirectory . $newname;
				if(move_uploaded_file($_FILES['newpdf']['tmp_name'], $target)) {
					$resultarr[] = true;
				}
				
				$query = 'UPDATE rgpLenses SET pdf = "' . $newname . '" WHERE tid = ' . $newid; 
				$resultarr[] = $mysqli->query($query);
					
			}
			
		return (!in_array(false, $resultarr)) ? true : false;
	
	}
	
	function updateLens($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['aliases']))
			$data['aliases'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['materialtext']))
			$data['materialtext'] = '';
		if(empty($data['designcategory']))
			$data['designcategory'] = 'NULL';
		if(empty($data['subcategory']))
			$data['subcategory'] = 'NULL';
		if(empty($data['addpower']))
			$data['addpower'] = '';
		if(empty($data['diameter']))
			$data['diameter'] = '';
		if(empty($data['basecurve']))
			$data['basecurve'] = '';
		if(empty($data['power']))
			$data['power'] = '';
		if(empty($data['centerthickness']))
			$data['centerthickness'] = '';
		if(empty($data['opticzone']))
			$data['opticzone'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		/*
		if(empty($data['pdf']))
			$data['pdf'] = '';
		*/
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		if(empty($data['cost']))
			$data['cost'] = '';/*
		if(empty($data['discontinued']))
			$data['discontinued'] = 'NULL';
		if(empty($data['display']))
			$data['display'] = 'NULL';*/
		
	
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE rgpLenses SET name = "' . addslashes($data['name']) . '", aliases = "' . addslashes($data['aliases']) . '", rgpCompanyID = ' . $data['company'] . ', materialText = "' . addslashes($data['materialtext']) . '", designCategoryID = ' . $data['designcategory'] . ', subcategoryID = ' . $data['subcategory'] . ', addPower = "' . addslashes($data['addpower']) . '", diameter = "' . addslashes($data['diameter']) . '", baseCurve = "' . addslashes($data['basecurve']) . '", power = "' . addslashes($data['power']) . '", centerThickness = "' . addslashes($data['centerthickness']) . '", opticZone = "' . addslashes($data['opticzone']) . '", url = "' . addslashes($data['url']) . '", otherInfo = "' . addslashes($data['otherinfo']) . '", cost = "' . addslashes($data['cost']) . '", discontinued = ' . $data['discontinued'] . ', display = ' . $data['display'] . ', updated = (SELECT NOW()) where tid = ' . $data['id'];
			
		$resultarr[] = $mysqli->query($query);
		
		for($i=0;$i<count($data['existinglensmaterials']);$i++) {
		
			if (isset($data['existinglensmaterials'][$i]["'savechanges'"]) && !isset($data['existinglensmaterials'][$i]["'delete'"])) {
			
				if($data['existinglensmaterials'][$i]["'material'"] == '-') {
					$data['existinglensmaterials'][$i]["'material'"] = 'NULL';
				}
			
				if($data['existinglensmaterials'][$i]["'materialcompany'"] == '-') {
					$data['existinglensmaterials'][$i]["'materialcompany'"] = 'NULL';
				}
			
				$anymat = ', anyMaterial = 0';
				if (isset($data['existinglensmaterials'][$i]["'anymaterial'"])) {
					$anymat = ', anyMaterial = 1';
				}
			
				$query = 'UPDATE rgpLens_materials SET materialID = ' . $data['existinglensmaterials'][$i]["'material'"] . ', materialCompanyID = ' . $data['existinglensmaterials'][$i]["'materialcompany'"] . $anymat . ' where tid = ' . $data['existinglensmaterials'][$i]["'tid'"];
			
				$resultarr[] = $mysqli->query($query);
		
			//	echo 'QUERY: ' . $query . '<br />RESULT: ' . $result . '<br /><br /><br />';
	
			}
			
			if(isset($data['existinglensmaterials'][$i]["'delete'"])) {
			
				$query = 'DELETE from rgpLens_materials where tid = ' . $data['existinglensmaterials'][$i]["'tid'"];
			
				$resultarr[] = $mysqli->query($query);
		
			//	echo 'QUERY: ' . $query . '<br />RESULT: ' . $result . '<br /><br /><br />';
			
			}
			
		
		}
		
		for($i=0;$i<count($data['newlensmaterials']);$i++) {
		
			if (isset($data['newlensmaterials'][$i]["'create'"])) {
			
				if($data['newlensmaterials'][$i]["'material'"] == '-') {
					$data['newlensmaterials'][$i]["'material'"] = 'NULL';
				}
			
				if($data['newlensmaterials'][$i]["'materialcompany'"] == '-') {
					$data['newlensmaterials'][$i]["'materialcompany'"] = 'NULL';
				}
			
				$anymat = (isset($data['newlensmaterials'][$i]["'anymaterial'"])) ? 1 : 0;
			
				$query = 'INSERT into rgpLens_materials (lensID, materialID, materialCompanyID, anyMaterial) VALUES (
					' . $data['id'] . ',
					' . $data['newlensmaterials'][$i]["'material'"] . ',
					' . $data['newlensmaterials'][$i]["'materialcompany'"] . ',
					' . $anymat . '
				)';
			
				$resultarr[] = $mysqli->query($query);
		
			//	echo 'QUERY: ' . $query . '<br />RESULT: ' . $result . '<br /><br /><br />';
		
			}
		
		}
		
			if(isset($_FILES['newimage'])) {

				$info = pathinfo($_FILES['newimage']['name']);
				$extension = '.' . $info['extension'];
				$newname = $data['id'] . $extension;
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
				$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'components/com_gplens/models/images/';
				$target = $uploadsDirectory . $newname;
				if(move_uploaded_file($_FILES['newimage']['tmp_name'], $target)) {
					$resultarr[] = true;
				
					$query = 'UPDATE rgpLenses SET image = "' . $newname . '" WHERE tid = ' . $data['id']; 
					$resultarr[] = $mysqli->query($query);
				}
					
			}
			
			if(isset($data['deleteimage'])) {
			
				$query = 'UPDATE rgpLenses SET image = "" WHERE tid = ' . $data['id']; 
				$resultarr[] = $mysqli->query($query);				
			
			}
		
			if(isset($_FILES['newpdf'])) {

				$info = pathinfo($_FILES['newpdf']['name']);
				$extension = '.' . $info['extension'];
				$newname = $data['id'] . $extension;
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
				$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'components/com_gplens/models/pdfs/';
				$target = $uploadsDirectory . $newname;
				if(move_uploaded_file($_FILES['newpdf']['tmp_name'], $target)) {
					$resultarr[] = true;
				
					$query = 'UPDATE rgpLenses SET pdf = "' . $newname . '" WHERE tid = ' . $data['id']; 
					$resultarr[] = $mysqli->query($query);
				}
					
			}
			
			if(isset($data['deletepdf'])) {
			
				$query = 'UPDATE rgpLenses SET pdf = "" WHERE tid = ' . $data['id']; 
				$resultarr[] = $mysqli->query($query);				
			
			}
		
		return (!in_array(false, $resultarr)) ? true : false;
	
	}
	
	function deletelens($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from rgpLenses where tid = ' . $id;
			
	//	echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}
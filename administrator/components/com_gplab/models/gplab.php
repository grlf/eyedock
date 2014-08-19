<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class GplabModelGplab extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';
	const LENSPDFURL = 'http://www.eyedock.com/modules/Lenses/pnpdf/';

	function addNewLab($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['email']))
			$data['email'] = '';
		if(empty($data['address']))
			$data['address'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO rgpLab (name, phone, url, email, address, display, comments) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['phone']) . '", 
			"' . addslashes($data['url']) . '", 
			"' . addslashes($data['email']) . '", 
			"' . addslashes($data['address']) . '", 
			' . $data['display'] . ', 
			"' . addslashes($data['comments']) . '"
			)';
			
		$resultarr[] = $mysqli->query($query);
		$newid = $mysqli->insert_id;
		
			if(isset($_FILES['newimage'])) {

				$info = pathinfo($_FILES['newimage']['name']);
				$extension = '.' . $info['extension'];
				$newname = $newid . $extension;
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
				$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'components/com_gplab/models/images/';
				$target = $uploadsDirectory . $newname;
				if(move_uploaded_file($_FILES['newimage']['tmp_name'], $target)) {
					$resultarr[] = true;
				}
				
				$query = 'UPDATE rgpLab SET logoImage = "' . $newname . '" WHERE tid = ' . $newid; 
				$resultarr[] = $mysqli->query($query);
				
			}
		
		return (!in_array(false, $resultarr)) ? true : false;
		
	}

	function updateLab($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['phone']))
			$data['phone'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['email']))
			$data['email'] = '';
		if(empty($data['address']))
			$data['address'] = '';
		if(empty($data['comments']))
			$data['comments'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE rgpLab SET name = "' . addslashes($data['name']) . '", phone = "' . addslashes($data['phone']) . '", url = "' . addslashes($data['url']) . '", email = "' . addslashes($data['email']) . '", address = "' . addslashes($data['address']) . '", display = ' . $data['display'] . ', comments = "' . addslashes($data['comments']) . '" where tid = ' . $data['id'];
			
		$resultarr[] =  $mysqli->query($query);
			
			if(isset($_FILES['newimage'])) {

				$info = pathinfo($_FILES['newimage']['name']);
				$extension = '.' . $info['extension'];
				$newname = $data['id'] . $extension;
				$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
				$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'components/com_gplab/models/images/';
				$target = $uploadsDirectory . $newname;
				echo $target;
				echo 'test';
				if(move_uploaded_file($_FILES['newimage']['tmp_name'], $target)) {
					$resultarr[] = true;
				
					$query = 'UPDATE rgpLab SET logoImage = "' . $newname . '" WHERE tid = ' . $data['id']; 
					$resultarr[] = $mysqli->query($query);
				}
					
			}
			
			if(isset($data['deleteimage'])) {
			
				$query = 'UPDATE rgpLab SET logoImage = "" WHERE tid = ' . $data['id']; 
				$resultarr[] = $mysqli->query($query);				
			
			}

	
		return (!in_array(false, $resultarr)) ? true : false;
	
	}
	
	function deleteLab($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from rgpLab where tid = ' . $id;
			
		echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}
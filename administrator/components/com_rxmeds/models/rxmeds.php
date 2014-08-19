<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class RxmedsModelRxmeds extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewMedication($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['type1']))
			$data['type1'] = '';
		if(empty($data['type2']))
			$data['type2'] = '';
		if(empty($data['preg']))
			$data['preg'] = '';
		if(empty($data['schedule']))
			$data['schedule'] = '';
		if(empty($data['generic']))
			$data['generic'] = '';
		if(empty($data['image1']))
			$data['image1'] = '';
		if(empty($data['image2']))
			$data['image2'] = '';
		if(empty($data['dose']))
			$data['dose'] = '';
		if(empty($data['peds']))
			$data['peds'] = '';
		if(empty($data['pedstext']))
			$data['pedstext'] = '';
		if(empty($data['nurse']))
			$data['nurse'] = '';
		if(empty($data['preserve1']))
			$data['preserve1'] = 'NULL';
		if(empty($data['preserve2']))
			$data['preserve2'] = 'NULL';
		if(empty($data['comments']))
			$data['comments'] = '';
		if(empty($data['pdf']))
			$data['pdf'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['display']))
			$data['display'] = '';
		if(empty($data['conc1']))
			$data['conc1'] = '';
		if(empty($data['chem1']))
			$data['chem1'] = 'NULL';
		if(empty($data['moa1']))
			$data['moa1'] = 'NULL';
		if(empty($data['conc2']))
			$data['conc2'] = '';
		if(empty($data['chem2']))
			$data['chem2'] = 'NULL';
		if(empty($data['moa2']))
			$data['moa2'] = 'NULL';
		if(empty($data['conc3']))
			$data['conc3'] = '';
		if(empty($data['chem3']))
			$data['chem3'] = 'NULL';
		if(empty($data['moa3']))
			$data['moa3'] = 'NULL';
		if(empty($data['conc4']))
			$data['conc4'] = '';
		if(empty($data['chem4']))
			$data['chem4'] = 'NULL';
		if(empty($data['moa4']))
			$data['moa4'] = 'NULL';
		if(empty($data['form1']))
			$data['form1'] = '';
		if(empty($data['size1']))
			$data['size1'] = '';
		if(empty($data['cost1']))
			$data['cost1'] = '';
		if(empty($data['form2']))
			$data['form2'] = '';
		if(empty($data['size2']))
			$data['size2'] = '';
		if(empty($data['cost2']))
			$data['cost2'] = '';
		if(empty($data['form3']))
			$data['form3'] = '';
		if(empty($data['size3']))
			$data['size3'] = '';
		if(empty($data['cost3']))
			$data['cost3'] = '';
		if(empty($data['form4']))
			$data['form4'] = '';
		if(empty($data['size4']))
			$data['size4'] = '';
		if(empty($data['cost4']))
			$data['cost4'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_rx_meds (pn_trade, pn_comp_id, pn_medType1, pn_medType2, pn_preg, pn_schedule, pn_generic, pn_image1, pn_image2, pn_dose, pn_peds, pn_ped_text, pn_nurse, pn_pres_id1, pn_pres_id2, pn_comments, pn_rxInfo, pn_med_url, pn_updated, pn_display, pn_conc1, pn_chem_id1, pn_moa_id1, pn_conc2, pn_chem_id2, pn_moa_id2, pn_conc3, pn_chem_id3, pn_moa_id3, pn_conc4, pn_chem_id4, pn_moa_id4, pn_form1, pn_size1, pn_cost1, pn_form2, pn_size2, pn_cost2, pn_form3, pn_size3, pn_cost3, pn_form4, pn_size4, pn_cost4) VALUES (
			"' . addslashes($data['name']) . '", 
			' . $data['company'] . ', 
			"' . addslashes($data['type1']) . '", 
			"' . addslashes($data['type2']) . '", 
			"' . addslashes($data['preg']) . '", 
			"' . addslashes($data['schedule']) . '", 
			"' . addslashes($data['generic']) . '", 
			"' . addslashes($data['image1']) . '", 
			"' . addslashes($data['image2']) . '", 
			"' . addslashes($data['dose']) . '", 
			"' . addslashes($data['peds']) . '", 
			"' . addslashes($data['pedtext']) . '", 
			"' . addslashes($data['nurse']) . '", 
			' . $data['preserve1'] . ', 
			' . $data['preserve2'] . ', 
			"' . addslashes($data['comments']) . '", 
			"' . addslashes($data['pdf']) . '", 
			"' . addslashes($data['url']) . '", 
			(SELECT NOW()) , 
			"' . addslashes($data['display']) . '", 
			"' . addslashes($data['conc1']) . '", 
			' . $data['chem1'] . ', 
			' . $data['moa1'] . ', 
			"' . addslashes($data['conc2']) . '", 
			' . $data['chem2'] . ', 
			' . $data['moa2'] . ', 
			"' . addslashes($data['conc3']) . '", 
			' . $data['chem3'] . ', 
			' . $data['moa3'] . ', 
			"' . addslashes($data['conc4']) . '", 
			' . $data['chem4'] . ', 
			' . $data['moa4'] . ', 
			"' . addslashes($data['form1']) . '", 
			"' . addslashes($data['size1']) . '", 
			"' . addslashes($data['cost1']) . '", 
			"' . addslashes($data['form2']) . '", 
			"' . addslashes($data['size2']) . '", 
			"' . addslashes($data['cost2']) . '", 
			"' . addslashes($data['form3']) . '", 
			"' . addslashes($data['size3']) . '", 
			"' . addslashes($data['cost3']) . '", 
			"' . addslashes($data['form4']) . '", 
			"' . addslashes($data['size4']) . '", 
			"' . addslashes($data['cost4']) . '"
			)';
			
	//	echo $query;

		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateMedication($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['type1']))
			$data['type1'] = '';
		if(empty($data['type2']))
			$data['type2'] = '';
		if(empty($data['preg']))
			$data['preg'] = '';
		if(empty($data['schedule']))
			$data['schedule'] = '';
		if(empty($data['generic']))
			$data['generic'] = '';
		if(empty($data['image1']))
			$data['image1'] = '';
		if(empty($data['image2']))
			$data['image2'] = '';
		if(empty($data['dose']))
			$data['dose'] = '';
		if(empty($data['peds']))
			$data['peds'] = '';
		if(empty($data['pedstext']))
			$data['pedstext'] = '';
		if(empty($data['nurse']))
			$data['nurse'] = '';
		if(empty($data['preserve1']))
			$data['preserve1'] = 'NULL';
		if(empty($data['preserve2']))
			$data['preserve2'] = 'NULL';
		if(empty($data['comments']))
			$data['comments'] = '';
		if(empty($data['pdf']))
			$data['pdf'] = '';
		if(empty($data['url']))
			$data['url'] = '';
		if(empty($data['display']))
			$data['display'] = '';
		if(empty($data['conc1']))
			$data['conc1'] = '';
		if(empty($data['chem1']))
			$data['chem1'] = 'NULL';
		if(empty($data['moa1']))
			$data['moa1'] = 'NULL';
		if(empty($data['conc2']))
			$data['conc2'] = '';
		if(empty($data['chem2']))
			$data['chem2'] = 'NULL';
		if(empty($data['moa2']))
			$data['moa2'] = 'NULL';
		if(empty($data['conc3']))
			$data['conc3'] = '';
		if(empty($data['chem3']))
			$data['chem3'] = 'NULL';
		if(empty($data['moa3']))
			$data['moa3'] = 'NULL';
		if(empty($data['conc4']))
			$data['conc4'] = '';
		if(empty($data['chem4']))
			$data['chem4'] = 'NULL';
		if(empty($data['moa4']))
			$data['moa4'] = 'NULL';
		if(empty($data['form1']))
			$data['form1'] = '';
		if(empty($data['size1']))
			$data['size1'] = '';
		if(empty($data['cost1']))
			$data['cost1'] = '';
		if(empty($data['form2']))
			$data['form2'] = '';
		if(empty($data['size2']))
			$data['size2'] = '';
		if(empty($data['cost2']))
			$data['cost2'] = '';
		if(empty($data['form3']))
			$data['form3'] = '';
		if(empty($data['size3']))
			$data['size3'] = '';
		if(empty($data['cost3']))
			$data['cost3'] = '';
		if(empty($data['form4']))
			$data['form4'] = '';
		if(empty($data['size4']))
			$data['size4'] = '';
		if(empty($data['cost4']))
			$data['cost4'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'UPDATE pn_rx_meds SET pn_trade = "' . addslashes($data['name']) . '", pn_comp_id = ' . $data['company'] . ', pn_medType1 = "' . addslashes($data['type1']) . '", pn_medType2 = "' . addslashes($data['type2']) . '", pn_preg = "' . addslashes($data['preg']) . '", pn_schedule = "' . addslashes($data['schedule']) . '", pn_generic = "' . addslashes($data['generic']) . '", pn_image1 = "' . addslashes($data['image1']) . '", pn_image2 = "' . addslashes($data['image2']) . '", pn_dose = "' . addslashes($data['dose']) . '", pn_peds = "' . addslashes($data['peds']) . '", pn_ped_text = "' . addslashes($data['pedstext']) . '", pn_nurse = "' . addslashes($data['nurse']) . '", pn_pres_id1 = ' . $data['preserve1'] . ', pn_pres_id2 = ' . $data['preserve2'] . ', pn_comments = "' . addslashes($data['comments']) . '", pn_rxInfo = "' . addslashes($data['pdf']) . '", pn_med_url = "' . addslashes($data['url']) . '", pn_updated = "' . date('m/d/Y') . '", pn_display = "' . addslashes($data['display']) . '", pn_conc1 = "' . addslashes($data['conc1']) . '", pn_chem_id1 = ' . $data['chem1'] . ', pn_moa_id1 = ' . $data['moa1'] . ', pn_conc2 = "' . addslashes($data['conc2']) . '", pn_chem_id2 = ' . $data['chem2'] . ', pn_moa_id2 = ' . $data['moa2'] . ', pn_conc3 = "' . addslashes($data['conc3']) . '", pn_chem_id3 = ' . $data['chem3'] . ', pn_moa_id3 = ' . $data['moa3'] . ', pn_conc4 = "' . addslashes($data['conc4']) . '", pn_chem_id4 = ' . $data['chem4'] . ', pn_moa_id4 = ' . $data['moa4'] . ', pn_form1 = "' . $data['form1'] . '", pn_size1 = "' . addslashes($data['size1']) . '", pn_cost1 = "' . addslashes($data['cost1']) . '", pn_form2 = "' . addslashes($data['form2']) . '", pn_size2 = "' . addslashes($data['size2']) . '", pn_cost2 = "' . addslashes($data['cost2']) . '", pn_form3 = "' . addslashes($data['form3']) . '", pn_size3 = "' . addslashes($data['size3']) . '", pn_cost3 = "' . addslashes($data['cost3']) . '", pn_form4 = "' . addslashes($data['form4']) . '", pn_size4 = "' . addslashes($data['size4']) . '", pn_cost4 = "' . addslashes($data['cost4']) . '" where pn_med_id = ' . $data['id'];
			
		//echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteMedication($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_rx_meds where pn_med_id = ' . $id;
			
		//echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}

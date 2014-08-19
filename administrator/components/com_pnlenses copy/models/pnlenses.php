<?php

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class PnlensesModelPnlenses extends JModel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = '';
	const LENSPDFURL = '';

	function addNewLens($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['aliases']))
			$data['aliases'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['polymer']))
			$data['polymer'] = 'NULL';
		if(empty($data['visitint']))
			$data['visitint'] = '0';
		if(empty($data['ew']))
			$data['ew'] = '0';
		if(empty($data['centerthickness']))
			$data['centerthickness'] = 'NULL';
		if(empty($data['dk']))
			$data['dk'] = 'NULL';
		if(empty($data['opticzone']))
			$data['opticzone'] = '';
		if(empty($data['processtext']))
			$data['processtext'] = '';
		if(empty($data['processsimple']))
			$data['processsimple'] = '';
		if(empty($data['qty']))
			$data['qty'] = '';
		if(empty($data['replacesimple']))
			$data['replacesimple'] = 'NULL';
		if(empty($data['replacetext']))
			$data['replacetext'] = '';
		if(empty($data['wear']))
			$data['wear'] = '';
		if(empty($data['price']))
			$data['price'] = '';
		if(empty($data['markings']))
			$data['markings'] = '';
		if(empty($data['fittingguide']))
			$data['fittingguide'] = '';
		if(empty($data['website']))
			$data['website'] = '';
		if(empty($data['image']))
			$data['image'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		if(empty($data['discontinued']))
			$data['discontinued'] = '0';
		if(empty($data['display']))
			$data['display'] = '1';
		if(empty($data['bcsimple']))
			$data['bcsimple'] = '';
		if(empty($data['bcall']))
			$data['bcall'] = '';
		if(empty($data['diamall']))
			$data['diamall'] = '';
		if(empty($data['maxplus']))
			$data['maxplus'] = 'NULL';
		if(empty($data['maxminus']))
			$data['maxminus'] = 'NULL';
		if(empty($data['maxdiam']))
			$data['maxdiam'] = 'NULL';
		if(empty($data['mindiam']))
			$data['mindiam'] = 'NULL';
		if(empty($data['diameter1']))
			$data['diameter1'] = '';
		if(empty($data['diameter2']))
			$data['diameter2'] = '';
		if(empty($data['diameter3']))
			$data['diameter3'] = '';
		if(empty($data['basecurves1']))
			$data['basecurves1'] = '';
		if(empty($data['basecurves2']))
			$data['basecurves2'] = '';
		if(empty($data['basecurves3']))
			$data['basecurves3'] = '';
		if(empty($data['powers1']))
			$data['powers1'] = '';
		if(empty($data['powers2']))
			$data['powers2'] = '';
		if(empty($data['powers3']))
			$data['powers3'] = '';
		if(empty($data['sphnotes']))
			$data['sphnotes'] = '';
		if(empty($data['toric']))
			$data['toric'] = '0';
		if(empty($data['torictype']))
			$data['torictype'] = '';
		if(empty($data['torictypesimple']))
			$data['torictypesimple'] = '';
		if(empty($data['cylpower']))
			$data['cylpower'] = '';
		if(empty($data['maxcylpower']))
			$data['maxcylpower'] = 'NULL';
		if(empty($data['cylaxis']))
			$data['cylaxis'] = '';
		if(empty($data['cylaxissteps']))
			$data['cylaxissteps'] = 'NULL';
		if(empty($data['oblique']))
			$data['oblique'] = 'NULL';
		if(empty($data['cylnotes']))
			$data['cylnotes'] = '';
		if(empty($data['bifocal']))
			$data['bifocal'] = '0';
		if(empty($data['bifocaltype']))
			$data['bifocaltype'] = '';
		if(empty($data['addtext']))
			$data['addtext'] = '';
		if(empty($data['maxadd']))
			$data['maxadd'] = 'NULL';
		if(empty($data['cosmetic']))
			$data['cosmetic'] = '0';
		if(empty($data['enhnames']))
			$data['enhnames'] = '';
		if(empty($data['enhsimplenames']))
			$data['enhsimplenames'] = '';
		if(empty($data['opaquenames']))
			$data['opaquenames'] = '';
		if(empty($data['opaquesimplenames']))
			$data['opaquesimplenames'] = '';
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'INSERT INTO pn_lenses (pn_name, pn_aliases, pn_comp_id, pn_poly_id, pn_visitint, pn_ew, pn_ct, pn_dk, pn_oz, pn_process_text, pn_process_simple, pn_qty, pn_replace_simple, pn_replace_text, pn_wear, pn_price, pn_markings, pn_fitting_guide, pn_website, pn_image, pn_other_info, pn_discontinued, pn_display, pn_redirect, pn_bc_simple, pn_bc_all, pn_diam_all, pn_max_plus, pn_max_minus, pn_max_diam, pn_min_diam, pn_diam_1, pn_base_curves_1, pn_powers_1, pn_diam_2, pn_base_curves_2, pn_powers_2, pn_diam_3, pn_base_curves_3, pn_powers_3, pn_sph_notes, pn_toric, pn_toric_type, pn_toric_type_simple, pn_cyl_power, pn_max_cyl_power, pn_cyl_axis, pn_cyl_axis_steps, pn_oblique, pn_cyl_notes, pn_bifocal, pn_bifocal_type, pn_add_text, pn_max_add, pn_cosmetic, pn_enh_names, pn_enh_names_simple, pn_opaque_names, pn_opaque_names_simple, pn_updated) VALUES (
			"' . addslashes($data['name']) . '", 
			"' . addslashes($data['aliases']) . '", 
			' . $data['company'] . ', 
			' . $data['polymer'] . ', 
			' . $data['visitint'] . ', 
			' . $data['ew'] . ', 
			' . $data['centerthickness'] . ', 
			' . $data['dk'] . ', 
			"' . addslashes($data['opticzone']) . '", 
			"' . addslashes($data['processtext']) . '", 
			"' . addslashes($data['processsimple']) . '", 
			"' . addslashes($data['qty']) . '", 
			' . $data['replacesimple'] . ', 
			"' . addslashes($data['replacetext']) . '", 
			"' . addslashes($data['wear']) . '", 
			"' . addslashes($data['price']) . '", 
			"' . addslashes($data['markings']) . '", 
			"' . addslashes($data['fittingguide']) . '", 
			"' . addslashes($data['website']) . '", 
			"' . addslashes($data['image']) . '", 
			"' . addslashes($data['otherinfo']) . '", 
			' . $data['discontinued'] . ', 
			' . $data['display'] . ', 
			"0",
			"' . addslashes($data['bcsimple']) . '", 
			"' . addslashes($data['bcall']) . '", 
			"' . addslashes($data['diamall']) . '", 
			' . $data['maxplus'] . ', 
			' . $data['maxminus'] . ', 
			' . $data['maxdiam'] . ', 
			' . $data['mindiam'] . ', 
			"' . addslashes($data['diameter1']) . '", 
			"' . addslashes($data['basecurves1']) . '", 
			"' . addslashes($data['powers1']) . '", 
			"' . addslashes($data['diameter2']) . '", 
			"' . addslashes($data['basecurves2']) . '", 
			"' . addslashes($data['powers2']) . '", 
			"' . addslashes($data['diameter3']) . '", 
			"' . addslashes($data['basecurves3']) . '", 
			"' . addslashes($data['powers3']) . '", 
			"' . addslashes($data['sphnotes']) . '", 
			' . $data['toric'] . ', 
			"' . addslashes($data['torictype']) . '", 
			"' . addslashes($data['torictypesimple']) . '", 
			"' . addslashes($data['cylpower']) . '", 
			' . $data['maxcylpower'] . ', 
			"' . addslashes($data['cylaxis']) . '", 
			' . $data['cylaxissteps'] . ', 
			' . $data['oblique'] . ', 
			"' . addslashes($data['cylnotes']) . '", 
			' . $data['bifocal'] . ', 
			"' . addslashes($data['bifocaltype']) . '", 
			"' . addslashes($data['addtext']) . '", 
			' . $data['maxadd'] . ', 
			' . $data['cosmetic'] . ', 
			"' . addslashes($data['enhnames']) . '", 
			"' . addslashes($data['enhsimplenames']) . '", 
			"' . addslashes($data['opaquenames']) . '", 
			"' . addslashes($data['opaquesimplenames']) . '", 
			(SELECT NOW())     
			)';
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function updateLens($data) {

		if(empty($data['name']))
			$data['name'] = '';
		if(empty($data['aliases']))
			$data['aliases'] = '';
		if(empty($data['company']))
			$data['company'] = 'NULL';
		if(empty($data['polymer']))
			$data['polymer'] = 'NULL';
		if(empty($data['visitint']))
			$data['visitint'] = '0';
		if(empty($data['ew']))
			$data['ew'] = '0';
		if(empty($data['centerthickness']))
			$data['centerthickness'] = 'NULL';
		if(empty($data['dk']))
			$data['dk'] = 'NULL';
		if(empty($data['opticzone']))
			$data['opticzone'] = '';
		if(empty($data['processtext']))
			$data['processtext'] = '';
		if(empty($data['processsimple']))
			$data['processsimple'] = '';
		if(empty($data['qty']))
			$data['qty'] = '';
		if(empty($data['replacesimple']))
			$data['replacesimple'] = 'NULL';
		if(empty($data['replacetext']))
			$data['replacetext'] = '';
		if(empty($data['wear']))
			$data['wear'] = '';
		if(empty($data['price']))
			$data['price'] = '';
		if(empty($data['markings']))
			$data['markings'] = '';
		if(empty($data['fittingguide']))
			$data['fittingguide'] = '';
		if(empty($data['website']))
			$data['website'] = '';
		if(empty($data['image']))
			$data['image'] = '';
		if(empty($data['otherinfo']))
			$data['otherinfo'] = '';
		if(empty($data['discontinued']))
			$data['discontinued'] = '0';
		if(empty($data['display']))
			$data['display'] = '1';
		if(empty($data['bcsimple']))
			$data['bcsimple'] = '';
		if(empty($data['bcall']))
			$data['bcall'] = '';
		if(empty($data['diamall']))
			$data['diamall'] = '';
		if(empty($data['maxplus']))
			$data['maxplus'] = 'NULL';
		if(empty($data['maxminus']))
			$data['maxminus'] = 'NULL';
		if(empty($data['maxdiam']))
			$data['maxdiam'] = 'NULL';
		if(empty($data['mindiam']))
			$data['mindiam'] = 'NULL';
		if(empty($data['diameter1']))
			$data['diameter1'] = '';
		if(empty($data['diameter2']))
			$data['diameter2'] = '';
		if(empty($data['diameter3']))
			$data['diameter3'] = '';
		if(empty($data['basecurves1']))
			$data['basecurves1'] = '';
		if(empty($data['basecurves2']))
			$data['basecurves2'] = '';
		if(empty($data['basecurves3']))
			$data['basecurves3'] = '';
		if(empty($data['powers1']))
			$data['powers1'] = '';
		if(empty($data['powers2']))
			$data['powers2'] = '';
		if(empty($data['powers3']))
			$data['powers3'] = '';
		if(empty($data['sphnotes']))
			$data['sphnotes'] = '';
		if(empty($data['toric']))
			$data['toric'] = '0';
		if(empty($data['torictype']))
			$data['torictype'] = '';
		if(empty($data['torictypesimple']))
			$data['torictypesimple'] = '';
		if(empty($data['cylpower']))
			$data['cylpower'] = '';
		if(empty($data['maxcylpower']))
			$data['maxcylpower'] = 'NULL';
		if(empty($data['cylaxis']))
			$data['cylaxis'] = '';
		if(empty($data['cylaxissteps']))
			$data['cylaxissteps'] = 'NULL';
		if(empty($data['oblique']))
			$data['oblique'] = 'NULL';
		if(empty($data['cylnotes']))
			$data['cylnotes'] = '';
		if(empty($data['bifocal']))
			$data['bifocal'] = '0';
		if(empty($data['bifocaltype']))
			$data['bifocaltype'] = '';
		if(empty($data['addtext']))
			$data['addtext'] = '';
		if(empty($data['maxadd']))
			$data['maxadd'] = 'NULL';
		if(empty($data['cosmetic']))
			$data['cosmetic'] = '0';
		if(empty($data['enhnames']))
			$data['enhnames'] = '';
		if(empty($data['enhsimplenames']))
			$data['enhsimplenames'] = '';
		if(empty($data['opaquenames']))
			$data['opaquenames'] = '';
		if(empty($data['opaquesimplenames']))
			$data['opaquesimplenames'] = '';
		if(is_array($data['bcsimple']))
			$data['bcsimple'] = implode(',',$data['bcsimple']);
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
$query = 'UPDATE pn_lenses SET pn_name = "' . addslashes($data['name']) . '", pn_aliases = "' . addslashes($data['aliases']) . '", pn_comp_id = ' . $data['company'] . ', pn_poly_id = ' . $data['polymer'] . ', pn_visitint = ' . $data['visitint'] . ', pn_ew = ' . $data['ew'] . ', pn_ct = ' . $data['centerthickness'] . ', pn_dk = "' . $data['dk'] . '", pn_oz = "' . addslashes($data['opticzone']) . '", pn_process_text = "' . addslashes($data['processtext']) . '", pn_process_simple = "' . addslashes($data['processsimple']) . '", pn_qty = "' . addslashes($data['qty']) . '", pn_replace_simple = ' . $data['replacesimple'] . ', pn_replace_text = "' . addslashes($data['replacetext']) . '", pn_wear = "' . addslashes($data['wear']) . '", pn_price = "' . addslashes($data['price']) . '", pn_markings = "' . addslashes($data['markings']) . '", pn_fitting_guide = "' . addslashes($data['fittingguide']) . '", pn_website = "' . addslashes($data['website']) . '", pn_image = "' . addslashes($data['image']) . '", pn_other_info = "' . addslashes($data['otherinfo']) . '", pn_discontinued = ' . $data['discontinued'] . ', pn_display = ' . $data['display'] . ', pn_bc_simple = "' . addslashes($data['bcsimple']) . '", pn_bc_all = "' . addslashes($data['bcall']) . '", pn_diam_all = "' . addslashes($data['diamall']) . '", pn_max_plus = ' . $data['maxplus'] . ', pn_max_minus = ' . $data['maxminus'] . ', pn_max_diam = ' . $data['maxdiam'] . ', pn_min_diam = ' . $data['mindiam'] . ', pn_diam_1 = "' . addslashes($data['diameter1']) . '", pn_base_curves_1 = "' . addslashes($data['basecurves1']) . '", pn_powers_1 = "' . addslashes($data['powers1']) . '", pn_diam_2 = "' . addslashes($data['diameter2']) . '", pn_base_curves_2 = "' . addslashes($data['basecurves2']) . '", pn_powers_2 = "' . addslashes($data['powers2']) . '", pn_diam_3 = "' . addslashes($data['diameter3']) . '", pn_base_curves_3 = "' . addslashes($data['basecurves3']) . '", pn_powers_3 = "' . addslashes($data['powers3']) . '", pn_sph_notes = "' . addslashes($data['sphnotes']) . '", pn_toric = ' . $data['toric'] . ', pn_toric_type = "' . addslashes($data['torictype']) . '", pn_toric_type_simple = "' . addslashes($data['torictypesimple']) . '", pn_cyl_power = "' . addslashes($data['cylpower']) . '", pn_max_cyl_power = ' . $data['maxcylpower'] . ', pn_cyl_axis = "' . addslashes($data['cylaxis']) . '", pn_cyl_axis_steps = ' . $data['cylaxissteps'] . ', pn_oblique = ' . $data['oblique'] . ', pn_cyl_notes = "' . addslashes($data['cylnotes']) . '", pn_bifocal = ' . $data['bifocal'] . ', pn_bifocal_type = "' . addslashes($data['bifocaltype']) . '", pn_add_text = "' . addslashes($data['addtext']) . '", pn_max_add = ' . $data['maxadd'] . ', pn_cosmetic = ' . $data['cosmetic'] . ', pn_enh_names = "' . addslashes($data['enhnames']) . '", pn_enh_names_simple = "' . addslashes($data['enhsimplenames']) . '", pn_opaque_names = "' . addslashes($data['opaquenames']) . '", pn_opaque_names_simple = "' . addslashes($data['opaquesimplenames']) . '", pn_updated = (SELECT NOW()) where pn_tid = ' . $data['id'];
			
	//	echo $query;

	
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}
	
	}
	
	function deleteLens($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'DELETE from pn_lenses where pn_tid = ' . $id;
			
		echo $query;	
			
		if($result = $mysqli->query($query)) {
			return true;
		}
		
		else {
			return false;
		}		
	
	}

}

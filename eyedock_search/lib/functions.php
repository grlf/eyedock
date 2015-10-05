<?php

function eyedock_get_companies(){

  $companies = ed_query("select pn_comp_tid as id, pn_comp_name as name from pn_lenses_companies where pn_hide=0 order by pn_comp_name asc");

  return $companies;
}

function eyedock_get_company_lenses($id){

  $id = abs(intval($id));

  $lenses = ed_query("select pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company,
  pnl.pn_dk as dk,
  IF(pnl.pn_max_plus > 0,concat('+',pnl.pn_max_plus,' to ',pnl.pn_max_minus),concat(pnl.pn_max_plus,' to ',pnl.pn_max_minus)) as powers,
  IF(pnl.pn_min_diam = pnl.pn_max_diam,pnl.pn_min_diam,concat(pnl.pn_max_diam,' - ',pnl.pn_min_diam)) as diameter,
  pnl.pn_bc_all as base_curves, 
  pnl.pn_discontinued as discontinued 
  from 
  pn_lenses pnl 
  left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid) 
  left join pn_lenses_polymers pnlp on (pnl.pn_poly_id = pnlp.pn_poly_tid) 
  where 
  pnl.pn_comp_id = '$id' 
  order by name asc");

  $lenses = eyedock_prepare_search_lenses($lenses);

  return $lenses;
}

function eyedock_get_parameters_lenses($data){

	//print_r($data);

  if(is_array($data)){
    extract($data);
  }

  $left_join = array();
  $where = array();

  $left_join[] = array(
    'tbl' => 'pn_lenses_companies pnlc',
    'on' => 'pnl.pn_comp_id = pnlc.pn_comp_tid'
  );

  $left_join[] = array(
    'tbl' => 'pn_lenses_polymers pnlp',
    'on' => 'pnl.pn_poly_id = pnlp.pn_poly_tid'
  );

  if($power != '' && $power != 0){
    $power = intval($power);
    if($power < 0){
      $where[] = "pnl.pn_max_minus <= '$power'";
    }else{
      $where[] = "pnl.pn_max_plus >= '$power'";
    }
  }

  if($diameter != ''){
    if(preg_match('/^([\d\.]*)mm\sor\s(more|less)$/', $diameter, $m)){
      $diameter = doubleval($m[1]);
      $diameter_sign = $m[2];
      if($diameter_sign == 'more'){
        $where[] = "pnl.pn_max_diam >= '$diameter'";
      }else{
        $where[] = "pnl.pn_min_diam <= '$diameter'";
      }
    }
  }

  if($dk != ''){
    if(preg_match('/^([\d]*)\sor\s(more|less)$/', $dk, $m)){
      $dk = intval($m[1]);
      $dk_sign = $m[2];
      if($dk_sign == 'more'){
        $where[] = "pnl.pn_dk >= '$dk'";
      }else{
        $where[] = "pnl.pn_dk <= '$dk'";
      }
    }
  }

  if($water != ''){
    if(preg_match('/^([\d\.]*)%\sor\s(more|less)$/', $water, $m)){
      $water = intval($m[1]);
      $w_sign = $m[2];
      if($w_sign == 'more'){
        $where[] = "pnlp.pn_h2o >= '$water'";
      }else{
        $where[] = "pnlp.pn_h2o <= '$water'";
      }
    }
  }

  if($ct != ''){
    if(preg_match('/^([\d\.]*)mm\sor\s(more|less)$/', $ct, $m)){
      $ct = doubleval($m[1]);
      $ct_sign = $m[2];
      if($ct_sign == 'more'){
        $where[] = "pnl.pn_ct >= '$ct'";
      }else{
        $where[] = "pnl.pn_ct <= '$ct'";
      }
    }
  }
  
  //TMZ added 03/24/2012
    	if($oz != ''){
		if(preg_match('/^([\d\.]*)mm\sor\s(more|less)$/', $oz, $m)){
			$oz = doubleval($m[1]);
			$oz_sign = $m[2];
			if($oz_sign == 'more'){
				$where[] = "pnl.pn_oz >= '$oz'";
			}else{
				$where[] = "pnl.pn_oz <= '$oz'";
			}
		}
	}


  if($replacement1 != '' && $replacement2 != ''){
    $days1 = eyedock_get_replacement_days($replacement1);
    $days2 = eyedock_get_replacement_days($replacement2);

    if($days1 > $days2){
      $tmp = $days1;
      $days1 = $days2;
      $days2 = $tmp;
    }

    $where[] = "(pnl.pn_replace_simple >= $days1 and pnl.pn_replace_simple <= $days2)";
  }

  if(is_array($basecurve_checked)){
    if(!isset($basecurve_checked['any'])){
      $validbc = array('flat' => 'flat','med' => 'median','steep' => 'steep');
      $bc = array();
      foreach($basecurve_checked as $bctype => $bcv){
        if(array_key_exists($bctype, $validbc)){
          $bc[] = "pnl.pn_bc_simple like '%" . addslashes($validbc[$bctype]) . "%'";
        }
      }
      $where[] = '(' . implode(' or ', $bc) . ')';
    }
  }

  if($toric_checked == 'Y' && $toric != ''){
    if(preg_match('/at\sleast\s(.*)D$/', $toric, $m)){
      $toric = doubleval($m[1]);
      $where[] = "pnl.pn_max_cyl_power <= '$toric'";
  	}else{
   	 $where[] = "pnl.pn_max_cyl_power = 0";
    }
  }

  if($bifocal_checked == 'Y' && $bifocal != ''){
    if(preg_match('/at\sleast\s(.*)D$/', $bifocal, $m)){
      $bifocal = doubleval($m[1]);
      $where[] = "pnl.pn_max_add >= '$bifocal'";
    }
  }else{
    $where[] = "pnl.pn_bifocal = 0";
  }

  if($tint_checked == 'Y'){
    $where[] = "pnl.pn_visitint > 0";
  }

  if($ew_checked == 'Y'){
    $where[] = "pnl.pn_ew > 0";
  }

  if($cosmetic_checked == 'Y'){
    $where[] = "pnl.pn_cosmetic > 0";
  }else{
    $where[] = "pnl.pn_cosmetic = 0";
  }

  if($oblique_axis_checked == 'Y'){
    $where[] = "pnl.pn_oblique > 30";
  }

  if($small_axis_checked == 'Y'){
    $where[] = "pnl.pn_cyl_axis_steps in ('1','5')";
  }

  $left_join_str = '';
  if(!empty($left_join)){
    foreach($left_join as $lj){
      $left_join_str .= ' left join ' . $lj['tbl'] . ' on (' . $lj['on'] . ')';
    }
  }

  $where_str = implode(' and ', $where);

  $query = "select pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company,
  pnl.pn_dk as dk,
  IF(pnl.pn_max_plus > 0,concat('+',pnl.pn_max_plus,' to ',pnl.pn_max_minus),concat(pnl.pn_max_plus,' to ',pnl.pn_max_minus)) as powers,
  IF(pnl.pn_min_diam = pnl.pn_max_diam,pnl.pn_min_diam,concat(pnl.pn_max_diam,' - ',pnl.pn_min_diam)) as diameter,
  pnl.pn_bc_all as base_curves, 
  pnl.pn_discontinued as discontinued 
  from 
  pn_lenses pnl" . $left_join_str . ' where ' . $where_str . ' order by name asc';
  
  //echo $query;

  $lenses = ed_query($query);

  $lenses = eyedock_prepare_search_lenses($lenses);

  return $lenses;
}

function eyedock_search_lenses($str){

  $query_start = "select pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, 
  pnl.pn_max_plus, pnl.pn_max_minus, pnl.pn_max_diam, pnl.pn_min_diam, pnl.pn_bc_all, pnl.pn_max_cyl_power, pnl.pn_max_add, pnl.pn_cosmetic,
  IF(pnl.pn_name = '" . addslashes($str) . "',1,0) as exact_name, IF(pnlc.pn_comp_name = '" . addslashes($str) . "',1,0) as exact_company ,
  IF(pnl.pn_name like '%" . addslashes($str) . "%',1,0) as full_str_name, IF(pnlc.pn_comp_name like '%" . addslashes($str) . "%',1,0) as full_str_company, 
  pnl.pn_discontinued as discontinued 
  from 
  pn_lenses pnl 
  left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid)";

  if($str != ''){
    $where = array();

    $tmp = explode(' ', $str);
    $words = array();
    foreach($tmp as $t){
      $t = trim($t);
      if($t != ''){
        $words[] = $t;
      }
    }

    $search_fields = array('pnl.pn_name', 'pnl.pn_aliases', 'pnlc.pn_comp_name');

    foreach($search_fields as $f){
      $word_where = array();
      foreach($words as $word){
        $word_where[] = $f . " like '%" . addslashes($word) . "%'";
      }
      $where[] = '(' . implode(' and ', $word_where) . ')';
    }

    $query_where = 'where ' . implode(' or ', $where);

  }else{
    $query_where = 'where 1';
  }

  $query_end = "order by exact_name desc, exact_company desc, full_str_name desc, full_str_company desc, name asc";

  $query = $query_start . ' ' . $query_where . ' ' . $query_end;

  $lenses = ed_query($query);

  $lenses = eyedock_prepare_search_lenses($lenses);

  return $lenses;
}

function eyedock_get_lenses($ids){

  if(!is_array($ids)){
    $ids = array();
  }

  $tmp = $ids;
  $ids = array();
  foreach($tmp as $t){
    if(intval($t) > 0){
      $ids[] = intval($t);
    }
  }

  $query = "select pnl.pn_tid, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company, 
  pnl.pn_max_plus, pnl.pn_max_minus, pnl.pn_max_diam, pnl.pn_min_diam, pnl.pn_bc_all, pnl.pn_max_cyl_power, pnl.pn_max_add, pnl.pn_cosmetic, 
  pnl.pn_discontinued as discontinued 
  from 
  pn_lenses pnl 
  left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid) 
  where 
  pn_tid in ('" . implode("','", $ids) . "') order by name asc";

  $lenses = ed_query($query);

  $lenses = eyedock_prepare_search_lenses($lenses);

  return $lenses;
}

function eyedock_prepare_search_lenses($lenses){

  if(!is_array($lenses)){
    $lenses = array();
  }

  if(!empty($lenses)){
    foreach($lenses as $k => $v){
      if(strstr($v['image'], ',')){
        $images = explode(',', $v['image']);
        $lenses[$k]['image'] = trim($images[count($images) - 1]);
      }
    }
  }

  return $lenses;

}

function eyedock_lens_detail($id){

  $id = abs(intval($id));
/*
  $query = "select pnl.pn_tid as id, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company,
  IF(pnl.pn_min_diam = pnl.pn_max_diam,pnl.pn_min_diam,concat(pnl.pn_max_diam,' - ',pnl.pn_min_diam)) as diameter,
  pnl.pn_bc_all as base_curves,
  pnl.pn_powers_1 as powers,
  pnlp.pn_poly_name as polymer,
  pnl.pn_dk as dk,
  IF(pnl.pn_dk > 0 and pnl.pn_ct > 0,(pnl.pn_dk / ( pnl.pn_ct * 10 ) ),'') as dkt, 
  pnlp.pn_fda_grp as fda_group,
  pnlp.pn_h2o as water,
  pnl.pn_ct as ct,
  pnl.pn_oz as oz,
  pnl.pn_process_text as man_process,
  pnl.pn_wear as wear,
  IF(pnl.pn_ew = 0,'no','yes') as ew,
  pnl.pn_qty as quantity,
  pnl.pn_replace_text as replacement,
  IF(pnl.pn_visitint > 0,'yes','no') as visitint,
  pnl.pn_other_info as comments,
  pnl.pn_toric as toric,
  pnl.pn_toric_type as toric_type,
  pnl.pn_cyl_power as cylinder_powers,
  pnl.pn_cyl_axis as axis,
  pnl.pn_bifocal as bifocal,
  pnl.pn_bifocal_type as bifocal_type,
  pnl.pn_add_text as add_powers,
  pnl.pn_cosmetic as cosmetic,
  pnl.pn_enh_names as enhancer_colors,
  pnl.pn_opaque_names as opaque_colors,
  pnl.pn_sph_notes as sph_notes,
  pnl.pn_cyl_notes as cyl_notes,
  pnl.pn_price as prices,
  pnl.pn_website as website,
  pnl.pn_fitting_guide as fitting_guide, 
  pnl.pn_discontinued as discontinued, 
  pnlc.pn_comp_desc as company_description 
  from 
  pn_lenses pnl 
  left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid) 
  left join pn_lenses_polymers pnlp on (pnl.pn_poly_id = pnlp.pn_poly_tid) 
  where 
  pnl.pn_tid = '$id'";
*/

  $query = "select pnl.pn_tid as id, pnl.pn_name as name, pnl.pn_image as image, pnlc.pn_comp_name as company,
  pnl.pn_diam_1 as diameter1,
  pnl.pn_base_curves_1 as basecurves1,
  pnl.pn_powers_1 as powers1,  
  pnl.pn_diam_2 as diameter2,
  pnl.pn_base_curves_2 as basecurves2,
  pnl.pn_powers_2 as powers2,  
  pnl.pn_diam_3 as diameter3,
  pnl.pn_base_curves_3 as basecurves3,
  pnl.pn_powers_3 as powers3,
  pnlp.pn_poly_name as polymer,
  pnl.pn_dk as dk,
  IF(pnl.pn_dk > 0 and pnl.pn_ct > 0,(pnl.pn_dk / ( pnl.pn_ct * 10 ) ),'') as dkt, 
  pnlp.pn_fda_grp as fda_group,
  pnlp.pn_h2o as water,
  pnl.pn_ct as ct,
  pnl.pn_oz as oz,
  pnl.pn_process_text as man_process,
  pnl.pn_wear as wear,
  IF(pnl.pn_ew = 0,'no','yes') as ew,
  pnl.pn_qty as quantity,
  pnl.pn_replace_text as replacement,
  IF(pnl.pn_visitint > 0,'yes','no') as visitint,
  pnl.pn_other_info as comments,
  pnl.pn_toric as toric,
  pnl.pn_toric_type as toric_type,
  pnl.pn_cyl_power as cylinder_powers,
  pnl.pn_cyl_axis as axis,
  pnl.pn_bifocal as bifocal,
  pnl.pn_bifocal_type as bifocal_type,
  pnl.pn_add_text as add_powers,
  pnl.pn_cosmetic as cosmetic,
  pnl.pn_enh_names as enhancer_colors,
  pnl.pn_opaque_names as opaque_colors,
  pnl.pn_sph_notes as sph_notes,
  pnl.pn_cyl_notes as cyl_notes,
  pnl.pn_markings as appearance,
  pnl.pn_price as prices,
  pnl.pn_website as website,
  pnl.pn_fitting_guide as fitting_guide, 
  pnl.pn_discontinued as discontinued, 
  pnlc.pn_comp_desc as company_description,
  pnlc.pn_phone as company_phone,
  pnlc.pn_address as company_address,
  pnlc.pn_city as company_city,
  pnlc.pn_state as company_state,
  pnlc.pn_zip as company_zip,
  pnlc.pn_url as company_url,
  pnlc.pn_email as company_email 
  from 
  pn_lenses pnl 
  left join pn_lenses_companies pnlc on (pnl.pn_comp_id = pnlc.pn_comp_tid) 
  left join pn_lenses_polymers pnlp on (pnl.pn_poly_id = pnlp.pn_poly_tid) 
  where 
  pnl.pn_tid = '$id'";

  $lens = ed_query_row($query);

  if(!empty($lens)){
    if(strstr($lens['image'], ',')){
      $images = explode(',', $lens['image']);
      $lens['image'] = trim($images[0]);
      if(count($images) > 1){
        $lens['images'] = array_map('trim', $images);
      }
    }
    if($lens['image'] != ''){
      $size = @getimagesize(EYEDOCK_LENS_IMG_URL . '/' . $lens['image']);
      if($size){
        $lens['image_width'] = $size[0];
      }
    }
    if($lens['website'] != '' && !preg_match('/^http[s]?:/i', $lens['website'])){
      $lens['website'] = 'http://www.eyedock.com/' . $lens['website'];
    }
    if($lens['fitting_guide'] != '' && !preg_match('/^http[s]?:/i', $lens['fitting_guide'])){
      $lens['fitting_guide'] = 'http://www.eyedock.com/' . $lens['fitting_guide'];
    }
    if($lens['dkt'] != ''){
      $lens['dkt'] = round($lens['dkt'], 1);
    }
    if($lens['comments'] != ''){
      if($lens['comments'] == strip_tags($lens['comments'])){
        $lens['comments'] = nl2br($lens['comments']);
      }
    }
  }

  return $lens;
}

function eyedock_fda_descriptions(){

      $return = array();

      $return[1] = "Low Water (<50 percent water content), Nonionic Polymers.<br />This group has the greatest resistance to protein deposition due 
to its lower water content and nonionic nature. Heat, chemical, and hydrogen peroxide care regimens can be used.";

      $return[2] =  "High Water (greater than 50 percent water content), Nonionic Polymers.<br />The higher water content of this group results in g
reater protein attraction than with group 1. However, the nonionic polymers reduce the potential for further attraction. Heat disinfection should be a
voided because of the high water content. In addition, sorbic acid and potassium sorbate preservatives can discolor the lenses.";

      $return[3] =  "Low Water (less then 50 percent water content), Ionic Polymers.<br />The lower water content but ionic nature of these polymers
 results in intermediate protein resistance. Heat, chemical and hydrogen peroxide care systems may be used.";

      $return[4] =  "High Water (greater then 50 percent water content), Ionic Polymers.<br />Because of the high water content and ionic nature of 
these polymers they attract more proteins than any other group. It is best to avoid heat disinfection and sorbic acid preservatives.";

     return $return;
}


function eyedock_get_replacement_days($str){
  if(preg_match('/daily/i',$str)){
    return 1;
  }elseif(preg_match('/weeks/i',$str)){
    return 14;
  }elseif(preg_match('/monthly/i',$str)){
    return 30;
  }elseif(preg_match('/([\d]*)\smonths/i',$str,$m)){
    return intval(intval($m[1]) * 30);
  }else{
    return 365;
  }

  return intval($str);
}

function eyedock_display_lens_popup($l){
?>
   <div class="edas-tab-search-lens-popup">
    <div class="edas-tab-search-lens-popup-content">
     <div><b><?=$l['name'];?></b></div>
     <div><?=$l['company'];?></div>
     <div>Dk <?=$l['dk'];?></div>
     <div>Power <?=$l['powers'];?></div>
     <div>Base curves: <?=$l['base_curves'];?></div>
     <div>Diameter: <?=$l['diameter'];?></div>
    </div>
   </div>  
<?
}

function ed_output_register($name, $value){
  global $ed_output;

  if(!is_array($ed_output)){
    $ed_output = array();
  }

  $ed_output[$name] = $value;
}

function eyedock_search_display($template, $close = true){
  global $ed_output;

  if(is_array($ed_output)){
    extract($ed_output);
  }

  if($close){
    ob_start();
  }

  if($template != '' && file_exists(EYEDOCK_SEARCH_TPL_DIR . '/' . $template . '.php')){
    include EYEDOCK_SEARCH_TPL_DIR . '/' . $template . '.php';
  }else{
    
  }

  if($close){
    $return = ob_get_contents();
    ob_end_clean();
    echo $return;
    exit;
  }

  return $return;

}

function ed_db_connect(){

  $success = mysql_connect(EYEDOCK_SEARCH_SQL_HOST, EYEDOCK_SEARCH_SQL_USER, EYEDOCK_SEARCH_SQL_PASS);

  if($success !== false){
    $success = mysql_select_db(EYEDOCK_SEARCH_SQL_DB);
  }

  if($success !== false){
    return true;
  }else{
    return false;
  }

}

function ed_query($query){

  $res = mysql_query($query);

  $return = array();
  while($row = mysql_fetch_assoc($res)){
    $return[] = $row;
  }

  return $return;
}

function ed_query_row($query){

  $result = ed_query($query);

  if(is_array($result) && !empty($result)){
    return array_pop($result);
  }else{
    return array();
  }

}
?>

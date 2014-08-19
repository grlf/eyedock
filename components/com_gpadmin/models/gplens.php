<?php

defined('_JEXEC') or die ('Restricted Access');

jimport('joomla.application.component.model');

class GpLensModelGpLens extends Jmodel {
	
	const GPHOST = 'mysql.eyedock.com';
	const GPUSER = 'eyedockdatauser';
	const GPPASS = 'kvBS^VQR';
	const GPDB = 'eyedock_data';
	
	const LENSIMGURL = 'http://www.eyedock.com/modules/Lenses/pnimages/lens_images/';

	function getCompanies() {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		$query = 'SELECT tid, name from rgpLab ORDER BY name';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$companies[] = $obj;
		}
		
		return $companies;
	
	}
	
	function getParameters() {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		
		$query = 'SELECT tid, name from rgpDesignCategory ORDER BY name';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$parameters['DesignCategory'][] = $obj;
		}
		
		$query = 'SELECT tid, name from rgpMaterials ORDER BY name';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$parameters['Materials'][] = $obj;
		}
		
		$query = 'SELECT * from rgpSubcategory ORDER BY name';
		$result = $mysqli->query($query);
		while($obj = $result->fetch_object()) {
			$parameters['Subcategory'][] = $obj;
		}
	
		return $parameters;
	
	}
	
	function getLensesByCompanyId($ids) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		
		foreach($ids as $id) {
			$where[] = 'l.rgpCompanyID = ' . $id;
		}
		
		$where = implode(' OR ', $where);
		
		$query = 'select l.tid as tid, l.name as lens_name, c.name as company, dc.name as type, sc.name as subtype 
		from 
		rgpLenses l 
		left join rgpLab c on (l.rgpCompanyid = c.tid) 
		left join rgpDesignCategory dc on (l.designCategoryID = dc.tid) 
		left join rgpSubcategory sc on (l.subcategoryID = sc.tid) 
		where ' . $where . ' order by lens_name asc';
		
		$result = $mysqli->query($query);
		while($lens = $result->fetch_object()) {
			$lenses[] = $lens;
		}
		
		return $lenses;
		
	}
	
	function getLensesBySearchstring($string) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		
		$query = 'select l.tid as tid, l.name as lens_name, c.name as company, dc.name as type, sc.name as subtype 
		from 
		rgpLenses l 
		left join rgpLab c on (l.rgpCompanyid = c.tid) 
		left join rgpDesignCategory dc on (l.designCategoryID = dc.tid) 
		left join rgpSubcategory sc on (l.subcategoryID = sc.tid) 
		where l.name like "' . $string .'%" order by lens_name asc';
		
		$result = $mysqli->query($query);
		while($lens = $result->fetch_object()) {
			$lenses[] = $lens;
		}
		
		return $lenses;	
	}
	
	function getLensesByParameters($params) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		
		$cat = $params->category;
		$subcat = $params->subcategory;
		$mat = $params->material;

		$where = array();
		$left_join = array();

		if($cat != 'all'){
			$where[] = "l.designCategoryID = '$cat'";
		}

		if($subcat != 'all'){
			$where[] = "l.subcategoryID = '$subcat'";
		}

		if($mat != 'all'){
			$left_join[] = "left join rgpLens_materials lm on (l.tid = lm.tid)";
			$where[] = "(lm.materialID = '$mat' or lm.anyMaterial = 1)";
		}

		if(!empty($left_join)){
			$left_join = implode(' ', $left_join);
		}else{
			$left_join = '';
		}

		if(!empty($where)){
			$where = implode(' and ', $where);
		}else{
			$where = '1';
		}

		$query = "select l.tid as tid, l.name as lens_name, c.name as company, dc.name as type, sc.name as subtype 
		from 
		rgpLenses l 
		left join rgpLab c on (l.rgpCompanyid = c.tid) 
		left join rgpDesignCategory dc on (l.designCategoryID = dc.tid) 
		left join rgpSubcategory sc on (l.subcategoryID = sc.tid) "
		. $left_join . 
		" where "
		. $where .
		" order by l.name asc";
			
		$result = $mysqli->query($query);
		while($lens = $result->fetch_object()) {
			$lenses[] = $lens;
		}
	
		return $lenses;
		
	}
	
	function getLensDetails($id) {

		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		
		$query = 'select l.tid as id, l.name as name, c.name as company, c.phone as c_phone, c.url as c_url, c.address as c_address, c.comments as c_comments, 
		l.image as thumbnail, dc.name as type, sc.name as subtype, l.baseCurve as basecurve, l.diameter as diameter, l.power as powers, l.addPower as addpower, 
		l.materialText as materialtext, l.centerThickness as centerthickness, l.opticZone as opticzone, l.otherInfo as comments, l.url as website, l.pdf as pdf, l.cost as cost 
		from 
		rgpLenses l 
		left join rgpLab c on (l.rgpCompanyid = c.tid) 
		left join rgpDesignCategory dc on (l.designCategoryID = dc.tid) 
		left join rgpSubcategory sc on (l.subcategoryID = sc.tid) 
		where l.tid = ' . $id;
		
		$result = $mysqli->query($query);
		$lens = $result->fetch_object();
		
		$query = 'select distinct m.name as material, m.tid as id from 
		rgpMaterials m 
		left join rgpLens_materials lm1 on m.tid = lm1.materialID 
		left join rgpLens_materials lm2 on m.rgpMaterialCompanyID = lm2.materialCompanyID 
		where 
		(lm1.lensID = ' . $lens->id . ' or lm2.lensID = ' . $lens->id . ') 
		order by m.name asc';
		
		$result = $mysqli->query($query);
		while($material = $result->fetch_object()) {
			$materials[] = $material;
		}
		
		?>
		
			<div class="json_wrapper" id="gp_lens_json"><?php echo json_encode($lens); ?></div>
			<div class="json_wrapper" id="gp_materials_json"><?php echo json_encode($materials); ?></div>
		
			<table id="gp_lens_details" cellspacing="0" cellpadding="0" width="730">

				<tbody>
					
					<tr>
					
						<td id="top_bar">
						
							<div id="gp_tab_lens_name" class="gp_tab active">
								
								<img src="/components/com_gplens/img/rounder_left.png" class="gp_rounder_left" />
								<span id="gp_lens_name"><?php echo $lens->name; ?></span>
								<img src="/components/com_gplens/img/rounder_right.png" class="gp_rounder_right" />
								
							</div>
						
							<a href="#"><div id="gp_details_close"></div></a>
						
						</td>
						
					</tr>
					
					<tr>
						<td id="gp_content_lens">
						
							<div id="gp_left_box_lens">
								
								<div id="gp_left_box_content_lens">
								
									<div id="gp_lens_company" class="gp_details_section">
										<div id="gp_lens_company_heading"><?php if($lens->company != '') { echo $lens->company; } ?></div>
										<div id="gp_lens_question_mark" class="gp_question_mark"><a href="#">[?]</a></div>
									</div>
									
									<div id="gp_lens_image">
										<?php if($lens->thumbnail != '') { ?><img src="<?php echo self::LENSIMGURL . $lens->thumbnail; ?>" class="gp_lens_logo" /><?php } ?>
									</div>
								
								</div>
							
							</div>
							
							<div id="gp_right_box_lens">
								
								<div id="gp_right_box_content_lens">
								
									<div id="gp_lens_types" class="gp_details_section">
										<div class="gp_details_header">Lens Type: </div> <?php if($lens->type != null) { echo $lens->type; } 
														 if($lens->subtype != null) { echo ', ' . $lens->subtype; }?>
									</div>
									
									<div id="gp_lens_powers" class="gp_details_section">
										<div class="gp_details_header">Powers:</div>
										<table border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td>Base Curves</td>
													<td>Diameters</td>
													<td>Powers</td>
												</tr>
												<tr>
													<td><?php if($lens->basecurve != null && $lens->basecurve != '') { echo $lens->basecurve; } ?></td>
													<td><?php if($lens->diameter != null && $lens->diameter != '') { echo $lens->diameter; } ?></td>
													<td><?php if($lens->powers != null && $lens->powers != '') { echo $lens->powers; } ?></td>
												</tr>
											</tbody>
										</table>
									</div>
									
									<div id="gp_lens_add_power" class="gp_details_section">
										<?php if($lens->addpower != '') { ?><div class="gp_details_header">Add Power: </div><?php echo $lens->addpower; } ?>
									</div>
									
									<div id="gp_lens_materials" class="gp_details_section">
										<?php if($lens->materialtext != '') { ?><div class="gp_details_header">Materials: </div><?php echo $lens->materialtext; } ?>
										<div id="gp_materials_question_mark" class="gp_question_mark"><a href="#">[?]</a></div>
									</div>
									
									<div id="gp_lens_center_thickness" class="gp_details_section">
										<?php if($lens->centerthickness != '') { ?><div class="gp_details_header">Center Thickness: </div><?php echo $lens->centerthickness; } ?>
									</div>
									
									<div id="gp_lens_optic_zone" class="gp_details_section">
										<?php if($lens->opticzone != '') { ?><div class="gp_details_header">Optic Zone: </div><?php echo $lens->opticzone; } ?>
									</div>
									
									<div id="gp_lens_comments" class="gp_details_section">
										<?php
										if($lens->comments != '') { ?>
											<div id="gp_lens_details_comments_header" class="gp_details_header">Comments:</div>
											<div id="gp_lens_details_comments_content"><?php echo $lens->comments; ?></div>
										<?php }
										?>
									</div>
									
									<div id="gp_lens_website_link" class="gp_details_section">
										<?php if($lens->website != '') { ?><a class="gp_details_link" href="<?php echo $lens->website; ?>">Link to Lens's Website</a><?php } ?>
									</div>
									
									<div id="gp_lens_package_insert" class="gp_details_section">
										<?php if($lens->pdf != '') { ?><a class="gp_details_link" href="<?php echo $lens->pdf; ?>">Link to Lens's Package Insert (.pdf)</a><?php } ?>
									</div>
									
									<div id="gp_lens_cost" class="gp_details_section">
										<?php
										if($lens->cost != '') {
											echo 'Lens Cost: ' . $lens->cost;
										}
										?>
									</div>
									
								</div>
							
							</div>
						
						</td>
						
					</tr>
					
				</tbody>
				
			</table>
		
		<?php
		
	}
	
	function getMaterialDetails($id) {
	
		$mysqli = new mysqli(self::GPHOST, self::GPUSER, self::GPPASS, self::GPDB);
		
		$query = 'select m.tid as id, m.name as material_name, m.dk as dk, m.wetAngle as wetangle, m.specificGravity as specificgravity, m.colors, m.colorsUV, m.url as website, m.otherInfo, m.refractiveIndex as refractiveindex, mt.name as materialtype, mc.name as company, mc.phone as c_phone, mc.url as c_url 
		from 
		rgpMaterials m 
		left join rgpMaterialType mt on (m.materialTypeID = mt.tid) 
		left join rgpMaterialCompany mc on (m.rgpMaterialCompanyID = mc.tid) 
		where m.tid = ' . $id . ' order by material_name asc';
		
		$result = $mysqli->query($query);
		$material = $result->fetch_object();
		
		$query = 'SELECT rgpLenses.name, rgpLenses.tid, c.name as company, dc.name as type, sc.name as subtype FROM rgpLenses left join rgpLab c on (rgpLenses.rgpCompanyid = c.tid) left join rgpDesignCategory dc on (rgpLenses.designCategoryID = dc.tid) left join rgpSubcategory sc on (rgpLenses.subcategoryID = sc.tid) LEFT JOIN rgpLens_materials ON rgpLens_materials.lensID = rgpLenses.tid WHERE rgpLens_materials.materialID = ' . $material->id . ' OR rgpLens_materials.anyMaterial= 1 ORDER BY rgpLenses.name ASC';

		$result = $mysqli->query($query);
		while($lens = $result->fetch_object()) {
			$lenses[] = $lens;
		}
		
		$query = 'SELECT rgpLenses.name, rgpLenses.tid, c.name as company, dc.name as type, sc.name as subtype FROM rgpLenses left join rgpLab c on (rgpLenses.rgpCompanyid = c.tid) left join rgpDesignCategory dc on (rgpLenses.designCategoryID = dc.tid) left join rgpSubcategory sc on (rgpLenses.subcategoryID = sc.tid) LEFT JOIN rgpLens_materials ON rgpLens_materials.lensID = rgpLenses.tid LEFT JOIN rgpMaterialCompany ON rgpMaterialCompany.tid = rgpLens_materials.materialCompanyID LEFT JOIN rgpMaterials ON rgpMaterials.rgpMaterialCompanyID = rgpMaterialCompany.tid WHERE rgpMaterials.tid= ' . $material->id . ' ORDER BY rgpLenses.tid ASC';

		$result = $mysqli->query($query);
		while($lens = $result->fetch_object()) {
			$lenses[] = $lens;
		}
		
		?>

			<div class="json_wrapper" id="gp_material_json"><?php echo json_encode($material); ?></div>
			<div class="json_wrapper" id="gp_lenses_json"><?php echo json_encode($lenses); ?></div>
		
			<table id="gp_lens_details" cellspacing="0" cellpadding="0" width="730">

				<tbody>
					
					<tr>
					
						<td id="top_bar">
						
							<div id="gp_tab_lens_name" class="gp_tab active">
								
								<img src="/components/com_gplens/img/rounder_left.png" class="gp_rounder_left" />
								<span id="gp_lens_name"><?php echo $material->material_name; ?></span>
								<img src="/components/com_gplens/img/rounder_right.png" class="gp_rounder_right" />
								
							</div>
						
							<a href="#"><div id="gp_details_close"></div></a>
						
						</td>
						
					</tr>
					
					<tr>
						<td id="gp_content_lens">
						
							<div id="gp_left_box_lens">
								
								<div id="gp_left_box_content_lens">
								
									<div id="gp_lens_company" class="gp_details_section">
										<div id="gp_lens_company_heading"><?php if($material->company != '') { echo $material->company; } ?></div>
										<div id="gp_lens_question_mark" class="gp_question_mark"><a href="#">[?]</a></div>
									</div>
									
									<div id="gp_lens_lenses_from_material" class="gp_details_section">Lenses from this material:</div>
									<div id="gp_left_box_details">
										
										<div id="gp_left_box_details_content"></div>
									
									</div>									
								
								</div>
							
							</div>
							
							<div id="gp_right_box_lens">
								
								<div id="gp_right_box_content_lens">
								
									<div id="gp_lens_types" class="gp_details_section">
										<div class="gp_details_header">Material Type: </div> <?php if($material->materialtype != null) { echo $material->materialtype; } ?>
									</div>
									
									<div id="gp_lens_add_power" class="gp_details_section">
										<?php if($material->dk != '') { ?><div class="gp_details_header">Dk: </div><?php echo $material->dk; } ?>
									</div>
									
									<div id="gp_lens_materials" class="gp_details_section">
										<?php if($material->wetangle != '') { ?><div class="gp_details_header">Wetting Angle: </div><?php echo $material->wetangle; } ?>
									</div>
									
									<div id="gp_lens_center_thickness" class="gp_details_section">
										<?php if($material->specificgravity != '') { ?><div class="gp_details_header">Specific Gravity: </div><?php echo $material->specificgravity; } ?>
									</div>
									
									<div id="gp_lens_optic_zone" class="gp_details_section">
										<?php if($material->refractiveindex != '') { ?><div class="gp_details_header">Refractive Index: </div><?php echo $material->refractiveindex; } ?>
									</div>
									
									<div id="gp_lens_optic_zone" class="gp_details_section">
										<?php if($material->colors != '') { ?><div class="gp_details_header">Colors: </div><?php echo $material->colors; } ?>
									</div>
									
									<div id="gp_lens_optic_zone" class="gp_details_section">
										<?php if($material->colorsUV != '') { ?><div class="gp_details_header">UV Colors: </div><?php echo $material->colorsUV; } ?>
									</div>
									
									<div id="gp_lens_comments" class="gp_details_section">
										<?php
										if($material->otherInfo != '') { ?>
											<div id="gp_lens_details_comments_header" class="gp_details_header">Other Information:</div>
											<div id="gp_lens_details_comments_content"><?php echo $material->otherInfo; ?></div>
										<?php }
										?>
									</div>
									
									<div id="gp_lens_website_link" class="gp_details_section">
										<?php if($lens->website != '') { ?><a class="gp_details_link" href="<?php echo $material->website; ?>">Link to Material's Website</a><?php } ?>
									</div>
									
								</div>
							
							</div>
						
						</td>
						
					</tr>
					
				</tbody>
				
			</table>		
		
		<?php
	
	}
	
}

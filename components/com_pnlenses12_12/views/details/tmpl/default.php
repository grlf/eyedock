<?php

/*
	ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
ini_set('html_errors', 'On'); */

defined('_JEXEC') or die('Restricted access'); 

$user =& JFactory::getUser();

//$document = JFactory::getDocument();
//$document->addStyleSheet('components/com_pnlenses/css/display.css');

//print_r ($this->lens);

?>
<div class="edas-ld-content">
	<div class="edas-ld-titleheader">
		<div class="ed-ld-lenstitle"><?=$this->lens['name']?>	</div>
			<a class="rating <? if ($this->lens['favorite']==1) echo "on"; ?>  " href="#1" title="favorite" id="display-star<?=$this->lens['id']?>" onClick="favStarClick(event,<? echo $user->id ?>);">Star</a>
			

		<input class="ed-ld-close-btn" type='button' onclick="do_close_lens();" value=" x " >
		
	</div>			
	<div class="edas-ld-main">
		<div class="edas-ld-main-content">
<!-- BEGIN LENS CONTENT =-->
			
			<input type="hidden" id="hiddenLensName" value="<?=$this->lens['name']?>"> 
			<input type="hidden" id="hiddenLensID" value="<?=$this->lens['id']?>"> 
			<div class='ed-ld-paramcolumns'>
				<div class='ed-ld-imagecolumn'>
					<div class='ed-ld-companyimage' onclick="show_details_popup('company','<?=$this->lens['company']?>' )">
<?
					if($this->lens['comp_logo'] != ''){
					?>
						<img src="<?=('http://www.eyedock.com/modules/Lenses/pnimages/comp_logos/' . $this->lens['comp_logo']);?>" 
<?=($this->lens['logo_image_width'] > 150?' style="width: 150px;"':'')?>
						/> 
<?
					} else {
						echo "<p>".$this->lens['company']."</p>";
					}
					?>
					</div>
					<br> 
					<div class="ed-ld-lensImage"
					<?php
						if(isset($this->lens['images']) && is_array($this->lens['images'])) { 
						
						echo  "onclick='show_details_popup(\"images\",\"".$this->lens['name']."\")' ";
						 }
						 ?>
						 >

<?
            if($this->lens['image'] != ''){
			?>
						<img src="<?=('http://www.eyedock.com/modules/Lenses/pnimages/lens_images/' . $this->lens['image']);?>" 
<?=($this->lens['image_width'] > 150?' style="width: 150px;"':'')?>
						/> <br> 
<?
				if(isset($this->lens['images']) && is_array($this->lens['images'])) echo "<span style='font-size:smaller'>click image for pics</span>";
			
			} ?>
					</div>
					</div> 
					
					<div class='ed-ld-paramcolumn-left'>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Material: 
							</div>
							<div class='ed-ld-data'>

<? echo ($this->lens['polymer'] != ''?$this->lens['polymer']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								FDA group: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['fda_group'] != ''?$this->lens['fda_group']:'&nbsp;')?>
<?
				if($this->lens['fda_group'] >=1 || $this->lens['fda_group'] <= 4){
				?>
								<div class="ed-ld-questionmark" onclick="show_details_popup('fda', <?=$this->lens['fda_group']?>)">[?]</div> 
<?
				}
				?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Water content: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['water'] != ''?($this->lens['water'] . '%'):'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								DK: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['dk'] != ''?$this->lens['dk'] . ($this->lens['dkt'] != ''?' (dk/t: ' . $this->lens['dkt'] . ')':''):'&nbsp;')?>
								<div class="ed-ld-questionmark" onclick="show_details_popup('dk', '')">[?]</div>  
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Modulus: 
							</div>
							<div class='ed-ld-data'>
								20 
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								CT: 
							</div>
							<div class='ed-ld-data'>

<?=(abs(doubleval($this->lens['ct'])) > 0?($this->lens['ct'] . ' mm'):'unknown')?>
								<div class="ed-ld-questionmark" onclick="show_details_popup('ct','')">[?]</div> 
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								OZ: 
							</div>
							<div class='ed-ld-data'>

<?=(abs(doubleval($this->lens['oz'])) > 0?($this->lens['oz'] . ' mm'):'unknown')?>
							</div>
						</div>
					</div>
<!--ed-ld-paramcolumn-left-->
					<div class='ed-ld-paramcolumn-right'>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Visibility Tint? 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['visitint'] != ''?$this->lens['visitint']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								EW: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['ew'] != ''?$this->lens['ew']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Manuf. Process: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['man_process'] != ''?$this->lens['man_process']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Quantity: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['quantity'] != ''?$this->lens['quantity']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Replacement: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['replacement'] != ''?$this->lens['replacement']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Wear type: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['wear'] != ''?$this->lens['wear']:'&nbsp;')?>
							</div>
						</div>
						<div class='ed-ld-row'>
							<div class='ed-ld-title'>
								Appearance: 
							</div>
							<div class='ed-ld-data'>

<?=($this->lens['appearance'] != ''?$this->lens['appearance']:'&nbsp;')?>
							</div>
						</div>
					</div>
<!--ed-ld-paramcolumn-right-->
				</div>
<!--ed-ld-ed-ld-paramcolumns-->
				<div class='ed-ld-row'>
					<table class='ed-ld-powertable'>
						<tr>
							<th>BC</th>
							<th>Diam</th>
							<th class='spherecell'>Sph</th>

<? if ($this->lens['toric'] ==1) { ?>
							<th>Cyl</th>
							<th>Axis</th>

<? } ?>
<? if ($this->lens['bifocal'] ==1) { ?>
							<th>Bifocal</th>

<? } ?>
<? if ($this->lens['cosmetic'] ==1) { ?>
							<th>Enh colors</th>
							<th>Opq colors</th>

<? } ?>
						</tr>
<? foreach ($this->lens['lensPowers'] as $row) { ?>
						<tr>
							<td><?=$row['baseCurve']?>
							</td>
							<td><?=$row['diameter']?>
							</td>
							<td><?=$row['sphere']?>
							</td>
<? if ($this->lens['toric'] ==1) { ?>
							<td><?=$row['cylinder']?>
							</td>
							<td><?=$row['axis']?>
							</td>
<? } ?>
<? if ($this->lens['bifocal'] ==1) { ?>
							<td><?=$row['addPwr']?>
							</td>
<? } ?>
<? if ($this->lens['cosmetic'] ==1) { ?>
							<td><?=$row['colors_enh']?>
							</td>
							<td><?=$row['colors_opq']?>
							</td>
<? } ?>
						</tr>
<? } ?>
					</table>
				</div>
				<div class='ed-ld-paramcolumns'>
					<div class='ed-ld-tablecomments'>
						<strong>Bifocal Type:</strong> center-near &nbsp;&nbsp;&nbsp; <strong>Toric Type:</strong> prism ballast &nbsp;&nbsp;&nbsp; 
					</div>
					<div class='ed-ld-row'>
						<div class='ed-ld-rowtitle'>
							More Info 
						</div>
						<div class='ed-ld-rowcontent'>

<?=($this->lens['comments'] != ''?$this->lens['comments']:'&nbsp;');?>
<?
              //offer to select a trial lens (unless it's a bifocal . . .)
              if($this->lens['bifocal'] != 1) { ?>
							<div class="ed-ld-mrsearch">
								Enter your refraction for a trial lens suggestion: &nbsp; 
								<input type="text" id="edas_mr" class="rxField" size="18"> &nbsp;&nbsp; 
							</div>
							<div class="rxFieldError">
							</div>

<?
              }

              if(trim($this->lens['prices']) != '' && trim($this->lens['prices']) != '0.00'){
              ?>
							<div class="edas-ld-wholesale-view">
								<a href="#" onclick="return eyedock_lens_detail_wholesale_toggle('<?=$this->lens['id']?>')">View Wholesale Prices</a> 
							</div>
							<div class="edas-ld-wholesale" id="<?=$this->lens['id']?>-wholesale" style="display: none;">
								<div>

<?=$this->lens['prices']?>
								</div>
								<div style="padding-top: 2px;">
									*cost may vary - please contact manufacturer to verify 
								</div>
							</div>

<? } 
              
              if($this->lens['website'] != ''){
              ?>
							<div class="edas-ld-link">
								<a href="<?=$this->lens['website']?>" target="_blank">View Website</a> 
							</div>

<? }

              if($this->lens['fitting_guide'] != ''){
              ?>
							<div class="edas-ld-link">
								<a href="<?=$this->lens['fitting_guide']?>" target="_blank">View Fitting Guide or Package Insert</a> 
							</div>

<? }  ?>
						</div>
<!--ed-ld-rowcontent-->
					</div>
<!--ed-ld-ed-ld-paramcolumns2-->
<!-- END LENS CONTENT =-->
				</div>
			</div>

<!-- popup text -->

<!--a div to hold the images -->
<? if(isset($this->lens['images']) && is_array($this->lens['images'])){
?>
			<div id="ed-ld-lensImages" class="ed-ld-popup-info"  >
<?foreach($this->lens['images'] as $i){?>
				<img src="<?=('http://www.eyedock.com/modules/Lenses/pnimages/lens_images/' . $i);?>" /> 
<?}?>
			</div>
<?
}
?>


<div id="ed-ld-companyInfo" class="ed-ld-popup-info" >
						<p>
						<?php if($this->lens['comp_logo'] != ''){
					?>
						<img src="<?=('http://www.eyedock.com/modules/Lenses/pnimages/comp_logos/' . $this->lens['comp_logo']);?>" 
<?=($this->lens['logo_image_width'] > 150?' style="width: 150px;"':'')?>
						/> 
<?
					} else {
						echo "<h3>".$this->lens['company']."</h3>";
					}
					?>
					</p>
						<p><?=$this->lens['company_description']?></p>
						<?=$this->lens['company_address']?><br/>
						<?=$this->lens['company_city']?><br/>
						<?=$this->lens['company_zip']?></p>
						<p><a href="<?=$this->lens['company_url']?>" style="color: #327180;" target="_blank"><?=$this->lens['company_url']?>
						</a> </p>
						<p><a href="mailto:<?=$this->lens['company_email']?>" style="color: #FA0;">
<?=$this->lens['company_email']?>
						</a> </p>
					</div>

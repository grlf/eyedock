<?php

/*
	ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
ini_set('html_errors', 'On'); */

defined('_JEXEC') or die('Restricted access'); 

$document = JFactory::getDocument();
$document->addStyleSheet( JPATH_COMPONENT.DS.'css/display.css');

?>

<div class="edas-ld-content">

 <div class="edas-ld-top">
 	<div class="edas-ld-top-left"><div class="edas-ld-top-right"><div class="edas-ld-top-content">

   <div class="edas-ld-title"><?=$lens['name']?></div>

   <div class="edas-ld-close" onclick="eyedock_lens_detail_close('<?=$lens['id']?>');"></div>

  </div></div></div>
 </div>

 <div class="edas-ld-main">
  <div class="edas-ld-main-content">

        <!-- BEGIN LENS CONTENT =-->

        <div class="rating">
          <div class="star">
            <a href="#1" title="favorite lens">star</a>
          </div>
        </div><input type="hidden" id="hiddenLensName" value="<?=$lens['name']?>"> <input type="hidden" id="hiddenLensID" value="<?=$lens['id']?>">

        <div class='ed-ld-paramcolumns'>
          <div class='ed-ld-imagecolumn'>
          
          	
            <div class='ed-ld-companyimage'>
            	 <span class="edas-popup-link" onclick="eld_companypopup_open(<?=$lens['id']?>);">
					  <?
					if($lens['comp_logo'] != ''){
					?>
						<img src="<?=(EYEDOCK_LENS_COMP_IMG_URL . '/' . $lens['comp_logo']);?>"<?=($lens['logo_image_width'] > 150?' style="width: 150px;"':'')?> />
					<?
					} else {
						echo "<p>".$lens['company']."</p>";
					}
					?>

					
              </span>
            </div><br>
          
          
          
			  <div class="ed-ld-lensImage">
   <?
            if($lens['image'] != ''){
			?>
				 <img src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $lens['image']);?>"<?=($lens['image_width'] > 150?' style="width: 150px;"':'')?> /> 
				 <br>
             
			<?
				if(isset($lens['images']) && is_array($lens['images'])) echo "<span style='font-size:smaller'>click image for pics</span>";
			
			} ?>
           
            </div><br>




            
            <div class="edas-company-details eyedock-detailed-popup" id="company_details_popup<?=$lens['id']?>">
     	<div class="eyedock-detailed-popup-close" onclick="eld_companypopup_open(<?=$lens['id']?>);">X</div>
     	<div class="edas-company-details-companyname"><?=$lens['company']?></div>
     	<div class="edas-company-details-description edas-company-details-block"><?=$lens['company_description']?></div>
     	<div class="edas-company-details-block">
     		<div class="edas-company-details-address"><?=$lens['company_address']?></div>
     		<div class="edas-company-details-city edas-address-inline"><?=$lens['company_city']?></div>
     		<div class="edas-company-details-state edas-address-inline"><?=$lens['company_state']?></div>
     		<div class="edas-company-details-zip edas-address-inline"><?=$lens['company_zip']?></div>
     	</div>
     	<div class="edas-company-details-block">
     	<div class="edas-company-details-url edas-popup-interior-link"><a href="<?=$lens['company_url']?>" style="color: #327180;" target = "_blank"><?=$lens['company_url']?></a></div>
     	<div class="edas-company-details-email edas-popup-interior-link"><a href="mailto:<?=$lens['company_email']?>" style="color: #FA0;"><?=$lens['company_email']?></a></div>
     	</div>
     </div>

            
            
          </div>

          <div class='ed-ld-paramcolumn-left'>
            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Material:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['polymer'] != ''?$lens['polymer']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                FDA group:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['fda_group'] != ''?$lens['fda_group']:'&nbsp;')?>
				<?
				if($lens['fda_group'] >=1 || $lens['fda_group'] <= 4){
				?>
							<span class="edas-popup-link" onclick="eld_popup_open('fda-<?=$lens['fda_group']?>', '<?=$lens['id']?>');">[?]</span>
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
                <?=($lens['water'] != ''?($lens['water'] . '%'):'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                DK:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['dk'] != ''?$lens['dk'] . ($lens['dkt'] != ''?' (dk/t: ' . $lens['dkt'] . ')':''):'&nbsp;')?>
                <span class="edas-popup-link" onclick="eld_popup_open('dk', '<?=$lens['id']?>');">[?]</span>
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
               <?=(abs(doubleval($lens['ct'])) > 0?($lens['ct'] . ' mm'):'unknown')?>
               <span class="edas-popup-link" onclick="eld_popup_open('ct', '<?=$lens['id']?>');">[?]</span>
              </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                OZ:
              </div>

              <div class='ed-ld-data'>
               <?=(abs(doubleval($lens['oz'])) > 0?($lens['oz'] . ' mm'):'unknown')?>
              </div>
            </div>
          </div><!--ed-ld-paramcolumn-left-->

          <div class='ed-ld-paramcolumn-right'>
            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Visibility Tint?
              </div>

              <div class='ed-ld-data'>
                <?=($lens['visitint'] != ''?$lens['visitint']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                EW:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['ew'] != ''?$lens['ew']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Manuf. Process:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['man_process'] != ''?$lens['man_process']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Quantity:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['quantity'] != ''?$lens['quantity']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Replacement:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['replacement'] != ''?$lens['replacement']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Wear type:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['wear'] != ''?$lens['wear']:'&nbsp;')?>
                </div>
            </div>

            <div class='ed-ld-row'>
              <div class='ed-ld-title'>
                Appearance:
              </div>

              <div class='ed-ld-data'>
                <?=($lens['appearance'] != ''?$lens['appearance']:'&nbsp;')?>
                </div>
            </div>
          </div><!--ed-ld-paramcolumn-right-->
        </div><!--ed-ld-ed-ld-paramcolumns-->

        <div class='ed-ld-row'>
          <table class='ed-ld-powertable'>
            <tr>

              <th>BC</th>

              <th>Diam</th>

              <th class='spherecell'>Sph</th>
				
			  <? if ($lens['toric'] ==1) { ?>
				  <th>Cyl</th>
				  <th>Axis</th>
              <? } ?>
              
              <? if ($lens['bifocal'] ==1) { ?>
					<th>Bifocal</th>
              <? } ?>
              
              <? if ($lens['cosmetic'] ==1) { ?>
				  <th>Enh colors</th>
				  <th>Opq colors</th>
              <? } ?>
              



            </tr>

<? foreach ($lens['lensPowers'] as $row) { ?>

            <tr>	

              <td><?=$row['baseCurve']?></td>

              <td><?=$row['diameter']?></td>

              <td><?=$row['sphere']?></td>
              
			  <? if ($lens['toric'] ==1) { ?>
				  <td><?=$row['cylinder']?></td>
				  <td><?=$row['axis']?></td>
              <? } ?>

			<? if ($lens['bifocal'] ==1) { ?>
              	<td><?=$row['addPwr']?></td>
            <? } ?>
              
              <? if ($lens['cosmetic'] ==1) { ?>
				<td><?=$row['colors_enh']?></td>
                <td><?=$row['colors_opq']?></td>
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
              <?=($lens['comments'] != ''?$lens['comments']:'&nbsp;');?>
              
              <?
              //offer to select a trial lens (unless it's a bifocal . . .)
              if($lens['bifocal'] != 1) { ?>

              <div class="ed-ld-mrsearch">Enter your refraction for a trial lens suggestion: &nbsp;<input type="text" id="edas_mr" class="rxField" size="18"> &nbsp;&nbsp;</div>

              <div class="rxFieldError"></div><?
              }

              if(trim($lens['prices']) != '' && trim($lens['prices']) != '0.00'){
              ?>

              <div class="edas-ld-wholesale-view">
                <a href="#" onclick="return eyedock_lens_detail_wholesale_toggle('<?=$lens['id']?>')">View Wholesale Prices</a>
              </div>

              <div class="edas-ld-wholesale" id="<?=$lens['id']?>-wholesale" style="display: none;">
                <div>
                  <?=$lens['prices']?>
                  </div>

                <div style="padding-top: 2px;">
                  *cost may vary - please contact manufacturer to verify
                </div>
              </div>
              
              <? } 
              
              if($lens['website'] != ''){
              ?>

              <div class="edas-ld-link">
                <a href="<?=$lens['website']?>" target="_blank">View Website</a>
              </div>
              
              <? }

              if($lens['fitting_guide'] != ''){
              ?>

              <div class="edas-ld-link">
                <a href="<?=$lens['fitting_guide']?>" target="_blank">View Fitting Guide or Package Insert</a>
              </div>
              
              
              <? }  ?>
              
              </div><!--ed-ld-rowcontent-->
          </div><!--ed-ld-ed-ld-paramcolumns2-->
          <!-- END LENS CONTENT =-->

  </div>
 </div>
 
 
 <!--a div to hold the images -->
 <? if(isset($lens['images']) && is_array($lens['images'])){
?>
     <div id="lensImagesPopup">
<?foreach($lens['images'] as $i){?>
      <img src="<?=(EYEDOCK_LENS_IMG_URL . '/' . $i);?>" />
<?}?>
     </div>
<?
}
?>
 
 
 

 <div class="edas-ld-bottom">
 	<div class="edas-ld-bottom-left"><div class="edas-ld-bottom-right"><div class="edas-ld-bottom-content">

  </div></div></div>
 </div>

</div>

<?
if($lens['company_description'] != ''){
?>
<div class="eyedock-detailed-popup" id="edp-<?=$lens['id'] . '-comp'?>" onclick="eld_popup_close('<?=$lens['id'] . '-comp'?>');">
 <div class="eyedock-detailed-popup-content">
  <div class="eyedock-detailed-popup-text"><?=$lens['company_description']?></div>
  <div class="eyedock-detailed-popup-close" onclick="eld_popup_close('<?=$lens['id'] . '-comp'?>');">X</div>
 </div>
</div>
<?
}
?>

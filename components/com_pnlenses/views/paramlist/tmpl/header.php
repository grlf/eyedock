<?php
	defined('_JEXEC') or die('Restricted access'); 

	//only show certain features to users
	$user =& JFactory::getUser();
	$user_type = $user->get('gid'); // 18 is registered
	//echo "user group: " . $user_type;
	$subscriber = ($user->get('gid') == 31)?1:0; //18 for local, 31 for live


//show company info if it's provided
if ($this->companyInfo)  {
	
?>
<div>
	<div style='float:left; margin:0 1em;'>
		<?php if($this->companyInfo['comp_logo'] != ''){
			?>
				<img style='max-height:100px; max-width:100px'  src="<?=('modules/Lenses/pnimages/comp_logos/' . $this->companyInfo['comp_logo']);?>" 
			<?=($this->companyInfo['logo_image_width'] > 150?' style="width: 150px;"':'')?>
			/> 
			<?
		} else {
			echo "<h3>".$this->companyInfo['company']."</h3>";
		}
		?>
	</div>
	<div>
		
		<?=$this->companyInfo['company_address']?><br/>
		<?=$this->companyInfo['company_city']?>, <?=$this->companyInfo['company_state']?> 
		<?=$this->companyInfo['company_zip']?></p>
		<a href="<?=$this->companyInfo['company_url']?>" style="color: #327180;" target="_blank"><?=$this->companyInfo['company_url']?>
		</a> <br/>
		<a href="mailto:<?=$this->companyInfo['company_email']?>" style="color: #FA0;">
		<?=$this->companyInfo['company_email']?>
		</a> <br/>
	</div>
</div>
<div style='clear:both'>&nbsp;</div>



<?

} // end company info



	if ( count($this->lenses) == 1 ) {
		echo "This lens seems to be the <i>best</i> match";
	} else {
		echo "These <b>" . count($this->lenses) . "</b> lenses are the <i>best</i> matches";
	}
?>
<div class="ed_lenses_settings_btns">
	<img src="/components/com_pnlenses/images/printer.png"  onclick='printLensList();'  />
	<img src="/components/com_pnlenses/images/settings.png"  onclick='showLensListSettings();'  />
	
</div>


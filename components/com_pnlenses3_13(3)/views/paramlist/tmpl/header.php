<?php
	defined('_JEXEC') or die('Restricted access'); 
?>

<?php
	if ( count($this->lenses) == 1 ) {
		echo "This lens seems to be the <i>best</i> match";
	} else {
		echo "These <b>" . count($this->lenses) . "</b> lenses seem to be the <i>best</i> matches";
	}
?>
<div class="ed_lenses_settings_btns">
	<img src="/components/com_pnlenses/images/printer.png"  onclick='printLensList();'  />
	<img src="/components/com_pnlenses/images/settings.png"  onclick='showLensListSettings();'  />
	
</div>


<?php
	defined('_JEXEC') or die('Restricted access'); 
?>

<?php
	echo "<b>" . count($this->lenses) . "</b> lenses were found";
?>
<div class="ed_lenses_settings_btns">
	<input type="button"  onclick='printLensList();' value="print" />
	<input type="button" onclick='showLensListSettings();' value="settings" />
	
</div>


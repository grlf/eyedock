<?php 

defined('_JEXEC') or die('Restricted access'); 

$userPrefs = $this->userPrefs;

//only show certain features to users
$user =& JFactory::getUser();
$user_type = $user->get('gid'); // 18 is registered
$subscriber = ($user->get('gid') == 18)?1:0;


//if the prefs haven't been set use these defaults
if (!isset($userPrefs) ) {
	$userPrefs['discontinued'] = 1;
	$userPrefs['userFav'] = 1;
	$userPrefs['favPct'] = 1;
	$userPrefs['image'] = 1;
	$userPrefs['allDiam'] = 1;
	$userPrefs['userFav'] = 1;
}


?>

<div id="ed-lenses-settings">
	<p><input type="checkbox" id="discontinuedCbx" name="discontinued" <? if ($userPrefs['discontinued']) echo 'checked="checked"' ?> />
	<label for="discontinuedCbx">Show discontinued lenses</label></p>
	
	<div>
		<u>Search results columns</u>
		<? if ($subscriber == 1) { ?>
			<p><input type="checkbox" id="userFavCbx" name="userFav" <? if ($userPrefs['userFav']) echo 'checked="checked"' ?> />
			<label for="userFavCbx">User Favorite</label>	</p>
			<p><input type="checkbox" id="favPctCbx" name="favPct" <? if ($userPrefs['favPct']) echo 'checked="checked"' ?> />
			<label for="favPctCbx">Lens Popularity</label></p>
		<? } ?>
		<p><input type="checkbox" id="imageCbx" name="image" <? if ($userPrefs['image']) echo 'checked="checked"' ?> />
		<label for="imageCbx">Lens Image</label></p>
		<p><input type="checkbox" id="allDiamCbx"  name="allDiam" <? if ($userPrefs['allDiam']) echo 'checked="checked"' ?>  />
		<label for="allDiamCbx">All Diameters*</label></p>
		<p><input type="checkbox" id="sDiamCbx" name="sDiam"  <? if ($userPrefs['sDiam']) echo 'checked="checked"' ?>/>
		<label for="sDiamCbx">Smallest Diameter*</label></p>
		<p><input type="checkbox" id="lDiamCbx" name="lDiam"  <? if ($userPrefs['lDiam']) echo 'checked="checked"' ?>/>
		<label for="lDiamCbx">Largest Diameter*</label></p>
		<p><input type="checkbox" id="dkCbx"  name="dk"  <? if ($userPrefs['dk']) echo 'checked="checked"' ?>/>
		<label for="dkCbx">Dk</label></p>
		<p><input type="checkbox" id="ctCbx" name="ct"  <? if ($userPrefs['ct']) echo 'checked="checked"' ?>/>
		<label for="ctCbx">CT*</label></p>
		<p><input type="checkbox" id="ozCbx"  name="oz"  <? if ($userPrefs['oz']) echo 'checked="checked"' ?>/>
		<label for="ozCbx">OZ*</label></p>
		<p><input type="checkbox" id="h2oCbx"  name="h2o"  <? if ($userPrefs['h2o']) echo 'checked="checked"' ?>/>
		<label for="h2oCbx">Water Content*</label></p>
	</div>
	<div style="font-size:smaller">*Column not available when searching by refraction</div>
	
	
	
</div>
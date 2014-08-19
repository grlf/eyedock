<?php 

defined('_JEXEC') or die('Restricted access'); 

//$theRoot = "/";
//$theRoot = "http://www.eyeodock.com/";

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_pnlenses/css/lenses.css');
$document->addStyleSheet('components/com_pnlenses/css/lenslists.css');
$document->addStyleSheet('components/com_pnlenses/css/advSearch.css');
$document->addStyleSheet('components/com_pnlenses/css/details.css');
$document->addStyleSheet('components/com_pnlenses/css/rating.css');

$document->addScript("/components/com_pnlenses/js/details.js");
$document->addScript("/components/com_pnlenses/js/lenses.js");
$document->addScript("/components/com_pnlenses/js/lenslists.js");
$document->addScript("/components/com_pnlenses/js/advSearch_data.js");
$document->addScript("/components/com_pnlenses/js/advSearch.js");
$document->addScript("/javascript/jquery/tablesorter/tablesorter.js");
$document->addScript("/javascript/jquery/jquery.printElement.js");

?>


<div id='ed-lenses-main'>
	<div id='ed-lenses-search'>
		<div id='ed-lenses-advSearch'>

<?php include_once "advSearches.php"; ?>
		</div>
<!-- end  ed-lenses-advSearch-->
	</div>
<!-- end ed-lenses-search-->
	<img id="adv-searches-loader" src="http://www.eyedock.com/components/com_pnlenses/images/loader.gif" alt="loading" /> 
	<div id='ed-lenses-lists'>
		<div id="ed-lenses-comparetable"></div>
		
	</div>
<!-- end  ed-lenses-lists-->
	<div id='ed-lenses-display'>
		<div id='ed-lenses-display-content'>
		</div>
	</div>
<!-- end display -->
<!-- popup dialogs -->
	<div id="ed-ld-companyInfo-dialog">
	</div>
</div>
<!-- end main -->

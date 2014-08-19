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
$document->addStyleSheet('/javascript/jquery/tablesorter/css/theme.ice.css');

//the following 4 lines should not be needed once this goes live...
// $document->addScript("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
// $document->addScript("/javascript/jquery/jquery.noConflict.js");
// $document->addScript("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.js");
// $document->addStyleSheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/redmond/jquery-ui.css');

$document->addScript("/javascript/rxClasses/RxObject.js");
$document->addScript("/javascript/jquery/tablesorter/js/jquery.tablesorter.min.js");
$document->addScript("/javascript/jquery/tablesorter/js/jquery.tablesorter.widgets.min.js");
$document->addScript("/javascript/jquery/bbq.js");
//$document->addScript("/javascript/jquery/tablesorter/jquery.scrollableFixedHeaderTable.js");
$document->addScript("/javascript/jquery/jquery.printElement.js");
$document->addScript("/components/com_pnlenses/js/details.js");
$document->addScript("/components/com_pnlenses/js/lenses.js");
$document->addScript("/components/com_pnlenses/js/lenslists.js");
$document->addScript("/components/com_pnlenses/js/advSearch_data.js");
$document->addScript("/components/com_pnlenses/js/advSearch.js");
?>


<div id='ed-lenses-main'>
	<div id='ed-lenses-search'>
		<div id='ed-lenses-advSearch'>

<?php include_once "advSearches.php"; ?>
		</div><!-- end  ed-lenses-advSearch-->
	</div><!-- end ed-lenses-search-->
	
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

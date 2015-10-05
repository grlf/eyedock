<?php 

defined('_JEXEC') or die('Restricted access'); 

//$theRoot = "/";
//$theRoot = "http://www.eyeodock.com/";

$document = JFactory::getDocument();
JHtml::_('bootstrap.framework');
$document->addStyleSheet('components/com_pnlenses/css/lenses.css');
$document->addStyleSheet('components/com_pnlenses/css/lenslists.css');
$document->addStyleSheet('components/com_pnlenses/css/advSearch.css');
$document->addStyleSheet('components/com_pnlenses/css/details.css');
$document->addStyleSheet('components/com_pnlenses/css/rating.css');
$document->addStyleSheet('/javascript/jquery/tablesorter/css/theme.ice.css');

$document->addScript("/javascript/rxClasses/RxObject.js");
$document->addScript("/javascript/jquery/tablesorter/js/jquery.tablesorter.min.js");
$document->addScript("/javascript/jquery/tablesorter/js/jquery.tablesorter.widgets.min.js");
$document->addScript("/javascript/jquery/bbq.js");
$document->addScript("/javascript/jquery/jquery.clearsearch.js");
//$document->addScript("/javascript/jquery/tablesorter/jquery.scrollableFixedHeaderTable.js");
$document->addScript("/javascript/jquery/jquery.printElement.js");
$document->addScript("/components/com_pnlenses/js/details.js");
$document->addScript("/components/com_pnlenses/js/lenses.js");
$document->addScript("/components/com_pnlenses/js/lenslists.js");
$document->addScript("/components/com_pnlenses/js/advSearch_data.js");
$document->addScript("/components/com_pnlenses/js/advSearch.js");

//include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/is_subscriber.php' );

//only show certain features to users
//$subscriber = isUserASubscriber();
//echo "<input type='hidden' id='lenses_subscriber' value='" . $subscriber  . "' />";



?>

<!-- google plus 1 button -->
    <script type="text/javascript">
      window.___gcfg = {
        lang: 'en-US'
      };

      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>


<h2>Soft Lens Searches</h2>
<div id='ed-lenses-main'>


	<div id='ed-lenses-search'>
		<div id="ed-lenses-cross"></div>
		<div id='ed-lenses-advSearch'>
		


			<?php include_once "advSearches.php"; ?>
		</div><!-- end  ed-lenses-advSearch-->

	</div><!-- end ed-lenses-search-->
	
	<div  id="adv-searches-loader"><img src="http://www.eyedock.com/components/com_pnlenses/images/cl_spinner.gif" alt="loading" /> searching. . .</div>
	<div id='ed-lenses-lists'>
		<div id="ed-lenses-comparetable"></div>
		
	</div>
<!-- end  ed-lenses-lists-->
	<div id='ed-lenses-display'>
		<div id='ed-lenses-display-content'>
		</div>
	</div>
<!-- end display -->
<div id='ed-lenses-directions' >
	<h3>What can I do here?</h3>
	<ul>
	<li>	Start entering a lens name. EyeDock will guess what you're looking for as you type </li>
	<li>	Search by entering your refraction (subscribers only). We'll vertex it, transpose it (if needed), search for suitable lenses, and show you the closest available parameters for each lens.</li>
	<li>	Use the filters to search by a multitude of parameter options.</li>
	</ul>
</div>
<!-- popup dialogs -->
	<div id="ed-ld-companyInfo-dialog">
	</div>
</div>
<!-- end main -->

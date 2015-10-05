<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- META FOR IOS & HANDHELD -->
<?php if ($this->getParam('responsive', 1)): ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<style type="text/stylesheet">
		@-webkit-viewport   { width: device-width; }
		@-moz-viewport      { width: device-width; }
		@-ms-viewport       { width: device-width; }
		@-o-viewport        { width: device-width; }
		@viewport           { width: device-width; }
	</style>
	<script type="text/javascript">
		//<![CDATA[
		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			var msViewportStyle = document.createElement("style");
			msViewportStyle.appendChild(
				document.createTextNode("@-ms-viewport{width:auto!important}")
			);
			document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
		}
		//]]>
	</script>
<?php endif ?>
<meta name="HandheldFriendly" content="true"/>
<meta name="apple-mobile-web-app-capable" content="YES"/>
<!-- //META FOR IOS & HANDHELD -->

<?php
// SYSTEM CSS
$this->addStyleSheet(JURI::base(true) . '/templates/system/css/system.css');
?>

<?php
// T3 BASE HEAD
$this->addHead();
?>

<?php
// CUSTOM CSS
if (is_file(T3_TEMPLATE_PATH . '/css/custom.css')) {
	$this->addStyleSheet(T3_TEMPLATE_URL . '/css/custom.css');
}
?>

<!-- Le HTML5 shim and media query for IE8 support -->
<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="<?php echo T3_URL ?>/js/respond.min.js"></script>
<![endif]-->

<!-- You can add Google Analytics here or use T3 Injection feature -->

<!-- Search Autocomplete -->
<script>
		// Addresses jQuery 1.8.16 bug 7555: http://bugs.jqueryui.com/ticket/7555
		jQuery('.ui-autocomplete-input').each(function (idx, elem) {
 			 var autocomplete = $(elem).data('autocomplete');
  			if ('undefined' !== typeof autocomplete) {
      			var blur = autocomplete.menu.options.blur;
      			autocomplete.menu.options.blur = function (evt, ui) {
          			if (autocomplete.pending === 0) {
              			blur.apply(this,  arguments);
          			}
      			};
  			}
		});

	</script>
	
	
	<script>
	jQuery().ready(function() {
	
		jQuery( "#top_search_form" ).submit(function( e ) {
			if (event.preventDefault) { 
				event.preventDefault(); 
			} else { 
				event.returnValue = false; 
			}
		});
			


		jQuery("#top_search").autocomplete({
			//TODO: add a flag for subscribers --> . . .&subscriber=1
			source: "/search2.php?all=1&info=1&subscriber=" + jQuery("#lenses_subscriber").val(),
			autoFocus: true
		});
		
	jQuery("#top_search").on( "autocompleteselect", function( event, ui ) {
			var phrase = jQuery("#top_search").val("");
			jQuery("#top_search").val("");
			//alert("here");
			if (ui.item.link) {
				window.location = ui.item.link;
			} else if (ui.item.type == "refraction") {
				getRxInfo (ui.item.rx);
			} else if (ui.item.type == "keratometry") {
				getKInfo (ui.item.k);
			} else {
				window.location = 	"http://www.eyedock.com/searchrouter.php?q=" + phrase;
			}
		} );


	})
	
	function getRxInfo (rx) {
		//var subs = (jQuery("#lenses_subscriber").val() == 1)?1:0;
		jQuery.ajax({
				type: "GET",
				url: "/utilities/utilities/rxsummary.php?rx=" + encodeURIComponent(rx) + "&subscriber="+jQuery("#lenses_subscriber").val(),
				dataType: "html",
				success: function(result){
					jQuery( "#rx_info_dialog" ).html(result);
					jQuery( "#rx_info_dialog" ).dialog( {title: 'Refraction info', width:'auto'} );
				}
			});		
	}
	
	function getKInfo (k) {
		jQuery.ajax({
				type: "GET",
				url: "/utilities/utilities/ksummary.php?k=" + encodeURIComponent(k) + "&subscriber="+jQuery("#lenses_subscriber").val(),
				dataType: "html",
				success: function(result){
					jQuery( "#rx_info_dialog" ).html(result);
					jQuery( "#rx_info_dialog" ).dialog( {title: 'Keratometry info', width:'auto'} );
				}
			});		
	}
	
</script>
<script type="text/javascript"> 
	function playVid(id) { 
 		var e = document.getElementById(id); 
 		if(e.style.display == 'block') 
  			e.style.display = 'none'; 
 		else 
  			e.style.display = 'block'; 
	} 
</script>
	
<?php JHtml::_('jquery.ui'); ?>
<?php 
	$document = JFactory::getDocument();
	$document->addScript("//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js");
	$document->addStyleSheet("//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/redmond/jquery-ui.css");
?>
<!-- AK added for search: ultimately remove to use the jQueryui autocomplete instead -->
<!--  <link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/javascript/jquery-autocomplete/demo/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/javascript/jquery-autocomplete/jquery.autocomplete.css" />
<link type="text/css" href="<?php echo $this->baseurl ?>/javascript/jquery-autocomplete/theme/ui.all.css" rel="Stylesheet" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/redmond/jquery-ui.css" type="text/css" media="all" />
<script type='text/javascript' src='<?php echo $this->baseurl ?>/javascript/jquery-autocomplete/jquery.autocomplete.js'></script>
-->	

<!-- TMZ added for rx calculations -->
<script type="text/javascript" src="<?php echo $this->baseurl ?>/javascript/rxClasses/rxField.js"></script>

<?php 
	/**
	 * Greenleaf Add search_mod_inc.php script
	 */

	$jinput = JFactory::getApplication()->input;
	$option = $jinput->get('option');
	$id = $jinput->get('id');
	
	if ($option == "com_content" && $id==70) :
?>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/javascript/overlib/overlib.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/modules/Meds/pnstyle/style.css" />
<?php elseif($option == "com_content" && $id==69) :?>
<?php JHtml::_('behavior.framework',true); ?>
<?php endif; ?>


<?php if (JFactory::getUser()->guest): ?> <!-- autologin -->
<script type="text/javascript" src="autologin.php"></script>
<?php endif; ?>

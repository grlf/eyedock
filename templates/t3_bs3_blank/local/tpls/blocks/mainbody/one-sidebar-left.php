<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mainbody 2 columns: sidebar - content
 */
?>
<div id="t3-mainbody" class="container t3-mainbody">
	<div class="row">

		<!-- MAIN CONTENT -->
		<div id="t3-content" class="t3-content col-xs-12 col-sm-8 col-sm-push-4 col-md-10 col-md-push-2">
			<?php if($this->hasMessage()) : ?>
			<jdoc:include type="message" />
			<?php endif ?>
			<?php if ($this->countModules('com-top')){ ?>
			<jdoc:include type="modules" name="<?php $this->_p('com-top') ?>" />
    		<?php	} ?>
			<jdoc:include type="component" />
			<?php if ($this->countModules('com-bottom')){ ?>
			<jdoc:include type="modules" name="<?php $this->_p('com-bottom') ?>" />
    		<?php	} ?>
		</div>
		<!-- //MAIN CONTENT -->

		<!-- SIDEBAR LEFT -->
		<div class="t3-sidebar t3-sidebar-left col-xs-12 col-sm-4 col-sm-pull-8 col-md-2 col-md-pull-10 <?php $this->_c($vars['sidebar']) ?>">
			<jdoc:include type="modules" name="<?php $this->_p($vars['sidebar']) ?>" style="T3Xhtml" />
		</div>
		    <script type="text/javascript">
		 	jQuery(document).ready(function() {
			 	jQuery(".module_calcs .module-ct").hide();
			 	jQuery( ".module_calcs .module-title" ).click(function() {
					jQuery( ".module_calcs .module-ct" ).toggle();
				});
		 	});
		    </script>
		<!-- //SIDEBAR LEFT -->

	</div>
</div> 
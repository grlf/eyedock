<?php

defined('_JEXEC') or die ('Restricted Access');



// css includes
JHTML::stylesheet('style.css', '/components/com_gplens/css/');

// js includes
JHTML::script('search_box.js', '/components/com_gplens/js/', true);
JHTML::script('tabs.js', '/components/com_gplens/js/', true);
JHTML::script('right_box.js', '/components/com_gplens/js/', true);
JHTML::script('left_box.js', '/components/com_gplens/js/', true);
JHTML::script('lens_detail.js', '/components/com_gplens/js/', true);
JHTML::script('deployment.js', '/components/com_gplens/js/', true);

?>

<table id="container" cellspacing="0" cellpadding="0" width="730">

	<tbody>
		
		<tr>
		
			<td id="top_bar">
			
				<a href="#">
					<div id="tab_company" class="tab active">
					
						<?php echo JHTML::image('components/com_gplens/img/rounder_left.png', 'rounder_left', array('class' => 'rounder_left')); ?>
						<span>Company Search</span>
						<?php echo JHTML::image('components/com_gplens/img/rounder_right.png', 'rounder_right', array('class' => 'rounder_right')); ?>
					
					</div>
				</a>
				
				<a href="#">
					<div id="tab_parameter" class="tab">
					
						<?php echo JHTML::image('components/com_gplens/img/rounder_left.png', 'rounder_left', array('class' => 'rounder_left')); ?>
						<span>Parameter Search</span>
						<?php echo JHTML::image('components/com_gplens/img/rounder_right.png', 'rounder_right', array('class' => 'rounder_right')); ?>
					
					</div>
				</a>
				
				<div id="search_box">
				
					<input type="text" value="Search everything..." autocomplete="off" spellcheck="false" />
				
				</div>
			
			</td>
			
		</tr>
		
		<tr>
			<td id="divider">
			
				<div id="main_divider"></div>
			
			</td>
		</tr>
		
		<tr>
			<td id="content">
			
				<div id="left_box">
					
					<div id="left_box_content"></div>
				
				</div>
				
				<div id="right_box">
					
					<div id="right_box_content"></div>
				
				</div>
			
			</td>
		</tr>
		
	</tbody>
	
</table>
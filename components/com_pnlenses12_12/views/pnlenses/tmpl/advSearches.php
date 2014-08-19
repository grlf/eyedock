<?php 

defined('_JEXEC') or die('Restricted access'); 

?>
<form id='ed-lenses-searchForm'>
	<div id='name-mr-div'>
		<input type='text' class='adv-rounded defaultText' id='ed-lenses-name_mr_input' onkeyup='checkRHSkeypress(event)' title='Enter a lens name or refraction' />

		<div class='rxFieldError'> </div>
	</div>
	<div class='skewright'>
		<div id='more-options'>
			<b>more options &darr;</b>
		</div>
	</div>
	<div id='adv-search-div'>
		<div style="clear: left;" id="adv-search-radios">
			<div class="adv-type-column">
				Toric 
				<div id='adv-search-toric-radio'>
					<input type='radio' id='toric-any' value='' name='toric-radio' checked='checked' />
					<label for="toric-any">any</label> 
					<input type='radio' id='toric-yes' value='' name='toric-radio' />
					<label for="toric-yes">yes</label> 
					<input type='radio' id='toric-no' value='' name='toric-radio' />
					<label for="toric-no">no</label> 
				</div>
			</div>
			<div class="adv-type-column">
				Bifocal 
				<div id='adv-search-bifocal-radio'>
					<input type='radio' id='bifocal-any' value='' name='bifocal-radio' checked='checked' />
					<label for="bifocal-any">any</label> 
					<input type='radio' id='bifocal-yes' value='' name='bifocal-radio' />
					<label for="bifocal-yes">yes</label> 
					<input type='radio' id='bifocal-no' value='' name='bifocal-radio' />
					<label for="bifocal-no">no</label> 
				</div>
			</div>
			<div class="adv-type-column">
				Cosmetic 
				<div id='adv-search-cosmetic-radio'>
					<input type='radio' id='cosmetic-any' value='' name='cosmetic-radio' checked='checked' />
					<label for="cosmetic-any">any</label> 
					<input type='radio' id='cosmetic-yes' value='' name='cosmetic-radio' />
					<label for="cosmetic-yes">yes</label> 
					<input type='radio' id='cosmetic-no' value='' name='cosmetic-radio' />
					<label for="cosmetic-no">no</label> 
				</div>
			</div>
			 
</div>

			<div style="clear: left;">
				<div id="adv-search-replace">
					<input type="checkbox" id="replace0" value='' checked='checked' />
					<label for="replace0">Any</label> 
					<input type="checkbox" id="replace1" value='1' />
					<label for="replace1">Daily</label> 
					<input type="checkbox" id="replace14" value='14' />
					<label for="replace14">2 Wk.</label> 
					<input type="checkbox" id="replace30" value='30' />
					<label for="replace30">1 Mo.</label> 
					<input type="checkbox" id="replace90" value='90' />
					<label for="replace90">3 Mo.</label> 
					<input type="checkbox" id="replace180" value='180' />
					<label for="replace180">6 Mo.</label> 
					<input type="checkbox" id="replace365" value='365' />
					<label for="replace365">Yearly</label> 
				</div>
			</div>
	
		<div class='filterRow'>
			<div class='filterLHS_div'>
				<select class='searchType'>
					<option value="" >More Options </option>
				</select>
			</div>
			<div class='operator_div'>
			</div>
			<div class='filterRHS_div'>
		
			</div>
			<div class="removeFilterRow_btn">&nbsp;</div>
			<div class="clonefilter_btn">&nbsp;</div>
			
		</div>

<!--filter row-->
	</div>
<!--end adv-search-div -->
</form>
​ 
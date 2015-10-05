<?php

?>

<div class="edas-parameter-search">

 <div class="edas-parameters">

  <div class="edas-parameters-title">Parameters</div>

  <div class="edas-parameters-content" id="eyedock-parameters">

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name">Power</div>
    <div class="edas-parameters-line-slider">
     <div class="edas-slider" id="power-slider"><div class="edas-slider-knob"></div></div>
    </div>
    <div class="edas-parameters-line-label edas-ajax-input" id="power-slider-value">&nbsp;</div>
   </div>

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name">dk</div>
    <div class="edas-parameters-line-slider">
     <div class="edas-slider" id="dk-slider"><div class="edas-slider-knob"></div></div>
    </div>
    <div class="edas-parameters-line-label edas-ajax-input" id="dk-slider-value">&nbsp;</div>
   </div>

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name edas-2-lines">Water content</div>
    <div class="edas-parameters-line-slider">
     <div class="edas-slider" id="water-slider"><div class="edas-slider-knob"></div></div>
    </div>
    <div class="edas-parameters-line-label edas-ajax-input" id="water-slider-value">&nbsp;</div>
   </div>

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name">Replacement</div>
    <div class="edas-parameters-line-slider">
     <div class="edas-slider" id="replacement1-slider"><div class="edas-slider-knob"></div></div>
     <div class="edas-slider" id="replacement2-slider"><div class="edas-slider-knob"></div></div>
    </div>
    <div class="edas-parameters-line-label">
     <div class="edas-ajax-input" id="replacement1-slider-value">&nbsp</div>
     <div class="edas-ajax-input" id="replacement2-slider-value">&nbsp</div>
    </div>
   </div>

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name">
     <label>
      <input id="toric-checkbox" type="checkbox" /><span>Toric</span>
     </label>
    </div>
    <div class="edas-parameters-line-slider">
     <div class="edas-slider" id="toric-slider"><div class="edas-slider-knob"></div></div>
    </div>
    <div class="edas-parameters-line-label edas-ajax-input" id="toric-slider-value">&nbsp</div>
   </div>

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name">
     <label>
      <input id="bifocal-checkbox" type="checkbox" /><span>Bifocal</span>
     </label>
    </div>
    <div class="edas-parameters-line-slider">
     <div class="edas-slider" id="bifocal-slider"><div class="edas-slider-knob"></div></div>
    </div>
    <div class="edas-parameters-line-label edas-ajax-input" id="bifocal-slider-value">&nbsp</div>
   </div>

   <div class="edas-parameters-line">
    <div class="edas-parameters-line-name">
     <label>
      <input id="cosmetic-checkbox" type="checkbox" /><span>Cosmetic</span>
     </label>
    </div>
    <div class="edas-parameters-line-slider">

     <span class="edas-button" id="edas-more-options">
      <div class="edas-button-left">
       <div class="edas-button-right">
        <div class="edas-button-content">
More Options
        </div>
       </div>
      </div>
     </span>

    </div>
    <div class="edas-parameters-line-label">&nbsp;</div>
   </div>

  </div>

 </div>

 <div class="edas-parameters-lenses">
  <div id="edas-parameters-results"></div>
  <div class="edas-loader" id="edas-parameters-results-loader"></div>
 </div>

 <div class="edas-clear"></div>

</div>

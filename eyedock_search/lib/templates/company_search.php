<?php

?>

<div class="edas-company-search">

 <div class="edas-company-list">
  <div class="edas-company-list-title"><span>Company</span></div>
  <div class="edas-company-list-contents">
<?foreach($companies as $c){?>
   <div class="edas-company-list-name" onclick="eyedock_get_company_lenses('<?=$c['id'];?>');"><?=$c['name'];?></div>
<?}?>
  </div>
 </div>

 <div class="edas-company-lenses">
  <div id="eyedock-company-lenses"></div>
  <div class="edas-loader" id="eyedock-company-lenses-loader"></div>
 </div>

 <div class="edas-clear"></div>

</div>

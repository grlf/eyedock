<?php 

defined('_JEXEC') or die('Restricted access'); 


 if (sizeof($this->dataList) > 0){ ?>
	   <select class='rhsVal' onChange='submitSearch()'>
	   <option selected='selected' value=''>any</option>

	 <?php  foreach ($this->dataList as $item){ ?>
		   <option value='<?= $item['id'] ?>'><?=$item['name']?></option>
	   <?php } //end foreach ?>

		</select>

  <?php } // end if ?>
  
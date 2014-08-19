<?php
	//a sorting function for arrays
	function sorter($a, $b)
	{
		if ((float) $a == (float) $b) {
			return 0;
		}
		return ((float)$a < (float)$b) ? 1 : -1;
	}
?>
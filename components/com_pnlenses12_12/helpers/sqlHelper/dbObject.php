<?php 
function dbObject () {
		$option = array(); //prevent problems
		$option['database'] = 'eyedock_data'; 
		$option['driver'] = 'mysql';        // Database driver name  
		//$option['host']     = 'localhost';    // Database host name
		//$option['user']     = 'root';       // User for database authentication
		//$option['password'] = 'root';   // Password for database authentication
		
		$option['host']     = 'mysql.eyedock.com';    // Database host name
		$option['user']     = 'eyedockdatauser';       // User for database authentication
		$option['password'] = 'kvBS^VQR';   // Password for database authentication
		
		
		 $db = & JDatabase::getInstance($option);
		 return $db;
   }
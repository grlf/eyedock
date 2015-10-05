<?php
function getCONNECT() {
	$connect =mysql_connect('mysql.eyedock.com', 'eyedockdatauser', 'kvBS^VQR');
	//$connect =mysql_connect('localhost', 'root', 'root');
	return $connect;
}
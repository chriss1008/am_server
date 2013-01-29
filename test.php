<?php
require "./config/config.php";
require "./lib/pdodb.php";

$db = new PDODB();
$res = $db->connect( $_DB['host'], $_DB['dbname'], $_DB['username'], $_DB['password'] );
if( !$res )
	self::exceptionResponse(500, "Server maintenance");

$res = $db->delete("customer_tags", array("customer_id"=>52, "tag"=>'คjค่'));

var_dump($res);
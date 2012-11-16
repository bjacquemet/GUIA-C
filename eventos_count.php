<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Guia-C</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>

<?php
require 'my.php'; 

ini_set("display_errors", 1);
error_reporting(E_ALL ^ E_NOTICE);

$dbhandle = mysql_connect($dbhost, $dbuser, $dbpass) or die("Couldn't connect to SQL Server on");

mysql_select_db($dbname, $dbhandle);
$query = 'SELECT * FROM LUGAR ORDER BY idLugar ASC;';

$result = mysql_query($query);

while($row = mysql_fetch_array($result))
{
	$lugar = 'http://pipes.yahoo.com/pipes/pipe.run?_id=9cd7fcd9e68ac27123e7cf4f4a0686b6&_render=json&idLugar='. $row['idLugar'] .'&hoy=' . date('Y-m-d');
	// $lugar = 'http://pipes.yahoo.com/pipes/pipe.run?_id=9cd7fcd9e68ac27123e7cf4f4a0686b6&_render=json&idLugar=311&hoy=' . date('Y-m-d');
	echo $lugar;
	$fp = fopen($lugar, 'r');
	$json=stream_get_contents($fp);
	$response = json_decode($json);
	fclose($fp);
	$count_eventos = $response->count;
	$add_count = 'INSERT into EVENTOS_COUNT VALUES ';
	
	$add_count .='(\'\',\''.$row['idLugar'].'\',\''.$count_eventos .'\');';	
	echo $add_count;

	$added = mysql_query($add_count) or die ('not working' .mysql_error());
	
}

mysql_close($dbhandle);

?>
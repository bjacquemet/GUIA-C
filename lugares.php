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

$barrio = 'http://pipes.yahoo.com/pipes/pipe.run?_id=69e6def3aed7ec2bad8a1b9ed45cf805&_render=json';

$fp = fopen($barrio, 'r');
$json=stream_get_contents($fp);
$response = json_decode($json);
fclose($fp);

$barrios = $response->value->items[0]->Item;

$dbhandle = mysql_connect($dbhost, $dbuser, $dbpass) or die("Couldn't connect to SQL Server on");

mysql_select_db($dbname, $dbhandle);

foreach ($barrios as $IBarrios)
{
	$idBarrio = $IBarrios->IdBarrio;
	echo '<br/> bario ' . $idBarrio;
	$lugar = 'http://pipes.yahoo.com/pipes/pipe.run?_id=4c9158c505beb2dbef894be996890b04&_render=json&barrio='. $idBarrio;
	$fp = fopen($lugar, 'r');
	$json=stream_get_contents($fp);
	$response = json_decode($json);
	fclose($fp);
	$lugares = $response->value->items[0]->Item;
	if (!empty($lugares))
	{
	foreach ($lugares as $ILugar)
	{
		$idL = $ILugar->IdLugar;
		$idB = $ILugar->IdBarrio;		
		$Nombre = str_replace("'", "\'", $ILugar->Nombre);		
		$Direccion = str_replace("'", "\'", $ILugar->Direccion);		
		$Resumen = str_replace("'", "\'", $ILugar->Resumen);		
		$Descripcion = str_replace("'", "\'", $ILugar->Descripcion);		
		$Latitud = $ILugar->Latitud;		
		$Longitud = $ILugar->Longitud;		
		
		$query = 'INSERT into LUGAR VALUES ';
		
		$query .='(\'\',\''.$idL.'\',\''.$idB.'\',\''.$Nombre.'\',\''.$Direccion.'\', \'\', \'\', \'\', \''.$Resumen.'\', \''.$Descripcion.'\', \'\', \'\', \'\', \'\', \'\', \''.$Longitud.'\', \''.$Latitud.'\' ,\'\', \'\');';	
	

		$result = mysql_query($query) or die ('not working' .mysql_error());
		
	}	
}
}
mysql_close($dbhandle);

?>
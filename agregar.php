<?php
require 'my.php';

$idLugar = $_GET["idLugar"];
$respuesta = $_GET["respuesta"];

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql : '.mysql_error());
mysql_select_db($dbname);

if (($idLugar != '') && ($respuesta != ''))
{
$query = "INSERT INTO RESPUESTA VALUES ('', '" .$idLugar ."', '" .$respuesta ."');";
mysql_query($query) or die ('not working');
}
else {
}
mysql_close($conn);




?>

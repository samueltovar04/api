<?php
include('db.php');
$lat = $_REQUEST['latitud'];
$lon = $_REQUEST['longitud'];
$dis = $_REQUEST['distancia'];
$imei= $_REQUEST['imei'];
$idu = $_REQUEST['id_usuario'];
$q="insert into gpslocalizador (latitud,longitud,distancia,imei,id_usuario) value ('$lat','$lon','$dis','$imei','$idu')";
$result = mysql_query($q) or die('{"resultado": "Error al Insertar'. mysql_error().'"}');
 header('Content-Type: application/json');
 echo '{"resultado": ['.$result.']}';
?>

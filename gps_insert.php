<?php
include('db.php');
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$dis = $_GET['dis'];
$imei= $_GET['imei'];
$idu = $_GET['id_usuario'];
$q="insert into (latitud,longitud,distancia,imei,id_usuario) value ('$lat','$lon','$dis','$imei','$idu')";
$result = mysql_query($q) or die('{"resultado": "Error al Insertar'. mysql_error().'"}');
 header('Content-Type: application/json');
 echo '{"resultado": ['.$result.']}';
?>

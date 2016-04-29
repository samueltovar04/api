<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include "../db.php";

//Extraer los datos enviados desde el generador de notificacion
//desde el backend.
$objDatos = json_decode(file_get_contents("php://input"));

$id_cliente=$objDatos->id_cliente;
$titulo=$objDatos->titulo;
$mensaje=$objDatos->mensaje;

//Se consulta el id del dispositivo registrado.
$query_dispositivo = "SELECT id_dispositivo from clientes WHERE reg_id = '$id_cliente'";
echo $objDispositivo = mysql_query($query_dispositivo); 
$rowDispositivo = mysql_fetch_array($objDispositivo, MYSQL_ASSOC);

echo $para = $rowDispositivo['id_dispositivo']; 

sendPush($para,$titulo,$mensaje);

function sendPush($para,$titulo,$mensaje){
	// API access key from Google API's Console
	// replace API
	define( 'API_ACCESS_KEY', 'AIzaSyCi4H53gbp2540E-m0r7oTr1unaJfYy84s');
	$registrationIds = array($para);
	$msg = array(
	'message' => $mensaje,
	'title' => $titulo,
	'vibrate' => 1,
	'sound' => 3
	// you can also add images, additionalData
	);

	$fields = array(
	'registration_ids' => $registrationIds,
	'data' => $msg
	);

	$headers = array(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );
	echo $result;
	echo $para;
}
?>

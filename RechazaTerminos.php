<?php 
/*debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include "db.php";

/* Extrae los valores enviados desde la aplicacion movil */
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

/*Declaracion de variables*/
$id_usuario = 0;
$comentarios = '';

/*Extraer los datos enviados desde la app*/
$objDatos = json_decode(file_get_contents("php://input"));

/*Guardar los datos en variables*/
 if(isset($objDatos->id_usuario)) 
 { 
    $id_usuario=$objDatos->id_usuario;
    $comentarios=$objDatos->comentarios;

     //Creamos nuestra consulta sql
     $query="INSERT into rechaza_clausula (id_cliente, comentarios) value ('$id_usuario', '$comentarios')";
  
    if(mysql_query($query)){
        $arr["mensaje"] = 'ok'; 
    }else 
        $arr["mensaje"] = 'error en la ejecucion del query: ' . mysql_error(); 
 }else 
  $arr["mensaje"] = 'error en variables recibidas por el servidor'; 

# JSON-encode the response
echo $json_response = json_encode($arr);
?>
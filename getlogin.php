<?php
include('db.php');
/* Define los valores que seran evaluados, en este ejemplo son valores estaticos,
en la verdadera aplicacion son dinamicos a partir de una base de datos */

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

/*Declare variables*/
$usuarioEnviado = "";
$passwordEnviado = "";
$id_dispositivo = "";
$callback= "";

/*Get variables from json post*/
if(isset($_GET['username']))         $usuarioEnviado = $_GET['username'];
if(isset($_GET['password']))         $passwordEnviado = $_GET['password'];
if(isset($_GET['id_dispositivo']))   $id_dispositivo = $_GET['id_dispositivo'];
if(isset($_GET['jsoncallback']))     $callback=$_GET['jsoncallback'];

$resultados = array();
 $q="select reg_id, email, cedula, password from clientes where cedula='$usuarioEnviado' and password='$passwordEnviado' limit 1";
 $result = mysql_query($q);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
$usuarioValido = $row['email'];
$cedulaValido = $row['cedula'];
$passwordValido = $row['password'];
$id_cliente = $row['reg_id'];
$email = $row['email'];

/* verifica que el usuario y password concuerden correctamente */
if(  $usuarioEnviado == $cedulaValido  && $passwordEnviado == $passwordValido ){
	$resultados["mensaje"] = "Usuario Correcto";
	$resultados["validacion"] = "ok";
    $resultados["id_cliente"] = $id_cliente;
    $resultados["email"] = $email;
    $queryNotificacion="UPDATE clientes SET id_dispositivo = '$id_dispositivo' where reg_id = '$id_cliente'";
    $resultado = mysql_query($queryNotificacion);

}else{
	/*esta informacion se envia si la validacion falla */
	$resultados["mensaje"] = "Usuario y password incorrectos";
	$resultados["validacion"] = "error";
}

/*convierte los resultados a formato json*/
$resultadosJson = json_encode($resultados);
echo $callback. '' . $resultadosJson . '';
?>

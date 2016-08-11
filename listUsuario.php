<?php
include('db.php');
 include "correo.php";
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
$data=array();
$cedula=$email=$nom='';
if(isset($_REQUEST['cedula']))
$cedula=htmlentities($_REQUEST['cedula'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
if(isset($_REQUEST['email']))
 $email=htmlentities($_REQUEST['email'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
if(isset($_REQUEST['nombre']))
 $nom=htmlentities($_REQUEST['nombre'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
if(empty($cedula)){
	$data['validacion']='1';
}else{
	$q=mysql_query("SELECT cedula,email, fullname FROM clientes where cedula='$cedula' limit 1");
	$row=mysql_fetch_array($q, MYSQL_ASSOC);
	if(!isset($row['cedula'])){
		$data['validacion']='2';
	}else{
		if(empty($email) || empty($nom)){
			$data['validacion']='3';
		}else{
			$q=mysql_query("insert into recomendados  (cedula,nombre,email) values ('$cedula','$nom','$email')");
			$data['validacion']='0';
			$are=array(0=>strtolower(trim($row['email'])),1=>strtolower(trim($email)));
		        $mensaje="Estimada(o): ".$nom."\n\n\t\t Nuestro Cliente ".$row['fullname']." le ha recomendado nuestro servicio de planchado &+, visite nuestro sitio http://www.soloplancho.com";
		         enviar_mensaje($are, $mensaje, 'Servicio de Planchado & +, SOLOPLANCHO.COM');
		}
	}
}
echo json_encode($data);
?>

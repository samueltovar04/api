<?php
include('correo.php');
include('db.php');
	$resultados = array();
	$resultados["error"] = "1";
	$resultados["hora"] = date("F j, Y, g:i a"); 
	$resultados["generador"] = "Enviado desde Solo Plancho" ;
if(isset($_REQUEST['balanza']) && !empty($_REQUEST['balanza']))
{
	$usu = $_REQUEST['balanza'];
	if(isset($_REQUEST['cedula'])){
		$ced = base64_decode(base64_decode($_REQUEST['cedula']));
	}else{
		$ced =0;
	}
}
?>

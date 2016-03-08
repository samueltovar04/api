<?php
include('db.php');
/* Define los valores que seran evaluados, en este ejemplo son valores estaticos,
en la verdadera aplicacion son dinamicos a partir de una base de datos */

/* Extrae los valores enviados desde la aplicacion movil */
if(empty($_REQUEST['email'])){
        $resultados["nombre"] = "";
	$resultados["email"] = "";
	$resultados["id_usuario"] = "";
	$resultados["cedula"] = "";
	$resultados["mensaje"] = "Campos Vacios";
	$resultados["validacion"] = "0";
}else
if(!isset($_REQUEST['email'])){
        $resultados["nombre"] = "";
	$resultados["email"] = "";
	$resultados["id_usuario"] = "";
	$resultados["cedula"] = "";
	$resultados["mensaje"] = "Campos Invalidos";
	$resultados["validacion"] = "0";
}else{
$usu = $_REQUEST['email'];
$cla = md5($_REQUEST['password']);

$resultados = array();
$resultados["hora"] = date("F j, Y, g:i a"); 
$resultados["generador"] = "Enviado desde Solo Plancho" ;
$q="select email, cedula,fullname, tipo, id_usuario, u.status,u.id_empresa,descripcion from usuarios u left join empresas e on(e.id_empresa=u.id_empresa) where email='$usu' and clave='$cla' and tipo='3'  and u.status='1' limit 1";
        $result = mysql_query($q);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
$usuarioValido = $row['email'];

/* verifica que el usuario y password concuerden correctamente */
if(  $usu == $usuarioValido){
	/*esta informacion se envia solo si la validacion es correcta */
	$resultados["nombre"] = $row['fullname'];
	$resultados["email"] = $row['email'];
	$resultados["id_usuario"] = $row['id_usuario'];
	$resultados["cedula"] = $row['cedula'];
	$resultados["mensaje"] = "Usuario Correcto";
	$resultados["validacion"] = "1";

}else{
	/*esta informacion se envia si la validacion falla */
		$resultados["nombre"] = "";
	$resultados["email"] = "";
	$resultados["id_usuario"] = "";
	$resultados["cedula"] = "";
	$resultados["mensaje"] = "Usuario y password incorrectos";
	$resultados["validacion"] = "0";
}
}
/*convierte los resultados a formato json*/

echo json_encode($resultados);
?>

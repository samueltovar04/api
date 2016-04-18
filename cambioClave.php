<?php
include('correo.php');
include('db.php');

if(isset($_REQUEST['newpas']) && !empty($_REQUEST['newpas']))
{
	$clave = md5($_REQUEST['newpas']);
	$usu = $_REQUEST['email'];
	$resultados = array();

		$q="update usuarios set password='$clave' where email='$usu'";
        	$result = mysql_query($q);
	
		if( $result){
			$resultados["mensaje"] = "Clave actualizada";
			$resultados["error"] = "1";
		}else{
			$resultados["mensaje"] = "Error actualizando ls clave";
			$resultados["error"] = "2";
		}
	
}else{
	$resultados["mensaje"] = "Error actualizando clave faltan datos";
	$resultados["error"] = "3";
}
/*convierte los resultados a formato json*/
echo json_encode($resultados);
?>

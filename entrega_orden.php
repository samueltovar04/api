<?php
require_once 'dbc.php'; 


if(isset($_REQUEST['cedula'])){
	$id = $_REQUEST['cedula'];
 $query="select reg_id,cedula,fullname,email,sexo,telefono,movil,id_cliente,ciudad,localidad,calle_av,edificio,numero from clientes left join direccion_cliente on(reg_id=id_cliente) where cedula=$id";
	$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

	$arr = array();
	if($result->num_rows > 0) {
	    	while($row = $result->fetch_assoc()) {
	        	$arr[] = $row;  
	    	}
	}
	else
	{
		 $arr=array("error"=>"Faltan Datos");
	}
}else
{
 $arr=array("error"=>"Faltan Datos");
}
# JSON-encode the response
echo $json_response = json_encode($arr);
?>

<?php 
require_once 'dbc.php'; // The mysql database connection script

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

if(isset($_REQUEST['id_cliente'])){
$id = $_REQUEST['id_cliente'];
$query="select reg_id,cedula,fullname,email,sexo,telefono,movil,id_cliente,ciudad,localidad,calle_av,edificio,numero from clientes left join direccion_cliente on(reg_id=id_cliente) where reg_id=$id";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

$arr = array();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $arr[] = $row;  
    }
}
}else
{
 $arr=array("error"=>"Faltan Datos");
}
# JSON-encode the response
echo $json_response = json_encode($arr);
?>

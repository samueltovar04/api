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

if(isset($_REQUEST['cedula'])){
$id =base64_decode(base64_decode($_REQUEST['cedula']));
$query="select cedula from clientes where cedula=$id and status='1'";
$result = $mysqli->query($query);

$arr = array();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["cedula"]) {
           $row["cedula"] = '1';
        }
        $arr[] = $row;  
    }
}else{
 $arr[]=array('cedula'=>'2');
}
}else
{
 $arr[]=array("cedula"=>"2");
}

# JSON-encode the response
echo $json_response = json_encode($arr);
?>

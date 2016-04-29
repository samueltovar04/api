<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

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

/*Extraer los datos enviados desde la app*/
$objDatos = json_decode(file_get_contents("php://input"));
//var_dump($objDatos[0]);



/*Guardar los datos en variables*/

if (isset($objDatos[0]->id_cliente)) {
        //recibo variables desde la app
    $id_cliente = $objDatos[0]->id_cliente;
    $movil = $objDatos[0]->movil;
    $email = $objDatos[0]->email;
    $cedula = $objDatos[0]->cedula;
    $fullname = $objDatos[0]->fullname;
    $sexo = $objDatos[0]->sexo;
    $telefono = $objDatos[0]->telefono;

    $ciudad = $objDatos[0]->ciudad;
    $localidad = $objDatos[0]->localidad;
    $calle_av = $objDatos[0]->calle_av;
    $edificio = $objDatos[0]->edificio;
    $numero = $objDatos[0]->edificio;


     //Se crea la consulta de actualizacion
    $query="UPDATE clientes 
             SET movil    = '$movil', 
                 cedula   = '$cedula',
                 fullname = '$fullname',
                 email    = '$email',
                 telefono = '$telefono'
             where reg_id='$id_cliente'";

    //se ejecuta y verifica la ejecucion del query
    if(mysql_query($query)){
        
        $query2="UPDATE direccion_cliente 
                 SET ciudad     = '$ciudad', 
                    localidad  = '$localidad',
                    calle_av   = '$calle_av',
                    edificio   = '$edificio',
                    numero     = '$numero'
                where id_cliente='$id_cliente'";
        if(mysql_query($query2))
        {
            $arr["mensaje"] = 'ok';
            $arr["query1"] = 'query1:    ' . $query;
            $arr["query2"] = 'query2:    ' . $query2;
        } 
        else 
        {
            $arr["mensaje"] = 'error en la ejecucion del query direccion' . mysql_error();
        }

    }else {
        $arr["mensaje"] = 'Error actualizando datos: ' . mysql_error();
        }  
 }else {
    $arr["mensaje"] = 'Error actualizando datos.';
    }


# JSON-encode the response
echo $json_response = json_encode($arr);
?>
<?php 
include "db.php";  // The mysql database connection script

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

$id_orden= $precio_pago = $iva = $total= $numero= $id_usuario = 0;
$metodo_pago= $forma_pago= $status = $forma_entrega = "No definido";

 $objDatos = json_decode(file_get_contents("php://input"));

 if(isset($objDatos->id_usuario)) 
 { 
     $id_orden = $objDatos->id_orden;
     $precio_pago=$objDatos->precio_pago;
     $iva=$objDatos->iva;
     $total=$objDatos->total;
     $metodo_pago=$objDatos->metodo_pago;
     $numero=$objDatos->numero;
     $forma_pago=$objDatos->forma_pago;
     $id_usuario=$objDatos->id_usuario;
     $status=$objDatos->status;
     $forma_entrega=$objDatos->retiro;

    $status = 'Por procesar';

    $query_precio = "SELECT precio_orden from orden_servicios WHERE id_orden = '$id_orden'";
    $objCosto = mysql_query($query_precio); 
    $rowCosto = mysql_fetch_array($objCosto, MYSQL_ASSOC);
    if(mysql_query($query_precio)){
        $precio_pago = $rowCosto['precio_orden'];      
    }else  
     $precio_pago = $rowCosto['00000'];

    $query_iva = "SELECT valor  from configuraciones WHERE descripcion = 'iva'";
    $objIva = mysql_query($query_iva); 
    $rowIva = mysql_fetch_array($objIva, MYSQL_ASSOC);
    if(mysql_query($query_iva)){
        $iva = $precio_pago * ($rowIva['valor'] / 100);      
    }else  
     $precio_pago = $rowCosto['00000'];

    $total = $precio_pago + $iva;

     //Creamos nuestra consulta sql
     $query="INSERT into pago_ordenes (id_orden, precio_pago, iva, total, metodo_pago, numero, forma_pago, id_usuario, status) 
                                        value ('$id_orden',
                                        '$precio_pago',
                                        '$iva',
                                        '$total',
                                        '$metodo_pago',
                                        '$numero',
                                        '$forma_pago',
                                        '$id_usuario',
                                        '$status')";
  
    if(mysql_query($query)){
        // y actualizamos la tabla de la orden con el forma_entrega en tienda o delivery  
        // Si forma de pago es pago al delivery se guarda el retiro como delivery por defecto.
        if ($forma_pago == 3) {
            $forma_entrega = 2; //retiro por delivery a domicilio
        }
        $queryEntrega="UPDATE orden_servicios SET forma_entrega = '$forma_entrega' where id_orden = '$id_orden'";
                if(mysql_query($queryEntrega)){
                     //Si todo salio bien imprimimos este mensaje
                    $arr["mensaje"] = 'ok';     
                }else {
                        $arr["mensaje"] = 'Error actualizando datos';
                }   
    }else 
        $arr["mensaje"] = 'error en la ejecucion del query'; 
 }else 
  $arr["mensaje"] = 'error en variables recibidas por el servidor'; 

# JSON-encode the response
echo $json_response = json_encode($arr);
?>
<?php 
/*debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
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

/*Declaracion de variables*/
$id_orden= $precio_pago = $iva = $total= $numero= $id_usuario = 0;
$metodo_pago= $forma_pago= $status = $forma_entrega = "No definido";

/*Extraer los datos enviados desde la app*/
$objDatos = json_decode(file_get_contents("php://input"));

/*Guardar los datos en variables*/
 if(isset($objDatos->id_usuario))
 {
    $id_orden = $objDatos->id_orden;
    $metodo_pago=$objDatos->metodo_pago;
    if (isset($metodo_pago)) {
        switch ($metodo_pago) {
            case '1':
                $metodo_pago = 'debito';
                break;
            case '2':
                $metodo_pago = 'credito';
                break;
            case '3':
                $metodo_pago = 'mercadopago';
                break;
            case '4':
                $metodo_pago = 'transferencia';
                break;
            case '5':
                $metodo_pago = 'deposito';
                break;
            default:
                $metodo_pago = 'no definido';
                break;
        }
    }
    $numero=$objDatos->numero;

    $forma_pago=$objDatos->forma_pago;
    if (isset($forma_pago)) {
        switch ($forma_pago) {
            case '1':
                $forma_pago='on-line';
                break;
            case '2':
                $forma_pago='tienda';
                break;
            case '3':
                $forma_pago='datafono';
                break;
            default:
                $forma_pago='no definido';
                break;
        }
    }
    $id_usuario=$objDatos->id_usuario;

    $forma_entrega=$objDatos->retiro;
    if (isset($forma_entrega)) {
        switch ($forma_entrega) {
            case '1':
                $forma_entrega='tienda';
                break;
            case '2':
                $forma_entrega='domicilio';
                break;
            
            default:
                $forma_entrega='no definido';
                break;
        }
    }

    $status = 1;

   /* $query_precio = "SELECT precio_orden from orden_servicios WHERE id_orden = '$id_orden'";
    $objCosto = mysql_query($query_precio); 
    $rowCosto = mysql_fetch_array($objCosto, MYSQL_ASSOC);
    if(mysql_query($query_precio)){
        $precio_pago = $rowCosto['precio_orden'];      
    }else  
     $precio_pago = $rowCosto['00000'];

    Se calcula el iva
    $query_iva = "SELECT valor  from configuraciones WHERE codigo = 'impuesto'";
    $objIva = mysql_query($query_iva); 
    $rowIva = mysql_fetch_array($objIva, MYSQL_ASSOC);
    if(mysql_query($query_iva)){
        $iva = $precio_pago * $rowIva['valor'];      
    }else  
        $precio_pago = $rowCosto['00000'];
*/
    /*Se calcula el monto total de pago
    $total = $precio_pago + $iva;*/
    /*se declara la factura
    
    $numero_factura = $id_orden;
    if($id_orden<10){
                $numero_factura='000000'.$id_orden;
            }elseif($id_orden<100){
                $numero_factura='00000'.$id_orden;
            }elseif($id_orden<1000){
                $numero_factura='0000'.$id_orden;
            }elseif($id_orden<10000){
                $numero_factura='000'.$id_orden;
            }elseif($id_orden<100000){
                $numero_factura='00'.$id_orden;
            }elseif($id_orden<1000000){
                $numero_factura='0'.$id_orden;
            } 
*/
     //Creamos nuestra consulta sql
     $query="UPDATE pago_ordenes set metodo_pago='$metodo_pago', numero='$numero', forma_pago='$forma_pago', id_usuario='$id_usuario', status='$status' where id_orden='$id_orden'";
  
    if(mysql_query($query)){
        // y actualizamos la tabla de la orden con el forma_entrega en tienda o delivery  
        // Si forma de pago es pago al delivery se guarda el retiro como delivery por defecto.
        if ($forma_pago == 3) {
            $forma_entrega = 2; //retiro por delivery a domicilio
        }

        $status = 8;
        $queryEntrega="UPDATE orden_servicios SET forma_entrega = '$forma_entrega', status = '$status' where id_orden = '$id_orden'";
                if(mysql_query($queryEntrega)){
                     //Si todo salio bien imprimimos este mensaje
                    $arr["mensaje"] = 'ok';     
                }else {
                        $arr["mensaje"] = 'Error actualizando datos: ' . mysql_error();
                }   
    }else 
        $arr["mensaje"] = 'error en la ejecucion del query: ' . mysql_error(); 
 }else 
  $arr["mensaje"] = 'error en variables recibidas por el servidor'; 

# JSON-encode the response
echo $json_response = json_encode($arr);
?>

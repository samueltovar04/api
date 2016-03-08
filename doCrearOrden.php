<?php 
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
$peso_libras=$cantidad_piezas=$recepcion=$id_cliente=$id_empresa=0;

 $objDatos = json_decode(file_get_contents("php://input"));

 $queryArticulos="select descripcion, id_articulo, categoria, status from articulos where status = '1'";
 $objArticulos = mysql_query($queryArticulos);

 $querycosto= "select valor from configuraciones where codigo='costo' and status = '1'"; 
 $objCosto = mysql_query($querycosto); 
 $rowCosto = mysql_fetch_array($objCosto, MYSQL_ASSOC); 

 if( $objDatos->peso_libras != NULL) 
 { 
     $peso_libras= $objDatos->peso_libras;
     $recepcion= $objDatos->recepcion;
     $id_cliente = $objDatos->id_cliente;
     $id_empresa = $objDatos->id_empresa;
     
    /*Calculo de precio*/
    if(isset($rowCosto['valor']) && !empty($rowCosto['valor'])){ 
        $precio= $peso_libras * $rowCosto['valor']; 
     }
    else{ 
        $precio= $peso_libras * 15000; 
     }

     /*momentaneo*/
     $objDatos->peso_libras = 0;
     $objDatos->recepcion = 0;
     $objDatos->id_cliente = 0;
     $objDatos->id_empresa = 0;

     /*esto se eliminara
     unset($objDatos['peso_libras']);
     unset($objDatos['recepcion']);
     unset($objDatos['id_cliente']);
     unset($objDatos['id_empresa']);*/

     foreach ($objDatos as $id => $valor) {
         # code...
        if ($id !== 'peso_libras' || $id !== 'recepcion' || $id !== 'id_cliente' || $id !== 'id_empresa' || $id !== 'precio') {
            # code...
            $cantidad_piezas += $valor;
        }
        
     }
     //$cantidad_piezas= $objDatos->peso_libras;
     //Creamos nuestra consulta sql     
    $query="INSERT INTO orden_servicios(peso_libras,cantidad_piezas,recepcion,id_cliente, id_empresa, precio_orden)  VALUES ('$peso_libras', '$cantidad_piezas', '$recepcion', '$id_cliente', '$id_empresa', '$precio')";
    if(mysql_query($query)){
         
        $ultimo_id = mysql_insert_id();

        foreach ($objDatos as $id => $valor) {
             
            if ($id !== 'peso_libras' || $id !== 'recepcion' || $id !== 'id_cliente' || $id !== 'id_empresa' || $id !== 'precio') {
                
               if ($id != 0) {
                    $query2="insert into orden_articulos (id_orden, id_articulo, cantidad)
                                         value ('$ultimo_id', '$id', '$valor')";
                    mysql_query($query2);
               }
               
                    }     
         }
        echo "ok";
    }else 
        echo "error"; 

 }else 
  echo "error"; 
 ?>
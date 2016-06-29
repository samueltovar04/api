<?php
 include "db.php";
 include "correo.php";

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

 $querycosto= "select valor from configuraciones where codigo='costo' and status = '1' limit 1";
 $objCosto = mysql_query($querycosto);
 $rowCosto = mysql_fetch_array($objCosto, MYSQL_ASSOC);

 if( $objDatos->id_cliente != NULL)
 {
     $peso_libras= 0;
     $recepcion= $objDatos->recepcion;
     $id_cliente = $objDatos->id_cliente;
     $id_empresa = $objDatos->id_empresa;
	
     /* Datos del Cliente */
     $querycliente= "select fullname,email from clientes where reg_id='$id_cliente' and status = '1' limit 1";
     $objCliente = mysql_query($querycliente);
     $rowCliente = mysql_fetch_array($objCliente, MYSQL_ASSOC);
    /*Calculo de precio*/
    if(isset($rowCosto['valor']) && !empty($rowCosto['valor'])){
        $precio= $peso_libras * $rowCosto['valor'];
     }
    else{
        $precio= $peso_libras * 15000;
     }

     /*momentaneo*/
     //$objDatos->peso_libras = 0;
     $objDatos->recepcion = 0;
     $objDatos->id_cliente = 0;
     $objDatos->id_empresa = 0;

     foreach ($objDatos as $id => $valor) {
         # code...
        if ($id !== 'recepcion' || $id !== 'id_cliente' || $id !== 'id_empresa' || $id !== 'precio') {
            # code...
            $cantidad_piezas += $valor;
        }
     }
     //$cantidad_piezas= $objDatos->peso_libras;
     //Creamos nuestra consulta sql
 
   if($recepcion==1){
	$re="domicilio";
	$query="INSERT INTO orden_servicios(cantidad_piezas,recepcion,id_cliente, id_empresa, precio_orden,status)  VALUES ('$cantidad_piezas', '$re', '$id_cliente', '$id_empresa', '$precio','2')";
	}
    else if($recepcion==2)
	{
		$re="drop-off";
		$query="INSERT INTO orden_servicios(cantidad_piezas,recepcion,id_cliente, id_empresa, precio_orden) VALUES ('$cantidad_piezas', '$re', '$id_cliente', '$id_empresa', '$precio')";
	}
	else{
		$re="tienda";
		$query="INSERT INTO orden_servicios(cantidad_piezas,recepcion,id_cliente, id_empresa, precio_orden)  VALUES ('$cantidad_piezas', '$re', '$id_cliente', '$id_empresa', '$precio')";
	}
    if(mysql_query($query)){

        $ultimo_id = mysql_insert_id();

        foreach ($objDatos as $id => $valor) {

            if ($id !== 'recepcion' || $id !== 'id_cliente' || $id !== 'id_empresa' || $id !== 'precio') {

               if ($id != 0) {
                    $query2="insert into orden_articulos (id_orden, id_articulo, cantidad)
                                         value ('$ultimo_id', '$id', '$valor')";
                    mysql_query($query2);
               }

            }
	}
	 /* Datos del IKARO*/
        $queryusu= "select fullname,email,movil,cedula from usuarios u inner join usuario_ordenes us on(us.id_usuario=u.id_usuario and us.status=1) where id_orden='$ultimo_id' limit 1";
        $objUsu = mysql_query($queryusu);
        $rowUsuario = mysql_fetch_array($objUsu, MYSQL_ASSOC);
	$are=array(0=>strtolower(trim($rowCliente['email'])),1=>strtolower(trim($rowUsuario['email'])));
                        
        $mensaje="Estimado(a) ".$rowCliente['fullname']."\n\n\t\tEn atención a su orden de servicio # $ord , la misma ha sido asignada a nuestro IKARO:"
        ."".$rowUsuario['fullname']." Cédula: ".$rowUsuario['cedula']." Celular: ".$rowUsuario['movil'].", para ser retirada en su domicilio.\n  www.soloplancho.com";
         $this->enviar_mensaje($are, $mensaje, 'ORDEN SERVICIO ASIGNADA A IKARO, SOLOPLANCHO.COM');
        echo "ok";
    }else
        echo "error" . mysql_error() . $query;

 }else
  echo "error";
 ?>

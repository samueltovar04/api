<?php
 include "db.php";
 if(isset($_POST['peso_libras']))
 {
 $id_orden=$_POST['id_orden'];
 $peso_libras=$_POST['peso_libras'];
 $precio_orden=$_POST['precio_orden'];
 $cantidad_piezas=$_POST['cantidad_piezas'];
 $fecha_solicitud=date("Y-m-d");
 $fecha_entrega=$_POST['fecha_entrega'];
 $recepcion=$_POST['recepcion'];
 $id_cliente=$_POST['id_cliente'];
 $status=$_POST['status'];
 $id_empresa=$_POST['id_empresa'];
 
 $q=mysql_query("INSERT INTO `orden_servicios` (`peso_libras`,`cantidad_piezas`,`recepcion`) VALUES ('$peso_libras','$cantidad_piezas','$recepcion')");
 if($q)
  echo "ok";
 else
  echo "error";
 }
 ?>
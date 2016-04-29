<?php
include('correo.php');
include('db.php');

/* 

if($registro['status']=='20') $chek='Anulada';
if($registro['status']=='1') $chek='Nueva Orden'; 
if($registro['status']=='2') $chek='Asignada Delivery';
if($registro['status']=='3') $chek='Entregada Delivery';
if($registro['status']=='4') $chek='En Tienda';
if($registro['status']=='5') $chek='Asignada Operador';
if($registro['status']=='6') $chek='Planchada';
if($registro['status']=='7') $chek='Pendiente Pago';
if($registro['status']=='8') $chek='Cancelada';
if($registro['status']=='9') $chek='Enviada';
if($registro['status']=='10') $chek='Entregada Cliente';
if($registro['status']=='11') $chek='>Observación';
 */
$resultados["error"] = "3";
$resultados["mensaje"] ='';
if(isset($_REQUEST['id_orden']) && !empty($_REQUEST['id_orden']))
{
	$ord = $_REQUEST['id_orden'];
	$query="select o.status,id_orden,reg_id,cedula,fullname,movil,email from orden_servicios o,clientes where reg_id=id_cliente and id_orden='$ord' limit 1";
	$res = mysql_query($query);
	$cli="";
		$row=mysql_fetch_array($res);
		$cli=$row['reg_id'];
	
	$numero = $_REQUEST['numero'];
	$date=date("Y-m-d h:i:s");
	$q="update pago_ordenes set status='2',numero='$numero',fecha_pago='$date' where id_orden='$ord'";
        $result = mysql_query($q);
	if( $result){
		$resultados["mensaje"] = "Orden # $ord Pago Aceptado";
		$resultados["error"] = "1";
		$are=array(0=>strtolower(trim($row['email'])));
                $mensaje="Estimado(a): ".$row['fullname']."\n\n\t\t La orden  servicio de planchado # $ord Fue Cancela mediante Datafono\n Número de transacción: $numero, Gracias por usar nuestro Servicio de planchado..."
                               . "Su cuenta email: ".strtolower(trim($row['email']));
			$arreglo=array('id_cliente'=>$cli,'titulo'=>"ORDEN SERVICIO CANCELADA A DOMICILIO",'mensaje'=>$mensaje);
			enviar_curl("http://api.soloplancho.com/notifications/sendNotification.php", $arreglo);
                        enviar_mensaje($are, $mensaje, 'ORDEN SERVICIO CANCELADA A DOMICILIO, SOLOPLANCHO.COM');
                        
	}else{
			$resultados["mensaje"] = "Error actualizando orden ($ord) $q";
			$resultados["error"] = "2";
		}
		
}
else{
	$resultados["mensaje"] = "Error actualizando orden faltan datos";
	$resultados["error"] = "3";
}

echo json_encode($resultados);
?>

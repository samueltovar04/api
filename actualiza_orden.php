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
if(isset($_REQUEST['id_orden']) && !empty($_REQUEST['id_orden']))
{
	$usu = $_REQUEST['id_orden'];
	if(isset($_REQUEST['cedula'])){
		$ced = base64_decode(base64_decode($_REQUEST['cedula']));
	}else{
		$ced =0;
	}	

	if(isset($_REQUEST['latitud'])){
		$lat = $_REQUEST['latitud'];
		$long = $_REQUEST['longitud'];
		$gps="update clientes set latitud='$lat', longitud='$long' where latitud='0' and cedula='$ced'";
		$resup = mysql_query($gps);
	}
	$resultados = array();
	$resultados["hora"] = date("F j, Y, g:i a"); 
	$resultados["generador"] = "Enviado desde Solo Plancho" ;
	$query="select o.status,id_orden,cedula,fullname,movil,email from orden_servicios o,clientes where reg_id=id_cliente and id_orden='$usu' and cedula='$ced' limit 1";
	$res = mysql_query($query);
	$date=date("Y-m-d H:i:s");
	if($res){
		$row=mysql_fetch_array($res);
	}else{
		$row=array();
	}
    if(count($row)>1){
	if($row['status']=='2'){
		$q="update orden_servicios set status='3' where id_orden='$usu'";
        	$result = mysql_query($q);
	
		if($result){
			$resultados["mensaje"] = "Orden # $usu Recibida por Delivery";
			$up2="update usuario_ordenes set status='3',fecha_cumple='$date' where id_orden='$usu' and status='1'";
        		$resulta = mysql_query($up2);
			$query="select uo.id_orden,email from usuario_ordenes uo, usuarios u where u.id_usuario=uo.id_usuario and uo.id_orden='$usu' and uo.status='3' limit 1";
			$res = mysql_query($query);
				$deli=array();
			if($res){
				$deli=mysql_fetch_array($res);
			}else{
				$deli['email']=$row['email'];
			}
			
			$resultados["error"] = "1";
			$are=array(0=>strtolower(trim($row['email'])),1=>strtolower(trim($deli['email'])));
                        $mensaje="Estimado(a): ".$row['fullname']."\n\n\t\t La orden de servicio de planchado # $usu Recibida por Delivery \n por soloplancho empresa líder en planchado también visite nuestra web http://www.soloplancho.com\n"
                               . "Su cuenta email: ".strtolower(trim($row['email']));
                        enviar_mensaje($are, $mensaje, 'ORDEN SERVICIO RECIBIDA POR DELIVERY, SOLOPLANCHO.COM');
                        
			
		}else{
			$resultados["mensaje"] = "Error actualizando orden ($usu)";
			$resultados["error"] = "2";
		}
	}else
	if($row['status']=='9'){
	    $sql="select status,forma_pago from pago_ordenes where id_orden='$usu' limit 1";
            $resulp = mysql_query($sql);
	    $pago=mysql_fetch_array($resulp);
	    if($pago['status']=='2'){
		$q="update orden_servicios set status='10',fecha_entrega='$date' where id_orden='$usu'";
	        $result = mysql_query($q);
		if( $result){
			$up2="update usuario_ordenes set status='5',fecha_cumple='$date' where id_orden='$usu' and status='4'";
	        	$resulta = mysql_query($up2);
			$query="select uo.id_orden,email from usuario_ordenes uo, usuarios u where u.id_usuario=uo.id_usuario and uo.id_orden='$usu' and uo.status='5' limit 1";
			$res = mysql_query($query);
			$deli=array();
			$deli['email']=$row['email'];
			$deli=mysql_fetch_array($res);
			$resultados["mensaje"] = "Orden # $usu Entregada Al Cliente";
			$resultados["error"] = "1";
			$are=array(0=>strtolower(trim($row['email'])),1=>strtolower(trim($deli['email'])));
                        $mensaje="Estimado(a): ".$row['fullname']."\n\n\t\t La orden de servicio de planchado # $usu Entregada Al Cliente \n por soloplancho empresa líder en planchado también visite nuestra web http://www.soloplancho.com\n"
                        ."Su cuenta email: ".strtolower(trim($row['email']));
                        enviar_mensaje($are, $mensaje, 'ORDEN SERVICIO ENTREGADA AL CLIENTE, SOLOPLANCHO.COM');			
			
		}else{
			$resultados["mensaje"] = "Error actualizando orden ($usu)";
			$resultados["error"] = "2";
		}
	    }else{
			$resultados["mensaje"] = "Orden de servicio ($usu) no se a cancelado, verifique forma pago y actualice";
			$resultados["error"] = "2";
		}
	}
	else{
		$resultados["mensaje"] = "Orden no esta en satus Asignada Delivery o Enviada al Cliente";
		$resultados["status"] = $row["status"];
		$resultados["id_orden"] = $row["id_orden"];
		$resultados["nombre"] = " Cliente: ".$row["cedula"]." ".$row["fullname"]." <br> Teléfono Movil: ".$row["movil"]."<br> Correo: ".$row["correo"];
		$resultados["error"] = "4";
	}
    }else{
	$resultados["mensaje"] = "Error Qr Cliente incorrecto verifique datos del cliente";
	$resultados["error"] = "3";
	}
}else{
	$resultados["mensaje"] = "Error actualizando orden faltan datos";
	$resultados["error"] = "3";
}
/*convierte los resultados a formato json*/
echo json_encode($resultados);
?>

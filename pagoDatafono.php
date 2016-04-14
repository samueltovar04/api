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
	$numero = $_REQUEST['numero'];
	$date=date("Y-m-d h:i:s");
	$q="update pago_ordenes set status='2',numero='$numero',fecha_pago='$date' where id_orden='$ord'";
        $result = mysql_query($q);
	if( $result){
		$resultados["mensaje"] = "Orden # $ord Pago Aceptado";
		$resultados["error"] = "1";
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

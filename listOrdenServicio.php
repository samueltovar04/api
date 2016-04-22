<?php
$data=array();
if(!isset($_REQUEST['id_usuario'])){
   $data=array('error'=>1);
} else {
   include "db.php";
   $idc=$_REQUEST['id_usuario'];
	$notic="";
	if(isset($_REQUEST['notify'])){
		$notic="and notify=0";
	}
 //peso_libras , precio_orden, cantidad_piezas, fecha_solicitud, fecha_entrega,
   $q=mysql_query("select c.cedula Cedula, c.fullname Cliente, c.email Correo, c.movil Movil,c.telefono Telefono, o.id_orden Orden, DATE_FORMAT(fecha_asigna, '%d-%m-%Y') AS \"Fecha Asignado\", recepcion Recepcion, ciudad, calle_av,localidad ,edificio,dc.numero, o.status,o.cantidad_piezas, o.observacion, o.peso_descuento,o.peso_libras,o.forma_entrega, uo.status  UsuarioOrdenS,uo.notify, po.forma_pago, po.fecha_pago, po.metodo_pago, po.numero_factura, po.precio_pago,po.iva,po.total,po.status Pago from orden_servicios o inner join clientes c on(o.id_cliente=c.reg_id) left join direccion_cliente dc on (reg_id=dc.id_cliente) inner join usuario_ordenes uo on(uo.id_orden=o.id_orden and uo.status IN('1','4')) left join pago_ordenes po on(o.id_orden=po.id_orden) where uo.id_usuario='$idc' $notic and o.status IN('2','9')");
   while ($row=mysql_fetch_object($q)){
	if($row->Cedula!=NULL)
	$data[]=$row;
	if(!empty($notic)){
		$q3="update usuario_ordenes set notify=1 where id_orden='".$row->Orden."' and id_usuario='$idc'";
			mysql_query($q3);
		 $q=mysql_query("select c.cedula Cedula, c.fullname Cliente, c.email Correo, c.movil Movil,c.telefono Telefono, o.id_orden Orden, DATE_FORMAT(fecha_asigna, '%d-%m-%Y') AS \"Fecha Asignado\", recepcion Recepcion, ciudad, calle_av,localidad ,edificio,dc.numero, o.status,o.cantidad_piezas, o.observacion, o.peso_descuento,o.peso_libras,o.forma_entrega, uo.status  UsuarioOrdenS,uo.notify, po.forma_pago, po.fecha_pago, po.metodo_pago, po.numero_factura, po.precio_pago,po.iva,po.total,po.status Pago from orden_servicios o inner join clientes c on(o.id_cliente=c.reg_id) left join direccion_cliente dc on (reg_id=dc.id_cliente) inner join usuario_ordenes uo on(uo.id_orden=o.id_orden and uo.status IN('1','4')) left join pago_ordenes po on(o.id_orden=po.id_orden) where uo.id_usuario='$idc' and o.status IN('2','9')");
		 while ($row=mysql_fetch_object($q)){
			if($row->Cedula!=NULL)
			$data[]=$row;
		}
	}
   }
}
$resultadosJson= json_encode($data);
echo '{"VALOR"' . ':' . $resultadosJson . '}';
exit;
?>

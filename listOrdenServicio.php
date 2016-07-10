<?php
$data=array();
if(!isset($_REQUEST['id_usuario'])){
   $data=array('error'=>1);
} else {
   include "db.php";
   $idc=$_REQUEST['id_usuario'];
 //peso_libras , precio_orden, cantidad_piezas, fecha_solicitud, fecha_entrega,
   $q=mysql_query("select c.cedula Cedula, c.fullname Cliente, c.email Correo, c.movil Movil,c.telefono Telefono, o.id_orden Orden, DATE_FORMAT(fecha_asigna, '%d-%m-%Y') AS \"Fecha Asignado\", recepcion Recepcion, ciudad, direccion, o.status,o.cantidad_piezas, o.observacion, o.peso_descuento,o.peso_libras,o.forma_entrega, uo.status  UsuarioOrdenS,uo.notify, po.forma_pago, po.fecha_pago, po.metodo_pago, po.numero_factura, po.precio_pago,po.iva,po.total,po.status Pago, u.status UsuarioStatusfrom orden_servicios o inner join clientes c on(o.id_cliente=c.reg_id) left join direccion_cliente dc on (reg_id=dc.id_cliente) inner join usuario_ordenes uo on(uo.id_orden=o.id_orden and uo.status IN('1','4')) left join usuarios u on(uo.id_usuario=u.id_usuario) left join pago_ordenes po on(o.id_orden=po.id_orden) where u.id_usuario='$idc' and o.status IN('2','9')");
	
   while ($row=mysql_fetch_object($q)){
	if($row->Cedula!=NULL)
		$data[]=$row;
   }

	/*$q2=mysql_query("select c.cedula Cedula, c.fullname Cliente, c.email Correo, c.movil Movil,c.telefono Telefono, DATE_FORMAT(b.fecha_registro, '%d-%m-%Y') AS \"Fecha Asignado\", ciudad, direccion,b.codigo Orden,'domicilio' as Recepcion,'0' as observacion,b.id_balanza,b.status from clientes c left join direccion_cliente dc on (reg_id=dc.id_cliente) inner join balanzas b on(c.id_balanza=b.id_balanza and b.status=20) inner join usuarios u on (u.id_usuario=b.id_usuario)  where u.id_usuario='$idc'");
	while ($row2=mysql_fetch_object($q2)){
		$data[]=$row2;
	}*/
}
$resultadosJson= json_encode($data);
if(empty($resultadosJson)) $resultadosJson= '[]';
echo '{"VALOR"' . ':' . $resultadosJson . '}';
exit;
?>

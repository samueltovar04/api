<?php
$data=array();
if(!isset($_REQUEST['id_usuario'])){
   $data=array('error'=>1);
} else {
   include "db.php";
   $idc=$_REQUEST['id_usuario'];
 //peso_libras , precio_orden, cantidad_piezas, fecha_solicitud, fecha_entrega,
   $q=mysql_query("select c.cedula Cedula, c.fullname Cliente, c.email Correo, o.id_orden Orden, DATE_FORMAT(fecha_asigna, '%d-%m-%Y') AS \"Fecha Asignado\", recepcion Recepcion, ciudad, calle_av,localidad ,edificio,numero,o.status from orden_servicios o inner join clientes c on(o.id_cliente=c.reg_id) left join direccion_cliente dc on (reg_id=dc.id_cliente) inner join usuario_ordenes uo on(uo.id_orden=o.id_orden and uo.status IN('1','4')) where uo.id_usuario='$idc' and o.status IN('2','9')");
   while ($row=mysql_fetch_object($q)){
	if($row->Cedula!=NULL)
	$data[]=$row;
   }
}
$resultadosJson= json_encode($data);
echo '{"VALOR"' . ':' . $resultadosJson . '}';
exit;
?>

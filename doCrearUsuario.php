<?php 
 include "db.php"; 
 include('correo.php');
 if(isset($_GET['email'])) 
 { 
      $celular=mysql_escape_string($_GET['movil']);
     $email=mysql_escape_string($_GET['email']);
     $cedula=mysql_escape_string($_GET['cedula']);
     $nombre=mysql_escape_string($_GET['fullname']);
     $sexo=mysql_escape_string($_GET['sexo']);
     //$telefono=mysql_escape_string($_GET['telefono']);

     $clave=mysql_escape_string($_GET['password']);
    if(isset($_GET['localidad'])){
         $ciudad=mysql_escape_string($_GET['ciudad']);
         $localidad=mysql_escape_string($_GET['localidad']);
//         $av=mysql_escape_string($_GET['calle_av']);
  //       $edificio=mysql_escape_string($_GET['edificio']);
    //     $numero=mysql_escape_string($_GET['numero']);
      }
        //$date=date("Y-m-d");
     //Creamos nuestra consulta sql
     $query="insert into clientes (movil, cedula, fullname, sexo, email, password) value ('$celular', '$cedula', '$nombre','$sexo','$email','$clave')";
     $select="select max(cedula) cedula from clientes where email='$email' or cedula='$cedula' limit 1";
        $objUsu = mysql_query($select);
        $rowUsuario = mysql_fetch_array($objUsu, MYSQL_ASSOC);
    if(isset($rowUsuario['cedula'])){
        echo "existe";
        }else
    if(mysql_query($query)){
         //Si todo salio bien imprimimos este mensaje
          $ultimo_id = mysql_insert_id();
        $query2="insert into direccion_cliente (ciudad, direccion, id_cliente) value ('$ciudad', '$localidad','$ultimo_id')";
        if(mysql_query($query2)){
        echo "ok";
	$are=array(0=>strtolower(trim($email)));
	$mensaje="Estimada(o)\n\tLe damos la mas cordial bienvenida a SoloPlancho.\n"
."Por favor elabore su Orden de Servicio (OS) vía APP.\n www.soloplancho.com\n"
."Su cuenta de correo: ".strtolower(trim($email))."\n"
."Su usuario de ingreso es su Cédula ".$cedula." y Clave: ".trim($clave);

	enviar_mensaje($are, $mensaje, 'REGISTRO EXITOSO EN SOLOPLANCHO.COM');
        }else 
            echo "error";
    }else 
        echo "error"; 
 }else 
  echo "error"; 
 ?>

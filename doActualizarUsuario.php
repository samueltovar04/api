<?php 
 include "db.php"; 
 
 if(isset($_POST['id_cliente'])) 
 { 
    $celular=mysql_escape_string($_POST['movil']);
    $email=mysql_escape_string($_POST['email']);
    $cedula=mysql_escape_string($_POST['cedula']);
    $nombre=mysql_escape_string($_POST['fullname']);
    $sexo=mysql_escape_string($_POST['sexo']);
    $telefono=mysql_escape_string($_POST['telefono']);
     
    $id_cliente=mysql_escape_string($_POST['id_cliente']);

    $ciudad=mysql_escape_string($_POST['ciudad']);
    $localidad=mysql_escape_string($_POST['localidad']);
    $av=mysql_escape_string($_POST['calle_av']);
    $edificio=mysql_escape_string($_POST['edificio']);
    $numero=mysql_escape_string($_POST['numero']);
        
    $date=date("Y-m-d");
     //Creamos nuestra consulta sql
     $query="UPDATE clientes SET movil ='$celular', 
                cedula= $cedula,
                fullname = $nombre,
                sexo = $sexo,
                email = $email,
                telefono = $telefono,
                reg_date = $date)
                where id_cliente='$id_cliente'";
  
    if(mysql_query($query))
    {
        $query2="UPDATE direccion_cliente SET ciudad ='$ciudad', 
                localidad = $localidad,
                calle_av = $av,
                edificio = $edificio,
                numero = $numero,
                id_cliente = $telefono,)
                where id_cliente='$id_cliente'";
        if(mysql_query($query2)) echo "ok"; else echo "error";

    }else echo "error"; 
 }else echo "error"; 
 ?>
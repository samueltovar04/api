<?php 
include "db.php";  // The mysql database connection script

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

 $id_usuario = $email = $claveactual = $nuevaclave = $confirmaclave = "No definido";

 $objDatos = json_decode(file_get_contents("php://input"));


 if(isset($objDatos->id_usuario)) 
 { 
     $id_usuario = $objDatos->id_usuario;
     $email = $objDatos->email;
     $claveactual=$objDatos->claveactual;
     $nuevaclave=$objDatos->nuevaclave;
     $confirmaclave=$objDatos->confirmaclave;

     $queryClave= "SELECT password from clientes WHERE reg_id = '$id_usuario'"; 
     $objClave = mysql_query($queryClave); 
     $rowClave = mysql_fetch_array($objClave, MYSQL_ASSOC); 

     if ($nuevaclave == $confirmaclave) {
         if ($claveactual == $rowClave['password']) {
                $query="UPDATE Clientes SET password = '$nuevaclave' where email = '$email' and reg_id = '$id_usuario'";
                if(mysql_query($query)){
                     //Si todo salio bien imprimimos este mensaje
                    $arr["mensaje"] = 'ok';     
                }else {
                        $arr["mensaje"] = 'Correo inválido';
                } 
         } else {
            $arr["mensaje"] = 'Error, Clave actual incorrecta.';
         }
     } else {
        $arr["mensaje"] = 'Error, claves no coinciden'; 
     }
     
 }else 
  $arr["mensaje"] = 'error en variables recibidas por el servidor'; 

# JSON-encode the response
echo $json_response = json_encode($arr);
?>
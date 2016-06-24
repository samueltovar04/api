<?php 
include('correo.php');
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

$correo= "No definido";

 $objDatos = json_decode(file_get_contents("php://input"));

 if(isset($objDatos->correo)) 
 { 
     $correo = $objDatos->correo;
     $queryCorreo= "SELECT reg_id,cedula,email,fullname,password from clientes WHERE email = '$correo'";
     $objCorreo = mysql_query($queryCorreo);
     $rowCorreo = mysql_fetch_array($objCorreo, MYSQL_ASSOC);

    if($correo == $rowCorreo["email"]){
                    $arr["mensaje"] = 'ok';
                    $are=array(0=>strtolower(trim($rowCorreo["email"])));
                    $mensaje="Estimado(a): ".$rowCorreo["fullname"]."\n\n\t\t Cédula: ".$rowCorreo["cedula"]." , clave de acceso es ".$rowCorreo["password"]
                        ." para ingresar en la App de soloplancho empresa líder en planchado también visite nuestra web http://www.soloplancho.com\n"
                        . "Recuerde que para ingresar debe colocar el Número de Cédula registrado ";
		    $arreglo=array('id_cliente'=>$rowCorreo["reg_id"],'titulo'=>"Recuperar Clave de Acceso",'mensaje'=>$mensaje);
                    enviar_curl("http://api.soloplancho.com/notifications/sendNotification.php", $arreglo);
	            enviar_mensaje($are, $mensaje, 'Recuperar Clave de Acceso, SOLOPLANCHO.COM');

         }
    }else 
        $arr["mensaje"] = 'error en la ejecucion del query'; 

# JSON-encode the response
echo $json_response = json_encode($arr);
?>

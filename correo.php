<?php
$resultados=array();
class cMailer{

	var $_Addresses;
	var $_countAdd = 0;
	var $_ConexionSMTP;
	var $_Sender;
	var $_server;
	var $_Subject = "";
	var $strRCPT;
	function cMailer(){
		
	}

	function AddAddress($address){
		$this->_Addresses[$this->_countAdd] = $address;
		$this->_countAdd++;
	}

	function AddSender($sender){
		$this->_Sender = $sender;
	}

	function AddMessage($message){
		$this->_Message = $message;
	}

	function AddSubject($subject){
		$this->_Subject = $subject;
	}

	function Send(){
		$strRCPT="";
		$strEHLO = "HELO ".$this->_server."\r\n";
		fputs($this->_ConexionSMTP,$strEHLO);

		$strMAIL = "MAIL FROM: ".$this->_Sender."\r\n";
		fputs($this->_ConexionSMTP,$strMAIL);

		for($i=0;$i<$this->_countAdd;$i++){
			$strRCPT .= "RCPT TO: ".$this->_Addresses[$i]."\r\n";
		}
		$strRCPT .= "\r\n";
		fputs($this->_ConexionSMTP,$strRCPT);


		$strDATA1 = "DATA\r\n";
		fputs($this->_ConexionSMTP,$strDATA1);
		

		if($this->_Subject != ""){

			$strS = "Subject: ".$this->_Subject."\r\n\r\n";
			fputs($this->_ConexionSMTP,$strS);
		}


		$strDATA2 = $this->_Message."\r\n.\r\n";
		fputs($this->_ConexionSMTP,$strDATA2);
		
		fputs($this->_ConexionSMTP,"QUIT\r\n");
		fclose($this->_ConexionSMTP);
	
	}

	function AddHost($host,$port=2500){
           try {
if (!$this->_ConexionSMTP = fsockopen("$host","$port",$e,$em,5))
throw new Exception ();
} catch (Exception $e) {
    $resultados['mensaje_correo']="Servidor no Responde";
return false;
}
		fgets($this->_ConexionSMTP,4096) or die('Ruta sin acceso');
		$this->_server = $host;
                return true;
	}

}


function enviar_mensaje($log,$mensaje,$asunto)
{
    $header = "From: soloplancho@soloplancho.com \n";
    $header .= "Mime-Version: 1.0\nContent-Type: text/html; charset=UTF-8\nContent-Transfer-Encoding: 7bit";
    $descripcion = "Este mensaje fue enviado por SOLOPLANCHO \"Los Número uno en Planchado\",\n";
    $descripcion=utf8_decode($descripcion.$mensaje);
    set_time_limit(0);
    $m = new cMailer();
    for($i=0;$i<count($log);$i++){
            $m->AddAddress($log[1]);
            $com="echo '$descripcion' | mail -s '$asunto' ".$log[$i];
            //system($com);
            mail($log[$i], $asunto, $descripcion, $header);
            }
    $m->AddAddress("soloplancho@gmail.com");
    $m->AddSender("soloplancho@gmail.com");
    $m->AddSubject("$asunto");

    $m->AddMessage("$descripcion");

    $re=$m->AddHost("localhost",2500);
    if($re)
    {
	$m->Send();
	if($m)
            $resultados['mensaje_correo']=" Datos Enviados correctamente";
	else
            $resultados['mensaje_correo']="no se envio $m";
        }
     else
        {
             $resultados['mensaje_correo']= "no conecta $m";
        }
        }

?>

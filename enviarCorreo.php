<?php 

//$a = $correo --> guardaremos el correo del cliente
//$b = $asunto --> el asunto del correo
//$c = $cuerpoCorreo --> indicara el mensaje en formato html
//$e = $subAsunto --> un mensaje corto del correo
//$f = $mensajeEnviado --> guardara lo que queramos hacer despues de enviar el correo
//$g = $adjunto --> indicara si el correo tendra un archivo adjunto
	//puede ser un alert de correo enviado o cualquier cosa.

  

	//IMPORTANTE
	//En caso de no poder hacer el envio de correos se debe verificar
	//la configuracion de la cuenta de google
	//y permitir el uso de aplicaciones menos seguras
  function mailSend($a,$b,$c,$e,$f,$g){
    require "PHPMailer-master/PHPMailerAutoload.php";

    $mail = new PHPMailer();
    $mail->SMTPDebug = false;
    $mail->isSMTP();
    $mail->Host = "mail.cajatepic.coop";
    $mail->SMTPAuth = true;
    $mail->Username = "soporte@cajatepic.coop";
    // $mail->Password = "hecj920331";
    $mail->Password = "#Benja.GeeK0";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;
    $mail->From = "soporte@cajatepic.coop";
    $mail->FromName = "Syscoop";
    if(!empty($g)){
      //verificamos si el correo se mandara un dato adjunto
      // $mail->addStringAttachment(file_get_contents($g), 'anexo.pdf');
      if(file_exists($g)){
        $mail->AddAttachment($g);
      }
      // $mail->AddStringAttachment(file_get_contents($g), 'file.pdf', 'base64', 'application/pdf');
    }
    //$mail->AddAddress("$correo");
    $correos = explode("|",$a);
    $res = 0;
    //tiene mas de un destinatario
    if(count($correos) > 1){
      foreach ($correos as $correo) {
        $mail->AddAddress($correo);
      }
      
    }else{
      $mail->AddAddress("$a","usuario");
      //a;slda;lsdk;aslfk;sldkf;sdlkf;s
      
    }

    $mail->IsHTML(true);
    $mail->Subject = $b;
    $link = "https://prestavale2.tecuanisoft.com/formatoPago/".$g;
    $mail->MsgHTML ("".$c."<br>
    <p style='text-align:center;font-size:x-large'><b>Atentamente</b><br>
    PLATAFORMA SYSCOOP
    </p>
    <p style='text-align:center;'><small>Este correo es enviado automaticamente,
    por lo que no tienes que contestarlo.</small></p>
    ");
    $mail->AltBody = " ".$e." ";

    try {
      $mail->send();
      $mail->clearAddresses();
    } catch (\Throwable $th) {
      $res = "DataError|" . $mail->ErrorInfo;
    }
    
    $mail->smtpClose();
    if($res == 0){
      return $f;
    }else{
      return $res;
    }

    
    
    // $mail->AddAddress("deisy_martinez@cajatepic.coop,contacto@cajatepic.coop");
    // $mail->AddAddress("sistemas@cajatepic.coop");
    // $mail->IsHTML(true);
    // $mail->Subject = $b;
    // $link = "https://prestavale2.tecuanisoft.com/formatoPago/".$g;
    // $mail->MsgHTML ("".$c."<br>
    // <p style='text-align:center;font-size:x-large'><b>Atentamente</b><br>
    // PLATAFORMA SYSCOOP
    // </p>
    // <p style='text-align:center;'><small>Este correo es enviado automaticamente,
    // por lo que no tienes que contestarlo.</small></p>
    // ");
    // $mail->AltBody = " ".$e." ";
    // if(!$mail->send()){
    // return "DataError|" . $mail->ErrorInfo;
    // // echo "asdasd";
    // }else{
    //   return $f;
    // }
    unset($mail);
  }

  // mailSend("sistemas@cajatepic.coop","hola","asdasd","subasunto","okidokis","");
?>
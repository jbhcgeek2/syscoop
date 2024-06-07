<?php
//pagna exclusiba para el envio de correos
//tendremos que cerar una funcion para hacer el envio de correos
//la cual llamaremos mailSend
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
function enviaCorreo($a,$b,$c,$e,$f,$g){
	//hacemos el include de la libreria
	require "../../PHPMailer-master/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->SMTPDebug = 0;
	$mail->isSMTP();
	$mail->Host = "smtp.gmail.com";
	$mail->SMTPAuth = true;
	$mail->Username = "goremaniaco@gmail.com";
	$mail->Password = "#Benja.GeeK0";
	$mail->SMTPSecure = "tls";
	$mail->Port = 587;
	$mail->From = "goremaniaco@gmail.com";
	$mail->FromName = "FOXCONN FORMS";
	if(!empty($g)){
		//verificamos si el correo se mandara un dato adjunto
		// $mail->addStringAttachment(file_get_contents($g), 'anexo.pdf');
		$mail->addAttachment($g);
		// $mail->AddStringAttachment(file_get_contents($g), 'file.pdf', 'base64', 'application/pdf');
	}
	//$mail->AddAddress("$correo");
	$mail->AddAddress("$a");
	$mail->IsHTML(true);
	$mail->Subject = $b;
	// $mail->MsgHTML ("".$c."<br>
	// <p style='text-align:center;font-size:x-large'><b>Atentamente</b><br>
	// Educacion Continua UAE
	// </p>
	// <p style='text-align:center;'><small>Este correo es enviado automaticamente,
	// por lo que no tienes que contestarlo.</small></p>
	// ");
	$cuerpoCorreo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>UNLOCK SECURE</title>
	  </head>
  
	  <body style="margin: 0; padding: 0;">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
		  <tr>
			<td>
			  <table align="center" border="0" cellpadding="0" cellspacing="0" width="90%">
			  <tr>
				<td align="center" bgcolor="#eeeeee" style="padding: 30px 0 30px 0;">
				  <img src="https://i1.wp.com/tijuanazonkeys.com.mx/wp-content/uploads/2019/12/foxconn-logo-min.png?fit=1222%2C481&ssl=1" alt="FOXCONN" width="90" style="display: block;"/>
				</td>
			  </tr>
  
			  <tr>
				<td bgcolor="" style="padding: 40px 30px 40px 30px;">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					  <td>
						'.$b.'
					  </td>
					</tr>
					<tr>
					  <td style="padding:20px 0 30px 0;">
						'.$c.'
					  </td>
					</tr>
					<tr>
					  <td style="text-align">
						FOXCONN SYSTEM
					  </td>
					</tr>
  
				  </table>
				</td>
			  </tr>
  
			  <tr>
				<td bgcolor="#01579b" style="padding: 10px 10px 10px 10px;">
				  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					  <td align="center" bgcolor="" style="font-size:10px;color:#ffffff;">
						<strong>
						This email is sent automatically, you do not have to perform any action. / Este correo es enviado automaticamente, no tienes que realizar alguna accion.
						</strong>
					  </td>
					</tr>
				  </table>
				</td>
			  </tr>
  
			  </table>
			</td>
		  </tr>
		</table>
	  </body>
  
	</html>';

	$mail->msgHTML($cuerpoCorreo);

	$mail->AltBody = " ".$e." ";
	if(!$mail->send()){
	// echo "Mailer Error: " . $mail->ErrorInfo;
	}else{
		return $f;
	}
}//fin de la funcion
?>

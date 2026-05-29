<?php
function envia_email_sendgrid($remetente,$destinatario,$assunto,$corpo_mensagem){

	// Envia email
	$url = 'https://api.sendgrid.com/';
	
	$params = array(
		"api_user" => SENDGRID_USUARIO,
		"api_key" => SENDGRID_SENHA,
		"to" => $destinatario,
		"subject" => $assunto,
		"html" => $corpo_mensagem,
		"text" => "Para visualizar este e-mail você deve possuir um programa que suporte html. Você também pode visualizar no navegador",
		"from" => $remetente
	);
	
	$request =  $url."api/mail.send.json";
	
	// Generate curl request
	$session = curl_init($request);

	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	
	// obtain response
	$response = curl_exec_follow($session);

	curl_close($session);
	
	if($response == '{"message":"success"}') {
		return true;
	}else{
		return false;
	}

}
?>
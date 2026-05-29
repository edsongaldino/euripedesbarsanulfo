<?php
function mascara_erro($mensagem,$mysql = true) {
	// erro vindo do mysql
	if($mysql) {
		$mensagem = "Número: ".mysql_errno()." - Erro: ".mysql_error()." - SQL: ".$mensagem;
		
		mysql_query("ROLLBACK");
	}
	
	/*
	// destroi a sessao
	session_destroy();
	
	// alerta email
	$url = "https://api.sendgrid.com/";
	$user = SENDGRID_USUARIO;
	$pass = SENDGRID_SENHA;
	
	$params = array(
					"api_user" => $user,
					"api_key" => $pass,
					"to" => "suporte@bolaopantanal.com.br",
					"subject" => utf8_encode("Erro"),
					"html" => utf8_encode($mensagem),
					"text" => utf8_encode($mensagem),
					"from" => "suporte@bolaopantanal.com.br",
					);
	
	$request =  $url."api/mail.send.json";
	
	// Generate curl request
	$session = curl_init($request);
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	
	// obtain response
	$response = curl_exec($session);
	curl_close($session);	
	*/
	
	// mostra pro usuario
	//die("Ocorreu um erro interno, por favor, tente novamente.");
	
	die($mensagem);
}
?>
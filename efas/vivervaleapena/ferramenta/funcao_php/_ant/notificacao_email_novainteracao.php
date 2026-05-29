<?php
function notificacao_email_novainteracao($codigo_atendimento) {
	$flag_erro = false;
		
	// consulta
	$sql_consulta = "
					SELECT
						atendimento_log.data_atendimento_log, atendimento_log.hora_atendimento_log,
						colaborador.codigo_colaborador,
						colaborador_email.descricao_colaborador_email
					FROM atendimento
						JOIN (
							SELECT atendimento_log.codigo_atendimento, atendimento_log.data_atendimento_log, atendimento_log.hora_atendimento_log FROM atendimento_log WHERE atendimento_log.codigo_log_tipo = 1
						) AS atendimento_log ON (atendimento.codigo_atendimento = atendimento_log.codigo_atendimento)
						JOIN colaborador ON (atendimento.codigo_colaborador = colaborador.codigo_colaborador)
						LEFT JOIN colaborador_email ON (colaborador.codigo_colaborador = colaborador_email.codigo_colaborador)
					WHERE atendimento.codigo_atendimento = ".$codigo_atendimento."
					GROUP BY atendimento.codigo_atendimento
					LIMIT 1
					";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta = mysql_fetch_assoc($query_consulta);
	
	// log
	$data_log = date("Y-m-d", time());
	$hora_log = date("H:i:s", time());
	
	// incluir (log notificacao por e-mail)
	$sql_incluir_log = "INSERT INTO atendimento_log (codigo_usuario,codigo_log_tipo,codigo_atendimento,data_atendimento_log,hora_atendimento_log,ip_atendimento_log) VALUES (".$resultado_consulta["codigo_colaborador"].",".(12).",".$codigo_atendimento.",'".$data_log."','".$hora_log."','".$_SERVER["REMOTE_ADDR"]."')";
	$query_incluir_log = mysql_query($sql_incluir_log) or mascara_erro_mysql($sql_incluir_log);
	
	if(!$query_incluir_log) {
		$flag_erro = true;
	}
	
	if(!$flag_erro) {
		// envio sendgrid
		$msg_mensagem = '
						<html>
						<body>
						<div align="center"><img src="http://www.lancamentosonline.com.br/externo/notificacao-email-nova-interacao-'.campo_form_codifica(($codigo_atendimento).($resultado_consulta["data_atendimento_log"]).($resultado_consulta["hora_atendimento_log"])).'.jpg" /></div>
						<div align="center">Este e-mail foi enviado para: '.$resultado_consulta["descricao_colaborador_email"].'<br />Caso não queira mais receber nossos emails, remova aqui.</div>
						</body>
						</html>
						';
		
		$url = 'http://sendgrid.com/';
		
		// envio corretor
		if($resultado_consulta["descricao_colaborador_email"]) {
			$params = array(
				'api_user'  => SENDGRID_USUARIO,
				'api_key'   => SENDGRID_SENHA,
				'to'        => utf8_encode($resultado_consulta["descricao_colaborador_email"]),
				'subject'   => utf8_encode("Nova interação do atendimento ".str_pad($codigo_atendimento, 6, "0", STR_PAD_LEFT).""),
				'html'      => utf8_encode($msg_mensagem),
				'from'      => utf8_encode("contato@lancamentosonline.com.br"),
				'fromname'	=> utf8_encode("Lançamentos Online"),
				'replyto'	=> utf8_encode("contato@lancamentosonline.com.br"),
			);	 
			 
			$request =  $url.'api/mail.send.json';
			 
			// generate curl request
			$session = curl_init($request);
		
			curl_setopt($session, CURLOPT_POST, true);
			curl_setopt($session, CURLOPT_POSTFIELDS, $params);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			 
			$response = curl_exec($session);
			curl_close($session);
			
			if($response != '{"message":"success"}') {
				$flag_erro = true;
			}
		}
	}
	
	if($flag_erro) {
		return false;
	} else {
		unset($params,$request,$session,$response);

		// envio administrador
		$params = array(
			'api_user'  => SENDGRID_USUARIO,
			'api_key'   => SENDGRID_SENHA,
			'to'        => utf8_encode("contato@lancamentosonline.com.br"),
			'subject'   => utf8_encode("(Cópia) Nova interação do atendimento ".str_pad($codigo_atendimento, 6, "0", STR_PAD_LEFT).""),
			'html'      => utf8_encode($msg_mensagem),
			'from'      => utf8_encode("contato@lancamentosonline.com.br"),
			'fromname'	=> utf8_encode("Lançamentos Online"),
			'replyto'	=> utf8_encode("contato@lancamentosonline.com.br"),
		);	 
		 
		$request =  $url.'api/mail.send.json';
		 
		// generate curl request
		$session = curl_init($request);
	
		curl_setopt($session, CURLOPT_POST, true);
		curl_setopt($session, CURLOPT_POSTFIELDS, $params);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		 
		$response = curl_exec($session);
		curl_close($session);

		return true;
	}
}
?>
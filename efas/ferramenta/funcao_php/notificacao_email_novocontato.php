<?php

function envia_email($destino, $nome_participante, $assunto, $corpo_mensagem)
{
        
	// Inicia a classe PHPMailer
	$mail = new PHPMailer(true);

	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); // Define que a mensagem será SMTP
	
		try {
		$mail->SMTPSecure = "tls"; // tbm já tentei tls
		$mail->Host = "smtp.gmail.com"; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
		$mail->SMTPAuth   = false;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
		$mail->Port       = 587; //  Usar 587 porta SMTP
		$mail->Username = 'edson@lancamentosonline.com.br'; // Usuário do servidor SMTP (endereço de email)
		$mail->Password = 'web@259864"'; // Senha do servidor SMTP (senha do email usado)
	
		//Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
		$mail->SetFrom('secretaria@euripedesbarsanulfo.org.br', 'Secretaria - EFAS'); //Seu e-mail
		$mail->AddReplyTo('secretaria@euripedesbarsanulfo.org.br', 'Secretaria - EFAS'); //Seu e-mail
		$mail->Subject = $assunto;//Assunto do e-mail
	
	
		//Define os destinatário(s)
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->AddAddress($destino, $nome_participante);
		$mail->AddCC('secretaria@euripedesbarsanulfo.org.br', 'Secretaria - EFAS'); // Copia
		//Campos abaixo são opcionais 
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
		//$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
		//$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
	
	
		//Define o corpo do email
		$mail->MsgHTML($corpo_mensagem); 
	
		////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
		//$mail->MsgHTML(file_get_contents('arquivo.html'));
	
		$mail->Send();
		//echo "Mensagem enviada com sucesso</p>\n";
		return true;
	
		//caso apresente algum erro é apresentado abaixo com essa exceção.
		}catch (phpmailerException $e) {
			echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
		return false;
	}
  
}

?>
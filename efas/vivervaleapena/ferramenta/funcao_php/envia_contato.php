<?php

// função para salvar contato
function envia_contato($nome,$email,$telefone,$assunto,$mensagem) {
	

	$corpo_mensagem = "
			
			<p>Ola,<br/> ".utf8_decode($nome)." esteve visitando seu site e lhe enviou uma mensagem.</p>
			<p>
			<b>Nome</b>: ".utf8_decode($nome)."<br>
			<b>E-mail</b>: ".$email."<br>
			<b>Telefone</b>: ".$telefone."<br>
			<b>Assunto</b>: ".utf8_decode($assunto)."<br>
			<b>Mensagem</b>: ".utf8_decode($mensagem)."<br>
			</p>
			<p>Este e um e-mail enviado pelo site www.pdadvogados.com</p>
			<p>PDA Advogados Associados</p>
						
			";
	
	// Inicia a classe PHPMailer
	$mail = new PHPMailer(true);

	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); // Define que a mensagem será SMTP
	
		try {
		$mail->SMTPSecure = "ssl"; // tbm já tentei tls
		$mail->Host = "mail.datapix.com.br"; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
		$mail->SMTPAuth   = false;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
		$mail->Port       = 465; //  Usar 587 porta SMTP
		$mail->Username = 'pdadvogados@datapix.com.br'; // Usuário do servidor SMTP (endereço de email)
		$mail->Password = 'z^0OUHTCq!h%'; // Senha do servidor SMTP (senha do email usado)
	
		//Define o remetente
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
		$mail->SetFrom('atendimento@pdadvogados.com', 'Atendimento - PDA Advogados Associados'); //Seu e-mail
		$mail->AddReplyTo($email, utf8_decode($nome)); //Seu e-mail
		$mail->Subject = utf8_decode($assunto);//Assunto do e-mail
	
	
		//Define os destinatário(s)
		//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->AddAddress('edsongaldino@datapix.com.br', 'PDA Advogados Associados');
		//$mail->AddBCC('edsongaldino@datapix.com.br', 'Datapix Tecnologia'); // Cópia Oculta
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
<?php

function verifica_email_cadastrado($email, $email_campo, $tabela, $pdo) {

	$verifica = $pdo->query("SELECT {$email_campo} 
							 FROM {$tabela}
							 WHERE {$email_campo} = '{$email}'
							 AND codigo_situacao = 1")->fetch(PDO::FETCH_ASSOC);

	if (!empty($verifica[$email_campo])):
		return true;
	else:
		return false;
	endif;			
}
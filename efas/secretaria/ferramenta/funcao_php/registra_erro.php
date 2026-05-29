<?php
function registra_erro($assunto,$mensagem) {
	// O return-path deve ser ser o mesmo e-mail do remetente.
	$headers = "MIME-Version: 1.1\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$headers .= "From: Paínel administrativo\n";
	$headers .= "Return-Path: Paínel administrativo\n";
	
	$para = ADMIN_EMAIL;
	$corpo = $mensagem;

	$envia = @mail($para,$assunto,$corpo,$headers);
}
?>
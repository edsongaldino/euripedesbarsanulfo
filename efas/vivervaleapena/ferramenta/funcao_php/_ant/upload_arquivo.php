<?php
function upload_arquivo($arquivo,$descricao,$caminho_upload) {
	$extensao = substr(strrchr($arquivo["name"],"."),1);
	$extensao = explode("?",$extensao);
	$extensao = $extensao[0];
	
	$descricao = remove_caracter_especial($descricao);
	
	if(strlen($descricao) > 80) {
		$descricao = substr($descricao,0,80);
	}
	
	$novo_nome = $descricao."_".date("Y-m-d",time())."_".date("H-i-s",time()).".".$extensao;
	
	$sql_sistema_informacao = "SELECT login_ftp_sistema, host_ftp_sistema, senha_ftp_sistema FROM sistema_informacao LIMIT 1";
	$query_sistema_informacao = mysql_query($sql_sistema_informacao) or mascara_erro_mysql($sql_sistema_informacao);
	$resultado_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);

	mysql_free_result($query_sistema_informacao);

	$ftp_conexao = ftp_connect($resultado_sistema_informacao['host_ftp_sistema']);
	$ftp_login = ftp_login($ftp_conexao, $resultado_sistema_informacao['login_ftp_sistema'], campo_form_decodifica($resultado_sistema_informacao['senha_ftp_sistema']));
	$ftp_upload_arquivo = ftp_put($ftp_conexao,$caminho_upload.$novo_nome,$arquivo["tmp_name"],FTP_BINARY);
	$ftp_fechando_conexao = ftp_quit($ftp_conexao);

	if($ftp_upload_arquivo) {
		return $novo_nome;
	} else {
		return false;
	}
}
?>
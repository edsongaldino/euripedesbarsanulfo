<?php
function delete_arquivo($arquivo,$caminho_arquivo) {
	$sql_sistema_informacao = "SELECT login_ftp_sistema, host_ftp_sistema, senha_ftp_sistema FROM sistema_informacao LIMIT 1";
	$query_sistema_informacao = mysql_query($sql_sistema_informacao) or mascara_erro_mysql($sql_sistema_informacao);
	$resultado_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
	
	mysql_free_result($query_sistema_informacao);
	
	$ftp_conexao = ftp_connect($resultado_sistema_informacao['host_ftp_sistema']);
	$ftp_login = ftp_login($ftp_conexao, $resultado_sistema_informacao['login_ftp_sistema'], campo_form_decodifica($resultado_sistema_informacao['senha_ftp_sistema']));
	$ftp_delete_arquivo = ftp_delete($ftp_conexao,$caminho_arquivo.$arquivo);
	$ftp_fechando_conexao = ftp_quit($ftp_conexao);

	if($ftp_delete_arquivo) {
		return true;
	} else {
		return false;
	}
}
?>
<?php
// Função para realizar o upload de arquivo
function cria_pasta($caminho, $pasta) {
	global $conexao;
	
	// Ftp
	$sql_sistema_informacao = "SELECT login_ftp_sistema, endereco_ftp_sistema, senha_ftp_sistema FROM sistema_informacao LIMIT 1";
	$query_sistema_informacao = mysqli_query($conexao, $sql_sistema_informacao);// or mascara_erro_mysql($sql_sistema_informacao);
	$resultado_sistema_informacao = mysqli_fetch_assoc($query_sistema_informacao);
	
	$ftp_conexao = ftp_connect($resultado_sistema_informacao['endereco_ftp_sistema']);
	$ftp_login = ftp_login($ftp_conexao, $resultado_sistema_informacao['login_ftp_sistema'], campo_form_decodifica($resultado_sistema_informacao['senha_ftp_sistema']));
	$ftp_cria_pasta_cliente = ftp_mkdir($ftp_conexao, $caminho.$pasta);
	
	$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	
	if($ftp_cria_pasta_cliente) {
		return $ftp_cria_pasta_cliente;
	} else {
		return false;
	}
}
?>


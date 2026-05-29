<?php
function conecta_mysql($local = "site") {
	if($local == "domus") {
		$conexao = mysql_connect(BD_DOMUS_HOST, BD_DOMUS_USUARIO, BD_DOMUS_SENHA) or mascara_erro("Erro na conexão: ".mysql_error(),false);
		$database = BD_DOMUS_BANCO;
		$conecta_mysql = mysql_select_db($database, $conexao) or mascara_erro("Não foi possível abrir o banco de dados: ".mysql_error(),false);
	} else {
		$conexao = mysql_connect(BD_HOST, BD_USUARIO, BD_SENHA) or mascara_erro("Erro na conexão: ".mysql_error(),false);
		$database = BD_BANCO;
		$conecta_mysql = mysql_select_db($database, $conexao) or mascara_erro("Não foi possível abrir o banco de dados: ".mysql_error(),false);		
	}
	return $conexao;
}
?>
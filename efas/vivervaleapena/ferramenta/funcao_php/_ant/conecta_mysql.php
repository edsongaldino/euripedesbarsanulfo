<?php
function conecta_mysql($tipo_conexao = "domus") {
	// conecta "domus"
	if($tipo_conexao == "domus") {
		$conexao_domus = mysql_connect(BD_HOST, BD_USUARIO, BD_SENHA) or die("Erro na conexão ".mysql_error());
		$database_domus = BD_BANCO;
		$conecta_mysql_domus = mysql_select_db($database_domus, $conexao_domus) or die("Nao foi possivel abrir o banco de dados ".mysql_error());
		
		return $conexao_domus;
	
	// conecta "site"
	} elseif($tipo_conexao == "site") {
		$conexao_site = mysql_connect(BD_SITE_HOST, BD_SITE_USUARIO, BD_SITE_SENHA) or die("Erro na conexão ".mysql_error());
		$database_site = BD_SITE_BANCO;
		$conecta_mysql_site = mysql_select_db($database_site, $conexao_site) or die("Nao foi possivel abrir o banco de dados ".mysql_error());
		
		return $conexao_site;		
	}
}
?>
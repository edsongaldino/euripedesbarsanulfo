<?php
function novo_log($codigo_log_tipo,$descricao,$comando) {
	$codigo_log_tipo = (int) addslashes(trim($codigo_log_tipo));
	$descricao = addslashes(trim($descricao));
	$comando = addslashes(trim($comando));
	
	// novo log
	$sql_novo = "INSERT INTO usuario_log (codigo_usuario,codigo_log_tipo,descricao_usuario_log,data_usuario_log,hora_usuario_log,ip_usuario_log,comando_usuario_log) VALUES (".$_SESSION["codigo_usuario_acesso"].",".$codigo_log_tipo.",'".$descricao."','".date("Ymd", time())."','".date("His", time())."','".$_SERVER["REMOTE_ADDR"]."','".codifica($comando)."')";
	$query_novo = mysql_query($sql_novo) or mascara_erro($sql_novo);
	$codigo_usuario_log = mysql_insert_id();
	
	if(isset($codigo_usuario_log)) {
		return (int) $codigo_usuario_log;
	}
}

function novo_log_2($codigo_log_tipo,$descricao,$comando, $data, $hora) {
	$codigo_log_tipo = (int) addslashes(trim($codigo_log_tipo));
	$descricao = addslashes(trim($descricao));
	$comando = addslashes(trim($comando));
	
	// novo log
	$sql_novo = "INSERT INTO usuario_log (codigo_usuario,codigo_log_tipo,descricao_usuario_log,data_usuario_log,hora_usuario_log,ip_usuario_log,comando_usuario_log) VALUES (".$_SESSION["codigo_usuario_acesso"].",".$codigo_log_tipo.",'".$descricao."','".$data."','".$hora."','".$_SERVER["REMOTE_ADDR"]."','".codifica($comando)."')";
	$query_novo = mysql_query($sql_novo) or mascara_erro($sql_novo);
	$codigo_usuario_log = mysql_insert_id();
	
	if(isset($codigo_usuario_log)) {
		return (int) $codigo_usuario_log;
	}
}

function grava_log($codigo_usuario,$codigo_log_tipo,$descricao,$comando) {
	$codigo_log_tipo = (int) addslashes(trim($codigo_log_tipo));
	$descricao = addslashes(trim($descricao));
	$comando = addslashes(trim($comando));
	
	// novo log
	$sql_novo = "INSERT INTO usuario_log (codigo_usuario,codigo_log_tipo,descricao_usuario_log,data_usuario_log,hora_usuario_log,ip_usuario_log,comando_usuario_log) VALUES (".$codigo_usuario.",".$codigo_log_tipo.",'".$descricao."','".date("Ymd", time())."','".date("His", time())."','".$_SERVER["REMOTE_ADDR"]."','".codifica($comando)."')";
	$query_novo = mysql_query($sql_novo) or mascara_erro($sql_novo);
	$codigo_usuario_log = mysql_insert_id();
	
	if(isset($codigo_usuario_log)) {
		return (int) $codigo_usuario_log;
	}
}

<?php require_once("sistema_mod_include.php"); ?>
<?php

// codigo
$codigo = campo_form_decodifica($_GET["codigo_participante"]);

$conexao = conecta_mysql();

// inicio da transacao
$begin_transacao = true;
$query_begin = mysqli_query($conexao,"BEGIN");

// excluir (email participante)
$sql_excluir_email_participante = "DELETE FROM email_participante WHERE email_participante.codigo_participante = '".$codigo."'";
$query_excluir_email_participante = mysqli_query($conexao,$sql_excluir_email_participante) or mascara_erro_mysql($sql_excluir_email_participante);

if($query_excluir_email_participante) {
	$sql_log[] = $sql_excluir_email_participante;
}else{
	$flag_erro_sql = true;	
}

// excluir (email participante)
$sql_excluir_telefone_participante = "DELETE FROM telefone_participante WHERE telefone_participante.codigo_participante = '".$codigo."'";
$query_excluir_telefone_participante = mysqli_query($conexao,$sql_excluir_telefone_participante) or mascara_erro_mysql($sql_excluir_telefone_participante);

if($query_excluir_telefone_participante) {
	$sql_log[] = $sql_excluir_telefone_participante;
}else{
	$flag_erro_sql = true;	
}

// excluir (usuário inscricao_evento)
$sql_excluir_inscricao_evento = "DELETE FROM inscricao_evento WHERE inscricao_evento.codigo_participante = '".$codigo."'";
$query_excluir_inscricao_evento = mysqli_query($conexao,$sql_excluir_inscricao_evento) or mascara_erro_mysql($sql_excluir_inscricao_evento);

if($query_excluir_inscricao_evento) {
	$sql_log[] = $sql_excluir_inscricao_evento;
}else{
	$flag_erro_sql = true;	
}

// excluir (inscricao_evento_curso)
$sql_excluir_inscricao_evento_curso = "DELETE FROM participante_evento_curso WHERE participante_evento_curso.codigo_participante = '".$codigo."'";
$query_excluir_inscricao_evento_curso = mysqli_query($conexao,$sql_excluir_inscricao_evento_curso) or mascara_erro_mysql($sql_excluir_inscricao_evento_curso);

if($query_excluir_inscricao_evento_curso) {
	$sql_log[] = $sql_excluir_inscricao_evento_curso;
}else{
	$flag_erro_sql = true;
}

// excluir (inscricao_evento_curso)
$sql_excluir_dados_complementares = "DELETE FROM dados_complementares WHERE dados_complementares.codigo_participante = '".$codigo."'";
$query_excluir_dados_complementares = mysqli_query($conexao,$sql_excluir_dados_complementares) or mascara_erro_mysql($sql_excluir_dados_complementares);

if($query_excluir_dados_complementares) {
	$sql_log[] = $sql_excluir_dados_complementares;
}else{
	$flag_erro_sql = true;
}


// excluir (inscricao_evento_curso)
$sql_excluir_comissao_trabalho_participante = "DELETE FROM comissao_trabalho_participante WHERE comissao_trabalho_participante.codigo_participante = '".$codigo."'";
$query_excluir_comissao_trabalho_participante = mysqli_query($conexao,$sql_excluir_comissao_trabalho_participante) or mascara_erro_mysql($sql_excluir_comissao_trabalho_participante);

if($query_excluir_comissao_trabalho_participante) {
	$sql_log[] = $sql_excluir_comissao_trabalho_participante;
}else{
	$flag_erro_sql = true;
}

// excluir (inscricao_evento_curso)
$sql_excluir_participante = "DELETE FROM participante WHERE participante.codigo_participante = '".$codigo."'";
$query_excluir_participante = mysqli_query($conexao,$sql_excluir_participante) or mascara_erro_mysql($sql_excluir_participante);

if($query_excluir_participante) {
	$sql_log[] = $sql_excluir_participante;
}else{
	$flag_erro_sql = true;
}


if(!$flag_erro_sql) {
	// fim da transacao
	$query_rollback = mysqli_query($conexao,"COMMIT");
	
	fecha_mysql();
		
	redireciona("participantes.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição removida com sucesso"));
} else {
	// fim da transacao
	$query_rollback = mysqli_query($conexao,"ROLLBACK");
	
	fecha_mysql();
	
	redireciona("participantes.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Não foi possível excluir a inscrição"));
}

?>
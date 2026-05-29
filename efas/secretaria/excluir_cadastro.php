<?php require_once("sistema_mod_include.php"); ?>
<?php

// codigo
$codigo = campo_form_decodifica($_GET["codigo"]);

$conexao = conecta_mysql();

if($codigo == $_SESSION["codigo_participante"]){
	
	redireciona("meus_cadastros.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Você não pode remover seu próprio cadastro!"));

}
// inicio da transacao
$begin_transacao = true;
$query_begin = mysqli_query($conexao,"BEGIN");

// excluir (usuário participante)
$sql_excluir_usuario_participante = "DELETE FROM usuario_participante WHERE codigo_participante = '".$codigo."'";
$query_excluir_usuario_participante = mysqli_query($conexao,$sql_excluir_usuario_participante) or mascara_erro_mysql($sql_excluir_usuario_participante);

if($query_excluir_usuario_participante) {
	$sql_log[] = $sql_excluir_usuario_participante;
} else {
	$flag_erro_sql = true;	
}

// excluir (Telefone participante)
$sql_excluir_telefone_participante = "DELETE FROM telefone_participante WHERE codigo_participante = '".$codigo."'";
$query_excluir_telefone_participante = mysqli_query($conexao,$sql_excluir_telefone_participante) or mascara_erro_mysql($sql_excluir_telefone_participante);

if($query_excluir_telefone_participante) {
	$sql_log[] = $sql_excluir_telefone_participante;
} else {
	$flag_erro_sql = true;	
}

// excluir (E-mail participante)
$sql_excluir_email_participante = "DELETE FROM email_participante WHERE codigo_participante = '".$codigo."'";
$query_excluir_email_participante = mysqli_query($conexao,$sql_excluir_email_participante) or mascara_erro_mysql($sql_excluir_email_participante);

if($query_excluir_email_participante) {
	$sql_log[] = $sql_excluir_email_participante;
} else {
	$flag_erro_sql = true;	
}

// excluir (Comissão trabalho participante)
$sql_excluir_comissao_trabalho_participante = "DELETE FROM comissao_trabalho_participante WHERE codigo_participante = '".$codigo."'";
$query_excluir_comissao_trabalho_participante = mysqli_query($conexao,$sql_excluir_comissao_trabalho_participante) or mascara_erro_mysql($sql_excluir_comissao_trabalho_participante);

if($query_excluir_comissao_trabalho_participante) {
	$sql_log[] = $sql_excluir_comissao_trabalho_participante;
} else {
	$flag_erro_sql = true;	
}

// excluir (Dados complementares)
$sql_excluir_dados_complementares = "DELETE FROM dados_complementares WHERE codigo_participante = '".$codigo."'";
$query_excluir_dados_complementares = mysqli_query($conexao,$sql_excluir_dados_complementares) or mascara_erro_mysql($sql_excluir_dados_complementares);

if($query_excluir_dados_complementares) {
	$sql_log[] = $sql_excluir_dados_complementares;
} else {
	$flag_erro_sql = true;	
}

// excluir (Participante Curso)
$sql_excluir_participante_evento_curso = "DELETE FROM participante_evento_curso WHERE codigo_participante = '".$codigo."'";
$query_excluir_participante_evento_curso = mysqli_query($conexao,$sql_excluir_participante_evento_curso) or mascara_erro_mysql($sql_excluir_participante_evento_curso);

if($query_excluir_participante_evento_curso) {
	$sql_log[] = $sql_excluir_participante_evento_curso;
} else {
	$flag_erro_sql = true;	
}

// excluir (participante)
$sql_excluir_participante = "DELETE FROM participante WHERE codigo_participante = '".$codigo."'";
$query_excluir_participante = mysqli_query($conexao,$sql_excluir_participante) or mascara_erro_mysql($sql_excluir_participante);

if($query_excluir_participante) {
	$sql_log[] = $sql_excluir_participante;
} else {
	$flag_erro_sql = true;	
}

	
if(!$flag_erro_sql) {
	// fim da transacao
	$query_rollback = mysqli_query($conexao,"COMMIT");
	
	fecha_mysql($conexao);
		
	redireciona("meus_cadastros.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição removida com sucesso"));
} else {
	// fim da transacao
	$query_rollback = mysqli_query($conexao,"ROLLBACK");
	
	fecha_mysql($conexao);
	
	redireciona("meus_cadastros.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Não foi possível excluir a inscrição"));
}
?>
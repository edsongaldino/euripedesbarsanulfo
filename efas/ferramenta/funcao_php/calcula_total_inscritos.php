<?php
// Função para converte valores para o padrão brasileiro REAL
function calcula_total_inscritos($tipo) {
	global $conexao;
 	$conexao = conecta_mysql();
	$tipo = mysqli_real_escape_string($conexao, $tipo);
	if($tipo == 1){
    // consulta total de crisn?as inscritas
	$sql_consulta = "SELECT count(inscricao_evento.codigo_participante) AS total_inscritos
						FROM inscricao_evento
						JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
					 WHERE participante.data_nascimento_participante < 2003-09-19";
	$query_consulta = mysqli_query($conexao, $sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta_cursos = mysqli_fetch_assoc($query_consulta);
	$total_inscritos = $resultado_consulta_cursos['total_inscritos'];
	}
	
	if($tipo == 2){
    // consulta total de jovens inscritos
	$sql_consulta = "SELECT count(inscricao_evento.codigo_participante) AS total_inscritos
						FROM inscricao_evento
						JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
					 WHERE participante.data_nascimento_participante > '2003-09-19' AND participante.data_nascimento_participante > '2001-09-19'";
	$query_consulta = mysqli_query($conexao, $sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta_cursos = mysqli_fetch_assoc($query_consulta);
	$total_inscritos = $resultado_consulta_cursos['total_inscritos'];
	}
	
	if($tipo == 3){
    // consulta total de adultos inscritos
	$sql_consulta = "SELECT count(inscricao_evento.codigo_participante) AS total_inscritos
						FROM inscricao_evento
						JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
					 WHERE participante.data_nascimento_participante < '2000-09-19'";
	$query_consulta = mysqli_query($conexao, $sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta_cursos = mysqli_fetch_assoc($query_consulta);
	$total_inscritos = $resultado_consulta_cursos['total_inscritos'];
	}
	
	if (isset($query_consulta)) {
		mysqli_free_result($query_consulta);
	}
	fecha_mysql($conexao);
	
    return $total_inscritos;

}
?>
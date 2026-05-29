<?php
// Função para converte valores para o padrão brasileiro REAL
function calcula_total_inscritos($tipo) {
 	$conexao = conecta_mysql();

    // consulta total de crisnças inscritas
	$sql_consulta = "SELECT count(inscricao_evento.codigo_participante) AS total_inscritos
						FROM inscricao_evento
					 WHERE inscricao_evento.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' AND inscricao_evento.tipo_inscricao = '$tipo'";
	$query_consulta = mysqli_query($conexao,$sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta_cursos = mysqli_fetch_assoc($query_consulta);
	$total_inscritos = $resultado_consulta_cursos['total_inscritos'];
	
	
	mysqli_free_result($query_consulta);
	fecha_mysql($conexao);
	
    return $total_inscritos;

}
?>
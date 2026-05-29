<?php
// Função para converte valores para o padrão brasileiro REAL
function calcula_total_inscritos_curso($codigo_curso) {
 	 
	$conexao = conecta_mysql();
	 
	$sql_consulta = "SELECT participante_evento_curso.codigo_participante FROM participante_evento_curso WHERE participante_evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' AND participante_evento_curso.codigo_curso = '".$codigo_curso."'";
	$query_consulta = mysqli_query($conexao,$sql_consulta) or mascara_erro_mysql($sql_consulta);
	$total_consulta = mysqli_num_rows($query_consulta);
    
   	return $total_consulta;
   
   	mysqli_free_result($query_consulta);
   	fecha_mysql($conexao);
}
?>
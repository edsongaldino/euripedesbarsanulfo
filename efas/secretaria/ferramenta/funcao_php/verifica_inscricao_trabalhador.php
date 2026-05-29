<?php
// Função que verifica se inscrição é de trabalkhador
function verifica_inscricao_trabalhador($codigo_participante) {
	$conexao = conecta_mysql();
 	// consulta total de crisnças inscritas
	$sql_consulta = "SELECT comissao_trabalho_participante.codigo_comissao_trabalho FROM comissao_trabalho_participante WHERE codigo_participante = '".$codigo_participante."'";
	$query_consulta = mysqli_query($conexao, $sql_consulta) or mascara_erro_mysql($sql_consulta);
	$resultado_consulta_trabalhador = mysqli_fetch_assoc($query_consulta);
    
	if($resultado_consulta_trabalhador['codigo_comissao_trabalho']){
	    return true;
	}else{
		return false;
	}
	mysqli_free_result($query_consulta);
	fecha_mysql();
	
}
?>
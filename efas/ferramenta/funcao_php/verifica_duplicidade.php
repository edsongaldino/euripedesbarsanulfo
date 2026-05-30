<?php
// Função para verificar duplicidade de dados antes de gravar
function verifica_duplicidade($tabela, $campo, $valor) {
	global $conexao;

	
	// SQL duplicidade
	$sql_duplicidade = "SELECT * FROM $tabela WHERE $campo = '".$valor."' LIMIT 1";
	$query_duplicidade = mysqli_query($conexao, $sql_duplicidade) or mascara_erro_mysql($sql_duplicidade,"index.php");
	$resultado_duplicidade = mysqli_fetch_assoc($query_duplicidade);
	
	
	if($resultado_duplicidade) {
		return true;
	} else {
		return false;
	}
}

// Verifica se já existe um participante com mesmo nome e data de nascimento no mesmo evento (duplicado)
function verifica_inscricao_duplicada($nome, $data_nascimento, $codigo_evento = null) {
	global $conexao;
	
	if ($codigo_evento === null) {
		$codigo_evento = CODIGO_EVENTO_ATIVO;
	}
	
	$nome_escaped = mysqli_real_escape_string($conexao, $nome);
	$data_escaped = mysqli_real_escape_string($conexao, $data_nascimento);
	$evento_escaped = (int)$codigo_evento;
	
	$sql = "SELECT inscricao_evento.codigo_inscricao_evento 
			FROM inscricao_evento 
			JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
			WHERE participante.nome_participante = '$nome_escaped'
			  AND participante.data_nascimento_participante = '$data_escaped'
			  AND inscricao_evento.codigo_evento = '$evento_escaped'
			LIMIT 1";
			
	$query = mysqli_query($conexao, $sql) or mascara_erro_mysql($sql, "index.php");
	$resultado = mysqli_fetch_assoc($query);
	
	if ($resultado) {
		return $resultado['codigo_inscricao_evento'];
	}
	return false;
}
?>
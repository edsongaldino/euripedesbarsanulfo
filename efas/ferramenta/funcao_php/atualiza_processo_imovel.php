<?php

function atualiza_processo_imovel($tipo_processo, $codigo_usuario, $codigo_imovel, $descricao_processo_imovel){
	global $conexao;
	
	$tipo_processo = mysqli_real_escape_string($conexao, $tipo_processo);
	$codigo_usuario = mysqli_real_escape_string($conexao, $codigo_usuario);
	$codigo_imovel = mysqli_real_escape_string($conexao, $codigo_imovel);
	$descricao_processo_imovel = mysqli_real_escape_string($conexao, $descricao_processo_imovel);
	
	// Insere data de inclusão do imóvel
	$sql_data_inclusao_imovel = "INSERT INTO processo_imovel (data_hora_processo, codigo_tipo_processo, codigo_usuario, codigo_imovel, descricao_processo_imovel) VALUES ('".date('Y-m-d H:i:s')."','".$tipo_processo."','".$codigo_usuario."','".$codigo_imovel."','".$descricao_processo_imovel."')";
	$query_data_inclusao_imovel = mysqli_query($conexao, $sql_data_inclusao_imovel);
	
	
}

?>
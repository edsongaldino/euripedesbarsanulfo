<?php

function verifica_pendencia_imovel($tipo, $codigo_imovel){
	global $conexao;


	if($tipo == "coordenadas"){	
		//Verifica pendencia latitude
		$sql_imovel = "SELECT latitude_imovel, longitude_imovel FROM imovel WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_imovel = mysqli_query($conexao, $sql_imovel);
		$coordenadas = mysqli_fetch_assoc($query_imovel);
		
		if($coordenadas["latitude_imovel"] == '-0' || $coordenadas["longitude_imovel"] == '-0'){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}
	
	
	if($tipo == "fotos"){	
		//Verifica pendencia latitude
		$sql_imovel_foto = "SELECT codigo_imovel_foto FROM imovel_foto WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_imovel_foto = mysqli_query($conexao, $sql_imovel_foto);
		$total_fotos = mysqli_num_rows($query_imovel_foto);
		
		if($total_fotos < 5){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}

	
	if($tipo == "dados"){	
	
		//Verifica pendencia dados
		$sql_dados_imovel = "SELECT valor_diaria_imovel, data_inicial_imovel, data_final_imovel FROM imovel WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_dados_imovel = mysqli_query($conexao, $sql_dados_imovel);
		$resultado_dados_imovel = mysqli_fetch_assoc($query_dados_imovel);
		
		if($resultado_dados_imovel["valor_diaria_imovel"] == '0.00' || $resultado_dados_imovel["data_inicial_imovel"] == '0000-00-00' || $resultado_dados_imovel["data_final_imovel"] == '0000-00-00'){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}
	
	if($tipo == "valor_diaria_imovel"){	
	
		//Verifica pendencia dados
		$sql_dados_imovel = "SELECT valor_diaria_imovel FROM imovel WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_dados_imovel = mysqli_query($conexao, $sql_dados_imovel);
		$resultado_dados_imovel = mysqli_fetch_assoc($query_dados_imovel);
		
		if($resultado_dados_imovel["valor_diaria_imovel"] == '0.00'){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}
	
	if($tipo == "data_inicial_imovel"){	
	
		//Verifica pendencia dados
		$sql_dados_imovel = "SELECT data_inicial_imovel FROM imovel WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_dados_imovel = mysqli_query($conexao, $sql_dados_imovel);
		$resultado_dados_imovel = mysqli_fetch_assoc($query_dados_imovel);
		
		if($resultado_dados_imovel["data_inicial_imovel"] == '0000-00-00'){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}
	
	if($tipo == "data_final_imovel"){	
	
		//Verifica pendencia dados
		$sql_dados_imovel = "SELECT data_final_imovel FROM imovel WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_dados_imovel = mysqli_query($conexao, $sql_dados_imovel);
		$resultado_dados_imovel = mysqli_fetch_assoc($query_dados_imovel);
		
		if($resultado_dados_imovel["data_final_imovel"] == '0000-00-00'){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}
	
	if($tipo == "capacidade_hospedes"){	
	
		//Verifica pendencia dados
		$sql_dados_imovel = "SELECT capacidade_hospedes FROM imovel WHERE codigo_imovel = '".$codigo_imovel."'";
		$query_dados_imovel = mysqli_query($conexao, $sql_dados_imovel);
		$resultado_dados_imovel = mysqli_fetch_assoc($query_dados_imovel);
		
		if($resultado_dados_imovel["capacidade_hospedes"] == ''){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
	}
	
}

function verifica_pendencia_proprietario($tipo, $cpf_proprietario){
	global $conexao;


		//Verifica pendencia documentos
		$sql_documento = "SELECT codigo_tipo_documento FROM documento WHERE cpf_proprietario = '".$cpf_proprietario."'";
		$query_documento = mysqli_query($conexao, $sql_documento);
		$total_documentos = mysqli_num_rows($query_documento);
		
		if($total_documentos < 4){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
		
}

function verifica_pendencia_documento_proprietario($tipo_documento, $cpf_proprietario){
	global $conexao;


		//Verifica pendencia documentos
		$sql_documento = "SELECT codigo_tipo_documento FROM documento WHERE cpf_proprietario = '".$cpf_proprietario."' AND codigo_tipo_documento = '".$tipo_documento."'";
		$query_documento = mysqli_query($conexao, $sql_documento);
		$total_documentos = mysqli_num_rows($query_documento);
		
		if($total_documentos < 1){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
		
}

function verifica_pendencia_telefone_proprietario($cpf_proprietario){
	global $conexao;


		//Verifica pendencia telefone
		$sql_telefone = "SELECT codigo_telefone FROM telefone WHERE cpf_proprietario = '".$cpf_proprietario."'";
		$query_telefone = mysqli_query($conexao, $sql_telefone);
		$total_telefone = mysqli_num_rows($query_telefone);
		
		if($total_telefone < 1){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
		
}

function verifica_pendencia_conta_proprietario($cpf_proprietario){
	global $conexao;


		//Verifica pendencia conta
		$sql_conta = "SELECT codigo_conta FROM conta WHERE cpf_proprietario = '".$cpf_proprietario."'";
		$query_conta = mysqli_query($conexao, $sql_conta);
		$total_conta = mysqli_num_rows($query_conta);
		
		if($total_conta < 1){
			$situacao = "ERRO";
			return $situacao;
		}else{
			$situacao = "OK";
			return $situacao;
		}
		
}

?>
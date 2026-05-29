<?php
// Fun��o para calcular total
function calcula_total_atendimento($situacao) {
	
	if($situacao == 0){
		
		if(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 1) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento FROM atendimento WHERE atendimento.codigo_situacao <> 2 ";
		
		// gestor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 2 || protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 6) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
							AND publico_construtoras.id_construtora = ".protege($_SESSION["codigo_construtora_acesso"],true)."
							AND atendimento.codigo_situacao <> 2 
							GROUP BY atendimento.codigo_atendimento
							";
							
							

		// consultor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 3) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							AND atendimento.codigo_situacao <> 2 
							GROUP BY atendimento.codigo_atendimento
							";

											
		//Gestor da Imobili�ria
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 4) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN parceiro_usuario ON (atendimento.codigo_usuario = parceiro_usuario.codigo_usuario)
								INNER JOIN usuario ON (usuario.codigo_usuario = atendimento.codigo_usuario)
							AND parceiro_usuario.codigo_parceiro = ".protege($_SESSION["codigo_parceiro_acesso"],true)."
							AND atendimento.codigo_situacao <> 2 
							GROUP BY atendimento.codigo_atendimento
							";
							
		//Consultor da Imobili�ria
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 5) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							AND atendimento.codigo_situacao <> 2 
							GROUP BY atendimento.codigo_atendimento
							";
		}
		
	}else{
		
		if(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 1) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento FROM atendimento WHERE atendimento.codigo_situacao = '".$situacao."'";
		
		// gestor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 2 || protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 6) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND publico_construtoras.id_construtora = ".protege($_SESSION["codigo_construtora_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
							
							

		// consultor
		} elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 3) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";

											
		//Gestor da Imobili�ria
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 4) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN parceiro_usuario ON (atendimento.codigo_usuario = parceiro_usuario.codigo_usuario)
								INNER JOIN usuario ON (usuario.codigo_usuario = atendimento.codigo_usuario)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND parceiro_usuario.codigo_parceiro = ".protege($_SESSION["codigo_parceiro_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
							
		//Consultor da Imobili�ria
		}elseif(protege($_SESSION["codigo_nivel_acesso_acesso"],true) == 5) {
			$sql_consulta = "SELECT atendimento.codigo_atendimento
								FROM atendimento
								INNER JOIN publico_empreendimentos ON (atendimento.id_empreendimento = publico_empreendimentos.id_empreendimento)
								INNER JOIN publico_construtoras ON (publico_empreendimentos.id_construtora = publico_construtoras.id_construtora)
								WHERE atendimento.codigo_situacao = '".$situacao."'
							AND atendimento.codigo_usuario = ".protege($_SESSION["codigo_usuario_acesso"],true)."
							GROUP BY atendimento.codigo_atendimento
							";
		}
	}
	
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$total_consulta = mysql_num_rows($query_consulta);
	
	return $total_consulta;

}


// Fun��o para calcular total
function calcula_total_confirmacoes($codigo_notificacao) {
	
	$sql_consulta = "SELECT participantes_evento.codigo_participantes_evento FROM participantes_evento WHERE participantes_evento.codigo_notificacao = '".$codigo_notificacao."'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$total_consulta = mysql_num_rows($query_consulta);
	
	return $total_consulta;

}

// Fun��o para calcular total de unidades geral
function total_unidades() {
	$total_unidades = 0;
	$sql_consulta = "SELECT 
						COUNT(empreendimentos_unidades.id_empreendimento_unidade) AS total_unidades 
						FROM empreendimentos_unidades 
						JOIN empreendimentos_torres ON (empreendimentos_unidades.id_empreendimento_torre = empreendimentos_torres.id_empreendimento_torre)
						JOIN empreendimentos ON (empreendimentos_torres.id_empreendimento = empreendimentos.id_empreendimento)
						WHERE empreendimentos_unidades.situacao = 'D' AND empreendimentos_unidades.`status` = 'L' AND empreendimentos.`status` = 'L'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	while($resultado = mysql_fetch_assoc($query_consulta)){
		$total_unidades = $total_unidades + $resultado["total_unidades"];
	}
	
	return $total_unidades;

}

// Fun��o para calcular total de unidades dispon�veis
function total_unidades_disponiveis($id_empreendimento) {
	
	$total_unidades = 0;
	$sql_consulta = "SELECT COUNT(*) AS total_unidades 
						FROM publico_unidades
						JOIN publico_torres ON (publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre)
						WHERE publico_unidades.situacao = 'D' AND publico_torres.id_empreendimento = '".$id_empreendimento."'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);
	
	return $resultado["total_unidades"];

}

// Fun��o para calcular total de unidades dispon�veis
function total_unidades_empreendimento($id_empreendimento) {
	
	$total_unidades = 0;
	$sql_consulta = "SELECT COUNT(*) AS total_unidades 
						FROM publico_unidades
						JOIN publico_torres ON (publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre)
						WHERE publico_torres.id_empreendimento = '".$id_empreendimento."'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);
	
	return $resultado["total_unidades"];

}

// Fun��o para calcular total de unidades geral
function total_empreendimentos() {
	$sql_consulta = "SELECT COUNT(*) AS total_empreendimentos FROM publico_empreendimentos WHERE publico_empreendimentos.`status` = 'L'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);

	return $resultado["total_empreendimentos"];

}

// Fun��o para calcular total de unidades geral
function total_construtoras() {
	$sql_consulta = "SELECT COUNT(*) AS total_construtoras FROM publico_construtoras WHERE publico_construtoras.`status` = 'L'";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);

	return $resultado["total_construtoras"];

}

// Fun��o para calcular total de unidades geral
function total_imobiliarias() {
	$sql_consulta = "SELECT COUNT(*) AS total_imobiliarias FROM parceiro WHERE parceiro.`codigo_parceiro_tipo` = 1 AND parceiro.codigo_situacao = 1";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);

	return $resultado["total_imobiliarias"];

}

// Fun��o para calcular total de unidades geral
function total_corretores() {
	$sql_consulta = "SELECT SUM(total_corretores) AS total_corretores FROM(SELECT COUNT(*) AS total_corretores FROM parceiro WHERE parceiro.`codigo_parceiro_tipo` = 2 AND parceiro.codigo_situacao = 1 UNION ALL SELECT COUNT(*) AS total_corretores FROM usuario WHERE (usuario.`codigo_nivel_acesso_tipo` = 5 OR usuario.`codigo_nivel_acesso_tipo` = 4) AND usuario.codigo_situacao = 1) AS total";
	$query_consulta = mysql_query($sql_consulta) or mascara_erro($sql_consulta);
	$resultado = mysql_fetch_assoc($query_consulta);

	return $resultado["total_corretores"];

}

// Função para calcular total de unidades geral
function total_envios_mkt_mes() {

	//Abre a conexão
	$pdo = Database::conexao();
	
	$data_inicial = date("Y")."-".date("m")."-01";
	$data_final = date("Y")."-".date("m")."-31";
	$sql_consulta = "SELECT COUNT(emkt_envio_contato.codigo_emkt_envio_contato) as total_envio FROM emkt_envio_contato WHERE emkt_envio_contato.data_emkt_envio_contato > '".$data_inicial."' AND emkt_envio_contato.data_emkt_envio_contato < '".$data_final."'";
	$result = $pdo->query( $sql_consulta );
	$resultado = $result->fetch( PDO::FETCH_ASSOC );

	return $resultado["total_envio"];

}

function total_envios_campanha($codigo_envio) {

	//Abre a conexão
	$pdo = Database::conexao();	
	$sql_consulta = "SELECT COUNT(emkt_envio_contato.codigo_emkt_envio_contato) as total_envio FROM emkt_envio_contato WHERE emkt_envio_contato.codigo_emkt_envio = '".$codigo_envio."'";
	$result = $pdo->query( $sql_consulta );
	$resultado = $result->fetch( PDO::FETCH_ASSOC );

	return $resultado["total_envio"];

}

function total_envios_pendentes($codigo_envio) {

	//Abre a conexão
	$pdo = Database::conexao();
	$sql_consulta = "SELECT COUNT(emkt_envio_contato.codigo_emkt_envio_contato) as total_envio FROM emkt_envio_contato WHERE emkt_envio_contato.codigo_emkt_envio = '".$codigo_envio."' AND emkt_envio_contato.situacao_emkt_envio_contato = 'AE'";
	$result = $pdo->query( $sql_consulta );
	$resultado = $result->fetch( PDO::FETCH_ASSOC );

	return $resultado["total_envio"];

}

// Função para calcular total de unidades geral
function total_envios_ativos() {

	//Abre a conexão
	$pdo = Database::conexao();
	
	$data_inicial = date("Y")."-".date("m")."-01";
	$data_final = date("Y")."-".date("m")."-31";
	$sql_consulta = "SELECT COUNT(emkt_envio_contato.codigo_emkt_envio_contato) as total_envio FROM emkt_envio_contato WHERE emkt_envio_contato.data_emkt_envio_contato > '".$data_inicial."' AND emkt_envio_contato.data_emkt_envio_contato < '".$data_final."' AND emkt_envio_contato.situacao_emkt_envio_contato = 'AE'";
	$result = $pdo->query( $sql_consulta );
	$resultado = $result->fetch( PDO::FETCH_ASSOC );

	return $resultado["total_envio"];

}

// Função para calcular total de unidades geral
function total_envios_visualizados() {

	//Abre a conexão
	$pdo = Database::conexao();
	
	$data_inicial = date("Y")."-".date("m")."-01";
	$data_final = date("Y")."-".date("m")."-31";
	$sql_consulta = "SELECT COUNT(emkt_envio_contato.codigo_emkt_envio_contato) as total_envio FROM emkt_envio_contato WHERE emkt_envio_contato.data_emkt_envio_contato > '".$data_inicial."' AND emkt_envio_contato.data_emkt_envio_contato < '".$data_final."' AND emkt_envio_contato.situacao_emkt_envio_contato = 'ER' AND emkt_envio_contato.data_leitura_emkt_envio_contato <> ''";
	$result = $pdo->query( $sql_consulta );
	$resultado = $result->fetch( PDO::FETCH_ASSOC );

	return $resultado["total_envio"];

}

// Função para calcular total de unidades geral
function total_visualizacoes_envio($codigo_envio) {

	//Abre a conexão
	$pdo = Database::conexao();
	$sql_consulta = "SELECT COUNT(emkt_envio_contato.codigo_emkt_envio_contato) as total_envio FROM emkt_envio_contato WHERE emkt_envio_contato.codigo_emkt_envio = '".$codigo_envio."' AND emkt_envio_contato.situacao_emkt_envio_contato = 'ER' AND emkt_envio_contato.data_leitura_emkt_envio_contato <> ''";
	$result = $pdo->query( $sql_consulta );
	$resultado = $result->fetch( PDO::FETCH_ASSOC );

	return $resultado["total_envio"];

}
	
?>
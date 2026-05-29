<?php
// Função para retorna a previsão de entrega
function previsao_entrega($id_emp,$etapa = false) {
	$sql_previsaoentrega = "SELECT previsao_entrega, etapa FROM empreendimentos_torres WHERE status = 'L' AND previsao_entrega >= DATE_FORMAT(CURDATE(),'%Y%m') AND id_empreendimento = ".$id_emp." ORDER BY previsao_entrega LIMIT 1";
	$query_previsaoentrega = mysql_query($sql_previsaoentrega);
	$executa_previsaoentrega = mysql_fetch_assoc($query_previsaoentrega);
	
	if($executa_previsaoentrega['previsao_entrega']) {
		$previsao_entrega_ano = substr($executa_previsaoentrega['previsao_entrega'],0,4);
		$previsao_entrega_mes = substr($executa_previsaoentrega['previsao_entrega'],4,2);
		
		$meses = array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");
		
		if(($etapa) && ($executa_previsaoentrega['etapa'])) {
			$legenda_previsao = '<span class="previsao-entrega-2">'.$meses[$previsao_entrega_mes-1]."/".$previsao_entrega_ano." - ".$executa_previsaoentrega['etapa'].utf8_decode("º Etapa").'</span>';
		} else {
			$legenda_previsao = '<span class="previsao-entrega">'.$meses[$previsao_entrega_mes-1]."/".$previsao_entrega_ano.'</span>';
		}
	} else {
		$sql_emp = "SELECT modalidade FROM view_empreendimentos WHERE id_empreendimento = ".$id_emp." LIMIT 1";
		$query_emp = mysql_query($sql_emp);
		$executa_emp = mysql_fetch_assoc($query_emp);
		
		$legenda_previsao = '<span class="previsao-entrega">'.$executa_emp["modalidade"].'</span>';
	}
	
	return $legenda_previsao;
}



function qtd_dormitorio($id_emp) {
	$sql_empreendimento_planta = "SELECT DISTINCT(qtd_dormitorio) AS qtd_dormitorio FROM empreendimentos_plantas WHERE id_empreendimento = ".$id_emp." AND status = 'L' ORDER BY qtd_dormitorio";
	$query_empreendimento_planta = mysql_query($sql_empreendimento_planta);
	$total_empreendimento_planta = mysql_num_rows($query_empreendimento_planta);

	if($total_empreendimento_planta > 1) {
		$i = 1;
		
		while($executa_empreendimento_planta = mysql_fetch_assoc($query_empreendimento_planta)) {
			$texto_dormitorio .= $executa_empreendimento_planta['qtd_dormitorio'];
			
			if($i == $total_empreendimento_planta-1) {
				$texto_dormitorio .= '<span class="texto_previsao"> e </span>';
			} elseif($i < $total_empreendimento_planta) {
				$texto_dormitorio .= '<span class="texto_previsao">, </span>';
			}

			$i += 1;
		}
		
	} else {
		$executa_empreendimento_planta = mysql_fetch_assoc($query_empreendimento_planta);
		
		if($executa_empreendimento_planta['qtd_dormitorio'] > 1) {
			$texto_dormitorio .= $executa_empreendimento_planta['qtd_dormitorio']."";
		} else {
			$texto_dormitorio .= $executa_empreendimento_planta['qtd_dormitorio']."";	
		}
	}
	
	return $texto_dormitorio;
}

function qtd_dormitorio_msg($id_emp) {
	$sql_empreendimento_planta = "SELECT DISTINCT(qtd_dormitorio) AS qtd_dormitorio FROM empreendimentos_plantas WHERE id_empreendimento = ".$id_emp." AND status = 'L' ORDER BY qtd_dormitorio";
	$query_empreendimento_planta = mysql_query($sql_empreendimento_planta);
	$total_empreendimento_planta = mysql_num_rows($query_empreendimento_planta);

	if($total_empreendimento_planta > 1) {
		$i = 1;
		
		while($executa_empreendimento_planta = mysql_fetch_assoc($query_empreendimento_planta)) {
			$texto_dormitorio .= $executa_empreendimento_planta['qtd_dormitorio'];
			
			if($i == $total_empreendimento_planta-1) {
				$texto_dormitorio .= ' e ';
			} elseif($i < $total_empreendimento_planta) {
				$texto_dormitorio .= ', ';
			}

			$i += 1;
		}
		
	} else {
		$executa_empreendimento_planta = mysql_fetch_assoc($query_empreendimento_planta);
		
		if($executa_empreendimento_planta['qtd_dormitorio'] > 1) {
			$texto_dormitorio .= $executa_empreendimento_planta['qtd_dormitorio']."";
		} else {
			$texto_dormitorio .= $executa_empreendimento_planta['qtd_dormitorio']."";	
		}
	}
	
	return $texto_dormitorio;
}

// Função para retorna a quantidade de suites do empreendimento
function qtd_suites($id_empreendimento) {

	// Suítes
	$sql_empreendimento_dorm_suites = "	
	SELECT MIN(total_suites) AS minimo_suites, MAX(total_suites) AS maximo_suites FROM
	(
	SELECT COUNT(id_empreendimento_planta_dormitorio) AS total_suites
			FROM empreendimentos_plantas_dormitorios
			JOIN empreendimentos_plantas ON (empreendimentos_plantas_dormitorios.id_empreendimento_planta = empreendimentos_plantas.id_empreendimento_planta)
			WHERE empreendimentos_plantas.id_empreendimento = '".$id_empreendimento."' AND empreendimentos_plantas_dormitorios.status = 'L' AND empreendimentos_plantas_dormitorios.suite = 'Sim'
			GROUP BY empreendimentos_plantas_dormitorios.id_empreendimento_planta

	) AS total;
	";
	
	$query_empreendimento_dorm_suites = mysql_query($sql_empreendimento_dorm_suites);
	$executa_empreendimento_dorm_suites = mysql_fetch_assoc($query_empreendimento_dorm_suites);

	if($executa_empreendimento_dorm_suites["maximo_suites"] > 0){
		if($executa_empreendimento_dorm_suites["maximo_suites"] == $executa_empreendimento_dorm_suites["minimo_suites"]){
			$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["maximo_suites"];
		}else{

			if(($executa_empreendimento_dorm_suites["maximo_suites"] - '1') == ($executa_empreendimento_dorm_suites["minimo_suites"])){
				$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["minimo_suites"].'<span class="texto_previsao"> e </span>'.$executa_empreendimento_dorm_suites["maximo_suites"];
			}else{
				$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["minimo_suites"].'<span class="texto_previsao"> a </span>'.$executa_empreendimento_dorm_suites["maximo_suites"];
			}

		}
	}else{
		$total_empreendimento_dorm_suites = '';
	}
	

	return $total_empreendimento_dorm_suites;

}

// Função para retorna a quantidade de suites do empreendimento
function qtd_suites_msg($id_empreendimento) {

	// Suítes
	$sql_empreendimento_dorm_suites = "	
	SELECT MIN(total_suites) AS minimo_suites, MAX(total_suites) AS maximo_suites FROM
	(
	SELECT COUNT(id_empreendimento_planta_dormitorio) AS total_suites
			FROM empreendimentos_plantas_dormitorios
			JOIN empreendimentos_plantas ON (empreendimentos_plantas_dormitorios.id_empreendimento_planta = empreendimentos_plantas.id_empreendimento_planta)
			WHERE empreendimentos_plantas.id_empreendimento = '".$id_empreendimento."' AND empreendimentos_plantas_dormitorios.status = 'L' AND empreendimentos_plantas_dormitorios.suite = 'Sim'
			GROUP BY empreendimentos_plantas_dormitorios.id_empreendimento_planta

	) AS total;
	";
	
	$query_empreendimento_dorm_suites = mysql_query($sql_empreendimento_dorm_suites);
	$executa_empreendimento_dorm_suites = mysql_fetch_assoc($query_empreendimento_dorm_suites);

	if($executa_empreendimento_dorm_suites["maximo_suites"] > 0){
		if($executa_empreendimento_dorm_suites["maximo_suites"] == $executa_empreendimento_dorm_suites["minimo_suites"]){
			$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["maximo_suites"];
		}else{

			if(($executa_empreendimento_dorm_suites["maximo_suites"] - '1') == ($executa_empreendimento_dorm_suites["minimo_suites"])){
				$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["minimo_suites"].' e '.$executa_empreendimento_dorm_suites["maximo_suites"];
			}else{
				$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["minimo_suites"].' a '.$executa_empreendimento_dorm_suites["maximo_suites"];
			}

		}
	}else{
		$total_empreendimento_dorm_suites = '';
	}
	

	return $total_empreendimento_dorm_suites;

}

// Função para retorna a quantidade de suites do empreendimento
function qtd_suites_planta($id_empreendimento_planta) {

	// Suítes
	$sql_empreendimento_dorm_suites = "	
	SELECT MIN(total_suites) AS minimo_suites, MAX(total_suites) AS maximo_suites FROM
	(
	SELECT COUNT(id_empreendimento_planta_dormitorio) AS total_suites
			FROM empreendimentos_plantas_dormitorios
			JOIN empreendimentos_plantas ON (empreendimentos_plantas_dormitorios.id_empreendimento_planta = empreendimentos_plantas.id_empreendimento_planta)
			WHERE empreendimentos_plantas.id_empreendimento_planta = '".$id_empreendimento_planta."' AND empreendimentos_plantas_dormitorios.status = 'L' AND empreendimentos_plantas_dormitorios.suite = 'Sim'
			GROUP BY empreendimentos_plantas_dormitorios.id_empreendimento_planta

	) AS total;
	";
	
	$query_empreendimento_dorm_suites = mysql_query($sql_empreendimento_dorm_suites);
	$executa_empreendimento_dorm_suites = mysql_fetch_assoc($query_empreendimento_dorm_suites);

	if($executa_empreendimento_dorm_suites["maximo_suites"] > 0){
		if($executa_empreendimento_dorm_suites["maximo_suites"] == $executa_empreendimento_dorm_suites["minimo_suites"]){
			$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["maximo_suites"];
		}else{

			if(($executa_empreendimento_dorm_suites["maximo_suites"] - '1') == ($executa_empreendimento_dorm_suites["minimo_suites"])){
				$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["minimo_suites"].'<span class="texto_previsao"> e </span>'.$executa_empreendimento_dorm_suites["maximo_suites"];
			}else{
				$total_empreendimento_dorm_suites = $executa_empreendimento_dorm_suites["minimo_suites"].'<span class="texto_previsao"> a </span>'.$executa_empreendimento_dorm_suites["maximo_suites"];
			}

		}
	}else{
		$total_empreendimento_dorm_suites = '';
	}
	

	return $total_empreendimento_dorm_suites;

}


// Função para retorna a quantidade de vagas do empreendimento
function qtd_vagas($id_empreendimento) {

	// Vagas
	$sql_empreendimento_vagas = "	
								SELECT
									MIN(publico_unidades.qtd_vaga_coberta) AS minimo_coberta,
									MAX(publico_unidades.qtd_vaga_coberta) AS maximo_coberta,
									MIN(publico_unidades.qtd_vaga_descoberta) AS minimo_descoberta,
									MAX(publico_unidades.qtd_vaga_descoberta) AS maximo_descoberta
								FROM publico_unidades
								JOIN publico_torres ON publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre
								JOIN publico_empreendimentos ON (publico_torres.id_empreendimento = publico_empreendimentos.id_empreendimento)
								WHERE publico_empreendimentos.id_empreendimento = '".$id_empreendimento."' 
								";
	
	$query_empreendimento_vagas = mysql_query($sql_empreendimento_vagas);
	$executa_empreendimento_vagas = mysql_fetch_assoc($query_empreendimento_vagas);

	if($executa_empreendimento_vagas["minimo_coberta"] <> 0){
		$vagas_minimo = $executa_empreendimento_vagas["minimo_coberta"];
	}

	if($executa_empreendimento_vagas["minimo_descoberta"] <> 0 && $executa_empreendimento_vagas["minimo_descoberta"] >= $executa_empreendimento_vagas["minimo_coberta"]){
		$vagas_minimo = $executa_empreendimento_vagas["minimo_descoberta"];
	}

	if($executa_empreendimento_vagas["maximo_coberta"] <> 0){
		$vagas_maximo = $executa_empreendimento_vagas["maximo_coberta"];
	}

	if($executa_empreendimento_vagas["maximo_descoberta"] <> 0 && $executa_empreendimento_vagas["maximo_descoberta"] >= $executa_empreendimento_vagas["maximo_coberta"]){
		$vagas_maximo = $executa_empreendimento_vagas["maximo_descoberta"];
	}

	if($vagas_minimo){
		$vagas_minimo = $vagas_minimo;	
	}else{
		$vagas_minimo = min($executa_empreendimento_vagas["maximo_coberta"], $executa_empreendimento_vagas["maximo_descoberta"]);
	}

	if($vagas_minimo <> 0 || $vagas_maximo <> 0){

		if($vagas_minimo == $vagas_maximo || $vagas_minimo == 0){
		
			$total_empreendimento_vagas = $vagas_maximo;
		}else{
			$total_empreendimento_vagas = $vagas_minimo.'<span class="texto_previsao"> a </span>'.$vagas_maximo;
		}

	}else{
		$total_empreendimento_vagas = '';
	}
	

	return $total_empreendimento_vagas;

}

// Função para retorna a quantidade de vagas do empreendimento
function qtd_vagas_msg($id_empreendimento) {

	// Vagas
	$sql_empreendimento_vagas = "	
								SELECT
									MIN(publico_unidades.qtd_vaga_coberta) AS minimo_coberta,
									MAX(publico_unidades.qtd_vaga_coberta) AS maximo_coberta,
									MIN(publico_unidades.qtd_vaga_descoberta) AS minimo_descoberta,
									MAX(publico_unidades.qtd_vaga_descoberta) AS maximo_descoberta
								FROM publico_unidades
								JOIN publico_torres ON publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre
								JOIN publico_empreendimentos ON (publico_torres.id_empreendimento = publico_empreendimentos.id_empreendimento)
								WHERE publico_empreendimentos.id_empreendimento = '".$id_empreendimento."' 
								";
	
	$query_empreendimento_vagas = mysql_query($sql_empreendimento_vagas);
	$executa_empreendimento_vagas = mysql_fetch_assoc($query_empreendimento_vagas);

	if($executa_empreendimento_vagas["minimo_coberta"] <> 0){
		$vagas_minimo = $executa_empreendimento_vagas["minimo_coberta"];
	}

	if($executa_empreendimento_vagas["minimo_descoberta"] <> 0 && $executa_empreendimento_vagas["minimo_descoberta"] >= $executa_empreendimento_vagas["minimo_coberta"]){
		$vagas_minimo = $executa_empreendimento_vagas["minimo_descoberta"];
	}

	if($executa_empreendimento_vagas["maximo_coberta"] <> 0){
		$vagas_maximo = $executa_empreendimento_vagas["maximo_coberta"];
	}

	if($executa_empreendimento_vagas["maximo_descoberta"] <> 0 && $executa_empreendimento_vagas["maximo_descoberta"] >= $executa_empreendimento_vagas["maximo_coberta"]){
		$vagas_maximo = $executa_empreendimento_vagas["maximo_descoberta"];
	}

	if($vagas_minimo){
		$vagas_minimo = $vagas_minimo;	
	}else{
		$vagas_minimo = min($executa_empreendimento_vagas["maximo_coberta"], $executa_empreendimento_vagas["maximo_descoberta"]);
	}

	if($vagas_minimo <> 0 || $vagas_maximo <> 0){

		if($vagas_minimo == $vagas_maximo || $vagas_minimo == 0){
		
			$total_empreendimento_vagas = $vagas_maximo;
		}else{
			$total_empreendimento_vagas = $vagas_minimo.' a '.$vagas_maximo;
		}

	}else{
		$total_empreendimento_vagas = '';
	}
	

	return $total_empreendimento_vagas;

}



// Função para retorna a quantidade de torres do empreendimento
function qtd_torres($id_empreendimento) {

	// Torres
	$sql_empreendimento_torres = "SELECT empreendimentos_torres.id_empreendimento_torre FROM empreendimentos_torres WHERE empreendimentos_torres.id_empreendimento = ".$id_empreendimento." AND empreendimentos_torres.`status` = 'L'";
	$query_empreendimento_torres = mysql_query($sql_empreendimento_torres);
	$total_empreendimento_torres = mysql_num_rows($query_empreendimento_torres);

	return $total_empreendimento_torres;

}


// Função para retorna a quantidade de metragem do empreendimento
function qtd_metragem($id_emp) {

	$sql_empreendimento_planta = "SELECT MIN(area_privativa_real) AS de_area, MAX(area_privativa_real) AS ate_area FROM empreendimentos_plantas WHERE id_empreendimento = ".$id_emp." AND status = 'L'";
	$query_empreendimento_planta = mysql_query($sql_empreendimento_planta);
	$executa_empreendimento_planta = mysql_fetch_assoc($query_empreendimento_planta);
	
	if($executa_empreendimento_planta['de_area'] == $executa_empreendimento_planta['ate_area']) {
		$texto_area = number_format($executa_empreendimento_planta['de_area'], 2, ',','.').' <span class="texto_previsao">m&#178;</span>';
	} else {
		$texto_area = number_format($executa_empreendimento_planta['de_area'], 2, ',','.').'<span class="texto_previsao"> a </span>'.number_format($executa_empreendimento_planta['ate_area'], 2, ',','.').' <span class="texto_previsao">m&#178;</span>';
	}
	
	return $texto_area;

}

// Função para retorna a quantidade de unidades do empreendimento
function qtd_unidade($id_emp) {
	$sql_empreendimento_torre = "SELECT MIN(unidade_andar) AS de_unidade, MAX(unidade_andar) AS ate_unidade FROM empreendimentos_torres WHERE id_empreendimento = ".$id_emp."";
	$query_empreendimento_torre = mysql_query($sql_empreendimento_torre);
	$executa_empreendimento_torre = mysql_fetch_assoc($query_empreendimento_torre);
	
	if($executa_empreendimento_torre['unidade_andar'] == $executa_empreendimento_torre['unidade_andar']) {
		$texto_unidade = $executa_empreendimento_torre['de_unidade'];
	} else {
		$texto_unidade = $executa_empreendimento_torre['de_unidade']." a ".$executa_empreendimento_torre['ate_unidade'];
	}
	
	return $texto_unidade;	
}

?>
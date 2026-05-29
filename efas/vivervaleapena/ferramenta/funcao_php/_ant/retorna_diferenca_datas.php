<?php
function retorna_diferenca_datas($data_inicio){
	// Define os valores a serem usados
	$data_inicial = $data_inicio;
	$data_final = date('Y-m-d');
	
	// Usa a função strtotime() e pega o timestamp das duas datas:
	$time_inicial = strtotime($data_inicial);
	$time_final = strtotime($data_final);
	
	// Calcula a diferença de segundos entre as duas datas:
	$diferenca = $time_final - $time_inicial; // 19522800 segundos
	
	// Calcula a diferença de dias
	$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
	
	// Exibe a quantidade de dias:
	return $dias;
}
?>
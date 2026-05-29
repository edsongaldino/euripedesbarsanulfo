<?php
// Função para calcular diferença entre datas
function calcula_diferenca_datas($data1, $data2){
	
	// Criacao das datas
	$data_inicial = \DateTime::createFromFormat('Y-m-d', $data1);
	$data_final   = \DateTime::createFromFormat('Y-m-d', $data2);

	// Calculo da diferenca entre as datas
	$diferenca = $data_final->diff($data_inicial);

	// Exibicao da diferenca em dias
	return $diferenca->format("%a");

}

function tempo_restante_reserva($data_final){

	#Informamos as datas e horários de início e fim no formato Y-m-d H:i:s e os convertemos para o formato timestamp
	$dia_hora_atual = strtotime(date("Y-m-d H:i:s"));
	$dia_hora_evento = strtotime(date($data_final));
	
	#Achamos a diferença entre as datas...
	$diferenca = $dia_hora_evento - $dia_hora_atual;
	
	#Fazemos a contagem...
	$dias = intval($diferenca / 86400);
	$marcador = $diferenca % 86400;
	$hora = intval($marcador / 3600);
	$marcador = $marcador % 3600;
	$minuto = intval($marcador / 60);
	$segundos = $marcador % 60;
	
	#Exibimos o resultado
	if($dias == 0){
		$tempo = "Faltam $hora horas";	
	}elseif($dias == 1){
		$tempo = "Falta $dias dia";
	}elseif($dias > 1){
		$tempo = "Faltam $dias dias";
	}elseif($dias < 0){
		$tempo = "";
	}
	


	return $tempo;

}
?>
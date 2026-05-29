<?php
// Função para converte valores para o padrão MySQL
function converte_valor_real($valor) {
	
	$valor = number_format($valor,2,",",".");
	
	if($valor > 0) {
		return $valor;
	} else {
		return 0;
	}
	
}	

function converte_valor_real2($valor) {
	
	$valor = number_format($valor,0,",",".");
	
	if($valor > 0) {
		return $valor;
	} else {
		return 0;
	}
	
}
?>
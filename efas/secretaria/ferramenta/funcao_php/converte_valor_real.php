<?php
// Função para converte valores para o padrão brasileiro REAL
function converte_valor_real($valor) {
	$valor = number_format($valor,2,',','.');	
	return $valor;

}
?>
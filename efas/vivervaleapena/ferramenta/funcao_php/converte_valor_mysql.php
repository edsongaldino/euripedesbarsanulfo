<?php
// Função para converte valores para o padrão MySQL
function converte_valor_mysql($valor) {
	
	$valor = str_replace('R$', '', $valor);
	
	$valor = str_replace(",",".",str_replace(".","",$valor));
	
	if($valor > 0) {
		return $valor;
	} else {
		return 0;
	}
	
}	
?>
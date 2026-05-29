<?php
function valida_inteiro($valor) {
	$valor = (int) trim($valor);
	
	if($valor) {
		return $valor;
	} else {
		return "null";
	}
}
?>
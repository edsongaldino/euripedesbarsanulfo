<?php
function valida_inteiro($valor) {
	$valor = (int) $valor;
	
	if($valor) {
		return $valor;
	} else {
		return "null";
	}
}
?>
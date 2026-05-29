<?php
function valida_float($valor) {
	$valor = (float) trim($valor);
	
	if(isset($valor)) {
		return $valor;
	} else {
		return "null";
	}
}
?>
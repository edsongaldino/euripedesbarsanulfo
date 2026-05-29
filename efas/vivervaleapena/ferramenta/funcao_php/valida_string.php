<?php
function valida_string($valor) {
	$valor = (string) trim($valor);
	
	if(strlen($valor) > 0) {
		return "'".$valor."'";
	} else {
		return "null";
	}
}
?>
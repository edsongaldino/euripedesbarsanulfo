<?php
function valida_cep($texto) {
	$texto = ereg_replace("[^0-9]", "", $texto);

	if(strlen($texto) == 8) {
		return $texto;
	} else {
		return false;	
	}
}
?>
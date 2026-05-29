<?php
function valida_cpf($texto) {
	$texto = ereg_replace("[^0-9]", "", $texto);

	if(strlen($texto) == 11) {
		return $texto;
	} else {
		return false;	
	}
}
?>
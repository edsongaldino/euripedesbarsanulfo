<?php
function valida_cnpj($texto) {
	$texto = ereg_replace("[^0-9]", "", $texto);

	if(strlen($texto) == 14) {
		return $texto;
	} else {
		return false;	
	}
}
?>
<?php
function valida_telefone($texto,$info_ddd = false) {
	if($info_ddd) {
		$texto = ereg_replace("[^0-9]", "", $texto);
	
		if(strlen($texto) == 10) {
			return $texto;
		} else {
			return false;	
		}
	} else {
		$texto = ereg_replace("[^0-9]", "", $texto);
	
		if(strlen($texto) == 8) {
			return $texto;
		} else {
			return false;	
		}
	}
}
?>
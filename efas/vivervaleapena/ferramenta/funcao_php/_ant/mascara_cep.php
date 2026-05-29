<?php
function mascara_cep($texto) {
	if($texto) {
		$cep = $texto[0].$texto[1].$texto[2].$texto[3].$texto[4]."-".$texto[5].$texto[6].$texto[7];

		return $cep;
	} else {
		return false;	
	}
}
?>
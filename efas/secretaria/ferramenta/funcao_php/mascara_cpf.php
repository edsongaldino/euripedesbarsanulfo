<?php
function mascara_cpf($texto) {
	if($texto) {
		$cpf = $texto[0].$texto[1].$texto[2].".".$texto[3].$texto[4].$texto[5].".".$texto[6].$texto[7].$texto[8]."-".$texto[9].$texto[10];

		return $cpf;
	} else {
		return false;	
	}
}
?>
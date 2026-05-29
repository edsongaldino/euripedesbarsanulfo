<?php
function mascara_telefone($texto,$info_ddd = false) {
	if($info_ddd) {
		if($texto) {
			$telefone = "(".$texto[0].$texto[1].") ".$texto[2].$texto[3].$texto[4].$texto[5]."-".$texto[6].$texto[7].$texto[8].$texto[9];
			
			return $telefone;
		} else {
			return false;	
		}
	} else {
		if($texto) {
			$telefone = $texto[0].$texto[1].$texto[2].$texto[3]."-".$texto[4].$texto[5].$texto[6].$texto[7];
			
			return $telefone;
		} else {
			return false;
		}
	}
}
?>
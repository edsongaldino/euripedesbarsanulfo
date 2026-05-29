<?php
function mascara_cnpj($texto) {
	if($texto) {
		$cnpj = $texto[0].$texto[1].".".$texto[2].$texto[3].$texto[4].".".$texto[5].$texto[6].$texto[7]."/".$texto[8].$texto[9].$texto[10].$texto[11]."-".$texto[12].$texto[13];
		
		return $cnpj;
	} else {
		return false;	
	}
}
?>
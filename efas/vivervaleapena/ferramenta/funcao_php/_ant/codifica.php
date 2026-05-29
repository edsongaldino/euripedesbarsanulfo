<?php
function codifica($valor, $int = false) {
	$valor = addslashes(trim($valor));
	
	if($int) {
		$valor = (int) $valor;
	}

	return base64_encode(strrev(base64_encode($valor)));
}
?>
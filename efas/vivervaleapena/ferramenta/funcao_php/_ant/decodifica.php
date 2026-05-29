<?php
function decodifica($valor, $int = false) {
	$valor = base64_decode(strrev(base64_decode($valor)));
	$valor = addslashes(trim($valor));

	if($int) {
		$valor = (int) $valor;
	}
		
	return $valor;
}
?>
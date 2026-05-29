<?php
function campo_form_decodifica($valor,$int = false) {
	$valor = base64_decode(strrev(base64_decode($valor)));
	$valor = protege_campo($valor);

	if($int) {
		$valor = (int) $valor;
	}
		
	return $valor;
}
?>
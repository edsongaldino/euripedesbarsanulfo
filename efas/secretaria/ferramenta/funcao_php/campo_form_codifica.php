<?php
function campo_form_codifica($valor,$int = false) {
	if($int) {
		$valor = (int) $valor;
	}
	
	$valor = protege_campo($valor);

	return base64_encode(strrev(base64_encode($valor)));
}
?>
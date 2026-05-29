<?php
function limpa_campo($valor){

	$valor = preg_replace("/\D+/", "", $valor); // remove qualquer caracter não numérico
	return $valor;
	
}
?>
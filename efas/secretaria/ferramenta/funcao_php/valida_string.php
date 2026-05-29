<?php
function valida_string($valor) {
	if($valor) {
		return "'".$valor."'";
	} else {
		return "null";
	}
}
?>
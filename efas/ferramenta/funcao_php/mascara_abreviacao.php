<?php
function mascara_abreviacao($abreviacao) {
	switch($abreviacao) {
		case "A":
			return "Ativo";
			break;
		case "I":
			return "Inativo";
			break;
		default:
			return $abreviacao;
			break;
	}
}
?>
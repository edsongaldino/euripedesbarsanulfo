<?php

// Função para verificar se o campo ta vazio, se tiver, coloca Não informado
function verifica_vazio($valor, $retorna) {
	$valor = trim($valor); // removo espaço em bairo

	if($valor) {
		return $valor;	
	} else {
		return $retorna;
	}
}

// Remove todos os acentos e caracteres espeicias
function remove_acentos($texto) {
	$texto = strtolower(strtr($texto, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ -", "aaaaeeiooouucAAAAEEIOOOUUC__"));
	$texto = ereg_replace("[^a-zA-Z0-9_]", "", $texto);
	
	return $texto;
}


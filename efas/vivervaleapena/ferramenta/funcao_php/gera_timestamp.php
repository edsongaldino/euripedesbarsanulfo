<?php
	// Cria uma funчуo que retorna o timestamp de uma data no formato DD/MM/AAAA
	function geraTimestamp($data) {
		$partes = explode('/', $data);
		return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
	}	
?>
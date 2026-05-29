<?php

function verifica_parceria($id_construtora, $codigo_parceiro, $pdo) {

	$verifica = $pdo->query("SELECT codigo_parceiro
							 FROM parceiro_construtora
							 WHERE codigo_parceiro = '{$codigo_parceiro}' AND id_construtora = '{$id_construtora}'")->fetch(PDO::FETCH_ASSOC);

	if (!empty($verifica["codigo_parceiro"])):
		return true;
	else:
		return false;
	endif;			
}

function verifica_situacao_parceria($id_construtora, $codigo_parceiro, $pdo) {

	$verifica = $pdo->query("SELECT situacao
							 FROM parceiro_construtora
							 WHERE codigo_parceiro = '{$codigo_parceiro}' AND id_construtora = '{$id_construtora}'")->fetch(PDO::FETCH_ASSOC);
	return $verifica["situacao"];
			
}

function verifica_convite($id_construtora, $codigo_parceiro, $pdo) {

	$verifica = $pdo->query("SELECT status
							 FROM parceiro_convite
							 WHERE codigo_parceiro = '{$codigo_parceiro}' AND id_construtora = '{$id_construtora}'")->fetch(PDO::FETCH_ASSOC);

	return $verifica["status"];		
}

function verifica_data_convite($id_construtora, $codigo_parceiro, $pdo) {

	$verifica = $pdo->query("SELECT data_envio_convite
							 FROM parceiro_convite
							 WHERE codigo_parceiro = '{$codigo_parceiro}' AND id_construtora = '{$id_construtora}'")->fetch(PDO::FETCH_ASSOC);

	return converte_data_portugues($verifica["data_envio_convite"]);		
}

function verifica_parceria_inativa($id_construtora, $pdo) {

	$verifica = $pdo->query("SELECT parceiro_convite.tipo_convite, parceiro_construtora.codigo_parceiro
								FROM parceiro_construtora
								INNER JOIN parceiro_convite ON (parceiro_convite.codigo_parceiro = parceiro_construtora.codigo_parceiro)
								WHERE parceiro_convite.id_construtora = '{$id_construtora}' AND parceiro_construtora.id_construtora = '{$id_construtora}' AND parceiro_construtora.situacao = 'B' AND parceiro_convite.tipo_convite = 'PC'
								GROUP BY parceiro_construtora.codigo_parceiro")->fetch(PDO::FETCH_ASSOC);
	if (!empty($verifica["codigo_parceiro"])):
		return true;
	else:
		return false;
	endif;			
}

function verifica_parceria_ativa($id_construtora, $codigo_parceiro, $pdo) {

	$verifica = $pdo->query("SELECT codigo_parceiro
							 FROM parceiro_construtora
							 WHERE codigo_parceiro = '{$codigo_parceiro}' AND id_construtora = '{$id_construtora}' AND situacao = 'A'")->fetch(PDO::FETCH_ASSOC);

	if (!empty($verifica["codigo_parceiro"])):
		return true;
	else:
		return false;
	endif;			
}

?>
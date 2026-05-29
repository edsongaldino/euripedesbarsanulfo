<?php

function verifica_envio($codigo_emkt_envio, $codigo_emkt_grupo, $pdo) {

	$verifica = $pdo->query("SELECT emkt_envio_grupo.codigo_emkt_grupo FROM emkt_envio_grupo WHERE emkt_envio_grupo.codigo_emkt_envio = '$codigo_emkt_envio' AND emkt_envio_grupo.codigo_emkt_grupo = '$codigo_emkt_grupo'")->fetch(PDO::FETCH_ASSOC);
	
	if (!empty($verifica["codigo_emkt_grupo"])):
		return true;
	else:
		return false;
	endif;			
}

?>
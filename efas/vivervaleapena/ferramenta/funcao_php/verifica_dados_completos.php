<?php

function verifica_dados_completos($tipo,$codigo_usuario, $pdo) {

	if($tipo == 1){

		$verifica = $pdo->query("SELECT telefone_usuario, foto_usuario, data_nascimento_usuario 
							 FROM usuario
							 WHERE codigo_usuario = '{$codigo_usuario}'")->fetch(PDO::FETCH_ASSOC);

		if (!empty($verifica["telefone_usuario"]) && !empty($verifica["foto_usuario"]) && !empty($verifica["data_nascimento_usuario"])):
			return false;
		else:
			return true;
		endif;	

	}else{

		$verifica = $pdo->query("SELECT telefone_usuario, foto_usuario, creci_usuario 
							 FROM usuario
							 WHERE codigo_usuario = '{$codigo_usuario}'")->fetch(PDO::FETCH_ASSOC);

		if (!empty($verifica["telefone_usuario"]) && !empty($verifica["foto_usuario"]) && !empty($verifica["creci_usuario"])):
			return false;
		else:
			return true;
		endif;	

	}		
}

?>
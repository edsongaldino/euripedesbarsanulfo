<?php
function lista_arquivo_diretorio($diretorio) {
	$conteudo_dir = array();
	if(is_dir($diretorio)) { // verifica se é diretorio 
		$abre_diretorio = @opendir($diretorio); // abre o diretorio
	
		if($abre_diretorio) { // verifica se abriu
			while(($arquivo = readdir($abre_diretorio)) != false) { // pega o conteudo
				if(filetype($diretorio.$arquivo) == "file") { // pega somente os arquivos, retirando os subdiretorios
					$conteudo_dir[] = $arquivo;
				}
			}
		
			closedir($abre_diretorio); // fecha o diretorio
			
			return $conteudo_dir; // retorna os arquivos
		}
	}
}
?>
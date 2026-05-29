<?php
// inclui todas as funчѕes disponэvel na subpasta "funcao_php"

$diretorio = "funcao_php/";

$conteudo_dir = array();

if(is_dir($diretorio)) {
	$abre_diretorio = opendir($diretorio);

	if($abre_diretorio) {
		while(($arquivo = readdir($abre_diretorio)) != false) {
			if(filetype($diretorio.$arquivo) == "file") {
				$conteudo_dir[] = "include(\"".$diretorio.$arquivo."\");";
			}
		}
	
		closedir($abre_diretorio);
	}
}

$conteudo_final = "<?php";
$conteudo_final .= "\n# este conteњdo щ gerado automaticamente pelo execuчуo do arquivo funcao_php_auto.php\n\n";
$conteudo_final .= implode("\n",$conteudo_dir);
$conteudo_final .= "\n?>";

// cria um arquivo temporario e salva o conteudo
$arquivo_temp_handle = fopen("funcao_php.php", "w");
fwrite($arquivo_temp_handle, $conteudo_final);
fclose($arquivo_temp_handle);
?>
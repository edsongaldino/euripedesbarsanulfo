<?php
function redimensiona_imagem($width_img,$height_img,$nome_arquivo,$novo_nome,$diretorio,$diretorio_destino,$forca_proporcao) {
	// pega tamanho da foto
	$tamanho = getimagesize($diretorio.$nome_arquivo);
	
	// fator imagem nova
	$fator1 = $width_img / $height_img;
	
	// fator imagem original
	$fator2 = $tamanho[0] / $tamanho[1];

	// faz o redimensionamento
	if($fator2 <= $fator1) {
		$width = $width_img;
		$width_ajuste = $tamanho[0]/$width;
		$height = $tamanho[1]/$width_ajuste;		
	} else {
		$height = $height_img;
		$height_ajuste = $tamanho[1]/$height;
		$width = $tamanho[0]/$height_ajuste;		
	}

	// verifica se щ pra forчa a proporчуo (ou seja, a imagem ficar toda dentro do tamanho definido)
	if($forca_proporcao == "S") {
		// verifica novamente pra ver se esta tudo ok
		if($width > $width_img) {
			$width = $width_img;
			$width_ajuste = $tamanho[0]/$width;
			$height = $tamanho[1]/$width_ajuste;
		}
		if($height > $height_img) {
			$height = $height_img;
			$height_ajuste = $tamanho[1]/$height;
			$width = $tamanho[0]/$height_ajuste;
		}
	}

	// ajusta a margem para centralizar a imagem
	$pos_x = ($width_img-$width)/2;
	$pos_y = ($height_img-$height)/2;
	
	// grava a imagem
	$tamanho_red = imagecreatetruecolor($width_img, $height_img); // definir tamanho fixo aqui
	imagefill($tamanho_red, 0, 0, imagecolorallocate($tamanho_red, 255, 255, 255)); // aplica o fundo branco
	imagecopyresampled($tamanho_red, imagecreatefromjpeg($diretorio.$nome_arquivo), $pos_x, $pos_y, 0, 0, $width, $height, $tamanho[0], $tamanho[1]);

	// gera a nova imagem e salva em uma variavel
	ob_start();	
	imagejpeg($tamanho_red, NULL, 100);
	$imagem_final = ob_get_contents();
	ob_end_clean();

	// cria um arquivo temporario e salva a imagem
	$arquivo_temp = tempnam($_SERVER['DOCUMENT_ROOT'].SUBPASTA_RAIZ."/ferramenta/tmp", "TMP");
	$arquivo_temp_handle = fopen($arquivo_temp, "w");
	fwrite($arquivo_temp_handle, $imagem_final);
	fclose($arquivo_temp_handle);
	
	// coletando informaчѕes sobre o FTP
	$sql_sistema_informacao = "SELECT login_ftp_sistema, host_ftp_sistema, senha_ftp_sistema FROM sistema_informacao LIMIT 1";
	$query_sistema_informacao = mysql_query($sql_sistema_informacao) or mascara_erro_mysql($sql_sistema_informacao);
	$resultado_sistema_informacao = mysql_fetch_assoc($query_sistema_informacao);
	
	mysql_free_result($query_sistema_informacao);
	
	// abre uma conexуo FTP e salva o arquivo
	$ftp_conexao = ftp_connect($resultado_sistema_informacao['host_ftp_sistema']);
	$ftp_login = ftp_login($ftp_conexao, $resultado_sistema_informacao['login_ftp_sistema'], campo_form_decodifica($resultado_sistema_informacao['senha_ftp_sistema']));
	$ftp_upload_arquivo = ftp_put($ftp_conexao,$diretorio_destino.$novo_nome,$arquivo_temp,FTP_BINARY);
	$ftp_fechando_conexao = ftp_quit($ftp_conexao);
	
	// apaga arquivo temporario
	unlink($arquivo_temp);
	
	if($ftp_upload_arquivo) {
		return true;	
	} else {
		return false;
	}
}
?>
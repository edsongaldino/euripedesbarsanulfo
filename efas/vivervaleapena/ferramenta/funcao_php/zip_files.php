<?php

function zip_files($pdo, $sql, $base_url = 0, $id_empreendimento)
{
	$files = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

	# create new zip opbject
	$zip = new ZipArchive();

	# create a temp file & open it
	$tmp_file = tempnam('.','');
	$zip->open($tmp_file, ZipArchive::CREATE);

	# loop through each file
	foreach($files as $file) {

		if ($base_url == 1):
			$url = "http://www.lancamentosonline.com.br/imagens/empreendimento/" . $id_empreendimento . "/ampliada/" . $file['arquivo'];
		else:
			$url = $file;
		endif;	

	    # download file
	    $download_file = file_get_contents($url);

	    #add it to the zip
	    $zip->addFromString(basename($url),$download_file);

	}

	# close zip
	$zip->close();

	# send the file to the browser as a download
	header('Content-disposition: attachment; filename=fotos.zip');
	header('Content-type: application/zip');
	readfile($tmp_file);

}
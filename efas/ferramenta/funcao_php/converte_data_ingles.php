<?php
function converte_data_ingles($data,$retorno = "0000-00-00") {
	if($data) {
		$data = explode("/",$data);
		return $data[2]."-".$data[1]."-".$data[0];
	} else {
		return $retorno;
	}
}
?>
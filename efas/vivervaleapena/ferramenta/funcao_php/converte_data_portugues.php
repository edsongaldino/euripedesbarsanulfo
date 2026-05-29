<?php
function converte_data_portugues($data,$retorno = "00/00/0000") {
	if($data) {
		if($data != "0000-00-00") {
			$data = explode("-",$data);
			return $data[2]."/".$data[1]."/".$data[0];
		} else {
			return $retorno;
		}
	} else {
		return $retorno;
	}
}

function converte_datetime_portugues($data) {
	$retorno = date('d/m/Y H:i:s', strtotime($data));
	return $retorno;
}

?>
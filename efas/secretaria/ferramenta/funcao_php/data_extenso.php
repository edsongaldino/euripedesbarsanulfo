<?php
function data_extenso() {
	$saudacao = "Cuiabá - MT";
	
	$dia = date("d", time());
	$mes = date("m", time());
	$ano = date("Y", time());
	
	switch($mes) {
		case 1:
			case "01":
				$mes_extenso = "janeiro";
				break;
			case "02":
				$mes_extenso = "fevereiro";
				break;
			case "03":
				$mes_extenso = "março";
				break;
			case "04":
				$mes_extenso = "abril";
				break;
			case "05":
				$mes_extenso = "maio";
				break;
			case "06":
				$mes_extenso = "junho";
				break;
			case "07":
				$mes_extenso = "julho";
				break;
			case "08":
				$mes_extenso = "agosto";
				break;
			case "09":
				$mes_extenso = "setembro";
				break;
			case "10":
				$mes_extenso = "outubro";
				break;
			case "11":
				$mes_extenso = "novembro";
				break;
			case "12":
				$mes_extenso = "dezembro";
				break;
	}
	
	return ($saudacao.", ".$dia." de ".ucfirst($mes_extenso)." de ".$ano);
}
?>
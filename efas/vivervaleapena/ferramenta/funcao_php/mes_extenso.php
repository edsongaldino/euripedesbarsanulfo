<?php
function mes_extenso($mes) {
	switch($mes) {
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
	
	return ucfirst($mes_extenso);
}

function mes_extenso_abreviado($mes) {
	switch($mes) {
		case "01":
			$mes_extenso = "JAN";
			break;
		case "02":
			$mes_extenso = "FEV";
			break;
		case "03":
			$mes_extenso = "MAR";
			break;
		case "04":
			$mes_extenso = "ABR";
			break;
		case "05":
			$mes_extenso = "MAI";
			break;
		case "06":
			$mes_extenso = "JUN";
			break;
		case "07":
			$mes_extenso = "JUL";
			break;
		case "08":
			$mes_extenso = "AGO";
			break;
		case "09":
			$mes_extenso = "SET";
			break;
		case "10":
			$mes_extenso = "OUT";
			break;
		case "11":
			$mes_extenso = "NOV";
			break;
		case "12":
			$mes_extenso = "DEZ";
			break;
	}
	
	return ucfirst($mes_extenso);
}
?>
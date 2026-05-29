<?php
function mascara_telefone($valor,$info_ddd=true) {
	if($info_ddd) {
		if($valor) {
			if(strlen($valor) == 11){
				$telefone = "(".$valor[0].$valor[1].") ".$valor[2].$valor[3].$valor[4].$valor[5].$valor[6]."-".$valor[7].$valor[8].$valor[9].$valor[10];
			}else{
				$telefone = "(".$valor[0].$valor[1].") ".$valor[2].$valor[3].$valor[4].$valor[5]."-".$valor[6].$valor[7].$valor[8].$valor[9];
			}
			return $telefone;
		} else {
			return false;
		}
	} else {
		if($valor) {
			$telefone = $valor[0].$valor[1].$valor[2].$valor[3]."-".$valor[4].$valor[5].$valor[6].$valor[7];
			return $telefone;
		} else {
			return false;
		}
	}
}

function formataTelefone($numero){
	if(strlen($numero) == 10){
		$novo = substr_replace($numero, '(', 0, 0);
		$novo = substr_replace($novo, '9', 3, 0);
		$novo = substr_replace($novo, ') ', 3, 0);
		$novo = substr_replace($novo, '-', 10, 0);
	}else{
		$novo = substr_replace($numero, '(', 0, 0);
		$novo = substr_replace($novo, ') ', 3, 0);
		$novo = substr_replace($novo, '-', 9, 0);
	}
	return $novo;
}


function mask($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 if($mask[$i] == '#')
 {
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }
 else
 {
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
}
?>
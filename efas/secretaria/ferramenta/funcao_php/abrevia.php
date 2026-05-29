<?php
function abrevia($valor,$tam_permitido) {
	if($valor) {
		$valor = trim($valor);
		$valor_final = "";
		
		if(strlen($valor) > $tam_permitido) {
			$palavras = explode(" ",$valor);
			$total_palavras = sizeof($palavras);
			
			for($i=0;$i<$total_palavras;$i++) {
				if(strlen($palavras[$i]) > 3) {
					$valor_final[] = $palavras[$i];
				}
			}
			
			$valor_final = implode(" ",$valor_final);
			$palavras = explode(" ",$valor_final);
			$total_palavras = sizeof($palavras);
			
			$valor_final = $palavras[0];
			
			if((strlen($valor_final) <= ($tam_permitido-3)) && ($total_palavras > 1)) {
				if(strlen($valor_final." ".$palavras[1]) <= ($tam_permitido)) {
					return $valor_final." ".$palavras[1];
				} else {
					return $valor_final." ".strtoupper(substr($palavras[1],0,1)).".";
				}
			} else {
				return $valor_final;
			}
		} else {
			return $valor;	
		}
	} else {
		return false;
	}
}
?>
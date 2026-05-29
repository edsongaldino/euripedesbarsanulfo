<?php
function sql_explode_like($campo,$texto,$filtro = "baixo") {
	$campo = addslashes(trim($campo));
	$texto = addslashes(trim($texto));
	
	if($texto) {
		if($filtro == "alto") {
			// apenas palavras que começam
			
			$palavra = $texto;			
			$termos_parcial = array();
				
			$termos = $campo." LIKE '".$palavra."%'";

			unset($termos_parcial);
			
			return "(".$termos.")";
		} elseif($filtro == "medio") {
			// apenas palavras que começa ou termina
			
			$palavras = explode(" ",$texto);
			$termos = array();	
			$termos_parcial = array();
				
			foreach($palavras as $palavra_atual) {
				$termos_parcial[] = $campo." LIKE '%".$palavra_atual."'";
				$termos_parcial[] = $campo." LIKE '".$palavra_atual."%'";
				$termos_parcial[] = $campo." LIKE '%".$palavra_atual." %'";
				$termos_parcial[] = $campo." LIKE '% ".$palavra_atual."%'";
				
				$termos[] = "(".implode(" OR ",$termos_parcial).")";
				
				unset($termos_parcial);
			}
	
			return "(".implode(" AND ",$termos).")";
		} elseif($filtro == "baixo") {
			// contem a palavra
			
			$palavras = explode(" ",$texto);
			$termos = array();
			
			foreach($palavras as $palavra_atual) {
				$termos[] = $campo." LIKE '%".$palavra_atual."%'";
			}
			
			return "(".implode(" AND ",$termos).")";
		}
	} else {			
		return NULL;
	}
}
?>
<?php
function sql_explode_like($campo,$texto,$filtro = "baixo") {
	$campo = protege_campo($campo);
	$texto = protege_campo($texto);
	
	if($texto) {
		if($filtro == "alto") {
			$palavra = $texto;			
			$termos_parcial = array();
				
			$termos = $campo." LIKE '".$palavra."%'";

			unset($termos_parcial);
			
			return "(".$termos.")";
		} elseif($filtro == "medio") {
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
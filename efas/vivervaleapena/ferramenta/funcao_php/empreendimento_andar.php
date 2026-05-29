<?php
function empreendimento_andar($tipo,$codigo) {
	
	// consulta torre
	if($tipo == "torre") {
		$sql_consulta_andar = "
							  SELECT andar, 'Térreo' AS legenda
							  FROM publico_unidades
							  WHERE id_empreendimento_torre = ".$codigo." AND andar = 'T'
							  
							  UNION
							  
							  SELECT andar, CONCAT(andar,'º andar') AS legenda
							  FROM publico_unidades
							  WHERE id_empreendimento_torre = ".$codigo." AND andar <> 'T' AND andar <> 'C'
							  GROUP BY CAST(andar as signed)
							  
							  UNION
							  
							  SELECT andar, 'Cobertura' AS legenda
							  FROM publico_unidades
							  WHERE id_empreendimento_torre = ".$codigo." AND andar = 'C'
							  ";
		$query_consulta_andar = mysql_query($sql_consulta_andar) or mascara_erro($sql_consulta_andar);
		$total_consulta_andar = mysql_num_rows($query_consulta_andar);
	
		$andar = array();
		
		while($resultado_consulta_andar = mysql_fetch_assoc($query_consulta_andar)) {
			$andar[] = array("andar" => $resultado_consulta_andar["andar"],"legenda" => $resultado_consulta_andar["legenda"]);
		}
		
		mysql_free_result($query_consulta_andar);
		
		if($total_consulta_andar) {
			return $andar;
		} else {
			return NULL;
		}
	}

	// consulta torre
	if($tipo == "empreendimento") {
		$sql_consulta_andar = "
							  SELECT publico_unidades.andar, 'Térreo' AS legenda
							  FROM publico_unidades
							  JOIN publico_torres ON publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre
							  WHERE publico_torres.id_empreendimento = ".$codigo." AND publico_unidades.andar = 'T'
							  
							  UNION
							  
							  SELECT publico_unidades.andar, CONCAT(andar,'º andar') AS legenda
							  FROM publico_unidades
							  JOIN publico_torres ON publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre
							  WHERE publico_torres.id_empreendimento = ".$codigo." AND andar <> 'T' AND andar <> 'C'
							  GROUP BY CAST(publico_unidades.andar as signed)
							  
							  UNION
							  
							  SELECT publico_unidades.andar, 'Cobertura' AS legenda
							  FROM publico_unidades
							  JOIN publico_torres ON publico_torres.id_empreendimento_torre = publico_unidades.id_empreendimento_torre
							  WHERE publico_torres.id_empreendimento = ".$codigo." AND publico_unidades.andar = 'C'
							  ";
		$query_consulta_andar = mysql_query($sql_consulta_andar) or mascara_erro($sql_consulta_andar);
		$total_consulta_andar = mysql_num_rows($query_consulta_andar);
	
		$andar = array();
		
		while($resultado_consulta_andar = mysql_fetch_assoc($query_consulta_andar)) {
			$andar[] = array("andar" => $resultado_consulta_andar["andar"],"legenda" => $resultado_consulta_andar["legenda"]);
		}
		
		mysql_free_result($query_consulta_andar);
		
		if($total_consulta_andar) {
			return $andar;
		} else {
			return NULL;
		}
	}
}
?>
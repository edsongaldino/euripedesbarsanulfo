<?php
function empreendimento_dormitorio($tipo,$codigo) {
	// consulta torre
	if($tipo == "torre") {
		$sql_consulta_dormitorio = "
									SELECT DISTINCT(COUNT(tmp.id_empreendimento_planta)) AS dormitorio, IF(COUNT(tmp.id_empreendimento_planta) > 1,'dormitórios','dormitório') AS legenda FROM (
										SELECT publico_plantas.id_empreendimento_planta
										FROM publico_torres
											INNER JOIN publico_empreendimentos ON (publico_torres.id_empreendimento = publico_empreendimentos.id_empreendimento)
											INNER JOIN publico_plantas ON (publico_empreendimentos.id_empreendimento = publico_plantas.id_empreendimento)
											INNER JOIN empreendimentos_plantas_dormitorios ON (publico_plantas.id_empreendimento_planta = empreendimentos_plantas_dormitorios.id_empreendimento_planta)
										WHERE empreendimentos_plantas_dormitorios.status = 'L' AND publico_torres.id_empreendimento_torre = ".$codigo."
										GROUP BY empreendimentos_plantas_dormitorios.id_empreendimento_planta_dormitorio
										ORDER BY publico_plantas.id_empreendimento_planta
									) tmp
									GROUP BY tmp.id_empreendimento_planta
									";
		$query_consulta_dormitorio = mysql_query($sql_consulta_dormitorio) or mascara_erro($sql_consulta_dormitorio);
		$total_consulta_dormitorio = mysql_num_rows($query_consulta_dormitorio);
	
		$dormitorio = array();
		
		while($resultado_consulta_dormitorio = mysql_fetch_assoc($query_consulta_dormitorio)) {
			$dormitorio[] = array("dormitorio" => $resultado_consulta_dormitorio["dormitorio"],"legenda" => $resultado_consulta_dormitorio["legenda"]);
		}
		
		mysql_free_result($query_consulta_dormitorio);
		
		if($total_consulta_dormitorio) {
			return $dormitorio;
		} else {
			return NULL;
		}
	}
}
?>
<?php
// Funзгo para retornar destinatarios envio
function destinatarios_envio($codigo_envio){
	
	// Conta o total de interaпїЅoes do atendimento
    $sql_consulta_atendimento = ("SELECT COUNT(atendimento.codigo_atendimento) AS total, atendimento.nome_atendimento FROM atendimento
                                    JOIN envio_destinatario_atendimento ON (envio_destinatario_atendimento.codigo_atendimento = atendimento.codigo_atendimento)
                                    WHERE envio_destinatario_atendimento.codigo_envio = '".$codigo_envio."' 
                                    GROUP BY atendimento.nome_atendimento ORDER BY atendimento.nome_atendimento ASC");
    $query_consulta_atendimento = mysql_query($sql_consulta_atendimento) or mascara_erro($sql_consulta_atendimento);
    

    $i = 0;
    while($resultado_consulta_atendimento = mysql_fetch_assoc($query_consulta_atendimento)){
           
        $destinatarios_envio .= $resultado_consulta_atendimento["nome_atendimento"].",";
         
        $i = $i+1;
    }

    return $destinatarios_envio;
}	
?>
<?php
// Funзгo para calcular diferenзa entre datas
function data_ultima_interacao($codigo_atendimento){
	
	// Conta o total de interaпїЅoes do atendimento
    $sql_consulta_atendimento = ("SELECT atendimento_interacao.descricao_atendimento_interacao, usuario_log.data_usuario_log, usuario_log.hora_usuario_log
                                    FROM atendimento_interacao 
                                    JOIN atendimento_interacao_log ON (atendimento_interacao_log.codigo_atendimento_interacao = atendimento_interacao.codigo_atendimento_interacao)
                                    JOIN usuario_log ON (atendimento_interacao_log.codigo_usuario_log = usuario_log.codigo_usuario_log)
                                    WHERE atendimento_interacao.codigo_atendimento = '".$resultado_consulta["codigo_atendimento"]."' 
                                    ORDER BY atendimento_interacao.codigo_atendimento_interacao DESC LIMIT 1");
    $query_consulta_atendimento = mysql_query($sql_consulta_atendimento) or mascara_erro($sql_consulta_atendimento);
    $resultado_consulta_atendimento = mysql_fetch_assoc($query_consulta_atendimento);



}	
?>
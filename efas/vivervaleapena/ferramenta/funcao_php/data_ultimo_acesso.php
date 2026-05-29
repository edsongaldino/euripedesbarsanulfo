<?php
// Funчуo para calcular data do ultimo login
function data_ultimo_acesso($codigo_usuario){
	conecta_mysql();

    $sql_consulta_data = ("SELECT usuario_log.data_usuario_log FROM usuario_log WHERE usuario_log.codigo_usuario = ".$codigo_usuario." AND usuario_log.codigo_log_tipo = 1 ORDER BY usuario_log.data_usuario_log DESC LIMIT 1");
    $query_consulta_data = mysql_query($sql_consulta_data) or mascara_erro($sql_consulta_data);
    $resultado_consulta_data = mysql_fetch_assoc($query_consulta_data);
    
    if($resultado_consulta_data["data_usuario_log"]){
        $data_ultimo_acesso = $resultado_consulta_data["data_usuario_log"];
    }else{
        $data_ultimo_acesso = '';
    }
    
    return $data_ultimo_acesso;
    fecha_mysql();
}	
?>
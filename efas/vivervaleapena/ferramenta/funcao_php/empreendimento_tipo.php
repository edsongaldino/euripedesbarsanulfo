<?php
function empreendimento_tipo($codigo,$tipo) {

    if($tipo = "Lançamento"){

        // consulta tipos
        $sql_consulta_tipo = "SELECT empreendimento_tipo.descricao_empreendimento_tipo FROM empreendimento_tipo WHERE codigo_empreendimento_tipo = '".$codigo."'";
        $query_consulta_tipo = mysql_query($sql_consulta_tipo) or mascara_erro($sql_consulta_tipo);
        $total_consulta_tipo = mysql_num_rows($query_consulta_tipo);
        $resultado_consulta_tipo = mysql_fetch_assoc($query_consulta_tipo);
        
        mysql_free_result($query_consulta_tipo);
        
        if($total_consulta_tipo) {
            return $resultado_consulta_tipo["descricao_empreendimento_tipo"];
        } else {
            return NULL;
        }

    }else{

        // consulta tipos
        $sql_consulta_tipo = "SELECT tipo_imovel.descricao_tipo_imovel FROM tipo_imovel WHERE codigo_tipo_imovel = '".$codigo."'";
        $query_consulta_tipo = mysql_query($sql_consulta_tipo) or mascara_erro($sql_consulta_tipo);
        $total_consulta_tipo = mysql_num_rows($query_consulta_tipo);
        $resultado_consulta_tipo = mysql_fetch_assoc($query_consulta_tipo);
        
        mysql_free_result($query_consulta_tipo);
        
        if($total_consulta_tipo) {
            return $resultado_consulta_tipo["descricao_tipo_imovel"];
        } else {
            return NULL;
        }

    }
	
}
?>
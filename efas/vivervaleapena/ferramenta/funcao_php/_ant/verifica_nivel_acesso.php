<?php
function verifica_nivel_acesso($nivel_acesso,$redireciona = true) {
	// verificacao
	$sql_verifica_nivel = "
						  SELECT usuario.codigo_usuario
						  FROM usuario
							  JOIN usuario_nivel_acesso ON (usuario.codigo_usuario = usuario_nivel_acesso.codigo_usuario)
							  JOIN nivel_acesso ON (usuario_nivel_acesso.codigo_nivel_acesso = nivel_acesso.codigo_nivel_acesso)
						  WHERE usuario_nivel_acesso.codigo_usuario = ".valida_inteiro($_SESSION["codigo_usuario_acesso"])." AND nivel_acesso.nivel_acesso = ".valida_string($nivel_acesso)."
						  GROUP BY nivel_acesso.codigo_nivel_acesso
						  LIMIT 1
						  ";
	$query_verifica_nivel = mysql_query($sql_verifica_nivel) or mascara_erro_mysql($sql_verifica_nivel);
	$resultado_verifica_nivel = mysql_fetch_assoc($query_verifica_nivel);
	$total_verifica_nivel = mysql_num_rows($query_verifica_nivel);

	mysql_free_result($query_verifica_nivel);
	
	// redireciona
	if($redireciona) {
		if(!$total_verifica_nivel) {
			// consulta
			$sql_consulta = "SELECT descricao_nivel_acesso FROM nivel_acesso WHERE nivel_acesso = ".valida_string($nivel_acesso)." LIMIT 1";
			$query_consulta = mysql_query($sql_consulta) or mascara_erro_mysql($sql_consulta);
			$resultado_consulta = mysql_fetch_assoc($query_consulta);
			mysql_free_result($query_consulta);
						
			fecha_mysql();
			
			if(!$resultado_consulta["descricao_nivel_acesso"]) {
				$resultado_consulta["descricao_nivel_acesso"] = "esta aчуo";
			}
			
			redireciona(SUBPASTA_RAIZ."/index.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Vocъ nуo tem permissуo para ".strtolower($resultado_consulta["descricao_nivel_acesso"])));
		}
	// apenas retorna true ou false
	} else {
		if($total_verifica_nivel) {
			return true;
		} else {
			return false;
		}
	}
}
?>
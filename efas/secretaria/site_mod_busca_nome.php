<?php include_once("sistema_mod_include.php"); ?>
<?php
	conecta_mysql();
	// dados
	$pesquisa_rapida = trim($_GET['term']);
	$pesquisa_rapida = str_replace("\'"," ",$pesquisa_rapida);
	$pesquisa_rapida = strtolower(strtr($pesquisa_rapida, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUC"));
	$pesquisa_rapida = mysqli_real_escape_string($conexao, trim($pesquisa_rapida));

	// consulta
	$pesquisa_rapida_cons = array();

	if($pesquisa_rapida) {
		$palavras = explode(" ",$pesquisa_rapida);
		
		foreach($palavras as $x) {
			$x = addslashes(trim($x));
			
			if($x) {
				$pesquisa_rapida_cons[] = "participante.nome_participante LIKE '%".$x."%'";
			}
		}
		
		$sql_pesquisa_rapida_cons = "WHERE (".implode(" AND ",$pesquisa_rapida_cons).")";
	}

	// vetor jquery
	$opcoes_pes_rap = array();

	$sql_opcoes_pes_rap = " SELECT participante.nome_participante FROM participante ".$sql_pesquisa_rapida_cons." GROUP BY participante.nome_participante";
	$query_opcoes_pes_rap = mysqli_query($conexao, $sql_opcoes_pes_rap);

	while($executa_opcoes_pes_rap = mysqli_fetch_assoc($query_opcoes_pes_rap)) {
		$nome_limpo = fix_double_utf8($executa_opcoes_pes_rap['nome_participante']);
		$nome_limpo = str_replace(array('\\', '"'), array('\\\\', '\"'), $nome_limpo);
		$opcoes_pes_rap[] = '"'.$nome_limpo.'"';
	}

	echo "[".implode(",",$opcoes_pes_rap)."]";
	
	fecha_mysql();

?>
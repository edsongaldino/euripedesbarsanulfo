<?php include("sistema_mod_include.php"); ?>
<?php

conecta_mysql();

if(campo_form_decodifica($_GET["acao"]) == "inscrever"){
// consulta participante
$sql_consulta_participante = "SELECT participante.codigo_cidade, participante.nome_participante, participante.centro_espirita_participante, participante.data_nascimento_participante, telefone_participante.numero_telefone_participante 
								FROM participante 
								JOIN telefone_participante ON (participante.codigo_participante = telefone_participante.codigo_participante)
								WHERE participante.codigo_participante = '".$_SESSION["codigo_participante"]."' LIMIT 1";
$query_consulta_participante = mysqli_query($conexao, $sql_consulta_participante) or mascara_erro_mysql($sql_consulta_participante);	
$resultado_consulta_participante = mysqli_fetch_assoc($query_consulta_participante);
}


// consulta cursos 0 à 11 anos
$sql_consulta_cursos_criancas = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
									JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
									JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
									JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
									JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
								 WHERE (curso.codigo_tema_curso = '1' OR curso.codigo_tema_curso = '2') AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_cursos_criancas = mysqli_query($conexao, $sql_consulta_cursos_criancas) or mascara_erro_mysql($sql_consulta_cursos_criancas);

// consulta tema específico 12 e 13 anos e adulto
$sql_consulta_tema_especifico_adulto = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE (curso.codigo_tema_curso = '4') AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_especifico_adulto = mysqli_query($conexao, $sql_consulta_tema_especifico_adulto) or mascara_erro_mysql($sql_consulta_tema_especifico_adulto);

// consulta tema atual 12 e 13 anos e adulto
$sql_consulta_tema_atual_adulto = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE (curso.codigo_tema_curso = '5' OR curso.codigo_tema_curso = '6') AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_atual_adulto = mysqli_query($conexao, $sql_consulta_tema_atual_adulto) or mascara_erro_mysql($sql_consulta_tema_atual_adulto);

// consulta tema específico 12 e 13 anos e adulto
$sql_consulta_tema_especifico_jovem= "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE curso.codigo_tema_curso = '3' AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_especifico_jovem = mysqli_query($conexao, $sql_consulta_tema_especifico_jovem) or mascara_erro_mysql($sql_consulta_tema_especifico_jovem);

// consulta tema atual 12 e 13 anos e adulto
$sql_consulta_tema_atual_jovem = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.quantidade_vagas, evento_curso.referencia FROM evento_curso 
											JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
											JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
											JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
											JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
										 WHERE curso.codigo_tema_curso = '5' AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
$query_consulta_tema_atual_jovem = mysqli_query($conexao, $sql_consulta_tema_atual_jovem) or mascara_erro_mysql($sql_consulta_tema_atual_jovem);


// consulta comissoes de trabalho
$sql_consulta_comissoes_trabalho = "SELECT comissao_trabalho.codigo_comissao_trabalho, comissao_trabalho.nome_comissao_trabalho FROM comissao_trabalho ORDER BY comissao_trabalho.nome_comissao_trabalho ASC";
$query_consulta_comissoes_trabalho = mysqli_query($conexao, $sql_consulta_comissoes_trabalho) or mascara_erro_mysql($sql_consulta_comissoes_trabalho);


$mensagem = campo_form_decodifica($_GET["mm"]);

if(campo_form_decodifica($_POST['acao']) == "gravar_participante_crianca") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	// dados responsável
	$nome_responsavel							= protege_campo($_POST['nome_responsavel']);
	$telefone_responsavel 						= protege_campo($_POST['telefone_responsavel']);
	$observacoes_crianca			 			= protege_campo($_POST['observacoes_crianca']);
	
	conecta_mysql();
	
	mysqli_query($conexao, "BEGIN");
	
	// inclui participante
	$sql_inclui_participante = "INSERT INTO participante (codigo_cidade, data_nascimento_participante, nome_participante, nome_participante_cracha, centro_espirita_participante) VALUES ('".$cidade_participante."', '".$data_nascimento_participante."','".$nome_participante."','".$nome_participante_cracha."','".$centro_espirita_participante."')";
	$query_inclui_participante = mysqli_query($conexao, $sql_inclui_participante) or mascara_erro_mysql($sql_inclui_participante,"index.php");
	$codigo_participante = mysqli_insert_id($conexao);
	
	// inclui dados complementares
	$sql_inclui_dados_complementares = "INSERT INTO dados_complementares (codigo_participante, nome_responsavel, telefone_responsavel, observacoes_crianca) VALUES ('".$codigo_participante."', '".$nome_responsavel."','".$telefone_responsavel."','".$observacoes_crianca."')";
	$query_inclui_dados_complementares = mysqli_query($conexao, $sql_inclui_dados_complementares) or mascara_erro_mysql($sql_inclui_dados_complementares,"index.php");
	
	$data_atual = date("Y-m-d");
	// inclui participante ao evento
	$sql_inclui_usuario_participante = "INSERT INTO inscricao_evento (codigo_evento, codigo_participante, codigo_situacao_inscricao, valor_inscricao_evento, data_inscricao_evento, tipo_inscricao) VALUES ('".$_SESSION["codigo_evento_acesso"]."', '".$codigo_participante."', '1', '12,50', '".$data_atual."', 'C')";
	$query_inclui_usuario_participante = mysqli_query($conexao, $sql_inclui_usuario_participante) or mascara_erro_mysql($sql_inclui_usuario_participante,"index.php");
	$codigo_inscricao_evento = mysqli_insert_id($conexao);
	
	// vincula o usuário à ibscrição
	$sql_inclui_usuario_inscricao_evento = "INSERT INTO usuario_inscricao_evento (codigo_usuario, codigo_inscricao_evento) VALUES ('".$_SESSION["codigo_usuario_acesso"]."', '".$codigo_inscricao_evento."')";
	$query_inclui_usuario_inscricao_evento = mysqli_query($conexao, $sql_inclui_usuario_inscricao_evento) or mascara_erro_mysql($sql_inclui_usuario_inscricao_evento,"index.php");
	
	
	// inclui curso participante
	for($i=0;$i<count($_POST['curso_crianca']);$i++){
		
	$sql_inclui_curso_participante = "INSERT INTO participante_evento_curso (codigo_participante, codigo_evento, codigo_curso) VALUES ('".$codigo_participante."','".$_SESSION["codigo_evento_acesso"]."', '".protege_campo($_POST['curso_crianca'][$i])."')";
	$query_inclui_curso_participante = mysqli_query($conexao, $sql_inclui_curso_participante) or mascara_erro_mysql($sql_inclui_curso_participante,"index.php");
	
	}
	

	if($query_inclui_participante && $query_inclui_usuario_participante && $query_inclui_usuario_inscricao_evento && $query_inclui_curso_participante){
		mysqli_query($conexao, "COMMIT");	
		fecha_mysql();
		redireciona("participantes.php?codigo_participante=".campo_form_codifica($codigo_participante)."&me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição realizada! veja abaixo."));
		
	} else {
		mysqli_query($conexao, "ROLLBACK");	
		fecha_mysql();
		redireciona("inscricao.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi realizada. Tente novamente!"));
	}
}

if(campo_form_decodifica($_POST['acao']) == "gravar_participante_adulto") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	$telefone_participante						= protege_campo(limpa_campo($_POST['telefone_participante']));
	$email_participante							= protege_campo($_POST['email_participante']);

	
	conecta_mysql();
	
	mysqli_query($conexao, "BEGIN");
	
	// inclui participante
	$sql_inclui_participante = "INSERT INTO participante (codigo_cidade, data_nascimento_participante, nome_participante, nome_participante_cracha, centro_espirita_participante) VALUES ('".$cidade_participante."', '".$data_nascimento_participante."','".$nome_participante."','".$nome_participante_cracha."','".$centro_espirita_participante."')";
	$query_inclui_participante = mysqli_query($conexao, $sql_inclui_participante) or mascara_erro_mysql($sql_inclui_participante,"index.php");
	$codigo_participante = mysqli_insert_id($conexao);
	
	if($telefone_participante):
	// inclui telefone
	$sql_inclui_telefone_participante = "INSERT INTO telefone_participante (codigo_participante, numero_telefone_participante) VALUES ('".$codigo_participante."', '".$telefone_participante."')";
	$query_inclui_telefone_participante = mysqli_query($conexao, $sql_inclui_telefone_participante) or mascara_erro_mysql($sql_inclui_telefone_participante,"index.php");
	endif;

	if($email_participante):
	// inclui email
	$sql_inclui_email_participante = "INSERT INTO email_participante (codigo_participante, descricao_email_participante) VALUES ('".$codigo_participante."', '".$email_participante."')";
	$query_inclui_email_participante = mysqli_query($conexao, $sql_inclui_email_participante) or mascara_erro_mysql($sql_inclui_email_participante,"index.php");
	endif;	

	// inclui curso participante
	for($i=0;$i<count($_POST['curso_participante']);$i++){
		
	$sql_inclui_curso_participante = "INSERT INTO participante_evento_curso (codigo_participante, codigo_evento, codigo_curso) VALUES ('".$codigo_participante."','".$_SESSION["codigo_evento_acesso"]."', '".protege_campo($_POST['curso_participante'][$i])."')";
	$query_inclui_curso_participante = mysqli_query($conexao, $sql_inclui_curso_participante) or mascara_erro_mysql($sql_inclui_curso_participante,"index.php");
	
	}
	
	$data_atual = date("Y-m-d");
	
	// inclui participante ao evento
	$sql_inclui_usuario_participante = "INSERT INTO inscricao_evento (codigo_evento, codigo_participante, codigo_situacao_inscricao, valor_inscricao_evento, data_inscricao_evento, tipo_inscricao) VALUES ('".$_SESSION["codigo_evento_acesso"]."', '".$codigo_participante."', '1', '25,00', '".$data_atual."', 'A')";
	$query_inclui_usuario_participante = mysqli_query($conexao, $sql_inclui_usuario_participante) or mascara_erro_mysql($sql_inclui_usuario_participante,"index.php");
	$codigo_inscricao_evento = mysqli_insert_id($conexao);
	
	// vincula o usuário à ibscrição
	$sql_inclui_usuario_inscricao_evento = "INSERT INTO usuario_inscricao_evento (codigo_usuario, codigo_inscricao_evento) VALUES ('".$_SESSION["codigo_usuario_acesso"]."', '".$codigo_inscricao_evento."')";
	$query_inclui_usuario_inscricao_evento = mysqli_query($conexao, $sql_inclui_usuario_inscricao_evento) or mascara_erro_mysql($sql_inclui_usuario_inscricao_evento,"index.php");
	
	if($query_inclui_participante && $query_inclui_curso_participante && $query_inclui_usuario_participante && $query_inclui_usuario_inscricao_evento){
		mysqli_query($conexao, "COMMIT");
		fecha_mysql();
		redireciona("participantes.php?codigo_participante=".campo_form_codifica($codigo_participante)."&me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição realizada! veja abaixo."));
		
	} else {	
		mysqli_query($conexao, "ROLLBACK");
		fecha_mysql();
		redireciona("inscricao.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi realizada. Tente novamente!"));
	}
}

if(campo_form_decodifica($_POST['acao']) == "gravar_participante_jovem") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	$telefone_participante						= protege_campo(limpa_campo($_POST['telefone_participante']));
	$email_participante							= protege_campo($_POST['email_participante']);

	
	conecta_mysql();
	
	mysqli_query($conexao, "BEGIN");
	
	// inclui participante
	$sql_inclui_participante = "INSERT INTO participante (codigo_cidade, data_nascimento_participante, nome_participante, nome_participante_cracha, centro_espirita_participante) VALUES ('".$cidade_participante."', '".$data_nascimento_participante."','".$nome_participante."','".$nome_participante_cracha."','".$centro_espirita_participante."')";
	$query_inclui_participante = mysqli_query($conexao, $sql_inclui_participante) or mascara_erro_mysql($sql_inclui_participante,"index.php");
	$codigo_participante = mysqli_insert_id($conexao);
	
	if($telefone_participante):
	// inclui telefone
	$sql_inclui_telefone_participante = "INSERT INTO telefone_participante (codigo_participante, numero_telefone_participante) VALUES ('".$codigo_participante."', '".$telefone_participante."')";
	$query_inclui_telefone_participante = mysqli_query($conexao, $sql_inclui_telefone_participante) or mascara_erro_mysql($sql_inclui_telefone_participante,"index.php");
	endif;

	if($email_participante):
	// inclui email
	$sql_inclui_email_participante = "INSERT INTO email_participante (codigo_participante, descricao_email_participante) VALUES ('".$codigo_participante."', '".$email_participante."')";
	$query_inclui_email_participante = mysqli_query($conexao, $sql_inclui_email_participante) or mascara_erro_mysql($sql_inclui_email_participante,"index.php");
	endif;

	// inclui curso participante
	for($i=0;$i<count($_POST['curso_participante']);$i++){
		
	$sql_inclui_curso_participante = "INSERT INTO participante_evento_curso (codigo_participante, codigo_evento, codigo_curso) VALUES ('".$codigo_participante."','".$_SESSION["codigo_evento_acesso"]."', '".protege_campo($_POST['curso_participante'][$i])."')";
	$query_inclui_curso_participante = mysqli_query($conexao, $sql_inclui_curso_participante) or mascara_erro_mysql($sql_inclui_curso_participante,"index.php");
	
	}
	
	$data_atual = date("Y-m-d");
	
	// inclui participante ao evento
	$sql_inclui_usuario_participante = "INSERT INTO inscricao_evento (codigo_evento, codigo_participante, codigo_situacao_inscricao, valor_inscricao_evento, data_inscricao_evento, tipo_inscricao) VALUES ('".$_SESSION["codigo_evento_acesso"]."', '".$codigo_participante."', '1', '25,00', '".$data_atual."', 'J')";
	$query_inclui_usuario_participante = mysqli_query($conexao, $sql_inclui_usuario_participante) or mascara_erro_mysql($sql_inclui_usuario_participante,"index.php");
	$codigo_inscricao_evento = mysqli_insert_id($conexao);
	
	// vincula o usuário à ibscrição
	$sql_inclui_usuario_inscricao_evento = "INSERT INTO usuario_inscricao_evento (codigo_usuario, codigo_inscricao_evento) VALUES ('".$_SESSION["codigo_usuario_acesso"]."', '".$codigo_inscricao_evento."')";
	$query_inclui_usuario_inscricao_evento = mysqli_query($conexao, $sql_inclui_usuario_inscricao_evento) or mascara_erro_mysql($sql_inclui_usuario_inscricao_evento,"index.php");
	
	if($query_inclui_participante && $query_inclui_curso_participante && $query_inclui_usuario_participante && $query_inclui_usuario_inscricao_evento){
		mysqli_query($conexao, "COMMIT");
		fecha_mysql();
		redireciona("participantes.php?codigo_participante=".campo_form_codifica($codigo_participante)."&me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição realizada! veja abaixo."));
		
	} else {	
		mysqli_query($conexao, "ROLLBACK");
		fecha_mysql();
		redireciona("inscricao.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi realizada. Tente novamente!"));
	}
}

if(campo_form_decodifica($_POST['acao']) == "gravar_participante_trabalhador") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	$telefone_participante						= protege_campo(limpa_campo($_POST['telefone_participante']));
	$email_participante							= protege_campo($_POST['email_participante']);

	
	conecta_mysql();
	
	
	mysqli_query($conexao, "BEGIN");
	
	// inclui participante
	$sql_inclui_participante = "INSERT INTO participante (codigo_cidade, data_nascimento_participante, nome_participante, nome_participante_cracha, centro_espirita_participante) VALUES ('".$cidade_participante."', '".$data_nascimento_participante."','".$nome_participante."', '".$nome_participante_cracha."','".$centro_espirita_participante."')";
	$query_inclui_participante = mysqli_query($conexao, $sql_inclui_participante) or mascara_erro_mysql($sql_inclui_participante,"index.php");
	$codigo_participante = mysqli_insert_id($conexao);
	
	if($telefone_participante):
	// inclui telefone
	$sql_inclui_telefone_participante = "INSERT INTO telefone_participante (codigo_participante, numero_telefone_participante) VALUES ('".$codigo_participante."', '".$telefone_participante."')";
	$query_inclui_telefone_participante = mysqli_query($conexao, $sql_inclui_telefone_participante) or mascara_erro_mysql($sql_inclui_telefone_participante,"index.php");
	endif;

	if($email_participante):
	// inclui email
	$sql_inclui_email_participante = "INSERT INTO email_participante (codigo_participante, descricao_email_participante) VALUES ('".$codigo_participante."', '".$email_participante."')";
	$query_inclui_email_participante = mysqli_query($conexao, $sql_inclui_email_participante) or mascara_erro_mysql($sql_inclui_email_participante,"index.php");
	endif;

	// inclui participante à comissao
	for($i=0;$i<count($_POST['comissao_trabalho']);$i++){
		
	$sql_inclui_participante_comissao = "INSERT INTO comissao_trabalho_participante (codigo_comissao_trabalho, codigo_participante) VALUES ('".protege_campo($_POST['comissao_trabalho'][$i])."', '".$codigo_participante."')";
	$query_inclui_participante_comissao = mysqli_query($conexao, $sql_inclui_participante_comissao) or mascara_erro_mysql($sql_inclui_participante_comissao,"index.php");
	
	}

	$data_atual = date("Y-m-d");
		
	// inclui participante ao evento
	$sql_inclui_usuario_participante = "INSERT INTO inscricao_evento (codigo_evento, codigo_participante, codigo_situacao_inscricao, valor_inscricao_evento, data_inscricao_evento, tipo_inscricao) VALUES ('".$_SESSION["codigo_evento_acesso"]."', '".$codigo_participante."', '1', '25,00', '".$data_atual."', 'T')";
	$query_inclui_usuario_participante = mysqli_query($conexao, $sql_inclui_usuario_participante) or mascara_erro_mysql($sql_inclui_usuario_participante,"index.php");
	$codigo_inscricao_evento = mysqli_insert_id($conexao);
	
	// vincula o usuário à ibscrição
	$sql_inclui_usuario_inscricao_evento = "INSERT INTO usuario_inscricao_evento (codigo_usuario, codigo_inscricao_evento) VALUES ('".$_SESSION["codigo_usuario_acesso"]."', '".$codigo_inscricao_evento."')";
	$query_inclui_usuario_inscricao_evento = mysqli_query($conexao, $sql_inclui_usuario_inscricao_evento) or mascara_erro_mysql($sql_inclui_usuario_inscricao_evento,"index.php");

	if($query_inclui_participante && $query_inclui_usuario_participante && $query_inclui_usuario_inscricao_evento){
		mysqli_query($conexao, "COMMIT");
		fecha_mysql();
		redireciona("participantes.php?codigo_participante=".campo_form_codifica($codigo_participante)."&me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição realizada! veja abaixo."));
		
	} else {	
		mysqli_query($conexao, "ROLLBACK");
		fecha_mysql();
		redireciona("inscricao.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi realizada. Tente novamente!"));
	}

}

if(campo_form_decodifica($_POST['acao']) == "gravar_participante_visitante") {
	
		// dados participanteis
		$nome_participante										= protege_campo($_POST['nome_participante']);
		$nome_participante_cracha							= protege_campo($_POST['nome_participante_cracha']);
		$cidade_participante 									= protege_campo($_POST['cidade_participante']);

		
		conecta_mysql();
		
		
		mysqli_query($conexao, "BEGIN");
		
		// inclui participante
		$sql_inclui_participante = "INSERT INTO participante (codigo_cidade, nome_participante, nome_participante_cracha) VALUES ('".$cidade_participante."', '".$nome_participante."', '".$nome_participante_cracha."')";
		$query_inclui_participante = mysqli_query($conexao, $sql_inclui_participante) or mascara_erro_mysql($sql_inclui_participante,"index.php");
		$codigo_participante = mysqli_insert_id($conexao);
		
		$data_atual = date("Y-m-d");
		
		// inclui participante ao evento
		$sql_inclui_usuario_participante = "INSERT INTO inscricao_evento (codigo_evento, codigo_participante, codigo_situacao_inscricao, valor_inscricao_evento, data_inscricao_evento, tipo_inscricao) VALUES ('".$_SESSION["codigo_evento_acesso"]."', '".$codigo_participante."', '1', '25,00', '".$data_atual."', 'T')";
		$query_inclui_usuario_participante = mysqli_query($conexao, $sql_inclui_usuario_participante) or mascara_erro_mysql($sql_inclui_usuario_participante,"index.php");
		$codigo_inscricao_evento = mysqli_insert_id($conexao);
		
		// vincula o usuário à ibscrição
		$sql_inclui_usuario_inscricao_evento = "INSERT INTO usuario_inscricao_evento (codigo_usuario, codigo_inscricao_evento) VALUES ('".$_SESSION["codigo_usuario_acesso"]."', '".$codigo_inscricao_evento."')";
		$query_inclui_usuario_inscricao_evento = mysqli_query($conexao, $sql_inclui_usuario_inscricao_evento) or mascara_erro_mysql($sql_inclui_usuario_inscricao_evento,"index.php");
		
		
		if($query_inclui_participante && $query_inclui_usuario_participante && $query_inclui_usuario_inscricao_evento){
			mysqli_query($conexao, "COMMIT");
			fecha_mysql();
			redireciona("participantes.php?codigo_participante=".campo_form_codifica($codigo_participante)."&me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição realizada! veja abaixo."));
			
		} else {	
			mysqli_query($conexao, "ROLLBACK");
			fecha_mysql();
			redireciona("inscricao.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi realizada. Tente novamente!"));
		}
}

// consulta
$sql_consulta_cidade = "SELECT codigo_cidade, nome_cidade FROM cidade ORDER BY cidade.nome_cidade ASC ";

$query_consulta_cidade1 = mysqli_query($conexao, $sql_consulta_cidade) or mascara_erro_mysql($sql_consulta_cidade);
$query_consulta_cidade2 = mysqli_query($conexao, $sql_consulta_cidade) or mascara_erro_mysql($sql_consulta_cidade);
$query_consulta_cidade3 = mysqli_query($conexao, $sql_consulta_cidade) or mascara_erro_mysql($sql_consulta_cidade);
$query_consulta_cidade4 = mysqli_query($conexao, $sql_consulta_cidade) or mascara_erro_mysql($sql_consulta_cidade);
$query_consulta_cidade5 = mysqli_query($conexao, $sql_consulta_cidade) or mascara_erro_mysql($sql_consulta_cidade);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include "site_mod_head_interno.php";?>
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script language="javascript" type="text/javascript">
	
	// instancia a pesquisa rapida
	$(document).ready(function() {
		$("#nome_participante_crianca").autocomplete({source: "site_mod_busca_nome.php", delay: 0, position: { my : "right top", at: "right bottom" }});
	});

	//instancia a pesquisa rapida
	$(document).ready(function() {
		$("#nome_participante_jovem").autocomplete({source: "site_mod_busca_nome.php", delay: 0, position: { my : "right top", at: "right bottom" }});
	});

	//instancia a pesquisa rapida
	$(document).ready(function() {
		$("#nome_participante_adulto").autocomplete({source: "site_mod_busca_nome.php", delay: 0, position: { my : "right top", at: "right bottom" }});
	});

	//instancia a pesquisa rapida
	$(document).ready(function() {
		$("#nome_participante_trabalhador").autocomplete({source: "site_mod_busca_nome.php", delay: 0, position: { my : "right top", at: "right bottom" }});
	});


	$(document).ready(function(){
        $("input[name='nome_participante']").blur(function(){
        var $nome_participante_cracha = $("input[name='nome_participante_cracha']");
        var $data_nascimento_participante = $("input[name='data_nascimento_participante']");
        var $telefone_participante = $("input[name='telefone_participante']");
        var $centro_espirita_participante = $("input[name='centro_espirita_participante']");
        var $email_participante = $("input[name='email_participante']");
        //$nome_participante_cracha.val('Carregando...');
        //$telefone_participante.val('Carregando...');

            $.getJSON(
            'consultar_participante.php',
            { nome: $( this ).val() },
            function( json )
            {
                $nome_participante_cracha.val( json.nome_participante_cracha );
                $data_nascimento_participante.val( json.data_nascimento_participante );
                $telefone_participante.val( json.numero_telefone_participante );
                $email_participante.val( json.email_participante );
                $centro_espirita_participante.val( json.centro_espirita_participante );
            }
            );
        });
    });

</script>
<script type='text/javascript' src='consultar_participante.js'></script>
</head>
<body>
<div class="navbar navbar-fixed-top">
  <?php include "site_mod_topo_interno.php";?>
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <?php include "site_mod_menu.php";?>
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
    

	<?php if($mensagem){?>
    <div class="alert alert-success"><?php echo $mensagem;?></div>
    <?php }?>


      <div class="row">
	      	
	      	<div class="span12">      		
	      		
	      		<div class="widget ">
	      			
	      			<div class="widget-header">
	      				<i class="icon-pencil"></i>
	      				<h3>Você está fazendo uma inscrição para o Evento: <strong>4391º Encontro Fraterno Auta de Souza, Cuiabá - MT no Wantuil de Freitas</strong></h3>
	  				</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
						
						
						<div class="tabbable">
						<ul class="nav nav-tabs">
						  <li class="active titulo_aba">
						    <a href="#formcrianca" data-toggle="tab">Inscrição Infantil</a>
						  </li>
                          <li class="titulo_aba">
						    <a href="#formjovem" data-toggle="tab">Jovem (12 e 13 Anos)</a>
						  </li>
						  <li class="titulo_aba">
						    <a href="#formadulto" data-toggle="tab">Adulto / Jovem (Acima de 14 anos)</a>
						  </li>
              <li class="titulo_aba">
						    <a href="#formtrabalhador" data-toggle="tab">Inscrição Trabalhador</a>
						  </li>

							<li class="titulo_aba">
						    <a href="#formvisitante" data-toggle="tab">Inscrição Visitante</a>
						  </li>

						</ul>
						
						<br>
						
							<div class="tab-content">
                            
								<div class="tab-pane active" id="formcrianca">
								<form name="gravar_participante_crianca" id="gravar_participante_crianca" class="form-horizontal" method="post" action="inscricao.php">
									<fieldset>
																		
										<div class="divisor">Dados da criança</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_crianca" name="nome_participante" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
										<div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->		
                                        					
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
											<select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
												<?php while($cidade = mysqli_fetch_assoc($query_consulta_cidade1)) {?>
													<option value="<?php echo $cidade["codigo_cidade"];?>" <?php if($resultado_consulta_participante["codigo_cidade"] == $cidade["codigo_cidade"]){echo "selected";}?>><?php echo $cidade["nome_cidade"];?></option>
												<?php }?>
												</select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" onkeyup="Mascara(this, Data);" maxlength="13">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="centro_espirita_participante">Centro Espírita / Posto de Assistência</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="centro_espirita_participante" name="centro_espirita_participante">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        		
										
										<br />
                                        <div class="divisor">Dados do Responsável pela criança</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="nome_responsavel">Nome do Responsável</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_responsavel" name="nome_responsavel" value="" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->


                    <div class="control-group">											
											<label class="control-label" for="telefone_responsavel">Telefone do Responsável</label>
											<div class="controls">
												<input type="text" class="span3 form-inscricao" id="telefone_responsavel" name="telefone_responsavel" onkeyup="Mascara(this, Telefone);" maxlength="15" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="observacoes_crianca">Observações quanto à alimentação, alergias, medicações e outros:</label>
											<div class="controls">
                                            	<textarea name="observacoes_crianca" id="observacoes_crianca" class="span6 form-inscricao-texto"></textarea>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
										<br />
                                        <div class="divisor">Escolha do curso</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_crianca">Escolha o Tema Específico da Criança:</label>
											<div class="controls">
                                                <select id="curso_crianca[]" name="curso_crianca[]" class="span4 form-inscricao-select">
                                                  <?php while($resultado_consulta_cursos_criancas = mysqli_fetch_assoc($query_consulta_cursos_criancas)) {?>
                                                  <option value="<?php echo $resultado_consulta_cursos_criancas["codigo_curso"];?>" <?php if(calcula_total_inscritos_curso($resultado_consulta_cursos_criancas["codigo_curso"]) >= $resultado_consulta_cursos_criancas["quantidade_vagas"]){$mensagem = " (LOTADO)";echo "disabled";}?>><?php echo $resultado_consulta_cursos_criancas["referencia"];?> - <?php echo utf8_encode($resultado_consulta_cursos_criancas["nome_curso"]).$mensagem;?></option>
                                                  <?php $mensagem = '';}?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
										
											
										 <br />
										
											
										<div class="form-actions">
                                        	<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante_crianca"); ?>">					
											<input type="submit" name="gravar_participante_crianca" id="gravar_participante_crianca" class="btn btn-primary salvar-inscricao" value="Salvar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>
                                
                                
                                <div class="tab-pane" id="formjovem">
								<form name="gravar_participante_jovem" id="gravar_participante_jovem" class="form-horizontal" method="post" action="inscricao.php">
									<fieldset>
																		
										<div class="divisor">Dados do participante (Jovem 12 e 13 anos)</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_jovem" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para o crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        						
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">

												<select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
												<?php while($cidade = mysqli_fetch_assoc($query_consulta_cidade2)) {?>
													<option value="<?php echo $cidade["codigo_cidade"];?>" <?php if($resultado_consulta_participante["codigo_cidade"] == $cidade["codigo_cidade"]){echo "selected";}?>><?php echo $cidade["nome_cidade"];?></option>
												<?php }?>
												</select>

											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo $resultado_consulta_participante["data_nascimento_participante"];?>" onkeyup="Mascara(this, Data);" maxlength="13">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="centro_espirita_participante">Centro Espírita / Posto de Assistência</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="centro_espirita_participante" name="centro_espirita_participante" value="<?php echo $resultado_consulta_participante["centro_espirita_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        
                                        <div class="control-group">											
											<label class="control-label" for="telefone_participante">Telefone</label>
											<div class="controls">
												<input type="text" class="span3 form-inscricao" id="telefone_participante" name="telefone_participante" value="<?php echo $resultado_consulta_participante["numero_telefone_participante"];?>" onkeyup="Mascara(this, Telefone);" maxlength="15">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="email_participante">E-mail</label>
											<div class="controls">
												<input type="text" class="span4 form-inscricao" id="email_participante" name="email_participante">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->			
										
										                                     
										<br />
                                        <div class="divisor">Escolha do curso</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_participante[]">Tema Atual:</label>
											<div class="controls">
                                                <select id="curso_participante[]" name="curso_participante[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_tema_atual_jovem = mysqli_fetch_assoc($query_consulta_tema_atual_jovem)) {?>
                           	                     	<option value="<?php echo $resultado_consulta_tema_atual_jovem["codigo_curso"];?>" <?php if(calcula_total_inscritos_curso($resultado_consulta_tema_atual_jovem["codigo_curso"]) >= $resultado_consulta_tema_atual_jovem["quantidade_vagas"]){$mensagem = " (LOTADO)";echo "disabled";}?>><?php echo $resultado_consulta_tema_atual_jovem["referencia"];?> - <strong><?php echo utf8_encode($resultado_consulta_tema_atual_jovem["nome_curso"]).$mensagem;?></strong></option>
                                                  <?php $mensagem = '';}?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_participante[]">Tema Específico:</label>
											<div class="controls">
                                                <select id="curso_participante[]" name="curso_participante[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_tema_especifico_jovem = mysqli_fetch_assoc($query_consulta_tema_especifico_jovem)) {?>
                                                  <option value="<?php echo $resultado_consulta_tema_especifico_jovem["codigo_curso"];?>" <?php if(calcula_total_inscritos_curso($resultado_consulta_tema_especifico_jovem["codigo_curso"]) >= $resultado_consulta_tema_especifico_jovem["quantidade_vagas"]){$mensagem = " (LOTADO)";echo "disabled";}?>><?php echo $resultado_consulta_tema_especifico_jovem["referencia"];?> - <strong><?php echo utf8_encode($resultado_consulta_tema_especifico_jovem["nome_curso"]).$mensagem;?></strong></option>
                                                  <?php $mensagem = '';}?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
											
										 <br />
										
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante_jovem"); ?>">					
											<input type="submit" name="gravar_participante_jovem" id="gravar_participante_jovem" class="btn btn-primary salvar-inscricao" value="Salvar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>

								<div class="tab-pane" id="formadulto">
								<form name="gravar_participante_adulto" id="gravar_participante_adulto" class="form-horizontal" method="post" action="inscricao.php">
									<fieldset>
																		
										<div class="divisor">Dados do participante (Jovem ou Adulto)</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_adulto" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para o crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        						
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
											<select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
												<?php while($cidade = mysqli_fetch_assoc($query_consulta_cidade3)) {?>
													<option value="<?php echo $cidade["codigo_cidade"];?>" <?php if($resultado_consulta_participante["codigo_cidade"] == $cidade["codigo_cidade"]){echo "selected";}?>><?php echo $cidade["nome_cidade"];?></option>
												<?php }?>
												</select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo $resultado_consulta_participante["data_nascimento_participante"];?>" onkeyup="Mascara(this, Data);" maxlength="13">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="centro_espirita_participante">Centro Espírita / Posto de Assistência</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="centro_espirita_participante" name="centro_espirita_participante" value="<?php echo $resultado_consulta_participante["centro_espirita_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        
                                        <div class="control-group">											
											<label class="control-label" for="telefone_participante">Telefone</label>
											<div class="controls">
												<input type="text" class="span3 form-inscricao" id="telefone_participante" name="telefone_participante" value="<?php echo $resultado_consulta_participante["numero_telefone_participante"];?>" onkeyup="Mascara(this, Telefone);" maxlength="15">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="email_participante">E-mail</label>
											<div class="controls">
												<input type="text" class="span4 form-inscricao" id="email_participante" name="email_participante">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->			
										
										                                     
										<br />
                                        <div class="divisor">Escolha do curso</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_participante[]">Tema Atual:</label>
											<div class="controls">
                                                <select id="curso_participante[]" name="curso_participante[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_tema_atual_adulto = mysqli_fetch_assoc($query_consulta_tema_atual_adulto)) {?>
                           	                     	<option value="<?php echo $resultado_consulta_tema_atual_adulto["codigo_curso"];?>" <?php if(calcula_total_inscritos_curso($resultado_consulta_tema_atual_adulto["codigo_curso"]) >= $resultado_consulta_tema_atual_adulto["quantidade_vagas"]){$mensagem = " (LOTADO)";echo "disabled";}?>><?php echo $resultado_consulta_tema_atual_adulto["referencia"];?> - <strong><?php echo utf8_encode($resultado_consulta_tema_atual_adulto["nome_curso"]).$mensagem;?></strong></option>
                                                  <?php $mensagem = '';}?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_participante[]">Tema Específico:</label>
											<div class="controls">
                                                <select id="curso_participante[]" name="curso_participante[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_tema_especifico_adulto = mysqli_fetch_assoc($query_consulta_tema_especifico_adulto)) {?>
                                                  <option value="<?php echo $resultado_consulta_tema_especifico_adulto["codigo_curso"];?>" <?php if(calcula_total_inscritos_curso($resultado_consulta_tema_especifico_adulto["codigo_curso"]) >= $resultado_consulta_tema_especifico_adulto["quantidade_vagas"]){$mensagem = " (LOTADO)";echo "disabled";}?>><?php echo $resultado_consulta_tema_especifico_adulto["referencia"];?> - <strong><?php echo utf8_encode($resultado_consulta_tema_especifico_adulto["nome_curso"]).$mensagem;?></strong></option>
                                                  <?php $mensagem = '';}?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
											
										 <br />
										
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante_adulto"); ?>">					
											<input type="submit" name="gravar_participante_adulto" id="gravar_participante_adulto" class="btn btn-primary salvar-inscricao" value="Salvar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>
                                
                <div class="tab-pane" id="formtrabalhador">
								<form name="gravar_participante_trabalhador" id="gravar_participante_trabalhador" class="form-horizontal" method="post" action="inscricao.php">
									<fieldset>
																		
										<div class="divisor">Dados do Trabalhador</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_trabalhador" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para o crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
																			
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
											<select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
												<?php while($cidade = mysqli_fetch_assoc($query_consulta_cidade4)) {?>
													<option value="<?php echo $cidade["codigo_cidade"];?>" <?php if($resultado_consulta_participante["codigo_cidade"] == $cidade["codigo_cidade"]){echo "selected";}?>><?php echo $cidade["nome_cidade"];?></option>
												<?php }?>
												</select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo $resultado_consulta_participante["data_nascimento_participante"];?>" onkeyup="Mascara(this, Data);" maxlength="13">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="centro_espirita_participante">Centro Espírita / Posto de Assistência</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="centro_espirita_participante" name="centro_espirita_participante" value="<?php echo $resultado_consulta_participante["centro_espirita_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        
                                        <div class="control-group">											
											<label class="control-label" for="telefone_participante">Telefone</label>
											<div class="controls">
												<input type="text" class="span3 form-inscricao" id="telefone_participante" name="telefone_participante" value="<?php echo $resultado_consulta_participante["numero_telefone_participante"];?>" onkeyup="Mascara(this, Telefone);" maxlength="15">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="email_participante">E-mail</label>
											<div class="controls">
												<input type="text" class="span4 form-inscricao" id="email_participante" name="email_participante">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->			
										
										                                     
										<br />
                                        <div class="divisor">Em qual comissão irá trabalhar?</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="comissao_trabalho[]">Comissão de trabalho:</label>
											<div class="controls">
                                                <select id="comissao_trabalho[]" name="comissao_trabalho[]" class="span6 form-inscricao-select" required>
                                                  <?php while($resultado_consulta_comissoes_trabalho = mysqli_fetch_assoc($query_consulta_comissoes_trabalho)) {?>
                                                  <option value="<?php echo $resultado_consulta_comissoes_trabalho["codigo_comissao_trabalho"];?>"><?php echo utf8_encode($resultado_consulta_comissoes_trabalho["nome_comissao_trabalho"]);?></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        							
											
										 <br />
										
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante_trabalhador"); ?>">					
											<input type="submit" name="gravar_participante_trabalhador" id="gravar_participante_trabalhador" class="btn btn-primary salvar-inscricao" value="Salvar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>

								<div class="tab-pane" id="formvisitante">
								<form name="gravar_participante_visitante" id="gravar_participante_visitante" class="form-horizontal" method="post" action="inscricao.php">
									<fieldset>
																		
										<div class="divisor">Dados do Visitante</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_visitante" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para o crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
																			
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
											<select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
												<?php while($cidade = mysqli_fetch_assoc($query_consulta_cidade5)) {?>
													<option value="<?php echo $cidade["codigo_cidade"];?>" <?php if($resultado_consulta_participante["codigo_cidade"] == $cidade["codigo_cidade"]){echo "selected";}?>><?php echo $cidade["nome_cidade"];?></option>
												<?php }?>
												</select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                                           							
											
										 <br />
										
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante_visitante"); ?>">					
											<input type="submit" name="gravar_participante_visitante" id="gravar_participante_visitante" class="btn btn-primary salvar-inscricao" value="Salvar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>
								
								
						  
						</div>
						
						
						
						
						
					</div> <!-- /widget-content -->
						
				</div> <!-- /widget -->
	      		
		    </div> <!-- /span8 -->
	      	
	      	
	      	</div>
	      	
	      </div> <!-- /row -->
    </div>
    <!-- /container --> 
  </div>
  <!-- /extra-inner --> 
</div>
<!-- /extra -->
<?php
mysqli_free_result($query_consulta_cursos_criancas);

fecha_mysql();
?>
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <?php include "site_mod_rodape.php";?>
        <!-- /span12 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /footer-inner --> 
</div>
<!-- /footer --> 
</body>
</html>

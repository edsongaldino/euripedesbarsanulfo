<?php include("sistema_mod_include.php"); ?>
<?php

conecta_mysql();

$codigo_inscricao_evento = $_GET["codigo_inscricao_evento"];

if($_GET["acao"] == "alterar"){
	// consulta participante
	$sql_consulta_participante = "SELECT
									inscricao_evento.codigo_participante, inscricao_evento.codigo_situacao_inscricao, inscricao_evento.tipo_inscricao, inscricao_evento.valor_inscricao_evento,
									participante.codigo_cidade, participante.nome_participante, participante.nome_participante_cracha, participante.centro_espirita_participante, participante.data_nascimento_participante, 
									telefone_participante.numero_telefone_participante,
									dados_complementares.nome_responsavel, dados_complementares.telefone_responsavel, dados_complementares.observacoes_crianca,
									comissao_trabalho.nome_comissao_trabalho, comissao_trabalho.codigo_comissao_trabalho,
									email_participante.descricao_email_participante
									FROM inscricao_evento 
									JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante) 
									LEFT JOIN telefone_participante ON (participante.codigo_participante = telefone_participante.codigo_participante)
									LEFT JOIN email_participante ON (participante.codigo_participante = email_participante.codigo_participante)
									LEFT JOIN dados_complementares ON (participante.codigo_participante = dados_complementares.codigo_participante)
									LEFT JOIN comissao_trabalho_participante ON (participante.codigo_participante = comissao_trabalho_participante.codigo_participante)
									LEFT JOIN comissao_trabalho ON (comissao_trabalho_participante.codigo_comissao_trabalho = comissao_trabalho.codigo_comissao_trabalho) 
								WHERE inscricao_evento.codigo_inscricao_evento = '".$codigo_inscricao_evento."' LIMIT 1";
	$query_consulta_participante = mysql_query($sql_consulta_participante) or mascara_erro_mysql($sql_consulta_participante);	
	$resultado_consulta_participante = mysql_fetch_assoc($query_consulta_participante);


	//Seleciona tema atual do participante
	$sql_consulta_tema_atual = "SELECT 
								participante_evento_curso.codigo_curso 
								FROM participante_evento_curso
								JOIN curso ON (participante_evento_curso.codigo_curso = curso.codigo_curso) 
								WHERE participante_evento_curso.codigo_participante = '".$resultado_consulta_participante["codigo_participante"]."' AND participante_evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' AND (curso.codigo_tema_curso = 5 OR curso.codigo_tema_curso = 6)";
	$query_consulta_tema_atual = mysql_query($sql_consulta_tema_atual) or mascara_erro_mysql($sql_consulta_tema_atual);	
	$resultado_consulta_tema_atual = mysql_fetch_assoc($query_consulta_tema_atual);

	//Seleciona tema específico do participante
	$sql_consulta_tema_especifico = "SELECT 
								participante_evento_curso.codigo_curso 
								FROM participante_evento_curso
								JOIN curso ON (participante_evento_curso.codigo_curso = curso.codigo_curso) 
								WHERE participante_evento_curso.codigo_participante = '".$resultado_consulta_participante["codigo_participante"]."' AND participante_evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' AND (curso.codigo_tema_curso = 3 OR curso.codigo_tema_curso = 4 OR curso.codigo_tema_curso = 1 OR curso.codigo_tema_curso = 2)";
	$query_consulta_tema_especifico = mysql_query($sql_consulta_tema_especifico) or mascara_erro_mysql($sql_consulta_tema_especifico);	
	$resultado_consulta_tema_especifico = mysql_fetch_assoc($query_consulta_tema_especifico);


	// consulta cursos 0 à 11 anos
	$sql_consulta_cursos_criancas = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso FROM evento_curso 
										JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
										JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
										JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
										JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
									WHERE (curso.codigo_tema_curso = '1' OR curso.codigo_tema_curso = '2') AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
	$query_consulta_cursos_criancas = mysql_query($sql_consulta_cursos_criancas) or mascara_erro_mysql($sql_consulta_cursos_criancas);


	// consulta tema específico 12 e 13 anos e adulto
	$sql_consulta_tema_especifico_adulto = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso FROM evento_curso 
												JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
												JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
												JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
												JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
											WHERE (curso.codigo_tema_curso = '3' OR curso.codigo_tema_curso = '4') AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
	$query_consulta_tema_especifico_adulto = mysql_query($sql_consulta_tema_especifico_adulto) or mascara_erro_mysql($sql_consulta_tema_especifico_adulto);

	// consulta tema atual 12 e 13 anos e adulto
	$sql_consulta_tema_atual_adulto = "SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso FROM evento_curso 
												JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
												JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
												JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
												JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
											WHERE (curso.codigo_tema_curso = '5' OR curso.codigo_tema_curso = '6') AND evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY curso.codigo_tema_curso ASC";
	$query_consulta_tema_atual_adulto = mysql_query($sql_consulta_tema_atual_adulto) or mascara_erro_mysql($sql_consulta_tema_atual_adulto);


	// consulta comissoes de trabalho
	$sql_consulta_comissoes_trabalho = "SELECT comissao_trabalho.codigo_comissao_trabalho, comissao_trabalho.nome_comissao_trabalho FROM comissao_trabalho ORDER BY comissao_trabalho.nome_comissao_trabalho ASC";
	$query_consulta_comissoes_trabalho = mysql_query($sql_consulta_comissoes_trabalho) or mascara_erro_mysql($sql_consulta_comissoes_trabalho);

}

$mensagem = campo_form_decodifica($_GET["mm"]);

if(campo_form_decodifica($_POST['acao']) == "alterar_participante_crianca") {
	
	// dados participanteis
	$codigo_participante						= protege_campo($_POST['codigo_participante']);
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	// dados responsável
	$nome_responsavel							= protege_campo($_POST['nome_responsavel']);
	$telefone_responsavel 						= protege_campo($_POST['telefone_responsavel']);
	$observacoes_crianca			 			= protege_campo($_POST['observacoes_crianca']);
	
	$situacao_inscricao			 				= protege_campo($_POST['situacao_inscricao']);
	$valor_inscricao			 				= protege_campo(converte_valor_decimal($_POST['valor_inscricao']));
	
	conecta_mysql();
	
	mysql_query("BEGIN");
	
	// altera participante
	$sql_altera_participante = "UPDATE participante SET codigo_cidade = '".$cidade_participante."', data_nascimento_participante = '".$data_nascimento_participante."', nome_participante = '".$nome_participante."', nome_participante_cracha = '".$nome_participante_cracha."', centro_espirita_participante = '".$centro_espirita_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_participante = mysql_query($sql_altera_participante) or mascara_erro_mysql($sql_altera_participante,"index.php");
	
	// altera dados complementares
	$sql_altera_dados_complementares = "UPDATE dados_complementares SET nome_responsavel = '".$nome_responsavel."', telefone_responsavel = '".$telefone_responsavel."', observacoes_crianca = '".$observacoes_crianca."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_dados_complementares = mysql_query($sql_altera_dados_complementares) or mascara_erro_mysql($sql_altera_dados_complementares,"index.php");

	//Deleta todos os cursos do participante
	$sql_deleta_curso_participante = "DELETE FROM participante_evento_curso WHERE codigo_participante = '".$codigo_participante."' AND codigo_evento = '".$_SESSION["codigo_evento_acesso"]."'";
	$query_deleta_curso_participante = mysql_query($sql_deleta_curso_participante) or mascara_erro_mysql($sql_deleta_curso_participante,"index.php");
	
	// altera curso participante
	for($i=0;$i<count($_POST['curso_crianca']);$i++){
		
	$sql_altera_curso_participante = "INSERT INTO participante_evento_curso (codigo_participante, codigo_evento, codigo_curso) VALUES ('".$codigo_participante."','".$_SESSION["codigo_evento_acesso"]."', '".protege_campo($_POST['curso_crianca'][$i])."')";
	$query_altera_curso_participante = mysql_query($sql_altera_curso_participante) or mascara_erro_mysql($sql_altera_curso_participante,"index.php");
	
	}
	
	if($_SESSION["codigo_tipo_usuario_acesso"]=='1' || $_SESSION["codigo_tipo_usuario_acesso"]=='5'){
	// altera situação
	$sql_altera_situacao = "UPDATE inscricao_evento SET codigo_situacao_inscricao = '".$situacao_inscricao."', valor_inscricao_evento = '".$valor_inscricao."' WHERE codigo_inscricao_evento = '".$codigo_inscricao_evento."'";
	$query_altera_situacao = mysql_query($sql_altera_situacao) or mascara_erro_mysql($sql_altera_situacao,"index.php");
	}
	
	if($query_altera_participante && $query_altera_dados_complementares && $query_deleta_curso_participante && $query_altera_curso_participante){
		mysql_query("COMMIT");	
		fecha_mysql();
		redireciona("participantes.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição alterada com sucesso."));
		
	} else {
		mysql_query("ROLLBACK");	
		fecha_mysql();
		redireciona("participantes.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi alterada. Tente novamente!"));
	}
}

if(campo_form_decodifica($_POST['acao']) == "alterar_participante_adulto") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$codigo_participante						= protege_campo($_POST['codigo_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	$telefone_participante						= protege_campo(limpa_campo($_POST['telefone_participante']));
	$email_participante							= protege_campo($_POST['email_participante']);

	$situacao_inscricao			 				= protege_campo($_POST['situacao_inscricao']);
	$valor_inscricao			 				= protege_campo(converte_valor_decimal($_POST['valor_inscricao']));

	
	conecta_mysql();
	
	mysql_query("BEGIN");
	
	// altera participante
	$sql_altera_participante = "UPDATE participante SET codigo_cidade = '".$cidade_participante."', data_nascimento_participante = '".$data_nascimento_participante."', nome_participante = '".$nome_participante."', nome_participante_cracha = '".$nome_participante_cracha."', centro_espirita_participante = '".$centro_espirita_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_participante = mysql_query($sql_altera_participante) or mascara_erro_mysql($sql_altera_participante,"index.php");

	// altera telefone
	$sql_altera_telefone_participante = "UPDATE telefone_participante SET numero_telefone_participante = '".$telefone_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_telefone_participante = mysql_query($sql_altera_telefone_participante) or mascara_erro_mysql($sql_altera_telefone_participante,"index.php");
	
	// altera email
	$sql_altera_email_participante = "UPDATE email_participante SET descricao_email_participante = '".$email_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_email_participante = mysql_query($sql_altera_email_participante) or mascara_erro_mysql($sql_altera_email_participante,"index.php");
	
	//Deleta todos os cursos do participante
	$sql_deleta_curso_participante = "DELETE FROM participante_evento_curso WHERE codigo_participante = '".$codigo_participante."' AND codigo_evento = '".$_SESSION["codigo_evento_acesso"]."'";
	$query_deleta_curso_participante = mysql_query($sql_deleta_curso_participante) or mascara_erro_mysql($sql_deleta_curso_participante,"index.php");

	// altera curso participante
	for($i=0;$i<count($_POST['curso_participante']);$i++){
		
	$sql_altera_curso_participante = "INSERT INTO participante_evento_curso (codigo_participante, codigo_evento, codigo_curso) VALUES ('".$codigo_participante."','".$_SESSION["codigo_evento_acesso"]."', '".protege_campo($_POST['curso_participante'][$i])."')";
	$query_altera_curso_participante = mysql_query($sql_altera_curso_participante) or mascara_erro_mysql($sql_altera_curso_participante,"index.php");
	
	}

	if($_SESSION["codigo_tipo_usuario_acesso"]=='1' || $_SESSION["codigo_tipo_usuario_acesso"]=='5'){
	// altera situação
	$sql_altera_situacao = "UPDATE inscricao_evento SET codigo_situacao_inscricao = '".$situacao_inscricao."', valor_inscricao_evento = '".$valor_inscricao."' WHERE codigo_inscricao_evento = '".$codigo_inscricao_evento."'";
	$query_altera_situacao = mysql_query($sql_altera_situacao) or mascara_erro_mysql($sql_altera_situacao,"index.php");
	}
	
	if($query_altera_participante && $query_altera_telefone_participante && $query_altera_email_participante && $query_altera_curso_participante){
		mysql_query("COMMIT");
		fecha_mysql();
		redireciona("participantes.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição atualizada com sucesso!"));
		
	} else {	
		mysql_query("ROLLBACK");
		fecha_mysql();
		redireciona("participantes.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi alterada. Tente novamente!"));
	}
}

if(campo_form_decodifica($_POST['acao']) == "alterar_participante_trabalhador") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$codigo_participante						= protege_campo($_POST['codigo_participante']);
	$nome_participante_cracha					= protege_campo($_POST['nome_participante_cracha']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	$telefone_participante						= protege_campo(limpa_campo($_POST['telefone_participante']));
	$email_participante							= protege_campo($_POST['email_participante']);

	$situacao_inscricao			 				= protege_campo($_POST['situacao_inscricao']);
	$valor_inscricao			 				= protege_campo(converte_valor_decimal($_POST['valor_inscricao']));


	
	conecta_mysql();
	
	mysql_query("BEGIN");
	
	// altera participante
	$sql_altera_participante = "UPDATE participante SET codigo_cidade = '".$cidade_participante."', data_nascimento_participante = '".$data_nascimento_participante."', nome_participante = '".$nome_participante."', nome_participante_cracha = '".$nome_participante_cracha."', centro_espirita_participante = '".$centro_espirita_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_participante = mysql_query($sql_altera_participante) or mascara_erro_mysql($sql_altera_participante,"index.php");

	// altera telefone
	$sql_altera_telefone_participante = "UPDATE telefone_participante SET numero_telefone_participante = '".$telefone_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_telefone_participante = mysql_query($sql_altera_telefone_participante) or mascara_erro_mysql($sql_altera_telefone_participante,"index.php");
	
	// altera email
	$sql_altera_email_participante = "UPDATE email_participante SET descricao_email_participante = '".$email_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_email_participante = mysql_query($sql_altera_email_participante) or mascara_erro_mysql($sql_altera_email_participante,"index.php");
	
	//Deleta todos os cursos do participante
	$sql_deleta_participante_comissao = "DELETE FROM comissao_trabalho_participante WHERE codigo_participante = '".$codigo_participante."'";
	$query_deleta_participante_comissao = mysql_query($sql_deleta_participante_comissao) or mascara_erro_mysql($sql_deleta_participante_comissao,"index.php");
	
	// altera participante à comissao
	for($i=0;$i<count($_POST['comissao_trabalho']);$i++){
		
	$sql_altera_participante_comissao = "INSERT INTO comissao_trabalho_participante (codigo_comissao_trabalho, codigo_participante) VALUES ('".protege_campo($_POST['comissao_trabalho'][$i])."', '".$codigo_participante."')";
	$query_altera_participante_comissao = mysql_query($sql_altera_participante_comissao) or mascara_erro_mysql($sql_altera_participante_comissao,"index.php");
	
	}

	if($_SESSION["codigo_tipo_usuario_acesso"]=='1' || $_SESSION["codigo_tipo_usuario_acesso"]=='5'){
	// altera situação
	$sql_altera_situacao = "UPDATE inscricao_evento SET codigo_situacao_inscricao = '".$situacao_inscricao."', valor_inscricao_evento = '".$valor_inscricao."' WHERE codigo_inscricao_evento = '".$codigo_inscricao_evento."'";
	$query_altera_situacao = mysql_query($sql_altera_situacao) or mascara_erro_mysql($sql_altera_situacao,"index.php");
	}
	

	if($query_altera_participante && $query_altera_telefone_participante && $query_altera_email_participante && $query_altera_participante_comissao){
		mysql_query("COMMIT");
		fecha_mysql();
		redireciona("participantes.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Inscrição alterada com sucesso!"));
		
	} else {	
		mysql_query("ROLLBACK");
		fecha_mysql();
		redireciona("participantes.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e a inscrição não foi alterada. Tente novamente!"));
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include "site_mod_head_interno.php";?>
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
	      				<h3>Você está editando a inscrição de: <strong><?php echo $resultado_consulta_participante["nome_participante"];?></strong></h3>
	  				</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
						
						
						<div class="tabbable">
						<ul class="nav nav-tabs">
                          <?php if($resultado_consulta_participante["tipo_inscricao"] == "C"){?>
						  <li class="active titulo_aba">
						    <a href="#formcrianca" data-toggle="tab">Inscrição Infantil</a>
						  </li>
                          <?php }else{?>
                          <?php if(verifica_inscricao_trabalhador($resultado_consulta_participante['codigo_participante'])){?>
                          <li class="active titulo_aba">
						    <a href="#formtrabalhador" data-toggle="tab">Inscrição Trabalhador</a>
						  </li>
						  <?php }else{?>
                          <li class="active titulo_aba">
						    <a href="#formadulto" data-toggle="tab">Inscrição Jovem/Adulto</a>
						  </li>
                          <?php }}?>
						</ul>
						
						<br>
						
							<div class="tab-content">
                            	<?php if($resultado_consulta_participante["tipo_inscricao"] == "C"){?>
								<div class="tab-pane active" id="formcrianca">
								<form name="alterar_participante_crianca" id="alterar_participante_crianca" class="form-horizontal" method="post" action="editar_inscricao.php?codigo_inscricao_evento=<?php echo $codigo_inscricao_evento;?>">
									<fieldset>
																		
										<div class="divisor">Dados da criança</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
                                            	<input type="hidden" class="span6 form-inscricao" id="codigo_participante" name="codigo_participante" value="<?php echo $resultado_consulta_participante["codigo_participante"];?>">
												<input type="text" class="span6 form-inscricao" id="nome_participante" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
										<div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" value="<?php echo $resultado_consulta_participante["nome_participante_cracha"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->		
                                        					
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
                                                <select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
                                                  <option value="4282" <?php if($resultado_consulta_participante["codigo_cidade"] == '4282'){echo "selected";}?>>Cuiabá</option>
                                                  <option value="4446" <?php if($resultado_consulta_participante["codigo_cidade"] == '4446'){echo "selected";}?>>Várzea Grande</option>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo converte_data_portugues($resultado_consulta_participante["data_nascimento_participante"]);?>" onkeyup="Mascara(this, Data);" maxlength="13" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="centro_espirita_participante">Centro Espírita / Posto de Assistência</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="centro_espirita_participante" name="centro_espirita_participante" value="<?php echo $resultado_consulta_participante["centro_espirita_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        		
										
										<br />
                                        <div class="divisor">Dados do Responsável pela criança</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="nome_responsavel">Nome do Responsável</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_responsavel" name="nome_responsavel" value="<?php echo $resultado_consulta_participante["nome_responsavel"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="telefone_responsavel">Telefone do Responsável</label>
											<div class="controls">
												<input type="text" class="span3 form-inscricao" id="telefone_responsavel" name="telefone_responsavel" onkeyup="Mascara(this, Telefone);" maxlength="14" value="<?php echo $resultado_consulta_participante["telefone_responsavel"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="observacoes_crianca">Observações quanto à alimentação, alergias, medicações e outros:</label>
											<div class="controls">
                                            	<textarea name="observacoes_crianca" id="observacoes_crianca" class="span6 form-inscricao-texto"><?php echo $resultado_consulta_participante["observacoes_crianca"];?></textarea>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
										<br />
                                        <div class="divisor">Escolha do curso</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_crianca">Escolha o Tema Específico da Criança:</label>
											<div class="controls">
                                                <select id="curso_crianca[]" name="curso_crianca[]" class="span4 form-inscricao-select">
                                                  <?php while($resultado_consulta_cursos_criancas = mysql_fetch_assoc($query_consulta_cursos_criancas)) {?>
                                                  <option value="<?php echo $resultado_consulta_cursos_criancas["codigo_curso"];?>" <?php if($resultado_consulta_tema_especifico["codigo_curso"] == $resultado_consulta_cursos_criancas["codigo_curso"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_cursos_criancas["nome_curso"]);?></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
										
										<?php if($_SESSION["codigo_tipo_usuario_acesso"]=='1' || $_SESSION["codigo_tipo_usuario_acesso"]=='5'){?>

										<br />
                                        <div class="divisor">Dados da Inscrição</div>
                                        <br />

										<div class="control-group">											
											<label class="control-label" for="valor_inscricao">Valor R$:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="valor_inscricao" name="valor_inscricao" value="<?php echo converte_valor_real($resultado_consulta_participante["valor_inscricao_evento"]);?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_crianca">Situação:</label>
											<div class="controls">
                                                <select id="situacao_inscricao" name="situacao_inscricao" class="span4 form-inscricao-select">
                                                  <?php 
												  // consulta situacaos
												  $sql_consulta_situacao_inscricao = "SELECT codigo_situacao_inscricao, descricao_situacao_inscricao FROM situacao_inscricao";
												  $query_consulta_situacao_inscricao = mysql_query($sql_consulta_situacao_inscricao) or mascara_erro_mysql($sql_consulta_situacao_inscricao);
												  while($resultado_consulta_situacao = mysql_fetch_assoc($query_consulta_situacao_inscricao)) {?>
                                                  <option value="<?php echo $resultado_consulta_situacao["codigo_situacao_inscricao"];?>" <?php if($resultado_consulta_participante["codigo_situacao_inscricao"] == $resultado_consulta_situacao["codigo_situacao_inscricao"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_situacao["descricao_situacao_inscricao"]);?></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
										
											
										 <br />
										<?php }?>
											
										<div class="form-actions">
                                        	<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("alterar_participante_crianca"); ?>">					
											<input type="submit" name="alterar_participante_crianca" id="alterar_participante_crianca" class="btn btn-primary salvar-inscricao" value="Alterar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>
                                <?php }else{?>
                                <?php if(verifica_inscricao_trabalhador($resultado_consulta_participante['codigo_participante'])){?>
                                
                                <div class="tab-pane active" id="formtrabalhador">
								<form name="alterar_participante_trabalhador" id="alterar_participante_trabalhador" class="form-horizontal" method="post" action="editar_inscricao.php?codigo_inscricao_evento=<?php echo $codigo_inscricao_evento;?>">
									<fieldset>
																		
										<div class="divisor">Dados do Trabalhador</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
                                            	<input type="hidden" class="span6 form-inscricao" id="codigo_participante" name="codigo_participante" value="<?php echo $resultado_consulta_participante["codigo_participante"];?>">
												<input type="text" class="span6 form-inscricao" id="nome_participante" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para o crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" value="<?php echo $resultado_consulta_participante["nome_participante_cracha"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
																			
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
                                                <select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
                                                  <option value="4282" <?php if($resultado_consulta_participante["codigo_cidade"] == '4282'){echo "selected";}?>>Cuiabá</option>
                                                  <option value="4446" <?php if($resultado_consulta_participante["codigo_cidade"] == '4446'){echo "selected";}?>>Várzea Grande</option>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo converte_data_portugues($resultado_consulta_participante["data_nascimento_participante"]);?>" onkeyup="Mascara(this, Data);" maxlength="13" required>
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
												<input type="text" class="span3 form-inscricao" id="telefone_participante" name="telefone_participante" value="<?php echo $resultado_consulta_participante["numero_telefone_participante"];?>" onkeyup="Mascara(this, Telefone);" maxlength="14" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="email_participante">E-mail</label>
											<div class="controls">
												<input type="text" class="span4 form-inscricao" id="email_participante" name="email_participante" value="<?php echo $resultado_consulta_participante["descricao_email_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->			
										
										                                     
										<br />
                                        <div class="divisor">Em qual comissão irá trabalhar?</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="comissao_trabalho[]">Comissão de trabalho:</label>
											<div class="controls">
                                                <select id="comissao_trabalho[]" name="comissao_trabalho[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_comissoes_trabalho = mysql_fetch_assoc($query_consulta_comissoes_trabalho)) {?>
                                                  <option value="<?php echo $resultado_consulta_comissoes_trabalho["codigo_comissao_trabalho"];?>" <?php if($resultado_consulta_participante["codigo_comissao_trabalho"] == $resultado_consulta_comissoes_trabalho["codigo_comissao_trabalho"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_comissoes_trabalho["nome_comissao_trabalho"]);?></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        							
										<?php if($_SESSION["codigo_tipo_usuario_acesso"]=='1' || $_SESSION["codigo_tipo_usuario_acesso"]=='5'){?>
										<br />
                                        <div class="divisor">Dados da Inscrição</div>
                                        <br />

										<div class="control-group">											
											<label class="control-label" for="valor_inscricao">Valor R$:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="valor_inscricao" name="valor_inscricao" value="<?php echo converte_valor_real($resultado_consulta_participante["valor_inscricao_evento"]);?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_crianca">Situação:</label>
											<div class="controls">
                                                <select id="situacao_inscricao" name="situacao_inscricao" class="span4 form-inscricao-select">
                                                  <?php 
												  // consulta situacaos
												  $sql_consulta_situacao_inscricao = "SELECT codigo_situacao_inscricao, descricao_situacao_inscricao FROM situacao_inscricao";
												  $query_consulta_situacao_inscricao = mysql_query($sql_consulta_situacao_inscricao) or mascara_erro_mysql($sql_consulta_situacao_inscricao);
												  while($resultado_consulta_situacao = mysql_fetch_assoc($query_consulta_situacao_inscricao)) {?>
                                                  <option value="<?php echo $resultado_consulta_situacao["codigo_situacao_inscricao"];?>" <?php if($resultado_consulta_participante["codigo_situacao_inscricao"] == $resultado_consulta_situacao["codigo_situacao_inscricao"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_situacao["descricao_situacao_inscricao"]);?></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										<?php }?>
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("alterar_participante_trabalhador"); ?>">					
											<input type="submit" name="alterar_participante_trabalhador" id="alterar_participante_trabalhador" class="btn btn-primary salvar-inscricao" value="Alterar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>
                                
                                <?php }else{?>
                                
                                <div class="tab-pane active" id="formadulto">
								<form name="alterar_participante_adulto" id="alterar_participante_adulto" class="form-horizontal" method="post" action="editar_inscricao.php?codigo_inscricao_evento=<?php echo $codigo_inscricao_evento;?>">
									<fieldset>
																		
										<div class="divisor">Dados do participante (Jovem ou Adulto)</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
                                            	<input type="hidden" class="span6 form-inscricao" id="codigo_participante" name="codigo_participante" value="<?php echo $resultado_consulta_participante["codigo_participante"];?>">
												<input type="text" class="span6 form-inscricao" id="nome_participante" name="nome_participante" value="<?php echo $resultado_consulta_participante["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="nome_participante_cracha">Nome para o crachá:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="nome_participante_cracha" name="nome_participante_cracha" value="<?php echo $resultado_consulta_participante["nome_participante_cracha"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        						
										<div class="control-group">											
											<label class="control-label" for="lastname">Cidade</label>
											<div class="controls">
                                                <select id="cidade_participante" name="cidade_participante" class="span4 form-inscricao-select">
                                                  <option value="4282" <?php if($resultado_consulta_participante["codigo_cidade"] == '4282'){echo "selected";}?>>Cuiabá</option>
                                                  <option value="4446" <?php if($resultado_consulta_participante["codigo_cidade"] == '4446'){echo "selected";}?>>Várzea Grande</option>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo converte_data_portugues($resultado_consulta_participante["data_nascimento_participante"]);?>" onkeyup="Mascara(this, Data);" maxlength="13" required>
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
												<input type="text" class="span3 form-inscricao" id="telefone_participante" name="telefone_participante" value="<?php echo $resultado_consulta_participante["numero_telefone_participante"];?>" onkeyup="Mascara(this, Telefone);" maxlength="14" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="email_participante">E-mail</label>
											<div class="controls">
												<input type="text" class="span4 form-inscricao" id="email_participante" name="email_participante" value="<?php echo $resultado_consulta_participante["descricao_email_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->			
										
										                                     
										<br />
                                        <div class="divisor">Escolha do curso</div>
                                        <br />
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_participante[]">Tema Atual:</label>
											<div class="controls">
                                                <select id="curso_participante[]" name="curso_participante[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_tema_atual_adulto = mysql_fetch_assoc($query_consulta_tema_atual_adulto)) {?>
                                                  <option value="<?php echo $resultado_consulta_tema_atual_adulto["codigo_curso"];?>" <?php if($resultado_consulta_tema_atual["codigo_curso"] == $resultado_consulta_tema_atual_adulto["codigo_curso"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_tema_atual_adulto["descricao_tema_curso"]);?> - <strong><?php echo utf8_encode($resultado_consulta_tema_atual_adulto["nome_curso"]);?></strong></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_participante[]">Tema Específico:</label>
											<div class="controls">
                                                <select id="curso_participante[]" name="curso_participante[]" class="span6 form-inscricao-select">
                                                  <?php while($resultado_consulta_tema_especifico_adulto = mysql_fetch_assoc($query_consulta_tema_especifico_adulto)) {?>
                                                  <option value="<?php echo $resultado_consulta_tema_especifico_adulto["codigo_curso"];?>" <?php if($resultado_consulta_tema_especifico["codigo_curso"] == $resultado_consulta_tema_especifico_adulto["codigo_curso"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_tema_especifico_adulto["descricao_tema_curso"]);?> - <strong><?php echo utf8_encode($resultado_consulta_tema_especifico_adulto["nome_curso"]);?></strong></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
										<?php if($_SESSION["codigo_tipo_usuario_acesso"]=='1' || $_SESSION["codigo_tipo_usuario_acesso"]=='5'){?>
										 <br />
                                        <div class="divisor">Dados da Inscrição</div>
                                        <br />

										<div class="control-group">											
											<label class="control-label" for="valor_inscricao">Valor R$:</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="valor_inscricao" name="valor_inscricao" value="<?php echo converte_valor_real($resultado_consulta_participante["valor_inscricao_evento"]);?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                                        <div class="control-group">											
											<label class="control-label" for="curso_crianca">Situação:</label>
											<div class="controls">
                                                <select id="situacao_inscricao" name="situacao_inscricao" class="span4 form-inscricao-select">
                                                  <?php 
												  // consulta situacaos
												  $sql_consulta_situacao_inscricao = "SELECT codigo_situacao_inscricao, descricao_situacao_inscricao FROM situacao_inscricao";
												  $query_consulta_situacao_inscricao = mysql_query($sql_consulta_situacao_inscricao) or mascara_erro_mysql($sql_consulta_situacao_inscricao);
												  while($resultado_consulta_situacao = mysql_fetch_assoc($query_consulta_situacao_inscricao)) {?>
                                                  <option value="<?php echo $resultado_consulta_situacao["codigo_situacao_inscricao"];?>" <?php if($resultado_consulta_participante["codigo_situacao_inscricao"] == $resultado_consulta_situacao["codigo_situacao_inscricao"]){echo "selected";}?>><?php echo utf8_encode($resultado_consulta_situacao["descricao_situacao_inscricao"]);?></option>
                                                  <?php }?>
                                                </select>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										<?php }?>
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("alterar_participante_adulto"); ?>">					
											<input type="submit" name="alterar_participante_adulto" id="alterar_participante_adulto" class="btn btn-primary salvar-inscricao" value="Alterar Inscrição">
										</div> <!-- /form-actions -->
									</fieldset>
								</form>
								</div>
                                
								<?php }}?>
								
						  
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
mysql_free_result($query_consulta_cursos_criancas);

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
<script src="js/bootstrap.js"></script>
</body>
</html>

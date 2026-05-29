<?php include("sistema_mod_include.php"); ?>
<?php

$conexao = conecta_mysql();

$codigo_usuario = campo_form_decodifica($_GET["codigo_usuario"]);

// consulta dados do usuário
$sql_consulta_usuario = "SELECT 
usuario.codigo_usuario, usuario.codigo_tipo_usuario, usuario.email_usuario, usuario.senha_usuario, usuario.situacao_usuario,
participante.centro_espirita_participante, participante.codigo_participante, participante.data_nascimento_participante, participante.nome_participante,
telefone_participante.numero_telefone_participante,
email_participante.descricao_email_participante
FROM usuario
JOIN participante ON usuario.codigo_participante = participante.codigo_participante
LEFT JOIN telefone_participante ON telefone_participante.codigo_participante = participante.codigo_participante
LEFT JOIN email_participante ON email_participante.codigo_participante = participante.codigo_participante
WHERE usuario.codigo_usuario = '".$codigo_usuario."'";
$query_consulta_usuario = mysqli_query($conexao,$sql_consulta_usuario) or mascara_erro_mysql($sql_consulta_usuario);
$resultado_consulta_usuario = mysqli_fetch_assoc($query_consulta_usuario);



$mensagem = campo_form_decodifica($_GET["mm"]);


if(campo_form_decodifica($_POST['acao']) == "alterar_participante_adulto") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$codigo_participante						= protege_campo($_POST['codigo_participante']);
	$data_nascimento_participante 	= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$centro_espirita_participante 	= protege_campo($_POST['centro_espirita_participante']);
	$telefone_participante					= protege_campo(limpa_campo($_POST['telefone_participante']));
	$email_participante							= protege_campo($_POST['email_participante']);
	$senha_usuario			 						= protege_campo(md5($_POST['senha_usuario']));
	$codigo_usuario			 						= protege_campo($_POST['codigo_usuario']);
	
	conecta_mysql();


	if(verifica_duplicidade("usuario", "email_usuario", $email_participante)):

		redireciona("editar_conta.php?codigo_usuario=".campo_form_codifica($codigo_usuario)."&me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Este e-mail já existe em nosso banco. Tente outro!"));
		
	endif;
	
	mysqli_query($conexao,"BEGIN");
	
	// altera participante
	$sql_altera_participante = "UPDATE participante SET data_nascimento_participante = '".$data_nascimento_participante."', nome_participante = '".$nome_participante."', centro_espirita_participante = '".$centro_espirita_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_participante = mysqli_query($conexao,$sql_altera_participante) or mascara_erro_mysql($sql_altera_participante,"index.php");

	// altera telefone
	$sql_altera_telefone_participante = "UPDATE telefone_participante SET numero_telefone_participante = '".$telefone_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_telefone_participante = mysqli_query($conexao,$sql_altera_telefone_participante) or mascara_erro_mysql($sql_altera_telefone_participante,"index.php");
	
	// altera email
	$sql_altera_email_participante = "UPDATE email_participante SET descricao_email_participante = '".$email_participante."' WHERE codigo_participante = '".$codigo_participante."'";
	$query_altera_email_participante = mysqli_query($conexao,$sql_altera_email_participante) or mascara_erro_mysql($sql_altera_email_participante,"index.php");

	// altera usuario
	$sql_altera_usuario = "UPDATE usuario SET email_usuario = '".$email_participante."', senha_usuario = '".$senha_usuario."' WHERE codigo_usuario = '".$codigo_usuario."'";
	$query_altera_usuario = mysqli_query($conexao,$sql_altera_usuario) or mascara_erro_mysql($sql_altera_usuario,"index.php");
	
		
	if($query_altera_participante && $query_altera_telefone_participante && $query_altera_email_participante && $query_altera_usuario){
		mysqli_query($conexao,"COMMIT");
		fecha_mysql();
		redireciona("editar_conta.php?codigo_usuario=".campo_form_codifica($codigo_usuario)."&me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Usuário atualizado com sucesso!"));
		
	} else {	
		mysqli_query($conexao,"ROLLBACK");
		fecha_mysql();
		redireciona("editar_conta.php?codigo_usuario=".campo_form_codifica($codigo_usuario)."&me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e os dados não foram alterados. Tente novamente!"));
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
	      				<h3>Você está editando os dados de: <strong><?php echo $resultado_consulta_usuario["nome_participante"];?></strong></h3>
	  				</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
						
						
						<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active titulo_aba">
						    <a href="#formadulto" data-toggle="tab">Minha conta</a>
						  </li>
						</ul>
						
						<br>
						
							<div class="tab-content">                                
                <div class="tab-pane active" id="formadulto">
								<form name="alterar_participante_adulto" id="alterar_participante_adulto" class="form-horizontal" method="post" action="editar_conta.php?codigo_usuario=<?php echo campo_form_codifica($codigo_usuario);?>">
									<fieldset>
																		
										<div class="divisor">Dados do Usuário</div>
                                        <br />
										<div class="control-group">											
											<label class="control-label" for="nome_participante">Nome completo</label>
											<div class="controls">
                        <input type="hidden" class="span6 form-inscricao" id="codigo_participante" name="codigo_participante" value="<?php echo $resultado_consulta_usuario["codigo_participante"];?>">
												<input type="hidden" class="span6 form-inscricao" id="codigo_usuario" name="codigo_usuario" value="<?php echo $resultado_consulta_usuario["codigo_usuario"];?>">
												<input type="text" class="span6 form-inscricao" id="nome_participante" name="nome_participante" value="<?php echo $resultado_consulta_usuario["nome_participante"];?>" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                       
                                        <div class="control-group">											
											<label class="control-label" for="data_nascimento_participante">Data de Nascimento</label>
											<div class="controls">
												<input type="text" class="span2 form-inscricao" id="data_nascimento_participante" name="data_nascimento_participante" value="<?php echo converte_data_portugues($resultado_consulta_usuario["data_nascimento_participante"]);?>" onkeyup="Mascara(this, Data);" maxlength="13" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
										
                                        <div class="control-group">											
											<label class="control-label" for="centro_espirita_participante">Centro Espírita / Posto de Assistência</label>
											<div class="controls">
												<input type="text" class="span6 form-inscricao" id="centro_espirita_participante" name="centro_espirita_participante" value="<?php echo $resultado_consulta_usuario["centro_espirita_participante"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	
                                        
                                        <div class="control-group">											
											<label class="control-label" for="telefone_participante">Telefone</label>
											<div class="controls">
												<input type="text" class="span3 form-inscricao" id="telefone_participante" name="telefone_participante" value="<?php echo $resultado_consulta_usuario["numero_telefone_participante"];?>" onkeyup="Mascara(this, Telefone);" maxlength="14" required>
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->
                                        
                    <div class="control-group">											
											<label class="control-label" for="email_participante">E-mail</label>
											<div class="controls">
												<input type="text" class="span4 form-inscricao" id="email_participante" name="email_participante" value="<?php echo $resultado_consulta_usuario["email_usuario"];?>">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->	

										<div class="control-group">											
											<label class="control-label" for="senha_usuario">Senha</label>
											<div class="controls">
												<input type="password" class="span4 form-inscricao" id="senha_usuario" name="senha_usuario" value="" placeholder="******">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->		
										
										                                     
											
										<div class="form-actions">
											<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("alterar_participante_adulto"); ?>">					
											<input type="submit" name="alterar_participante_adulto" id="alterar_participante_adulto" class="btn btn-primary salvar-inscricao" value="Alterar Inscrição">
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
fecha_mysql($conexao);
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

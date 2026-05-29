<?php
session_start();
require_once("ferramenta/configuracoes.php");
require_once("ferramenta/funcao_php.php");

?>
<?php

if(campo_form_decodifica($_POST['acao']) == "gravar_participante") {
	
	// dados participanteis
	$nome_participante							= protege_campo($_POST['nome_participante']);
	$data_nascimento_participante 				= protege_campo(converte_data_ingles($_POST['data_nascimento_participante']));
	$cidade_participante 						= protege_campo($_POST['cidade_participante']);
	$centro_espirita_participante 				= protege_campo($_POST['centro_espirita_participante']);
	
	//dados de acesso
	$email_usuario	 		= protege_campo($_POST['email_usuario']);
	$senha_usuario 			= md5(protege_campo($_POST['senha_usuario']));
	
	$conexao = conecta_mysql();
	
	if(verifica_duplicidade('usuario','email_usuario',$email_usuario) == true){
		
	
		//dados participanteis
		$_SESSION["nome_participante"] = protege_campo($_POST['nome_participante']);
		$_SESSION["data_nascimento_participante"] = protege_campo($_POST['data_nascimento_participante']);
		$_SESSION["cidade_participante"] = protege_campo($_POST['cidade_participante']);
									
		//dados de acesso
		$_SESSION["email_usuario"] = protege_campo($_POST['email_usuario']);
		$_SESSION["senha_usuario"] = protege_campo($_POST['senha_usuario']);
		
		
		redireciona("index.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Este e-mail já está cadastrado, verifique!"));
		
	}
	
	mysqli_query($conexao,"BEGIN");
	
	// inclui participante
	$sql_inclui_participante = "INSERT INTO participante (codigo_cidade, nome_participante, centro_espirita_participante, data_nascimento_participante) VALUES ('".$cidade_participante."', '".$nome_participante."', '".$centro_espirita_participante."', '".$data_nascimento_participante."')";
	$query_inclui_participante = mysqli_query($conexao,$sql_inclui_participante) or mascara_erro_mysql($sql_inclui_participante,"index.php");
	$codigo_participante = mysqli_insert_id($conexao);
	
	// inclui usuario
	$sql_inclui_usuario = "INSERT INTO usuario (codigo_participante,codigo_tipo_usuario,email_usuario,senha_usuario,situacao_usuario) VALUES ('".$codigo_participante."','2','".$email_usuario."','".$senha_usuario."','A')";
	$query_inclui_usuario = mysqli_query($conexao,$sql_inclui_usuario) or mascara_erro_mysql($sql_inclui_usuario,"index.php");
	$codigo_usuario = mysqli_insert_id($conexao);
	
	// inclui telefones
	for($i=0;$i<count($_POST['telefone_participante']);$i++){
		
	$sql_inclui_telefone = "INSERT INTO telefone_participante (codigo_participante,numero_telefone_participante) VALUES ('".$codigo_participante."','".protege_campo(limpa_campo($_POST['telefone_participante'][$i]))."')";
	$query_inclui_telefone = mysqli_query($conexao,$sql_inclui_telefone) or mascara_erro_mysql($sql_inclui_telefone,"index.php");
	
	}
	
	mysqli_query($conexao,"COMMIT");
	
	if($query_inclui_participante){
			
		fecha_mysql();
		redireciona("index.php?me=".campo_form_codifica(0,true)."&mm=".campo_form_codifica("Seu cadastro foi realizado. Acesse o sistema com seus dados cadastrais!"));
		
	} else {	
		fecha_mysql();
		redireciona("index.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Ocorreram erros e o cadastro não foi realizado. Tente novamente!"));
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  
<head>
<?php include "site_mod_head.php";?>
</head>

<body>
	
	<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
	<?php include "site_mod_topo.php";?>		
	</div> <!-- /navbar-inner -->
	
	</div> <!-- /navbar -->



<div class="account-container register">
	
	<div class="content clearfix">
		
		<form name="cadastrar_participante" id="cadastrar_participante" method="post" action="cadastro.php" enctype="multipart/form-data">
		
			<h1>Faça seu cadastro e realize suas inscrições</h1>			
			
			<div class="login-fields">
				
				<p>Crie sua conta:</p>
				
				<div class="field">
					<label for="nome_participante">Nome completo:</label>
					<input type="text" id="nome_participante" name="nome_participante" value="" placeholder="Digite seu nome" class="login" required/>
				</div> <!-- /field -->
                
                <div class="field">
					<label for="data_nascimento_participante">Data de Nascimento:</label>
					<input type="text" id="data_nascimento_participante" name="data_nascimento_participante" placeholder="Data de Nascimento (dd/mm/aaaa)" value="" class="login" onkeyup="Mascara(this, Data);" maxlength="10" required />
				</div> <!-- /field -->
                           
                <div class="field">
					<label for="cidade_participante">Cidade:</label>
                    <select id="cidade_participante" name="cidade_participante" class="login">
                      <option value="4282">Cuiabá</option>
                      <option value="4446">Várzea Grande</option>
                    </select>
				</div> <!-- /field -->
				
                <div class="field">
					<label for="centro_espirita_participante">Centro Espírita / Posto de Assistência:</label>
					<input type="text" id="centro_espirita_participante" name="centro_espirita_participante" value="" placeholder="Centro Espírita / Posto de Assistência" class="login" />
				</div> <!-- /field -->
                
                <div class="field">
					<label for="telefone">Telefone:</label>
					<input type="text" id="telefone_participante[]" name="telefone_participante[]" value="" class="login" onkeyup="Mascara(this, Telefone);" placeholder="Telefone com DDD (00) 0000-0000" maxlength="14" required/>
				</div> <!-- /field -->
                
                
                <div class="field">
					<label for="email">Email:</label>
					<input type="text" id="email_usuario" name="email_usuario" value="" placeholder="Email" class="login" required/>
				</div> <!-- /field -->
                
				<div class="field">
					<label for="password">Senha:</label>
					<input type="password" id="senha_usuario" name="senha_usuario" value="" placeholder="Senha" class="login" required/>
				</div> <!-- /field -->
				
				<div class="field">
					<label for="confirma_senha">Confirme sua senha:</label>
					<input type="password" id="confirma_senha" name="confirma_senha" value="" placeholder="Confirme sua senha" class="login" required/>
				</div> <!-- /field -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Aceite os termos e condições de uso.</label>
				</span>
				<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante"); ?>">					
				<input type="submit" name="gravar" id="gravar" class="button btn btn-primary btn-large" value="Cadastrar">
				
			</div> <!-- .actions -->
			
		</form>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->


<!-- Text Under Box -->
<div class="login-extra">
	Já possui uma conta? <a href="index.php">Entre com seus dados de acesso</a>
</div> <!-- /login-extra -->


<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<script src="js/signin.js"></script>

</body>

 </html>

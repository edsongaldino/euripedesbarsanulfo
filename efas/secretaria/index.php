<?php include("sistema_mod_include.php"); ?>
<?php

$mensagem = campo_form_decodifica($_GET["mm"]);

if(campo_form_decodifica($_POST["acao"]) == "entrar") {
	
	// dados
	$email_usuario = protege_campo($_POST['email_usuario']);
	$senha_usuario = md5(protege_campo($_POST['senha_usuario']));
	
	$conexao = conecta_mysql();
	
	// autenticacao
	$sql_autentica = "
				  SELECT
					  usuario.codigo_usuario, usuario.codigo_participante, usuario.codigo_evento, usuario.codigo_tipo_usuario, usuario.email_usuario, usuario.senha_usuario, usuario.situacao_usuario,
					  participante.nome_participante
				  FROM usuario  
					  JOIN participante ON (usuario.codigo_participante = participante.codigo_participante)
				  WHERE usuario.email_usuario = '".$email_usuario."' AND usuario.senha_usuario = '".$senha_usuario."'
				  LIMIT 1
				  ";
	$query_autentica = mysqli_query($conexao,$sql_autentica); //or mascara_erro_mysql($sql_autentica)
	$resultado_autentica = mysqli_fetch_assoc($query_autentica);
	$total_autentica = mysqli_num_rows($query_autentica);
	
	mysqli_free_result($query_autentica);

	if($total_autentica) {
			
		// cria sessao
		$_SESSION["key_acesso"] = md5(KEY_SESSAO);
		$_SESSION["codigo_acesso"] = substr(md5(date("YmdHis", time()).str_shuffle("0123456789abcdefghijlmnopqrstuvxzwyk")),0,30);
		$_SESSION["email_usuario_acesso"] = $resultado_autentica["email_usuario"];
		$_SESSION["codigo_usuario_acesso"] = $resultado_autentica["codigo_usuario"];
		$_SESSION["codigo_tipo_usuario_acesso"] = $resultado_autentica["codigo_tipo_usuario"];
		$_SESSION["codigo_participante"] = $resultado_autentica["codigo_participante"];
		$_SESSION["nome_usuario_acesso"] = $resultado_autentica['nome_participante'];
		$_SESSION["codigo_evento_acesso"] = $resultado_autentica['codigo_evento'];
	
		fecha_mysql($conexao);

		redireciona("relatorios.php");
	} else {
		fecha_mysql($conexao);
		redireciona("index.php?me=".campo_form_codifica(1,true)."&mm=".campo_form_codifica("Usuário ou senha informados estão incorretos!"));
	}

}
?>
<!DOCTYPE html>
<html lang="pt-br">
  
<head>
<?php include "site_mod_head.php";?>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>

<body>
	
	<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
	<?php include "site_mod_topo.php";?>		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->

<div class="container">
<?php if($mensagem){?>
<div class="alert alert-success"><?php echo $mensagem;?></div>
<?php }?>
</div>
<div class="logo_sistema"></div>
<div class="account-container">
	
	<div class="content clearfix">
		
		<form name="entrar" method="post" action="index.php">
		
			<h1>Faça seu login</h1>	
            <h3>Ainda não tem login? <a href="cadastro.php">Cadastre-se aqui!</a></h3>	
			<div class="login-fields">
				
				<p>Acesse com seus dados para efetuar suas inscrições:</p>
				<div class="field">
					<label for="email">E-mail</label>
					<input type="text" id="email_usuario" name="email_usuario" value="" placeholder="E-mail" class="login username-field" />
				</div> <!-- /field -->
				
				<div class="field">
					<label for="senha">Senha:</label>
					<input type="password" id="senha_usuario" name="senha_usuario" value="" placeholder="Senha" class="login password-field"/>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Aceito os termos de uso</label>
				</span>
				<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("entrar"); ?>">				
				<button type="submit" name="entrar" id="entrar" class="button btn btn-success btn-large">Entrar</button>
				
			</div> <!-- .actions -->
			
			
			
		</form>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->



<div class="login-extra">
	<a href="#">Esqueci minha senha</a>
</div> <!-- /login-extra -->


<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<script src="js/signin.js"></script>

</body>

</html>

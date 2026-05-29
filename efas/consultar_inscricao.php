<?php include "sistema_mod_include.php";?>
<?php
$conexao = conecta_mysql();
// consulta comissoes de trabalho
$sql_consulta_comissoes_trabalho = "SELECT comissao_trabalho.codigo_comissao_trabalho, comissao_trabalho.nome_comissao_trabalho FROM comissao_trabalho ORDER BY comissao_trabalho.nome_comissao_trabalho ASC";
$query_consulta_comissoes_trabalho = mysqli_query($conexao,$sql_consulta_comissoes_trabalho) or mascara_erro_mysql($sql_consulta_comissoes_trabalho);

$mensagem = campo_form_decodifica($_GET["mm"]);

// consulta estados
$sql_consulta_estado = "SELECT codigo_estado, uf_estado, nome_estado FROM estado ORDER BY nome_estado ASC";
$query_consulta_estado = mysqli_query($conexao,$sql_consulta_estado) or mascara_erro_mysql($sql_consulta_estado);
$num = mysqli_num_rows($query_consulta_estado);
for ($i = 0; $i < $num; $i++) {
  $dados = mysqli_fetch_array($query_consulta_estado);
  $arrEstados[$dados['codigo_estado']] = $dados['uf_estado'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php include "site_mod_head.php";?>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script language="javascript" type="text/javascript">
        // instancia a pesquisa rapida
        $(document).ready(function() {
            $("#nome_participante").autocomplete({source: "site_mod_busca_nome.php", delay: 0, position: { my : "right top", at: "right bottom" }});
        });

    </script>
    <script type='text/javascript' src='consultar_participante.js'></script>
</head>
<body>
<!--header-->
<div class="top"></div>
<div class="header">
	<div class="container">
			<div class="header-top">
				<?php include "site_mod_topo.php";?>
			</div>
			<div class="banner-main">
                <?php include "site_mod_banner.php";?>
	        </div>
            </div>
</div>
<!--//header-->
<!--content-->
<div class="contact">
    <div class="container">


        <div class="contact-top ">
            <h3>Consulte sua Inscrição</h3>
        </div>		

        <div class="contact-grids">
            <div class="contact-form">
                <form name="gravar_participante_trabalhador" id="gravar_participante_trabalhador" class="form-horizontal" method="get" action="confirma_inscricao2.php">
                    <div class="contact-bottom">

                        <div class="col-md-3 in-contact">
                            <span>Digite seu número de inscrição:</span>
                            <input type="text" name="id" id="codigo_inscricao_evento" class="text" value="" required>
                            <input type="hidden" name="acao" id="codigo_inscricao_evento" class="text" value="add">
                        </div>
                        <span>Não sabe seu número de inscrição? Verifique o e-mail de confirmação enviado na sua conta ou mande um e-mail para <strong>3420efas@gmail.com</strong></span>
                        <div class="clearfix"> </div>
                    </div>
                    <input type="submit" value="CONSULTAR INSCRIÇÃO">
                </form>
            </div>
        </div>
    </div>
</div>
<!--//content-->
<!--footer-->
<div class="footer">
	<?php include "site_mod_rodape.php";?>
</div>
<!--//footer-->
<?php fecha_mysql($conexao);?>
</body>
</html>
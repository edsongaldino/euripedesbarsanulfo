<?php include "sistema_mod_include.php";?>
<?php
$conexao = conecta_mysql();
// consulta comissoes de trabalho
$sql_consulta_comissoes_trabalho = "SELECT comissao_trabalho.codigo_comissao_trabalho, comissao_trabalho.nome_comissao_trabalho FROM comissao_trabalho ORDER BY comissao_trabalho.nome_comissao_trabalho ASC";
$query_consulta_comissoes_trabalho = mysqli_query($conexao,$sql_consulta_comissoes_trabalho) or mascara_erro_mysql($sql_consulta_comissoes_trabalho);

$mensagem = campo_form_decodifica($_GET["mm"] ?? "");

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
    <link rel="stylesheet" type="text/css" href="css/code.jquery.com_ui_1.10.3_themes_smoothness_jquery-ui.css">
    <script src="js/code.jquery.com_ui_1.10.3_jquery-ui.js"></script>
    <script type="text/javascript" src="js/rawgit.com_digitalBush_jquery.maskedinput_1.4.0_dist_jquery.maskedinput.js"></script>
    <script language="javascript" type="text/javascript">
        //instancia a pesquisa rapida
        $(document).ready(function() {
            $("#nome_participante").autocomplete({source: "site_mod_busca_nome.php", delay: 0, position: { my : "right top", at: "right bottom" }});
        });
    </script>
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
    <script type="text/javascript">
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

    jQuery(function ($) {
        $("#data-nascimento").mask("99/99/9999", {
            completed: function () {
                console.log('complete')
                var value = $(this).val().split('/');
                var maximos = [31, 12, 2100];
                var novoValor = value.map(function (parcela, i) {
                    if (parseInt(parcela, 10) > maximos[i]) return maximos[i];
                    return parcela;
                });
                if (novoValor.toString() != value.toString()) $(this).val(novoValor.join('/')).focus();
            }
        });

        $("#fone").mask("(99) 9999-9999");
        $("#cpf").mask("999.999.999-99");
        $("#cep").mask("99.999-999");
    });
    </script>
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


       <!--content-mid-->
		<div class="content-mid box-inscricao">
			<div class="col-md-3 mid">
				<a href="inscricao_crianca.php">
				<div class="mid1">
					<h4>Inscrição para Crianças</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>
			<div class="col-md-3 mid">
				<a href="inscricao_jovem.php">
				<div class="mid1">
					<h4>Inscrição para Jovens</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>
			<div class="col-md-3 mid">
				<a href="inscricao_adulto.php">
				<div class="mid1">
					<h4>Inscrição para Adultos</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>
            <div class="col-md-3 mid">
				<a href="inscricao_trabalhador.php">
				<div class="mid1 active">
					<h4>Inscrição para Trabalhadores</h4>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
					<div class="clearfix"> </div>
				</div>
				</a>
			</div>
			<div class="clearfix"> </div>
		</div>
		<!--content-mid-->


        <div class="contact-top ">
            <h3>Inscrição - Trabalhador</h3>
        </div>		

        <div class="contact-grids">
            <div class="contact-form">
                <form name="gravar_participante_trabalhador" id="gravar_participante_trabalhador" class="form-horizontal" method="post" action="grava_inscricao.php">
                    <div class="contact-bottom">

                        <div class="col-md-4 in-contact">
                            <span>Name completo :</span>
                            <input type="text" name="nome_participante" id="nome_participante" class="text" value="" required>
                        </div>
                        <div class="col-md-3 in-contact">
                            <span>Nome para crachá :</span>
                            <input type="text" name="nome_participante_cracha" id="nome_participante_cracha" class="text" value="" required>
                        </div>

                        <div class="col-md-2 in-contact">
                            <span>Data Nasc.</span>
                            <input type="text" name="data_nascimento_participante" id="data-nascimento" placeholder="00/00/0000" value="" required>
                        </div>

                        <div class="col-md-3 in-contact">
                            <span>Telefone :</span>
                            <input type="text" name="telefone_participante" id="telefone_participante" class="text" value="" maxlength="15" onkeypress="mascara(this)" required>
                        </div>


                        <div class="col-md-4 in-contact">
                            <span>E-mail :</span>
                            <input type="text" name="email_participante" id="email_participante" class="text" value="" required>
                        </div>
                        <!--
                        <div class="col-md-1 in-contact">
                            <span>UF :</span>
                            <select name="estado_participante" id="estado_participante" onchange="buscar_cidades()" required>
                                <option value="">UF</option>
                                <?php foreach ($arrEstados as $value => $name) {
                                echo "<option value='{$value}'>{$name}</option>";
                                }?>
                            </select>
                        </div>

                        <div class="col-md-3 in-contact" id="load_cidades">
                            <span>Cidade :</span>
                            <select name="cidade_participante" id="cidade_participante" required>
                            <option value="">Selecione o estado</option>
                            </select>
                        </div>
                            -->

                        <div class="col-md-4 in-contact">
                            <span>Centro Espírita:</span>
                            <input type="text" name="centro_espirita_participante" id="centro_espirita_participante" class="text" value="">
                        </div>

                        <div class="col-md-6 in-contact">
                            <span>Comissão de Trabalho:</span>

                            <select id="comissao_trabalho[]" name="comissao_trabalho[]">
                                <option value="">Selecione a comissão</option>
                                <?php while($resultado_consulta_comissoes_trabalho = mysqli_fetch_assoc($query_consulta_comissoes_trabalho)) {?>
                                <option value="<?php echo $resultado_consulta_comissoes_trabalho["codigo_comissao_trabalho"];?>"><?php echo $resultado_consulta_comissoes_trabalho["nome_comissao_trabalho"];?></option>
                                <?php }?>
                            </select>

                        </div>

                        <div class="col-md-12 in-contact">
                            <span>Autorização para uso de imagem:</span>
                            <input type="checkbox" name="aceito_uso_imagem" id="aceito_uso_imagem" value="S" required> Autorizo o uso de minhas imagens captadas durante o evento
                        </div>

                        <div class="clearfix"> </div>
                    </div>
                    <input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("gravar_participante_trabalhador"); ?>">
                   <input type="submit" value="FINALIZAR INSCRIÇÃO">
                </form>
            </div>
            </div>
    </div>
</div>
<!--//content-->
<!--footer-->
<div class="footer">
	<?php include "site_mod_rodape.php";?>
    <script type='text/javascript' src='consultar_participante.js'></script>
</div>
<!--//footer-->
<?php fecha_mysql($conexao);?>
</body>
</html>
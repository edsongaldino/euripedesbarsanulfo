<?php include("sistema_mod_include.php"); ?>
<?php
$mensagem = campo_form_decodifica($_GET["mm"]);
$conexao = conecta_mysql();
// consulta inscrições de trabalhadores
$sql_consulta_trabalhadores = "SELECT 
								inscricao_evento.codigo_situacao_inscricao, inscricao_evento.valor_inscricao_evento, inscricao_evento.data_inscricao_evento, 
								participante.nome_participante, participante.data_nascimento_participante,
								situacao_inscricao.descricao_situacao_inscricao,
								evento.nome_evento,
								comissao_trabalho.nome_comissao_trabalho
								FROM inscricao_evento 
								JOIN evento ON (inscricao_evento.codigo_evento = evento.codigo_evento)
								JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
								JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
								JOIN comissao_trabalho_participante ON (participante.codigo_participante = comissao_trabalho_participante.codigo_participante)
								JOIN comissao_trabalho ON (comissao_trabalho_participante.codigo_comissao_trabalho = comissao_trabalho.codigo_comissao_trabalho)
							  WHERE inscricao_evento.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ORDER BY inscricao_evento.data_inscricao_evento ASC";
$query_consulta_trabalhadores = mysqli_query($conexao,$sql_consulta_trabalhadores) or mascara_erro_mysql($sql_consulta_trabalhadores);
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
      <!-- /widget -->
          <div class="widget widget-table action-table">
            <div class="widget-header"> <i class="icon-th-list"></i>
              <h3>Relação de Trabalhadores</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th> Nome do Participante</th>
                    <th> Idade</th>
                    <th> Comissão de trabalho</th>
                    <th> Data da Inscrição</th>
                    <th> Valor da Inscrição</th>
                    <th> Situação</th>
                    <th class="td-actions"></th>
                  </tr>
                </thead>
                <tbody>
                
                  <?php while($resultado_consulta_trabalhadores = mysqli_fetch_assoc($query_consulta_trabalhadores)) {?>
                  <tr>
                    <td> <?php echo utf8_encode($resultado_consulta_trabalhadores["nome_participante"]);?></td>
                    <td> <?php echo calcula_idade($resultado_consulta_trabalhadores["data_nascimento_participante"]);?></td>
                    <td> <?php echo utf8_encode($resultado_consulta_trabalhadores["nome_comissao_trabalho"]);?></td>
                    <td> <?php echo converte_data_portugues($resultado_consulta_trabalhadores["data_inscricao_evento"]);?> </td>
                    <td> R$ <?php echo converte_valor_real($resultado_consulta_trabalhadores["valor_inscricao_evento"]);?> </td>
                    <td> <i class="icon-ok-sign"> </i> <?php echo $resultado_consulta_trabalhadores["descricao_situacao_inscricao"];?></td>
                    <td class="td-actions"><a href="javascript:;" class="btn btn-small"><i class="btn-icon-only icon-edit"> </i></a><a href="javascript:;" class="btn btn-danger btn-small"><i class="btn-icon-only icon-remove"> </i></a></td>
                  </tr>
                  <?php }?>
                
                </tbody>
              </table>
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /extra-inner --> 
</div>
<?php
mysqli_free_result($query_consulta_trabalhadores);
fecha_mysql();
?>
<!-- /extra -->
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

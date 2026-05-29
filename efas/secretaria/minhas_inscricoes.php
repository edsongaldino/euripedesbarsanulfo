<?php include("sistema_mod_include.php"); ?>
<?php
$mensagem = campo_form_decodifica($_GET["mm"]);
conecta_mysql();
// consulta inscrições de participantes vinculados ao usuário
$sql_consulta_inscricoes_usuario = "SELECT 
									inscricao_evento.codigo_inscricao_evento, inscricao_evento.codigo_situacao_inscricao, inscricao_evento.valor_inscricao_evento, inscricao_evento.data_inscricao_evento, 
									participante.nome_participante, participante.data_nascimento_participante,
									situacao_inscricao.descricao_situacao_inscricao,
									evento.nome_evento
									FROM inscricao_evento 
									JOIN evento ON (inscricao_evento.codigo_evento = evento.codigo_evento)
									JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
									JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
									JOIN usuario_inscricao_evento ON (inscricao_evento.codigo_inscricao_evento = usuario_inscricao_evento.codigo_inscricao_evento)
								  WHERE inscricao_evento.codigo_evento = '1' AND usuario_inscricao_evento.codigo_usuario = '".$_SESSION["codigo_usuario_acesso"]."' ORDER BY inscricao_evento.data_inscricao_evento ASC";
$query_consulta_inscricoes_usuario = mysqli_query($conexao, $sql_consulta_inscricoes_usuario) or mascara_erro_mysql($sql_consulta_inscricoes_usuario);
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

      <!-- /widget -->
          <div class="widget widget-table action-table">
            <div class="widget-header"> <i class="icon-th-list"></i>
              <h3>Minhas Inscrições</h3>
              <a href="inscricao.php" class="btn btn-small btn-success btn-inscricao"><i class="btn-icon-only icon-pencil"> </i> FAZER INSCRIÇÃO DE OUTRO PARTICIPANTE</a>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th> Nome do Participante</th>
                    <th> Idade</th>
                    <th> Evento</th>
                    <th> Data da Inscrição</th>
                    <th> Valor da Inscrição</th>
                    <th> Situação</th>
                    <th class="td-actions"></th>
                  </tr>
                </thead>
                <tbody>
                
                  <?php while($resultado_consulta_inscricoes_usuario = mysqli_fetch_assoc($query_consulta_inscricoes_usuario)) {?>
                  <tr>
                    <td> <?php echo $resultado_consulta_inscricoes_usuario["nome_participante"];?></td>
                    <td> <?php echo calcula_idade($resultado_consulta_inscricoes_usuario["data_nascimento_participante"]);?></td>
                    <td> <?php echo utf8_encode($resultado_consulta_inscricoes_usuario["nome_evento"]);?></td>
                    <td> <?php echo converte_data_portugues($resultado_consulta_inscricoes_usuario["data_inscricao_evento"]);?> </td>
                    <td> R$ <?php echo converte_valor_real($resultado_consulta_inscricoes_usuario["valor_inscricao_evento"]);?> </td>
                    <td> <i class="icon-ok-sign"> </i> <?php echo $resultado_consulta_inscricoes_usuario["descricao_situacao_inscricao"];?></td>
                    <td class="td-actions"><a href="javascript:;" class="btn btn-small"><i class="btn-icon-only icon-edit"> </i></a><a href="excluir_inscricao.php?codigo=<?php echo campo_form_codifica($resultado_consulta_inscricoes_usuario["codigo_inscricao_evento"]);?>" class="btn btn-danger btn-small"><i class="btn-icon-only icon-remove"> </i></a></td>
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
mysqli_free_result($query_consulta_inscricoes_usuario);

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
<script src="js/bootstrap.js"></script>
</body>
</html>

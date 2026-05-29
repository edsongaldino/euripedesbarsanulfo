<?php include("sistema_mod_include.php"); ?>
<?php
$mensagem = campo_form_decodifica($_GET["mm"]);
conecta_mysql();

// consulta inscrições de participantes vinculados ao usuário

$sql_consulta_usuario_participante = "SELECT 
									participante.codigo_participante, participante.nome_participante, participante.data_nascimento_participante
									FROM usuario_participante 
									JOIN participante ON (usuario_participante.codigo_participante = participante.codigo_participante)
								  WHERE usuario_participante.codigo_usuario = '".$_SESSION["codigo_usuario_acesso"]."' ORDER BY participante.nome_participante ASC";
$query_consulta_usuario_participante = mysqli_query($conexao, $sql_consulta_usuario_participante) or mascara_erro_mysql($sql_consulta_usuario_participante);
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
              <h3>Meus cadastros</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th> Nome do Participante</th>
                    <th> Idade</th>
                    <th> Data de Nascimento</th>
                    <th class="td-actions"></th>
                  </tr>
                </thead>
                <tbody>
                
                  <?php while($resultado_consulta_usuario_participante = mysqli_fetch_assoc($query_consulta_usuario_participante)) {?>
                  <tr>
                    <td> <?php echo $resultado_consulta_usuario_participante["nome_participante"];?></td>
                    <td> <?php echo calcula_idade($resultado_consulta_usuario_participante["data_nascimento_participante"]);?></td>
                    <td> <?php echo converte_data_portugues($resultado_consulta_usuario_participante["data_nascimento_participante"]);?></td>
                    <td class="td-actions"><a href="javascript:;" class="btn btn-small"><i class="btn-icon-only icon-edit"> </i></a><a href="excluir_cadastro.php?codigo=<?php echo campo_form_codifica($resultado_consulta_usuario_participante["codigo_participante"]);?>" class="btn btn-danger btn-small"><i class="btn-icon-only icon-remove"> </i></a></td>
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
mysqli_free_result($query_consulta_usuario_participante);

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

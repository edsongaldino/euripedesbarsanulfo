<?php include("sistema_mod_include.php"); ?>
<?php
$conexao = conecta_mysql();

// processamento da ação
if(campo_form_decodifica($_POST["acao"]) == "buscar_curso") {
	// dados
	$nome_curso = protege_campo($_POST['nome_curso']);
  $codigo_instituto = protege_campo($_POST['codigo_instituto']);
  $codigo_tema_curso = protege_campo($_POST['codigo_tema_curso']);
	
	// filtros
	$sql_filtro = array();

	if($nome_curso) {
		$sql_filtro[] = sql_explode_like("curso.nome_curso",$nome_curso);
	}
	
	if($codigo_instituto) {
		$sql_filtro[] = sql_explode_like("curso.codigo_instituto",$codigo_instituto);
  }
  
  if($codigo_tema_curso) {
		$sql_filtro[] = sql_explode_like("curso.codigo_tema_curso",$codigo_tema_curso);
	}
	
	
	if(sizeof($sql_filtro)) {
		$sql_where_consulta = "AND ".implode(" AND ",$sql_filtro);
	}
}

// consulta
$sql_consulta = "

				SELECT curso.codigo_curso, curso.nome_curso, instituto.nome_instituto, evento.nome_evento, tema_curso.descricao_tema_curso, evento_curso.referencia, evento_curso.quantidade_vagas FROM evento_curso 
					JOIN evento ON (evento_curso.codigo_evento = evento.codigo_evento)
					JOIN curso ON (evento_curso.codigo_curso = curso.codigo_curso)
					JOIN participante_evento_curso ON (participante_evento_curso.codigo_curso = curso.codigo_curso)
					JOIN tema_curso ON (curso.codigo_tema_curso = tema_curso.codigo_tema_curso)
					JOIN instituto ON (curso.codigo_instituto = instituto.codigo_instituto)
				 WHERE evento_curso.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."' ".$sql_where_consulta." GROUP BY curso.codigo_curso, instituto.nome_instituto, evento_curso.referencia, evento_curso.quantidade_vagas ORDER BY curso.codigo_curso ASC 
";
$query_consulta = mysqli_query($conexao,$sql_consulta);
$total_consulta = mysqli_num_rows($query_consulta);

// consulta temas
$sql_consulta_tema = "SELECT codigo_tema_curso, descricao_tema_curso FROM tema_curso";
$query_consulta_tema = mysqli_query($conexao,$sql_consulta_tema);

// consulta institutos
$sql_consulta_institutos = "SELECT codigo_instituto, nome_instituto FROM instituto";
$query_consulta_institutos = mysqli_query($conexao,$sql_consulta_institutos);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php include "site_mod_head_interno.php";?>
  <!-- Add fancyBox main JS and CSS files -->
  <script type="text/javascript" src="ferramenta/fancybox/source/jquery.fancybox.pack.js?v=2.1.7"></script>
  <link rel="stylesheet" type="text/css" href="ferramenta/fancybox/source/jquery.fancybox.css?v=2.1.7" media="screen" />

  <style type="text/css">
    .fancybox-custom .fancybox-skin {
      box-shadow: 0 0 50px #222;
    }
  </style>
    <script type="text/javascript">
      
        $(document).ready(function() {
          $(".various").fancybox({
            maxWidth	: 500,
            maxHeight	: 250,
            fitToView	: false,
            width		: '70%',
            height		: '70%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
          });
        });
          
    </script>

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
              <h3>Cursos</h3>
            </div>


             <!-- widget-busca -->
						<div class="widget widget-table action-table">
						<div class="widget-busca"> <i class="icon-filter"></i>
						<h3>Faça sua busca</h3>
						<form  id="buscar_curso" name="buscar_curso" method="post" action="cursos.php">
						<div class="control-group">	
							<div class="controls">
								<input type="text" class="span4" id="nome_curso" name="nome_curso" value="" placeholder="Nome do curso">

								<select name="codigo_instituto" id="codigo_instituto" class="span3" placeholder="Instituto">
									<option value="">Filtrar pela Instituto</option>
									<?php while($resultado_consulta_instituto = mysqli_fetch_assoc($query_consulta_institutos)) {?>
									<option value="<?php echo $resultado_consulta_instituto["codigo_instituto"];?>"><?php echo $resultado_consulta_instituto["nome_instituto"];?></option>
									<?php }?>
								</select>

                <select name="codigo_tema_curso" id="codigo_tema_curso" class="span3" placeholder="Tema">
									<option value="">Filtrar por Tema</option>
									<?php while($resultado_consulta_situacao = mysqli_fetch_assoc($query_consulta_tema)) {?>
									<option value="<?php echo $resultado_consulta_situacao["codigo_tema_curso"];?>"><?php echo $resultado_consulta_situacao["descricao_tema_curso"];?></option>
									<?php }?>
								</select>
								
							</div>
							<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("buscar_curso"); ?>">
							<button type="submit" name="buscar" class="btn btn-primary buscar">Filtrar</button> 
						</div>
						
						</form>
						</div>
						
						<br/>
						<!-- /widget-busca -->
						<div class="total">Sua consulta retornou <span class="total_consulta"><?php echo $total_consulta;?></span> <?php if($total_consulta > 1){echo " registros";}else{echo " registro";}?></div>

            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                  	<th> Código do curso </th>
                    <th> Nome do curso </th>
                    <th> Tema Curso / Idade</th>
                    <th> Instituto</th>
                    <th> Qtd Vagas</th>
                    <th> Qtd Inscritos</th>
                    <th class="td-actions"> </th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($resultado_consulta_cursos = mysqli_fetch_assoc($query_consulta)) {?>
                  <tr>
                  	<td> <?php echo $resultado_consulta_cursos["codigo_curso"];?> </td>
                    <td> <?php echo $resultado_consulta_cursos["nome_curso"];?> </td>
                    <td> <?php echo $resultado_consulta_cursos["descricao_tema_curso"];?> </td>
                    <td> <?php echo $resultado_consulta_cursos["nome_instituto"];?> </td>
                    <td> <?php echo $resultado_consulta_cursos["quantidade_vagas"];?> </td>
                    <td> <?php echo calcula_total_inscritos_curso($resultado_consulta_cursos["codigo_curso"]);?> </td>
                    <td class="td-actions">
                    <a class="various btn btn-small" href="inscritos_curso.php?codigo_curso=<?php echo campo_form_codifica($resultado_consulta_cursos["codigo_curso"]);?>"><i class="btn-icon-only icon-eye-open" title="Visualizar inscritos no Curso"></i></a>
                    <a class="various btn btn-small" data-fancybox-type="iframe" href="atualizar_sinopse.php?codigo_curso=<?php echo campo_form_codifica($resultado_consulta_cursos["codigo_curso"]);?>"><i class="btn-icon-only icon-list" title="Editar Sinopse do Curso"></i></a>
                    <a class="various btn btn-small" data-fancybox-type="iframe" href="atualizar_vagas.php?codigo_curso=<?php echo campo_form_codifica($resultado_consulta_cursos["codigo_curso"]);?>"><i class="btn-icon-only icon-edit" title="Editar quantidade de vagas"></i></a>
                    </td>
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
mysqli_free_result($query_consulta);

fecha_mysql($conexao);
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

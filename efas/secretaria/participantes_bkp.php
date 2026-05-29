<?php include("sistema_mod_include.php"); ?>
<?php
$mensagem = campo_form_decodifica($_GET["mm"]);
conecta_mysql();

$codigo_participante = campo_form_decodifica($_GET["codigo_participante"]);
if($codigo_participante){
    $sql_where_consulta = " AND participante.codigo_participante = '".$codigo_participante."'";
}

if(campo_form_decodifica($_POST['acao']) == "buscar_participante") {
	
	$nome_participante								= protege_campo($_POST['nome_participante']);
	$situacao									        = protege_campo($_POST['situacao']);	
	$data_inscricao								    = protege_campo($_POST['data_inscricao']);

	
	// filtros
	$sql_filtro = array();

	if($nome_participante) {
		$sql_filtro[] = sql_explode_like("participante.nome_participante",$nome_participante);
	}
	
	if($situacao) {
		$sql_filtro[] = "inscricao_evento.codigo_situacao_inscricao = '$situacao'";
	}
	
	if($data_inscricao) {
		$sql_filtro[] = "inscricao_evento.data_inscricao_evento = '$data_inscricao'";
	}
	
	if(sizeof($sql_filtro)) {
		$sql_where_consulta = " AND ".implode(" AND ",$sql_filtro);
	}
	
}


// consulta inscrições de participantes vinculados ao usuário
$sql_consulta_inscricoes = "SELECT 
									inscricao_evento.codigo_inscricao_evento, inscricao_evento.codigo_situacao_inscricao, inscricao_evento.valor_inscricao_evento, inscricao_evento.data_inscricao_evento, 
									participante.codigo_participante, participante.nome_participante, participante.data_nascimento_participante,
									situacao_inscricao.descricao_situacao_inscricao,
									evento.nome_evento
									FROM inscricao_evento 
									JOIN evento ON (inscricao_evento.codigo_evento = evento.codigo_evento)
									JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
									JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
								  WHERE inscricao_evento.codigo_evento = '".$_SESSION["codigo_evento_acesso"]."'".$sql_where_consulta." 
                  ORDER BY participante.nome_participante ASC";
$query_consulta_inscricoes = mysqli_query($conexao, $sql_consulta_inscricoes) or mascara_erro_mysql($sql_consulta_inscricoes);
$total_inscricoes = mysqli_num_rows($query_consulta_inscricoes);

// consulta situacaos
$sql_consulta_situacao_inscricao = "SELECT codigo_situacao_inscricao, descricao_situacao_inscricao FROM situacao_inscricao";
$query_consulta_situacao_inscricao = mysqli_query($conexao, $sql_consulta_situacao_inscricao) or mascara_erro_mysql($sql_consulta_situacao_inscricao);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include "site_mod_head_interno.php";?>
<script type="text/javascript">
// Confirma exclusao
	function confirma_acao(msg,url){
	global $conexao;

		if(confirm(msg)) {
			open(url,"_self");
		}
	}
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
    	<?php if($mensagem){?>
        <div class="alert alert-success"><?php echo $mensagem;?></div>
        <?php }?>
          <div class="row">	      	
	      	
	      	<div class="span12">

          <!-- widget-busca -->
						<div class="widget widget-table action-table">
						<div class="widget-busca"> <i class="icon-filter"></i>
						<h3>Faça sua busca</h3>
						<form  id="buscar_participante" name="buscar_participante" method="post" action="/participantes.php">
						<div class="control-group">	
							<div class="controls">
								<input type="text" class="span4" id="nome_participante" name="nome_participante" value="" placeholder="Nome do Participante">
								<input type="date" class="span1" id="data_inscricao" name="data_inscricao" value="" placeholder="Data inicial">

								<select name="situacao" id="situacao" class="span3" placeholder="Situação">
									<option value="">Filtrar pela situação</option>
									<?php while($resultado_consulta_situacao = mysqli_fetch_assoc($query_consulta_situacao_inscricao)) {?>
									<option value="<?php echo $resultado_consulta_situacao["codigo_situacao_inscricao"];?>"><?php echo utf8_encode($resultado_consulta_situacao["descricao_situacao_inscricao"]);?></option>
									<?php }?>
								</select>
								
							</div>
							<input type="hidden" id="acao" name="acao" value="<?php echo campo_form_codifica("buscar_participante"); ?>">
							<button type="submit" name="buscar" class="btn btn-primary buscar">Filtrar</button> 
						</div>
						
						</form>
						</div>
						
						<br/>
						<!-- /widget-busca -->
						<div class="total">Sua consulta retornou <span class="total_consulta"><?php echo $total_inscricoes;?></span> <?php if($total_inscricoes > 1){echo " registros";}else{echo " registro";}?></div>
	      				
			      <div class="widget widget-table action-table">
						<div class="widget-header"> <i class="icon-th-list"></i>
						<h3>Gerenciamento de Participantes</h3>
						<a href="/inscricao.php" class="btn btn-small btn-success adicionar"><i class="btn-icon-only icon-plus"> </i> Adicionar participante</a>
						</div>

          <!-- /widget -->
          <div class="widget widget-table action-table">
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th> Código</th>
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
                
                  <?php while($resultado_consulta_inscricoes = mysqli_fetch_assoc($query_consulta_inscricoes)) {?>
                  <tr>
                    <td> <?php echo $resultado_consulta_inscricoes["codigo_participante"];?></td>
                    <td> <?php echo $resultado_consulta_inscricoes["nome_participante"];?></td>
                    <td> <?php echo calcula_idade($resultado_consulta_inscricoes["data_nascimento_participante"]);?></td>
                    <td> <?php echo utf8_encode($resultado_consulta_inscricoes["nome_evento"]);?></td>
                    <td> <?php echo converte_data_portugues($resultado_consulta_inscricoes["data_inscricao_evento"]);?> </td>
                    <td> R$ <?php echo converte_valor_real($resultado_consulta_inscricoes["valor_inscricao_evento"]);?> </td>

                    <?php if($resultado_consulta_inscricoes["codigo_situacao_inscricao"] == 1){?>
                    <td> <i class="icon-ok-sign vm"> </i> <?php echo $resultado_consulta_inscricoes["descricao_situacao_inscricao"];?></td>
                    <?php }else{?>
                    <td> <i class="icon-ok-sign vd"> </i> <?php echo $resultado_consulta_inscricoes["descricao_situacao_inscricao"];?></td>
                    <?php }?>
                    <td class="td-actions"><a href="imprime_cracha.php?codigo_participante=<?php echo $resultado_consulta_inscricoes["codigo_participante"];?>" target="_blank" title="Imprimir Crachá" class="btn btn-small"><i class="btn-icon-only icon-print"> </i></a><a href="editar_inscricao.php?acao=alterar&codigo_inscricao_evento=<?php echo $resultado_consulta_inscricoes["codigo_inscricao_evento"];?>" class="btn btn-small" title="Editar Inscrição"><i class="btn-icon-only icon-edit"> </i></a>
                    <?php if($_SESSION["codigo_tipo_usuario_acesso"]=='1'){?>
                    <a href="javascript: confirma_acao('Tem certeza que deseja excluir este participante?','excluir_inscricao.php?codigo_participante=<?php echo campo_form_codifica($resultado_consulta_inscricoes["codigo_participante"]);?>');" class="btn btn-small" title="Imprimir Ficha"><i class="btn-icon-only icon-trash"> </i></a>
                    <?php }?>
                    </td>
                  </tr>
                  <?php }?>
                
                </tbody>
              </table>
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget --> 

          </div> <!-- /span12 -->

	      </div> <!-- /row -->
    </div>
    <!-- /container --> 
  </div>
  <!-- /extra-inner --> 
</div>
<?php
mysqli_free_result($query_consulta_inscricoes);

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

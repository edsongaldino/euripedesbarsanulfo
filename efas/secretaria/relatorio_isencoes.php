<?php include("sistema_mod_include.php"); ?>
<?php
$conexao = conecta_mysql();

// Consulta nome do evento ativo
$codigo_evento_ativo = $_SESSION["codigo_evento_acesso"];
$sql_evento = "SELECT nome_evento FROM evento WHERE codigo_evento = '$codigo_evento_ativo' LIMIT 1";
$query_evento = mysqli_query($conexao, $sql_evento);
$resultado_evento = mysqli_fetch_assoc($query_evento);
$nome_evento = $resultado_evento ? $resultado_evento['nome_evento'] : "Evento Ativo";

// Consulta todas as inscrições com isenção (código_situacao_inscricao = 4)
$sql_consulta_isencoes = "SELECT 
								inscricao_evento.codigo_inscricao_evento, 
								inscricao_evento.codigo_situacao_inscricao, 
								inscricao_evento.valor_inscricao_evento, 
								inscricao_evento.data_inscricao_evento, 
								inscricao_evento.tipo_inscricao,
								participante.nome_participante, 
								participante.data_nascimento_participante,
								situacao_inscricao.descricao_situacao_inscricao
							FROM inscricao_evento 
							JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
							JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
							WHERE inscricao_evento.codigo_evento = '$codigo_evento_ativo' 
							  AND inscricao_evento.codigo_situacao_inscricao = 4 
							ORDER BY participante.nome_participante ASC";
$query_consulta_isencoes = mysqli_query($conexao, $sql_consulta_isencoes) or mascara_erro_mysql($sql_consulta_isencoes);
$total_isencoes = mysqli_num_rows($query_consulta_isencoes);

// Calcula totais por tipo e valor total
$total_valor_isento = 0;
$total_criancas = 0;
$total_jovens = 0;
$total_adultos = 0;
$total_trabalhadores = 0;

$isencoes_lista = array();
while($row = mysqli_fetch_assoc($query_consulta_isencoes)) {
	$isencoes_lista[] = $row;
	$total_valor_isento += (float)$row['valor_inscricao_evento'];
	
	if ($row['tipo_inscricao'] == 'C') $total_criancas++;
	elseif ($row['tipo_inscricao'] == 'J') $total_jovens++;
	elseif ($row['tipo_inscricao'] == 'A') $total_adultos++;
	elseif ($row['tipo_inscricao'] == 'T') $total_trabalhadores++;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php include "site_mod_head_interno.php";?>
<style>
	@media print {
		.navbar, .subnavbar, .footer, .btn-acoes, .widget-header .pull-right {
			display: none !important;
		}
		.main {
			padding: 0 !important;
		}
		.widget {
			border: none !important;
			box-shadow: none !important;
		}
		.widget-content {
			border: none !important;
		}
	}
	.stats-box-title {
		font-size: 13px;
		font-weight: bold;
		text-transform: uppercase;
		color: #555;
	}
	.stats-box-all-info {
		font-size: 24px;
		font-weight: bold;
		margin-top: 5px;
	}
	.vd { color: #468847; }
</style>
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
  </div>
</div>
<!-- /subnavbar -->

<div class="main">
  <div class="main-inner">
    <div class="container">
      
      <div class="row btn-acoes" style="margin-bottom: 20px;">
      	<div class="span12">
      		<a href="relatorios.php?pagina=7" class="btn"><i class="icon-arrow-left"></i> Voltar para Relatórios</a>
      		<div class="pull-right">
      			<button onclick="exportarExcel()" class="btn btn-success"><i class="icon-share"></i> Exportar para Excel</button>
      			<button onclick="window.print()" class="btn btn-primary"><i class="icon-print"></i> Imprimir</button>
      		</div>
      	</div>
      </div>

      <div class="row">
      	<div class="span12">
      		<div class="info-box" style="margin-bottom: 20px;">
               <div class="row-fluid stats-box">
                  <div class="span3">
                  	<div class="stats-box-title">Total de Isenções</div>
                    <div class="stats-box-all-info"><i class="icon-user" style="color:#2f96b4;"></i> <?php echo $total_isencoes;?></div>
                  </div>
                  
                  <div class="span3">
                    <div class="stats-box-title">Valor Total Isento</div>
                    <div class="stats-box-all-info"><i class="icon-money" style="color:#51a351;"></i> R$ <?php echo converte_valor_real($total_valor_isento);?></div>
                  </div>
                  
                  <div class="span6">
                    <div class="stats-box-title">Detalhamento por Categoria</div>
                    <div style="margin-top: 8px; font-size: 14px; font-weight: bold; display: flex; gap: 20px;">
                    	<span><i class="icon-chevron-right" style="color:#F38630;"></i> Crianças: <?php echo $total_criancas;?></span>
                    	<span><i class="icon-chevron-right" style="color:#E0E4CC;"></i> Jovens: <?php echo $total_jovens;?></span>
                    	<span><i class="icon-chevron-right" style="color:#69D2E7;"></i> Adultos: <?php echo $total_adultos;?></span>
                    	<span><i class="icon-chevron-right" style="color:#a883ff;"></i> Trabalhadores: <?php echo $total_trabalhadores;?></span>
                    </div>
                  </div>
               </div>
            </div>
      	</div>
      </div>

      <div class="row">	      	
      	<div class="span12">
      		<div class="widget widget-table action-table">
            <div class="widget-header"> 
              <i class="icon-th-list"></i>
              <h3>Relatório de Inscrições com Isenção - <strong><?php echo htmlspecialchars($nome_evento); ?></strong></h3>
            </div>
            
            <div class="widget-content">
              <table class="table table-striped table-bordered" id="tabela-isencoes">
                <thead>
                  <tr>
                    <th>Código Insc.</th>
                    <th>Nome do Participante</th>
                    <th>Idade</th>
                    <th>Categoria</th>
                    <th>Data da Inscrição</th>
                    <th>Valor Original</th>
                    <th>Situação</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($total_isencoes == 0){ ?>
                  <tr>
                    <td colspan="7" style="text-align: center; font-style: italic; padding: 20px;">
                      Nenhuma inscrição com isenção encontrada para este evento.
                    </td>
                  </tr>
                  <?php } else { ?>
                    <?php foreach($isencoes_lista as $inscricao) {
                    	$tipo_nome = "";
                    	if ($inscricao["tipo_inscricao"] == "C") $tipo_nome = "Criança";
                    	elseif ($inscricao["tipo_inscricao"] == "J") $tipo_nome = "Jovem";
                    	elseif ($inscricao["tipo_inscricao"] == "A") $tipo_nome = "Adulto";
                    	elseif ($inscricao["tipo_inscricao"] == "T") $tipo_nome = "Trabalhador";
                    ?>
                    <tr>
                      <td><?php echo $inscricao["codigo_inscricao_evento"];?></td>
                      <td><?php echo $inscricao["nome_participante"];?></td>
                      <td><?php echo calcula_idade($inscricao["data_nascimento_participante"]);?> anos</td>
                      <td><?php echo $tipo_nome;?></td>
                      <td><?php echo converte_data_portugues($inscricao["data_inscricao_evento"]);?></td>
                      <td>R$ <?php echo converte_valor_real($inscricao["valor_inscricao_evento"]);?></td>
                      <td class="vd"><i class="icon-ok-sign"></i> <?php echo $inscricao["descricao_situacao_inscricao"];?></td>
                    </tr>
                    <?php } ?>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
      	</div>
      </div>

    </div>
  </div>
</div>

<div class="footer btn-acoes">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <?php include "site_mod_rodape.php";?>
      </div>
    </div>
  </div>
</div>

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script>
function exportarExcel() {
    var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; 
    var j = 0;
    var tab = document.getElementById('tabela-isencoes'); // id of table

    for (j = 0 ; j < tab.rows.length ; j++) {     
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    }

    tab_text = tab_text + "</table>";
    
    // Add UTF-8 BOM to prevent encoding issues in Excel
    var url = 'data:application/vnd.ms-excel;charset=utf-8,\uFEFF' + encodeURIComponent(tab_text);
    var link = document.createElement("a");
    link.href = url;
    link.download = "relatorio_isencoes.xls";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
</body>
</html>
<?php
mysqli_free_result($query_consulta_isencoes);
fecha_mysql($conexao);
?>

<?php include("sistema_mod_include.php"); ?>
<?php
$conexao = conecta_mysql();
if($_GET["codigo_inscricao_evento"]){
$tipo_inscricao_evento = campo_form_decodifica($_GET["tipo"],true);

$codigo_inscricao_evento = campo_form_decodifica($_GET["codigo_inscricao_evento"],true);
//$codigo_inscricao_evento = $_GET["codigo_inscricao_evento"];

// consulta inscrições de participantes vinculados ao usuário
$sql_consulta_inscricao = "SELECT 
									inscricao_evento.codigo_inscricao_evento, inscricao_evento.codigo_situacao_inscricao, inscricao_evento.valor_inscricao_evento, inscricao_evento.data_inscricao_evento,  inscricao_evento.tipo_inscricao,
									participante.nome_participante, participante.data_nascimento_participante,
									situacao_inscricao.descricao_situacao_inscricao,
									evento.nome_evento
									FROM inscricao_evento 
									JOIN evento ON (inscricao_evento.codigo_evento = evento.codigo_evento)
									JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
									JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
								  WHERE inscricao_evento.codigo_inscricao_evento = '".$codigo_inscricao_evento."'";
$query_consulta_inscricao = mysqli_query($conexao,$sql_consulta_inscricao) or mascara_erro_mysql($sql_consulta_inscricao);
$resultado_consulta_inscricao = mysqli_fetch_assoc($query_consulta_inscricao);

}else{

$codigo_inscricao_evento = $_POST["codigo_inscricao_evento"];

// consulta inscrições de participantes vinculados ao usuário
$sql_consulta_inscricao = "SELECT 
									inscricao_evento.codigo_inscricao_evento, inscricao_evento.codigo_situacao_inscricao, inscricao_evento.valor_inscricao_evento, inscricao_evento.data_inscricao_evento, inscricao_evento.tipo_inscricao,
									participante.nome_participante, participante.data_nascimento_participante,
									situacao_inscricao.descricao_situacao_inscricao,
									evento.nome_evento
									FROM inscricao_evento 
									JOIN evento ON (inscricao_evento.codigo_evento = evento.codigo_evento)
									JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
									JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
								  WHERE inscricao_evento.codigo_inscricao_evento = '".$codigo_inscricao_evento."'";
$query_consulta_inscricao = mysqli_query($conexao,$sql_consulta_inscricao) or mascara_erro_mysql($sql_consulta_inscricao);
$resultado_consulta_inscricao = mysqli_fetch_assoc($query_consulta_inscricao);

}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "site_mod_head.php";?>
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
<!--//header-->
<!--content-->
<div class="contact">
    <div class="container">
        <div class="contact-top ">
            <h3>Sua inscrição foi realizada com sucesso!</h3>
        </div>

		<div class="alert alert-info" role="alert">
			<strong>À partir do dia 05/10 o pagamento só poderá ser feito no dia do evento! Agradecemos pela sua inscrição antecipada.</strong>
		</div>

		
		<div class="table-responsive">

		<table class="table ls-table" id="tabela1">
			<thead>
				<tr>
					<th class="txt-center">Inscrição</th>
					<th class="hidden-xs">Data da Inscrição</th>
					<th>Nome completo</th>
					<th>Status</th>
					<th>Valor R$</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<form method="post" target="pagseguro" action="https://pagseguro.uol.com.br/v2/checkout/payment.html">
				<!-- Campos obrigatórios -->  
				<input name="receiverEmail" type="hidden" value="secretaria@euripedesbarsanulfo.org.br">  
				<input name="currency" type="hidden" value="BRL">  
		
				<!-- Itens do pagamento (ao menos um item é obrigatório) -->  
				<input name="itemId1" type="hidden" value="<?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?>">  
				<input name="itemDescription1" type="hidden" value="<?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?> - <?php echo $resultado_consulta_inscricao["nome_participante"];?>">  
				<input name="itemAmount1" type="hidden" value="<?php echo $resultado_consulta_inscricao["valor_inscricao_evento"];?>">  
				<input name="itemQuantity1" type="hidden" value="1">  
				<input name="itemWeight1" type="hidden" value="0">  
				<!--
				<input name="itemId2" type="hidden" value="0002">  
				<input name="itemDescription2" type="hidden" value="Notebook Rosa">  
				<input name="itemAmount2" type="hidden" value="25600.00">  
				<input name="itemQuantity2" type="hidden" value="2">  
				<input name="itemWeight2" type="hidden" value="750"> -->
		
				<!-- Código de referência do pagamento no seu sistema (opcional) -->  
				<input name="reference" type="hidden" value="EFAS2022 - <?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?>">  		
						
				<tr>
					<td class="txt-center"><?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?></td>
					<td class="hidden-xs"><?php echo converte_data_portugues($resultado_consulta_inscricao["data_inscricao_evento"]);?></td>
					<td><?php echo $resultado_consulta_inscricao["nome_participante"];?></td>
					<td><?php echo $resultado_consulta_inscricao["descricao_situacao_inscricao"];?></td>
					<td>R$ <?php echo converte_valor_real($resultado_consulta_inscricao["valor_inscricao_evento"]);?></td>
					<!--<td><input alt="Pague com PagSeguro" name="submit"  type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/120x53-pagar.gif"/></td>-->
				</tr>
			</form>
			</tbody>
		</table>
	</div>
        


</div>
<!--//content-->
<!--footer-->
<div class="footer">
	<?php include "site_mod_rodape.php";?>
</div>
<!--//footer-->
</body>
</html>
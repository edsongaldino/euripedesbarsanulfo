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

   
if(!isset($_SESSION['carrinho'])){ 
    $_SESSION['carrinho'] = array(); 
} 

//adiciona produto  
if(isset($_GET['acao'])){

    //ADICIONAR CARRINHO 
    if($_GET['acao'] == 'add'){ 
      $id = intval($_GET['id']); 
      if(!isset($_SESSION['carrinho'][$id])){ 
        $_SESSION['carrinho'][$id] = 1; 
      } else { 
        $_SESSION['carrinho'][$id] += 1; 
      } 
    } //REMOVER CARRINHO 
  
    if($_GET['acao'] == 'del'){ 
      $id = intval($_GET['id']); 
      if(isset($_SESSION['carrinho'][$id])){ 
        unset($_SESSION['carrinho'][$id]); 
      } 
	} 
          
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

		<div class="alert alert-info margin-15" role="alert">
			<strong>À partir do dia 05/SET o pagamento só poderá ser feito no dia do evento! Agradecemos pela sua inscrição antecipada.</strong>
		</div>

		<!--
		<div class="box-consultar-inscricao">
            <div class="contact-form">
                <form name="gravar_participante_trabalhador" id="gravar_participante_trabalhador" class="form-horizontal" method="get" action="confirma_inscricao2.php">
                    <div class="contact-bottom">

                        <div class="col-md-3 in-contact">
                            <span>Digite seu número de inscrição:</span>
                            <input type="text" name="id" id="codigo_inscricao_evento" class="text" value="" required>
                            <input type="hidden" name="acao" id="codigo_inscricao_evento" class="text" value="add">
                        </div>
                        <input type="submit" value="ADICIONAR INSCRIÇÃO AO PAGAMENTO">
                    </div>
                    
                </form>
            </div>
        </div>
		-->		

		<div class="row lista-inscricao">

			<div class="col-md-12 titulo-confirma">
				<div class="col-md-1">Nº Insc.</div>
				<div class="col-md-2">Data da Inscrição</div>
				<div class="col-md-4">Nome completo</div>
				<div class="col-md-2">Status</div>
				<div class="col-md-2">Valor R$</div>
				<div class="col-md-1"></div>
			</div>

			<form method="post" target="pagseguro" action="https://pagseguro.uol.com.br/v2/checkout/payment.html">
				<!-- Campos obrigatórios -->  
				<input name="receiverEmail" type="hidden" value="aosamapostolo@gmail.com">  
				<input name="currency" type="hidden" value="BRL">  
				
				<?php if(count($_SESSION['carrinho']) > 0):?>
				<?php $i = 0;foreach($_SESSION['carrinho'] as $id => $qtd): $i=$i+1;?>
				<?php
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
												WHERE inscricao_evento.codigo_inscricao_evento = '".$id."'";
				$query_consulta_inscricao = mysqli_query($conexao,$sql_consulta_inscricao) or mascara_erro_mysql($sql_consulta_inscricao);
				$resultado_consulta_inscricao = mysqli_fetch_assoc($query_consulta_inscricao);
				?>
				<!-- Itens do pagamento (ao menos um item é obrigatório) -->  
				<input name="itemId<?php echo $i;?>" type="hidden" value="<?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?>">  
				<input name="itemDescription<?php echo $i;?>" type="hidden" value="<?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?> - <?php echo $resultado_consulta_inscricao["nome_participante"];?>">  
				<input name="itemAmount<?php echo $i;?>" type="hidden" value="<?php echo $resultado_consulta_inscricao["valor_inscricao_evento"];?>">  
				<input name="itemQuantity<?php echo $i;?>" type="hidden" value="1">  
				<input name="itemWeight<?php echo $i;?>" type="hidden" value="0">  
				<!--
				<input name="itemId2" type="hidden" value="0002">  
				<input name="itemDescription2" type="hidden" value="Notebook Rosa">  
				<input name="itemAmount2" type="hidden" value="25600.00">  
				<input name="itemQuantity2" type="hidden" value="2">  
				<input name="itemWeight2" type="hidden" value="750"> -->
		
				<!-- Código de referência do pagamento no seu sistema (opcional) -->  
				<input name="reference" type="hidden" value="EFAS2023">  		
						
				<div class="col-md-12 linha-confirma">
					<div class="col-md-1"><?php echo $resultado_consulta_inscricao["codigo_inscricao_evento"];?></div>
					<div class="col-md-2"><?php echo converte_data_portugues($resultado_consulta_inscricao["data_inscricao_evento"]);?></div>
					<div class="col-md-4"><?php echo $resultado_consulta_inscricao["nome_participante"];?></div>
					<div class="col-md-2"><?php echo $resultado_consulta_inscricao["descricao_situacao_inscricao"];?></div>
					<div class="col-md-2">R$ <?php echo converte_valor_real($resultado_consulta_inscricao["valor_inscricao_evento"]);?></div>
					<div class="col-md-1"></div>
				</div>
				<?php endforeach;?>
				<?php endif;?>

				

				<!--
				<div class="col-md-12 pagar-inscricao margin-15">
					<?php if($resultado_consulta_inscricao["tipo_inscricao"] == 'C'): ?>
						<?php include "botao_pagamento_crianca.php";?>
					<?php else: ?>
						<?php include "botao_pagamento_adulto.php";?>
					<?php endif; ?>
				</div>
				-->
				
				
				
				

			</form>
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
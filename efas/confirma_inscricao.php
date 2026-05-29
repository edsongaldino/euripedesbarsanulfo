<?php include("sistema_mod_include.php"); ?>
<?php
$conexao = conecta_mysql();

// Se solicitada limpeza manual do carrinho
if (isset($_GET['reset_cart']) && $_GET['reset_cart'] == 1) {
    $_SESSION['carrinho_inscricoes'] = [];
    redireciona("inscricao.php");
    exit;
}

// Se solicitada remoção de inscrição específica do carrinho e banco
if (isset($_GET['remove_id'])) {
    $remove_id = (int)$_GET['remove_id'];
    
    // 1. Remover da sessão
    if (isset($_SESSION['carrinho_inscricoes']) && is_array($_SESSION['carrinho_inscricoes'])) {
        if (($key = array_search($remove_id, $_SESSION['carrinho_inscricoes'])) !== false) {
            unset($_SESSION['carrinho_inscricoes'][$key]);
            $_SESSION['carrinho_inscricoes'] = array_values($_SESSION['carrinho_inscricoes']);
        }
    }
    
    // 2. Remover do banco de dados se for pendente (codigo_situacao_inscricao = 1) para evitar lixo
    $sql_check = "SELECT codigo_situacao_inscricao, codigo_participante FROM inscricao_evento WHERE codigo_inscricao_evento = '$remove_id'";
    $query_check = mysqli_query($conexao, $sql_check);
    if ($query_check && $row_check = mysqli_fetch_assoc($query_check)) {
        if ($row_check['codigo_situacao_inscricao'] == 1) {
            $cod_part = $row_check['codigo_participante'];
            
            // Inicia transação para remoção limpa
            mysqli_query($conexao, "BEGIN");
            
            mysqli_query($conexao, "DELETE FROM participante_evento_curso WHERE codigo_participante = '$cod_part'");
            mysqli_query($conexao, "DELETE FROM comissao_trabalho_participante WHERE codigo_participante = '$cod_part'");
            mysqli_query($conexao, "DELETE FROM dados_complementares WHERE codigo_participante = '$cod_part'");
            mysqli_query($conexao, "DELETE FROM telefone_participante WHERE codigo_participante = '$cod_part'");
            mysqli_query($conexao, "DELETE FROM email_participante WHERE codigo_participante = '$cod_part'");
            mysqli_query($conexao, "DELETE FROM inscricao_evento WHERE codigo_inscricao_evento = '$remove_id'");
            mysqli_query($conexao, "DELETE FROM participante WHERE codigo_participante = '$cod_part'");
            
            mysqli_query($conexao, "COMMIT");
        }
    }
    
    redireciona("confirma_inscricao.php?me=" . campo_form_codifica(0, true) . "&mm=" . campo_form_codifica("Inscrição excluída com sucesso."));
    exit;
}

// Inicializa carrinho se não existir
if (!isset($_SESSION['carrinho_inscricoes'])) {
    $_SESSION['carrinho_inscricoes'] = [];
}

// Captura parâmetro de URL e garante que está no carrinho
$cod = null;
if (isset($_GET["codigo_inscricao_evento"])) {
    $cod = campo_form_decodifica($_GET["codigo_inscricao_evento"], true);
} elseif (isset($_POST["codigo_inscricao_evento"])) {
    $cod = $_POST["codigo_inscricao_evento"];
}

if ($cod && !in_array($cod, $_SESSION['carrinho_inscricoes'])) {
    $_SESSION['carrinho_inscricoes'][] = $cod;
}

$ids_carrinho = $_SESSION['carrinho_inscricoes'];

// Fallback caso sessão esteja vazia mas tenhamos o ID na URL
if (empty($ids_carrinho) && $cod) {
    $ids_carrinho = [$cod];
}

$lista_inscricoes = [];
if (!empty($ids_carrinho)) {
    $ids_escaped = array_map(function($id) use ($conexao) {
        return (int)$id;
    }, $ids_carrinho);
    $ids_str = implode(',', $ids_escaped);

    $sql_consulta_inscricao = "SELECT 
                                    inscricao_evento.codigo_inscricao_evento, 
                                    inscricao_evento.codigo_situacao_inscricao, 
                                    inscricao_evento.valor_inscricao_evento, 
                                    inscricao_evento.data_inscricao_evento,  
                                    inscricao_evento.tipo_inscricao,
                                    participante.nome_participante, 
                                    participante.data_nascimento_participante,
                                    situacao_inscricao.descricao_situacao_inscricao,
                                    evento.nome_evento
                                    FROM inscricao_evento 
                                    JOIN evento ON (inscricao_evento.codigo_evento = evento.codigo_evento)
                                    JOIN situacao_inscricao ON (situacao_inscricao.codigo_situacao_inscricao = inscricao_evento.codigo_situacao_inscricao)
                                    JOIN participante ON (inscricao_evento.codigo_participante = participante.codigo_participante)
                                    WHERE inscricao_evento.codigo_inscricao_evento IN ($ids_str)
                                    ORDER BY inscricao_evento.codigo_inscricao_evento ASC";
    $query_consulta_inscricao = mysqli_query($conexao, $sql_consulta_inscricao) or mascara_erro_mysql($sql_consulta_inscricao);
    
    while ($row = mysqli_fetch_assoc($query_consulta_inscricao)) {
        $lista_inscricoes[] = $row;
    }
}

// Se veio a solicitação de limpeza do carrinho pós-pagamento (quando direciona com clear_cart=1)
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] == 1) {
    $_SESSION['carrinho_inscricoes'] = [];
}

// Calcular totais e separar itens pendentes
$itens_pendentes = [];
$tem_pendentes = false;
$tem_aprovados = false;
$valor_total_pendente = 0;

foreach ($lista_inscricoes as $insc) {
    if ($insc['codigo_situacao_inscricao'] == 1) {
        $itens_pendentes[] = [
            'codigo_inscricao' => $insc['codigo_inscricao_evento'],
            'nome_participante' => $insc['nome_participante'],
            'valor_inscricao' => $insc['valor_inscricao_evento']
        ];
        $valor_total_pendente += (float)$insc['valor_inscricao_evento'];
        $tem_pendentes = true;
    } else {
        $tem_aprovados = true;
    }
}

$link_pagamento = false;
if ($tem_pendentes && !empty($itens_pendentes)) {
    $link_pagamento = gerar_pagamento_mp($itens_pendentes);
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
            <h3>Inscrições Realizadas com Sucesso!</h3>
        </div>

		<div class="alert alert-info" role="alert">
			<strong>À partir do dia 05/SET o pagamento só poderá ser feito no dia do evento! Agradecemos pela sua inscrição antecipada.</strong>
		</div>

		
		<div class="row lista-inscricao">

			<div class="col-md-12 titulo-confirma">
				<div class="col-md-1">Nº Insc.</div>
				<div class="col-md-2">Data da Inscrição</div>
				<div class="col-md-4">Nome completo</div>
				<div class="col-md-2">Status</div>
				<div class="col-md-2">Valor R$</div>
				<div class="col-md-1" style="text-align: center;">Ações</div>
			</div>

			<?php 
			if (!empty($lista_inscricoes)):
				foreach ($lista_inscricoes as $inscricao):
			?>
			<div class="col-md-12 linha-confirma" style="display: flex; align-items: center; border-bottom: 1px solid #eee; padding: 12px 0; margin-bottom: 5px;">
				<div class="col-md-1"><?php echo $inscricao["codigo_inscricao_evento"];?></div>
				<div class="col-md-2"><?php echo converte_data_portugues($inscricao["data_inscricao_evento"]);?></div>
				<div class="col-md-4" style="font-weight: 500;"><?php echo $inscricao["nome_participante"];?></div>
				<div class="col-md-2">
					<?php if ($inscricao["codigo_situacao_inscricao"] == 1): ?>
						<span class="label label-warning" style="font-size: 12px; font-weight: bold; padding: 4px 8px; border-radius: 4px;"><?php echo $inscricao["descricao_situacao_inscricao"];?></span>
					<?php else: ?>
						<span class="label label-success" style="font-size: 12px; font-weight: bold; padding: 4px 8px; border-radius: 4px;"><?php echo $inscricao["descricao_situacao_inscricao"];?></span>
					<?php endif; ?>
				</div>
				<div class="col-md-2" style="font-weight: bold;">R$ <?php echo converte_valor_real($inscricao["valor_inscricao_evento"]);?></div>
				<div class="col-md-1" style="text-align: center;">
					<?php if ($inscricao["codigo_situacao_inscricao"] == 1): ?>
						<a href="confirma_inscricao.php?remove_id=<?php echo $inscricao["codigo_inscricao_evento"];?>" class="text-danger link-remover" style="color: #a94442; font-weight: bold; font-size: 13px; text-decoration: none;" onclick="return confirm('Deseja realmente excluir esta inscrição?');" title="Excluir Inscrição">
							Excluir
						</a>
					<?php else: ?>
						<span style="color: #ccc; font-size: 12px;">-</span>
					<?php endif; ?>
				</div>
			</div>
			<?php 
				endforeach;
			else:
			?>
			<div class="col-md-12 text-center" style="padding: 40px; background: #fafafa; border-radius: 8px; border: 1px dashed #ddd; margin-bottom: 20px;">
				<p style="font-size: 16px; color: #777; margin-bottom: 0;">Nenhuma inscrição encontrada na lista.</p>
			</div>
			<?php endif; ?>

			<?php if (count($lista_inscricoes) > 1): ?>
			<div class="col-md-12" style="margin-top: 15px; padding: 15px 0; font-size: 16px; border-top: 2px solid #ddd; display: flex; justify-content: flex-end; align-items: center;">
				<div style="font-weight: bold; margin-right: 20px; color: #555;">Total das Inscrições:</div>
				<div style="font-weight: 800; font-size: 18px; color: #2d3e50; padding-right: 15px;">
					R$ <?php
						$soma_total = 0;
						foreach ($lista_inscricoes as $insc) {
							$soma_total += (float)$insc['valor_inscricao_evento'];
						}
						echo converte_valor_real($soma_total);
					?>
				</div>
			</div>
			<?php endif; ?>

			<div class="col-md-12 pagar-inscricao margin-15" style="margin-top: 40px; text-align: center;">
				<?php if ($tem_pendentes): ?>
					<?php if ($link_pagamento): ?>
						<div style="background-color: #f7f9fc; padding: 25px; border-radius: 8px; border: 1px solid #e1e8ed; display: inline-block; max-width: 600px; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
							<span style="font-size: 16px; color: #555; display: block; margin-bottom: 12px;">
								Total Pendente (<?php echo count($itens_pendentes); ?> <?php echo (count($itens_pendentes) > 1) ? 'inscrições' : 'inscrição'; ?>): 
								<strong style="font-size: 22px; color: #2d3e50; margin-left: 5px;">R$ <?php echo converte_valor_real($valor_total_pendente); ?></strong>
							</span>
							<a href="<?php echo $link_pagamento; ?>" class="btn-pagar-mp" style="display: inline-block; background-color: #009ee3; color: white; font-size: 18px; font-weight: bold; padding: 15px 35px; border-radius: 6px; text-decoration: none; box-shadow: 0 4px 8px rgba(0,158,227,0.25); transition: all 0.2s ease;">
								Pagar com Mercado Pago
							</a>
							<p style="margin-top: 12px; color: #777; font-size: 14px; margin-bottom: 0;">Você será redirecionado para a plataforma segura do Mercado Pago.</p>
						</div>
					<?php else: ?>
						<div class="alert alert-warning" style="display: inline-block; margin-top: 15px;">
							<strong>Atenção:</strong> O link de pagamento do Mercado Pago não pôde ser gerado. Certifique-se de configurar o Token de Acesso válido no arquivo de configurações.
						</div>
					<?php endif; ?>
				<?php else: ?>
					<?php if ($tem_aprovados): ?>
						<div class="alert alert-success" style="display: inline-block; margin-top: 15px; font-size: 16px; padding: 15px 30px;">
							<strong>Inscrições Confirmadas!</strong> O pagamento já foi registrado e aprovado. Obrigado!
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<div class="col-md-12 acoes-carrinho" style="margin-top: 40px; margin-bottom: 20px; text-align: center; border-top: 1px solid #eee; padding-top: 30px;">
				<a href="inscricao.php" class="btn btn-default btn-adicionar-outra" style="margin-right: 15px; padding: 12px 25px; font-weight: bold; border: 2px solid #2d3e50; color: #2d3e50; background: transparent; border-radius: 5px; transition: all 0.3s ease; text-decoration: none; display: inline-block;">
					+ Adicionar Outra Inscrição
				</a>
				<?php if (!empty($ids_carrinho)): ?>
				<a href="confirma_inscricao.php?reset_cart=1" class="btn btn-link btn-limpar-carrinho" style="color: #a94442; text-decoration: none; font-size: 15px; padding: 10px; font-weight: 500; display: inline-block;" onclick="return confirm('Tem certeza que deseja limpar a lista de inscrições?');">
					Limpar Lista / Nova Inscrição
				</a>
				<?php endif; ?>
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
</body>
<?php fecha_mysql($conexao);?>
</html>
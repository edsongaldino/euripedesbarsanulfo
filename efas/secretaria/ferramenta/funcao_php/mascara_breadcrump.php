<?php
function mascara_breadcrump($pagina_ativo) {
	// paginas
	$pagina = array(
		1 => array("Página inicial","/index.php"),
		2 => array("Alterar senha","/sessao_usuario/editar_se.php"),
		3 => array("Proprietários","/sessao_proprietarios/consultar.php"),
		4 => array("Lançamentos Online","/sessao_estatistica/consultar_lancamentosonline.php"),
		5 => array("Enquetes","/sessao_estatistica/consultar_enquete.php"),
		6 => array("Imóveis","/sessao_imoveis/consultar.php"),
		7 => array("Índices","/sessao_indice/consultar.php"),
		8 => array("INCC","/sessao_indice/consultar_incc.php"),
		9 => array("IGP-M","/sessao_indice/consultar_igpm.php"),
		10 => array("TR","/sessao_indice/consultar_tr.php"),
		11 => array("Cotações: índices e bolsas","/sessao_indice/.php"),
		12 => array("Empreendimentos","/sessao_empreendimento/consultar.php"),
		13 => array("Disponibilidade","/sessao_disponibilidade/consultar_empreendimento_selecao.php"),
		14 => array("Tabela de preço","/sessao_tabelapreco/consultar_empreendimento_selecao.php"),
		15 => array("Parceiros","/sessao_parceiro/consultar.php"),
		16 => array("Incluir novo parceiro","/sessao_parceiro/incluir.php"),
		17 => array("Arquivos","/sessao_arquivo/consultar_empreendimento_selecao.php")
	);
	
	/*
	$pagina = array(
		1 => array("Página inicial","/index.php"),
		2 => array("Alterar senha","/sessao_usuario/editar_se.php"),
		20 => array("Atendimentos em andamento","/sessao_atendimento/consultar_andamento.php"),
		3 => array("Atendimentos arquivados","/sessao_atendimento/consultar_arquivado.php"),
		4 => array("Atendimentos excluídos","/sessao_atendimento/consultar_excluido.php"),
		5 => array("Atendimentos finalizados","/sessao_atendimento/consultar_finalizado.php"),
		6 => array("Clientes","/sessao_cliente/consultar.php"),
		7 => array("Editar cliente","/sessao_cliente/editar.php"),
		8 => array("Novo cliente","/sessao_cliente/incluir.php"),
		9 => array("Colaboradores","/sessao_colaborador/consultar.php"),
		10 => array("Editar colaborador","/sessao_colaborador/editar.php"),
		11 => array("Novo colaborador","/sessao_colaborador/incluir.php"),
		12 => array("Alterar senha","/sessao_colaborador/editar_se.php"),
		13 => array("Contatos excluídos","/sessao_contato/consultar_excluido.php"),
		14 => array("Novos contatos","/sessao_contato/consultar_novo.php"),
		15 => array("Editar contato","/sessao_contato/editar.php"),
		16 => array("Novo contato","/sessao_contato/incluir.php"),
		17 => array("Empreendimentos","/sessao_empreendimento/consultar.php"),
		18 => array("Editar empreendimento","/sessao_empreendimento/editar.php"),
		19 => array("Novo empreendimento","/sessao_empreendimento/incluir.php")
	);
	*/
	
	$total_pagina = sizeof($pagina);
	
	$breadcrump = array();
	
	foreach($pagina_ativo as $item) {
		if($item <= $total_pagina) {
			$breadcrump[] = '<a href="'.(SUBPASTA_RAIZ).($pagina[$item][1]).'" title="'.$pagina[$item][0].'" target="_parent">'.$pagina[$item][0].'</a>';
		}
	}
	
	return "Você está em: ".implode(" > ",$breadcrump);
}
?>
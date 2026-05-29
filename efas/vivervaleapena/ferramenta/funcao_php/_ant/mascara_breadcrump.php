<?php
function mascara_breadcrump($pagina_ativo) {
	// paginas
	$pagina = array(
		1 => array("Página inicial","/index.php"),
		2 => array("Alterar senha","/sessao_usuario/editar_senha.php"),
		3 => array("Estatísticas","/sessao_estatistica/consultar.php"),
			4 => array("Lançamentos Online","/sessao_estatistica/lancamentosonline/consultar.php"),
			5 => array("Enquetes","/sessao_estatistica/enquete/consultar.php"),
			6 => array("Empreendimentos","/sessao_estatistica/empreendimento/consultar_empreendimento_selecao.php"),
		7 => array("Índices","/sessao_indice/consultar.php"),
			8 => array("INCC","/sessao_indice/incc/consultar.php"),
			9 => array("IGP-M","/sessao_indice/igpm/consultar.php"),
			10 => array("TR","/sessao_indice/tr/consultar.php"),
			11 => array("Cotações: índices e bolsas","/sessao_indice/cotacao/consultar.php"),
		12 => array("Empreendimentos","/sessao_empreendimento/consultar.php"),
		13 => array("Disponibilidade","/sessao_disponibilidade/consultar_empreendimento_selecao.php"),
14 => array("Tabela de preço","/sessao_tabela_preco/consultar_empreendimento_selecao.php"),

		15 => array("Parceiros","/sessao_parceiro/consultar.php"),
		16 => array("Incluir novo parceiro","/sessao_parceiro/incluir.php"),
		17 => array("Arquivos","/sessao_arquivo/consultar_empreendimento_selecao.php"),

		18 => array("Preços","/sessao_preco/consultar.php"),
			19 => array("Condições de pagamento","/sessao_preco/condicao_pagamento/consultar_empreendimento_selecao.php"),
			20 => array("Valores das unidades","/sessao_preco/valor/consultar_empreendimento_selecao.php"),
			21 => array("Tabela de preço","/sessao_preco/tabela/consultar_empreendimento_selecao.php")
	);

	$total_pagina = sizeof($pagina);
	
	$breadcrump = array();
	
	foreach($pagina_ativo as $item) {
		if($item <= $total_pagina) {
			$breadcrump[] = '<a href="'.(SUBPASTA_RAIZ).($pagina[$item][1]).'" title="'.$pagina[$item][0].'" target="_parent">'.$pagina[$item][0].'</a>';
		} else {
			if(is_array($item)) {
				$breadcrump[] = '<a href="'.$item[0].'" target="_self" title="'.$item[1].'">'.$item[1].'</a>';
			}
		}
	}
	
	return "Você está em: ".implode(" > ",$breadcrump);
}
?>
<?php
function mascara_breadcrump($pagina_ativo) {
	// paginas
	$pagina = array(
		1 	=> array("Página inicial","/index.php"),
		2	=> array("Alterar senha","/sessao_usuario/editar_senha.php"),
		3 	=> array("Usuários","/sessao_gerenciamento/usuarios/"),
		4	=> array("Novo usuário","/sessao_gerenciamento/usuarios/novo.php"),
		5	=> array("Parceiros","/sessao_gerenciamento/parceiros/consultar.php"),
		6	=> array("Novo parceiro","/sessao_gerenciamento/parceiros/novo.php"),
		7	=> array("Clientes","/sessao_gerenciamento/clientes/consultar.php"),
		8 	=> array("Novo cliente","/sessao_gerenciamento/clientes/novo.php"),
		9	=> array("Atendimento","/sessao_atendimento/consultar.php"),
		10	=> array("Novo atendimento","/sessao_atendimento/novo.php"),
		11	=> array("Imobiliárias","/sessao_gerenciamento/imobiliarias/consultar.php"),
		12	=> array("Nova imobiliária","/sessao_gerenciamento/imobiliarias/novo.php"),
		13	=> array("Notificação","/index.php"),
		14	=> array("Disponibilidade","/sessao_consulta/tabela_disponibilidade/consultar.php?sessao_menu=PT1RTw=="),
	);

	$total_pagina = sizeof($pagina);
	
	$breadcrump = array();
	
	foreach($pagina_ativo as $item) {
		if($item <= $total_pagina) {
			$breadcrump[] = '<a href="'.($pagina[$item][1]).'" title="'.$pagina[$item][0].'" target="_parent">'.$pagina[$item][0].'</a>';
		} else {
			if(is_array($item)) {
				$breadcrump[] = '<a href="'.$item[0].'" target="_self" title="'.$item[1].'">'.$item[1].'</a>';
			}
		}
	}
	
	return "Você está em: ".implode(" > ",$breadcrump);
}
?>
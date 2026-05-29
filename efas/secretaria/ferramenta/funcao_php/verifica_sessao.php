<?php
function verifica_sessao() {
	$pagina_atual = substr(strrchr($_SERVER["SCRIPT_NAME"],"/"),1);
	$pagina_atual = explode("?",$pagina_atual);
	$pagina_atual = $pagina_atual[0];

	if(($pagina_atual != "index.php") && ($pagina_atual != "imagem.php") && ($pagina_atual != "imagem_rodape.php") && ($pagina_atual != "imagem_topo.php") && ($pagina_atual != "imagem_centro.php") && ($pagina_atual != "cliente_consultar_andamento.php") && ($pagina_atual != "cliente_finalizar_atendimento.php") && ($pagina_atual != "cliente_nova_interacao.php") && ($pagina_atual != "consultar_arquivo_externo.php")) {
		if(@$_SESSION['key_acesso'] != md5(KEY_SESSAO)) {
			redireciona("index.php");
		}
	}
}
?>
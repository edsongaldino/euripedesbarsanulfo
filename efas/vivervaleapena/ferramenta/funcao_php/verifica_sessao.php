<?php
function verifica_sessao() {
	$pagina_atual = substr(strrchr($_SERVER["SCRIPT_NAME"],"/"),1);
	$pagina_atual = explode("?",$pagina_atual);
	$pagina_atual = $pagina_atual[0];
	
	$excecao = array(
					"index.php",
					);

	if(!in_array($pagina_atual,$excecao)) {
		if($_SESSION['key_acesso'] != md5(KEY_SESSAO)) {
			// redireciona
			echo '<script type="text/javascript">';
			echo "window.open('/sistema/index.php?me=".codifica(1,true)."&mm=".codifica("Para entrar no sistema, você deve efetuar o login")."','_parent');";
			echo "</script>";
			
			die("Aguarde");
		}
	}
}
?>
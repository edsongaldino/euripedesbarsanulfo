<?php
function conecta_mysql() {
	global $conexao;
	if (!isset($conexao) || !$conexao) {
		$conexao = mysqli_connect(BD_HOST, BD_USUARIO, BD_SENHA, BD_BANCO);
		if ($conexao) {
			mysqli_set_charset($conexao, "utf8");
		}
	}
	return $conexao;
}
?>
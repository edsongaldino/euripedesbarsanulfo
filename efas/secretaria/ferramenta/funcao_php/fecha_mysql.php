<?php
function fecha_mysql($conexao = null) {
	global $conexao;
	if ($conexao) {
		mysqli_close($conexao);
		$conexao = null;
	}
}
?>
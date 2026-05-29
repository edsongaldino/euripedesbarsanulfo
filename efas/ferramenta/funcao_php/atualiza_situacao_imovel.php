<?php

function atualiza_situacao_imovel($situacao_imovel, $codigo_imovel){
global $conexao;

$situacao_imovel = mysqli_real_escape_string($conexao, $situacao_imovel);
$codigo_imovel = mysqli_real_escape_string($conexao, $codigo_imovel);

// Altera situação imóvel
$sql_alterar_imovel = "UPDATE imovel SET situacao_imovel = '".$situacao_imovel."' WHERE codigo_imovel = ".$codigo_imovel."";
$query_alterar_imovel = mysqli_query($conexao, $sql_alterar_imovel);
return $situacao_imovel;


}

?>
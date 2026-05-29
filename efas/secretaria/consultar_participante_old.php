<?php include_once("sistema_mod_include.php"); ?>
<?php
 
$nome_participante = $_POST['nome_participante'];
$conexao = conecta_mysql();
$sql_participante = "SELECT nome_participante FROM participante WHERE nome_participante LIKE '%".$nome_participante."%' LIMIT 1";
$query_participante = mysqli_query($conexao,$sql_participante);
$resultado_participante = mysqli_fetch_assoc($query_participante);
 
$dados['nome_participante_cracha'] = (string) $resultado_participante["nome_participante_cracha"];
$dados['data_nascimento_participante']     = (string) $resultado_participante["data_nascimento_participante"];
$dados['telefone_participante']  = (string) $resultado_participante["numero_telefone_participante"];
$dados['email_participante']  = (string) $resultado_participante["descricao_email_participante"];
$dados['centro_espirita_participante']  = (string) $resultado_participante["centro_espirita_participante"];
 
echo json_encode($dados);

fecha_mysql($conexao);
?>
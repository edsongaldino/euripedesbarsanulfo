<?php
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SCRIPT_NAME'] = '/efas/secretaria/test_query.php';

require_once("ferramenta/configuracoes.php");

session_start();
$_SESSION['key_acesso'] = md5(KEY_SESSAO);

require_once("sistema_mod_include.php");

$nome = "edson galdino";
$db = conecta_mysql();

if (!$db) {
    echo "Erro de conexão MySQL: " . mysqli_connect_error() . "\n";
    exit;
}

$nome_esc = mysqli_real_escape_string($db, $nome);
$nome_double = mysqli_real_escape_string($db, utf8_encode($nome_esc));

$sql = "SELECT 
            participante.nome_participante, participante.nome_participante_cracha, participante.data_nascimento_participante, participante.centro_espirita_participante,
            email_participante.descricao_email_participante, telefone_participante.numero_telefone_participante
            FROM participante 
            LEFT JOIN email_participante ON participante.codigo_participante = email_participante.codigo_participante
            LEFT JOIN telefone_participante ON participante.codigo_participante = telefone_participante.codigo_participante
            WHERE (participante.nome_participante LIKE '%".$nome_esc."%' OR participante.nome_participante LIKE '%".$nome_double."%')
            LIMIT 5";

echo "SQL Query: " . $sql . "\n\n";

$query = mysqli_query($db, $sql);
if (!$query) {
    echo "Erro na Query: " . mysqli_error($db) . "\n";
} else {
    echo "Número de linhas encontradas: " . mysqli_num_rows($query) . "\n";
    while ($row = mysqli_fetch_assoc($query)) {
        print_r($row);
    }
}
?>

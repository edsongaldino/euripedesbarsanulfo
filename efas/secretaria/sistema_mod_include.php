<?php
session_start();

require_once("ferramenta/configuracoes.php");
require_once("ferramenta/funcao_php.php");

verifica_sessao();

if (isset($_GET['evento'])) {
    $codigo_evento = (int)$_GET['evento'];
    $_SESSION["codigo_evento_acesso"] = $codigo_evento;

    if (isset($_SESSION["codigo_usuario_acesso"])) {
        $codigo_usuario = (int)$_SESSION["codigo_usuario_acesso"];
        $conexao_evt = conecta_mysql();
        if ($conexao_evt) {
            $sql_user_update = "UPDATE usuario SET codigo_evento = $codigo_evento WHERE codigo_usuario = $codigo_usuario";
            mysqli_query($conexao_evt, $sql_user_update);
        }
    }
}

ini_set('display_errors', 0);

function fix_double_utf8($str) {
    if ($str === null || $str === '') {
        return $str;
    }
    if (mb_check_encoding($str, 'UTF-8')) {
        $decoded = utf8_decode($str);
        if (mb_check_encoding($decoded, 'UTF-8') && $decoded !== $str && preg_match('//u', $decoded)) {
            return $decoded;
        }
    }
    return $str;
}
?>
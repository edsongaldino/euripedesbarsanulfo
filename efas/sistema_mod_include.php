<?php
session_start();
require_once("ferramenta/configuracoes.php");
require_once("ferramenta/funcao_php.php");

if (isset($_GET['evento'])) {
    $_SESSION['codigo_evento_inscricao'] = (int)$_GET['evento'];
}

if (!isset($_SESSION['codigo_evento_inscricao'])) {
    $conexao_evt = conecta_mysql();
    if ($conexao_evt) {
        $res_evt = mysqli_query($conexao_evt, "SELECT codigo_evento FROM evento ORDER BY codigo_evento DESC LIMIT 1");
        if ($res_evt && $row_evt = mysqli_fetch_row($res_evt)) {
            $_SESSION['codigo_evento_inscricao'] = (int)$row_evt[0];
        } else {
            $_SESSION['codigo_evento_inscricao'] = 11;
        }
    } else {
        $_SESSION['codigo_evento_inscricao'] = 11;
    }
}

if (!defined('CODIGO_EVENTO_ATIVO')) {
    define('CODIGO_EVENTO_ATIVO', $_SESSION['codigo_evento_inscricao']);
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
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

// Bloquear inscrições online se estiver em produção (MODO_LOCAL = false) e as inscrições online estiverem fechadas
if (!defined('MODO_LOCAL') || !MODO_LOCAL) {
    if (defined('INSCRICOES_ONLINE_ABERTAS') && !INSCRICOES_ONLINE_ABERTAS) {
        $current_page = basename($_SERVER['PHP_SELF']);
        $blocked_pages = [
            'inscricao_adulto.php',
            'inscricao_crianca.php',
            'inscricao_jovem.php',
            'inscricao_trabalhador.php',
            'grava_inscricao.php'
        ];
        if (in_array($current_page, $blocked_pages)) {
            redireciona("inscricao.php?me=" . campo_form_codifica(1, true) . "&mm=" . campo_form_codifica("As inscrições online estão encerradas. Novas inscrições somente presencial no dia do evento."));
            exit;
        }
    }
}
?>
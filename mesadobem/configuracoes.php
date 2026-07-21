<?php
// Configurações do projeto Mesa do Bem - Reutilizando as da secretaria/evento principal
$config_path = dirname(__DIR__) . '/efas/ferramenta/configuracoes.php';

if (file_exists($config_path)) {
    require_once($config_path);
} else {
    // Fallback se não encontrar
    define("BD_HOST","efasmt.com.br");
    define("BD_USUARIO","efasmtco_camp");
    define("BD_SENHA","vK^~Ks#h1$52");
    define("BD_BANCO","efasmtco_campanha");
    date_default_timezone_set('America/Cuiaba');
}

// Configuração do Evento Ativo no Mesa do Bem (pode ser configurado aqui)
if (!defined('CODIGO_EVENTO_ATIVO')) {
    define('CODIGO_EVENTO_ATIVO', 11); // Evento padrão, mude conforme necessário
}
?>

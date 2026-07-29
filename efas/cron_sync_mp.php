<?php
// cron_sync_mp.php
// Script para rodar via cron job diariamente e sincronizar pagamentos do Mercado Pago com o banco de dados.

ini_set('display_errors', 0);
error_reporting(E_ALL);

// Mudar para o diretório do script para que os includes relativos funcionem
chdir(__DIR__);

// Validar acesso via web por token de segurança
$token = $_GET['token'] ?? '';
$secret = 'efas_secure_sync_2026';
if (php_sapi_name() !== 'cli' && $token !== $secret) {
    http_response_code(403);
    die("Acesso negado.");
}

include("ferramenta/configuracoes.php");
include("ferramenta/funcao_php.php");

$conexao = conecta_mysql();
if (!$conexao) {
    http_response_code(500);
    die("Falha na conexão com o banco de dados.");
}

// Log function
$log_dir = __DIR__ . '/ferramenta/tmp';
$log_file = $log_dir . '/mercadopago_sync.log';

function log_sync($msg) {
    global $log_dir, $log_file;
    $date = date('Y-m-d H:i:s');
    $formatted_msg = "[$date] $msg\n";
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    @file_put_contents($log_file, $formatted_msg, FILE_APPEND);
    echo $formatted_msg . "<br>\n";
}

log_sync("Iniciando sincronização diária de pagamentos...");

$access_token = defined('MP_ACCESS_TOKEN') ? MP_ACCESS_TOKEN : '';
if (empty($access_token) || $access_token === "SEU_ACCESS_TOKEN_AQUI") {
    log_sync("ERRO: MP_ACCESS_TOKEN não configurado.");
    exit;
}

// Consultar os pagamentos recentes no Mercado Pago (últimos 100)
$url = "https://api.mercadopago.com/v1/payments/search?sort=date_created&criteria=desc&limit=100";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $access_token
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    log_sync("ERRO: Falha ao buscar pagamentos do Mercado Pago. HTTP CODE: $http_code | Resposta: $response");
    exit;
}

$data = json_decode($response, true);
$results = $data['results'] ?? [];

log_sync("Encontrados " . count($results) . " pagamentos recentes no Mercado Pago.");

$updated_count = 0;

foreach ($results as $payment) {
    $status = $payment['status'] ?? '';
    $ext_ref = $payment['external_reference'] ?? '';
    
    if ($status === 'approved' && !empty($ext_ref)) {
        $ids = [];
        
        // Parseia os IDs do external_reference
        if (strpos($ext_ref, 'EFAS-MESAS-') === 0) {
            $ids_str = substr($ext_ref, strlen('EFAS-MESAS-'));
            $ids = array_map('intval', explode('-', $ids_str));
            
            if (!empty($ids)) {
                $ids_str_sql = implode(',', $ids);
                
                // Verifica se alguma dessas reservas ainda está com status 1 (Pendente)
                $sql_check = "SELECT codigo_reserva FROM reserva_mesa WHERE codigo_reserva IN ($ids_str_sql) AND codigo_situacao = 1";
                $query_check = mysqli_query($conexao, $sql_check);
                
                $pending_ids = [];
                if ($query_check) {
                    while ($row = mysqli_fetch_assoc($query_check)) {
                        $pending_ids[] = (int)$row['codigo_reserva'];
                    }
                }
                
                if (!empty($pending_ids)) {
                    $pending_str = implode(',', $pending_ids);
                    $sql_update = "UPDATE reserva_mesa SET codigo_situacao = 2 WHERE codigo_reserva IN ($pending_str)";
                    $query_update = mysqli_query($conexao, $sql_update);
                    
                    if ($query_update) {
                        log_sync("SUCESSO: Reservas de mesa atualizadas para Pago (status 2) para os IDs: $pending_str (Pagamento MP ID: " . $payment['id'] . ")");
                        $updated_count += count($pending_ids);
                    } else {
                        log_sync("ERRO: Falha ao atualizar reservas de mesa no banco: $pending_str | Erro: " . mysqli_error($conexao));
                    }
                }
            }
        } elseif (strpos($ext_ref, 'EFAS-MULTI-') === 0) {
            $ids_str = substr($ext_ref, strlen('EFAS-MULTI-'));
            $ids = array_map('intval', explode('-', $ids_str));
            
            if (!empty($ids)) {
                $ids_escaped = array_map('intval', $ids);
                $ids_str_sql = implode(',', $ids_escaped);
                
                // Verifica se alguma dessas inscrições ainda está com status 1 (Pendente)
                $sql_check = "SELECT codigo_inscricao_evento FROM inscricao_evento WHERE codigo_inscricao_evento IN ($ids_str_sql) AND codigo_situacao_inscricao = 1";
                $query_check = mysqli_query($conexao, $sql_check);
                
                $pending_ids = [];
                if ($query_check) {
                    while ($row = mysqli_fetch_assoc($query_check)) {
                        $pending_ids[] = (int)$row['codigo_inscricao_evento'];
                    }
                }
                
                if (!empty($pending_ids)) {
                    $pending_str = implode(',', $pending_ids);
                    $sql_update = "UPDATE inscricao_evento SET codigo_situacao_inscricao = 2 WHERE codigo_inscricao_evento IN ($pending_str)";
                    $query_update = mysqli_query($conexao, $sql_update);
                    
                    if ($query_update) {
                        log_sync("SUCESSO: Inscrições atualizadas para Pago (status 2) para os IDs: $pending_str (Pagamento MP ID: " . $payment['id'] . ")");
                        $updated_count += count($pending_ids);
                    } else {
                        log_sync("ERRO: Falha ao atualizar inscrições no banco: $pending_str | Erro: " . mysqli_error($conexao));
                    }
                }
            }
        } else {
            if (preg_match('/EFAS-(\d+)/', $ext_ref, $matches)) {
                $ids = [(int)$matches[1]];
            } else {
                preg_match_all('/\d+/', $ext_ref, $matches);
                if (!empty($matches[0])) {
                    $ids = array_map('intval', $matches[0]);
                }
            }
            
            if (!empty($ids)) {
                $ids_escaped = array_map('intval', $ids);
                $ids_str_sql = implode(',', $ids_escaped);
                
                $sql_check = "SELECT codigo_inscricao_evento FROM inscricao_evento WHERE codigo_inscricao_evento IN ($ids_str_sql) AND codigo_situacao_inscricao = 1";
                $query_check = mysqli_query($conexao, $sql_check);
                
                $pending_ids = [];
                if ($query_check) {
                    while ($row = mysqli_fetch_assoc($query_check)) {
                        $pending_ids[] = (int)$row['codigo_inscricao_evento'];
                    }
                }
                
                if (!empty($pending_ids)) {
                    $pending_str = implode(',', $pending_ids);
                    $sql_update = "UPDATE inscricao_evento SET codigo_situacao_inscricao = 2 WHERE codigo_inscricao_evento IN ($pending_str)";
                    $query_update = mysqli_query($conexao, $sql_update);
                    
                    if ($query_update) {
                        log_sync("SUCESSO: Inscrições atualizadas para Pago (status 2) para os IDs: $pending_str (Pagamento MP ID: " . $payment['id'] . ")");
                        $updated_count += count($pending_ids);
                    } else {
                        log_sync("ERRO: Falha ao atualizar inscrições no banco: $pending_str | Erro: " . mysqli_error($conexao));
                    }
                }
            }
        }
    }
}

log_sync("Sincronização concluída. Total de inscrições atualizadas: $updated_count");
fecha_mysql($conexao);
?>

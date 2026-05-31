<?php
// Define custom error log or output for debugging
ini_set('display_errors', 0);

include("ferramenta/configuracoes.php");
include("ferramenta/funcao_php.php");
$conexao = conecta_mysql();

// Log webhook request
$log_dir = __DIR__ . '/ferramenta/tmp';
$log_file = $log_dir . '/mercadopago_webhook.log';

function log_msg($msg) {
    global $log_dir, $log_file;
    $date = date('Y-m-d H:i:s');
    $formatted_msg = "[$date] $msg\n";
    
    // Ensure dir exists
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    
    // Try to write to local log file first, fallback to PHP system error log
    if (!is_writable($log_dir) || @file_put_contents($log_file, $formatted_msg, FILE_APPEND) === false) {
        error_log("MercadoPago Webhook: " . trim($msg));
    }
}

$raw_payload = file_get_contents('php://input');
log_msg("Payload received: " . ($raw_payload ?: 'empty') . " | GET: " . json_encode($_GET));

// Identificar o ID do pagamento
$payment_id = null;

// Webhook / IPN (Prioritize JSON POST body payload details)
$data = json_decode($raw_payload, true);
if (isset($data['data']['id'])) {
    $payment_id = $data['data']['id'];
} elseif (isset($data['id'])) {
    $payment_id = $data['id'];
} elseif (isset($_GET['data_id'])) {
    $payment_id = $_GET['data_id'];
} elseif (isset($_GET['id'])) {
    $payment_id = $_GET['id'];
}

if (!$payment_id) {
    log_msg("ERROR: Payment ID not found in payload.");
    http_response_code(400);
    echo "Payment ID not found.";
    exit;
}

// Consultar o pagamento na API do Mercado Pago
$access_token = defined('MP_ACCESS_TOKEN') ? MP_ACCESS_TOKEN : '';
if (empty($access_token) || $access_token === "SEU_ACCESS_TOKEN_AQUI") {
    log_msg("ERROR: MP_ACCESS_TOKEN not configured.");
    http_response_code(500);
    echo "Mercado Pago Access Token not configured.";
    exit;
}

$url = "https://api.mercadopago.com/v1/payments/" . $payment_id;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $access_token
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Evita hangs e 502/504 se a API do MP demorar a responder

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    log_msg("ERROR: Failed to fetch payment details from MP. HTTP CODE: $http_code | Response: $response");
    
    // Se for um erro do cliente (ex: 404 Not Found), retornar 202 para o MP parar de tentar enviar
    if ($http_code >= 400 && $http_code < 500) {
        http_response_code(202);
        echo "Accepted but could not process (client error).";
    } else {
        http_response_code(500);
        echo "Failed to fetch payment details.";
    }
    exit;
}

$payment = json_decode($response, true);
log_msg("INFO: Payment status: " . ($payment['status'] ?? 'unknown') . " | Ref: " . ($payment['external_reference'] ?? 'none'));

if (isset($payment['status']) && $payment['status'] === 'approved' && isset($payment['external_reference'])) {
    $ext_ref = $payment['external_reference'];
    $ids = [];
    
    // Parseia os IDs do external_reference
    if (strpos($ext_ref, 'EFAS-MULTI-') === 0) {
        $ids_str = substr($ext_ref, strlen('EFAS-MULTI-'));
        $ids = array_map('intval', explode('-', $ids_str));
    } else {
        // Fallback antigo ou id unico
        if (preg_match('/EFAS-(\d+)/', $ext_ref, $matches)) {
            $ids = [(int)$matches[1]];
        } else {
            preg_match_all('/\d+/', $ext_ref, $matches);
            if (!empty($matches[0])) {
                $ids = array_map('intval', $matches[0]);
            }
        }
    }
    
    if (!empty($ids)) {
        // Atualiza a situação das inscrições no banco de dados para 2 (Pago)
        $ids_escaped = array_map('intval', $ids);
        $ids_str = implode(',', $ids_escaped);
        
        if ($conexao) {
            $sql_update = "UPDATE inscricao_evento SET codigo_situacao_inscricao = 2 WHERE codigo_inscricao_evento IN ($ids_str)";
            $query_update = mysqli_query($conexao, $sql_update);
            
            if ($query_update) {
                log_msg("SUCCESS: Updated status to 2 for IDs: $ids_str");
            } else {
                log_msg("ERROR: Database update failed for IDs: $ids_str | Error: " . mysqli_error($conexao));
            }
        } else {
            log_msg("ERROR: Database connection was not established. Cannot update IDs: $ids_str");
        }
    } else {
        log_msg("WARNING: No valid IDs parsed from external_reference: $ext_ref");
    }
}

fecha_mysql($conexao);
http_response_code(200);
echo "OK";
?>

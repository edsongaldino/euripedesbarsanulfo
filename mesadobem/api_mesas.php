<?php
// API endpoint for Table Reservations (Mesa do Bem)
header('Content-Type: application/json; charset=utf-8');
require_once("db.php");

$conexao = conecta_mysql();
if (!$conexao) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados.']);
    exit;
}

$codigo_evento = (int)CODIGO_EVENTO_ATIVO;
$valor_mesa = 100.00; // Preço padrão de cada mesa (pode ser alterado aqui)

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Retorna as mesas reservadas (tanto pendentes quanto pagas)
    $sql = "SELECT numero_mesa, codigo_situacao FROM reserva_mesa WHERE codigo_evento = '$codigo_evento' AND codigo_situacao IN (1, 2)";
    $result = mysqli_query($conexao, $sql);
    
    $mesas = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mesas[] = [
                'numero' => (int)$row['numero_mesa'],
                'situacao' => (int)$row['codigo_situacao']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'codigo_evento' => $codigo_evento,
        'valor_mesa' => $valor_mesa,
        'mesas' => $mesas
    ]);
    exit;
}

if ($method === 'POST') {
    // Processa a reserva e gera o link de pagamento do Mercado Pago
    $raw_input = file_get_contents('php://input');
    $input = json_decode($raw_input, true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $nome = protege_campo($input['nome'] ?? '');
    $email = protege_campo($input['email'] ?? '');
    $telefone = protege_campo($input['telefone'] ?? '');
    $mesas_selecionadas = $input['mesas'] ?? [];
    
    if (empty($nome) || empty($email) || empty($telefone)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos de cadastro são obrigatórios.']);
        exit;
    }
    
    if (empty($mesas_selecionadas) || !is_array($mesas_selecionadas)) {
        echo json_encode(['success' => false, 'message' => 'Você precisa selecionar pelo menos uma mesa.']);
        exit;
    }
    
    // Validar se as mesas já estão reservadas
    $mesas_ids_str = implode(',', array_map('intval', $mesas_selecionadas));
    $sql_check = "SELECT numero_mesa FROM reserva_mesa WHERE codigo_evento = '$codigo_evento' AND numero_mesa IN ($mesas_ids_str) AND codigo_situacao IN (1, 2)";
    $result_check = mysqli_query($conexao, $sql_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $mesas_ocupadas = [];
        while ($row = mysqli_fetch_assoc($result_check)) {
            $mesas_ocupadas[] = $row['numero_mesa'];
        }
        echo json_encode([
            'success' => false,
            'message' => 'As seguintes mesas já foram reservadas ou estão pendentes de pagamento: ' . implode(', ', $mesas_ocupadas)
        ]);
        exit;
    }
    
    // Iniciar transação
    mysqli_query($conexao, "BEGIN");
    
    $reserva_ids = [];
    $erro = false;
    
    foreach ($mesas_selecionadas as $num_mesa) {
        $num_mesa = (int)$num_mesa;
        
        // Remove qualquer reserva anterior cancelada para esta mesa para evitar erro de UNIQUE KEY
        $sql_delete_cancelled = "DELETE FROM reserva_mesa WHERE codigo_evento = '$codigo_evento' AND numero_mesa = '$num_mesa' AND codigo_situacao = 3";
        mysqli_query($conexao, $sql_delete_cancelled);
        
        $sql_insert = "INSERT INTO reserva_mesa (codigo_evento, numero_mesa, nome_participante, email_participante, telefone_participante, valor_reserva, codigo_situacao) 
                       VALUES ('$codigo_evento', '$num_mesa', '$nome', '$email', '$telefone', '$valor_mesa', 1)";
        
        if (mysqli_query($conexao, $sql_insert)) {
            $reserva_ids[] = mysqli_insert_id($conexao);
        } else {
            $erro = true;
            break;
        }
    }
    
    if ($erro || empty($reserva_ids)) {
        mysqli_query($conexao, "ROLLBACK");
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar as reservas. Tente novamente.']);
        exit;
    }
    
    // Obter o Access Token configurado nas constantes
    $access_token = defined('MP_ACCESS_TOKEN') ? MP_ACCESS_TOKEN : '';
    if (empty($access_token) || $access_token === "SEU_ACCESS_TOKEN_AQUI") {
        // Fallback local para testes
        mysqli_query($conexao, "COMMIT");
        echo json_encode([
            'success' => true,
            'message' => 'Reserva criada (Modo de Testes - Token do Mercado Pago não configurado).',
            'checkout_url' => 'index.php?status=success&ref=EFAS-MESAS-' . implode('-', $reserva_ids)
        ]);
        exit;
    }
    
    // Determinar url de retorno
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $is_local = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
    $back_domain = $is_local ? "https://efas.euripedesbarsanulfo.org.br" : "https://" . $host;
    $script_path = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir_path = dirname($script_path);
    $dir_path = str_replace('\\', '/', $dir_path);
    if ($dir_path === '/' || $dir_path === '\\') {
        $dir_path = '';
    }
    $base_url = $back_domain . $dir_path;
    
    $items = [];
    foreach ($mesas_selecionadas as $num_mesa) {
        $items[] = [
            "title" => "Reserva de Mesa #" . $num_mesa . " - Mesa do Bem",
            "quantity" => 1,
            "currency_id" => "BRL",
            "unit_price" => (float)$valor_mesa
        ];
    }
    
    $external_reference = "EFAS-MESAS-" . implode("-", $reserva_ids);
    
    $mp_payload = [
        "items" => $items,
        "external_reference" => $external_reference,
        "back_urls" => [
            "success" => $base_url . "/index.php?status=success&ref=" . $external_reference,
            "failure" => $base_url . "/index.php?status=failure",
            "pending" => $base_url . "/index.php?status=pending"
        ],
        "notification_url" => $back_domain . "/efas/retorno_mp.php",
        "auto_return" => "approved"
    ];
    
    $ch = curl_init("https://api.mercadopago.com/checkout/preferences");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $access_token,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mp_payload));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200 || $http_code === 201) {
        $mp_data = json_decode($response, true);
        $checkout_url = $mp_data['init_point'] ?? '';
        $preference_id = $mp_data['id'] ?? '';
        
        $reserva_ids_str = implode(',', $reserva_ids);
        $sql_update_preference = "UPDATE reserva_mesa SET mp_preference_id = '$preference_id' WHERE codigo_reserva IN ($reserva_ids_str)";
        mysqli_query($conexao, $sql_update_preference);
        
        mysqli_query($conexao, "COMMIT");
        
        echo json_encode([
            'success' => true,
            'checkout_url' => $checkout_url
        ]);
    } else {
        mysqli_query($conexao, "ROLLBACK");
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao gerar pagamento no Mercado Pago. Código HTTP: ' . $http_code
        ]);
    }
}

fecha_mysql($conexao);
?>

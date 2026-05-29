<?php
function gerar_pagamento_mp($itens_ou_id, $nome_participante = null, $valor_inscricao = null) {
    // URL da API de Preferências do Mercado Pago
    $url = "https://api.mercadopago.com/checkout/preferences";
    
    // Obter o Access Token configurado nas constantes
    $access_token = defined('MP_ACCESS_TOKEN') ? MP_ACCESS_TOKEN : '';
    
    if (empty($access_token) || $access_token === "SEU_ACCESS_TOKEN_AQUI" || empty($itens_ou_id)) {
        return false;
    }

    // Se o domínio for local, o Mercado Pago rejeita auto_return com URLs locais.
    // Usamos um domínio público de fallback para permitir gerar a preferência em testes locais.
    $back_domain = DOMINIO;
    $is_local = (
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false ||
        strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false ||
        strpos($_SERVER['HTTP_HOST'] ?? '', '.test') !== false
    );
    if ($is_local) {
        $back_domain = "https://efas.euripedesbarsanulfo.org.br";
    }

    // Normalizar itens para suportar tanto array de múltiplos itens quanto parâmetros estáticos legados
    $itens_inscricao = [];
    if (is_array($itens_ou_id)) {
        $itens_inscricao = $itens_ou_id;
    } else {
        $itens_inscricao = [
            [
                'codigo_inscricao' => $itens_ou_id,
                'nome_participante' => $nome_participante,
                'valor_inscricao' => $valor_inscricao
            ]
        ];
    }

    // Montar os itens dinamicamente
    $items = [];
    $consolidated_ids = [];
    $last_id = null;
    
    foreach ($itens_inscricao as $item) {
        $items[] = [
            "id" => (string)$item['codigo_inscricao'],
            "title" => "Inscrição EFAS - " . $item['nome_participante'],
            "quantity" => 1,
            "currency_id" => "BRL",
            "unit_price" => (float)$item['valor_inscricao']
        ];
        $consolidated_ids[] = $item['codigo_inscricao'];
        $last_id = $item['codigo_inscricao'];
    }
    
    $external_reference = "EFAS-MULTI-" . implode("-", $consolidated_ids);

    // Montar o corpo da requisição JSON
    $body = [
        "items" => $items,
        "external_reference" => $external_reference,
        "back_urls" => [
            "success" => $back_domain . "/confirma_inscricao.php?clear_cart=1&codigo_inscricao_evento=" . campo_form_codifica($last_id, true) . "&tipo=" . campo_form_codifica(2, true) . "&me=" . campo_form_codifica(0, true) . "&mm=" . campo_form_codifica("Pagamento realizado com sucesso!"),
            "failure" => $back_domain . "/confirma_inscricao.php?codigo_inscricao_evento=" . campo_form_codifica($last_id, true) . "&tipo=" . campo_form_codifica(2, true) . "&me=" . campo_form_codifica(1, true) . "&mm=" . campo_form_codifica("Ocorreu um erro no pagamento. Tente novamente!"),
            "pending" => $back_domain . "/confirma_inscricao.php?codigo_inscricao_evento=" . campo_form_codifica($last_id, true) . "&tipo=" . campo_form_codifica(2, true) . "&me=" . campo_form_codifica(0, true) . "&mm=" . campo_form_codifica("Pagamento pendente de aprovação.")
        ],
        "notification_url" => $back_domain . "/retorno_mp.php",
        "auto_return" => "approved"
    ];
    
    $json_body = json_encode($body);
    
    // Iniciar a chamada cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $access_token,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_body);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200 || $http_code === 201) {
        $result = json_decode($response, true);
        // Retorna o link de pagamento (init_point)
        return $result['init_point'] ?? false;
    }
    
    return false;
}
?>

<?php
include "sistema_mod_include.php";

$status = $_GET['status'] ?? '';
$ref = $_GET['ref'] ?? '';
$msg_alerta = '';
$alert_class = '';

if ($status === 'success') {
    $msg_alerta = "Sua reserva foi registrada com sucesso! Assim que o pagamento for confirmado, suas mesas estarão garantidas.";
    $alert_class = "alert-success";
} elseif ($status === 'failure') {
    $msg_alerta = "Ocorreu um erro ou o pagamento foi cancelado. Tente fazer a reserva novamente.";
    $alert_class = "alert-danger";
} elseif ($status === 'pending') {
    $msg_alerta = "Seu pagamento está em análise. Enviaremos uma confirmação assim que for aprovado.";
    $alert_class = "alert-warning";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Mesas - Evento Beneficente</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Estilos -->
    <style>
        :root {
            --primary: #028090;
            --primary-hover: #02c39a;
            --accent: #f77f00;
            --bg-dark: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            
            /* Status Colors */
            --color-avail: #2ec4b6;
            --color-sold: #e63946;
            --color-sel: #ffb703;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at 50% 50%, #1e293b 0%, var(--bg-dark) 100%);
            min-height: 100vh;
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        header p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
            display: flex;
            gap: 25px;
            flex: 1;
        }

        /* Layout Principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Sidebar de Cadastro e Pagamento */
        .sidebar {
            width: 380px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            height: fit-content;
            position: sticky;
            top: 110px;
        }

        /* Mapa do Salão */
        .map-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .map-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }

        .map-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .dimensions {
            font-size: 0.85rem;
            color: var(--text-muted);
            background: rgba(255,255,255,0.05);
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Container do Grid - Habilita Scroll Lateral no Mobile */
        .grid-container {
            width: 100%;
            overflow-x: auto;
            padding: 10px 0;
            display: flex;
            justify-content: center;
        }

        /* Grid do Salão */
        .salon-grid {
            display: grid;
            grid-template-columns: repeat(16, 54px);
            grid-template-rows: repeat(7, 54px);
            gap: 12px;
            position: relative;
            background: rgba(255,255,255,0.02);
            padding: 20px;
            border-radius: 12px;
            border: 1px dashed rgba(255,255,255,0.05);
            min-width: fit-content;
        }

        /* Paço Central Overlay */
        .paco-central {
            grid-column: 7 / span 4;
            grid-row: 3 / span 3;
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.75rem;
            padding: 5px;
            pointer-events: none;
        }

        .paco-central strong {
            color: var(--text-light);
            font-size: 0.8rem;
            margin-bottom: 2px;
        }

        /* Elemento Mesa */
        .table-item {
            width: 54px;
            height: 54px;
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #ffffff;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }

        /* Estilização que imita a imagem das mesas */
        .table-item::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-sizing: border-box;
            z-index: 1;
        }

        /* Mini cadeirinhas ao redor da mesa */
        .table-item::after {
            content: '';
            position: absolute;
            top: -3px; left: -3px; right: -3px; bottom: -3px;
            border: 2px solid transparent;
            border-radius: 10px;
            opacity: 0.4;
            transition: all 0.25s ease;
        }

        /* Estado: Disponível */
        .table-item.available {
            background: var(--color-avail);
            box-shadow: 0 0 10px rgba(46, 196, 182, 0.2);
        }
        .table-item.available:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(46, 196, 182, 0.5);
            z-index: 5;
        }

        /* Estado: Selecionada */
        .table-item.selected {
            background: var(--color-sel);
            color: #000000;
            box-shadow: 0 0 12px rgba(255, 183, 3, 0.5);
            transform: scale(1.05);
        }
        .table-item.selected::before {
            border-color: rgba(0, 0, 0, 0.2);
        }

        /* Estado: Ocupada/Vendida */
        .table-item.sold {
            background: var(--color-sold);
            opacity: 0.7;
            cursor: not-allowed;
            box-shadow: none;
        }
        .table-item.sold:hover {
            transform: none;
        }

        /* Legendas */
        .legend-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
            margin-top: 20px;
            background: rgba(255,255,255,0.02);
            padding: 12px 25px;
            border-radius: 12px;
            width: 100%;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-color.avail { background: var(--color-avail); }
        .legend-color.sold { background: var(--color-sold); }
        .legend-color.sel { background: var(--color-sel); }

        /* Alertas */
        .alert {
            padding: 15px;
            border-radius: 12px;
            font-size: 0.95rem;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(46, 196, 182, 0.15);
            border-color: var(--color-avail);
            color: var(--color-avail);
        }

        .alert-danger {
            background: rgba(230, 57, 70, 0.15);
            border-color: var(--color-sold);
            color: var(--color-sold);
        }

        .alert-warning {
            background: rgba(255, 183, 3, 0.15);
            border-color: var(--color-sel);
            color: var(--color-sel);
        }

        /* Sidebar Formulário e Passos */
        .step-section {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .step-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .step-num {
            background: var(--primary);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            padding: 10px 14px;
            border-radius: 10px;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-hover);
        }

        /* Lista de Mesas Selecionadas */
        .selected-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 10px;
            padding-right: 5px;
        }

        .selected-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .selected-item .remove-btn {
            background: none;
            border: none;
            color: var(--color-sold);
            cursor: pointer;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .empty-selected-msg {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-align: center;
            padding: 15px 0;
            border: 1px dashed var(--border-color);
            border-radius: 10px;
        }

        /* Resumo e Pagamento */
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        .summary-total {
            border-top: 1px solid var(--border-color);
            padding-top: 12px;
            margin-top: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
        }

        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
        }

        .payment-radio {
            display: none;
        }

        .payment-label {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.01);
        }

        .payment-radio:checked + .payment-label {
            border-color: var(--primary-hover);
            background: rgba(2, 195, 154, 0.1);
            color: #ffffff;
        }

        .pay-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, #00a896 100%);
            border: none;
            color: #ffffff;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(2, 128, 144, 0.3);
        }

        .pay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(2, 128, 144, 0.5);
            background: linear-gradient(135deg, var(--primary-hover) 0%, #02c39a 100%);
        }

        .pay-btn:disabled {
            background: #475569;
            color: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Prompt de Scroll para Mobile */
        .scroll-prompt {
            display: none;
            font-size: 0.8rem;
            color: var(--primary-hover);
            margin-bottom: 10px;
            text-align: center;
            animation: pulse 1.5s infinite alternate;
        }

        @keyframes pulse {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: static;
            }
            .scroll-prompt {
                display: block;
            }
            .grid-container {
                justify-content: flex-start;
            }
        }

        @media (max-width: 600px) {
            header h1 {
                font-size: 1.4rem;
            }
            .map-card {
                padding: 15px;
            }
            .salon-grid {
                padding: 10px;
                gap: 8px;
                grid-template-columns: repeat(16, 46px);
                grid-template-rows: repeat(7, 46px);
            }
            .table-item {
                width: 46px;
                height: 46px;
                font-size: 0.85rem;
            }
            .paco-central {
                font-size: 0.65rem;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>RESERVA DE MESAS - EVENTO BENEFICENTE</h1>
        <p>Escolha suas mesas no mapa abaixo, faça seu cadastro e finalize o pagamento</p>
    </header>

    <div class="container">
        
        <div class="main-content">
            
            <?php if (!empty($msg_alerta)): ?>
                <div class="alert <?php echo $alert_class; ?>">
                    <?php echo htmlspecialchars($msg_alerta); ?>
                </div>
            <?php endif; ?>

            <div class="map-card">
                <div class="map-header">
                    <span class="map-title">Disposição das Mesas</span>
                    <span class="dimensions">Área Total: 450,00 m² (30m x 15m)</span>
                </div>

                <div class="scroll-prompt">
                    &larr; Deslize para as laterais para ver todas as mesas &rarr;
                </div>

                <div class="grid-container">
                    <div class="salon-grid" id="salonGrid">
                        <!-- Gerado via JS ou renderizado dinamicamente -->
                    </div>
                </div>

                <div class="legend-container">
                    <div class="legend-item">
                        <div class="legend-color avail"></div>
                        <span>Disponível (R$ 100,00)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color sel"></div>
                        <span>Selecionada</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color sold"></div>
                        <span>Reservada / Indisponível</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <!-- Passo 1 -->
            <div class="step-section">
                <div class="step-title">
                    <span class="step-num">1</span>
                    <span>Cadastro Simples</span>
                </div>
                <div class="form-group">
                    <label for="nome_comprador">Nome Completo</label>
                    <input type="text" id="nome_comprador" class="form-control" placeholder="Seu nome">
                </div>
                <div class="form-group">
                    <label for="email_comprador">E-mail</label>
                    <input type="email" id="email_comprador" class="form-control" placeholder="seu@email.com">
                </div>
                <div class="form-group">
                    <label for="telefone_comprador">Celular / WhatsApp</label>
                    <input type="text" id="telefone_comprador" class="form-control" placeholder="(00) 99999-9999">
                </div>
            </div>

            <!-- Passo 2 -->
            <div class="step-section">
                <div class="step-title">
                    <span class="step-num">2</span>
                    <span>Mesas Selecionadas</span>
                </div>
                <div class="selected-list" id="selectedList">
                    <div class="empty-selected-msg">
                        Clique nas mesas desejadas no mapa para selecioná-las
                    </div>
                </div>
            </div>

            <!-- Passo 3 -->
            <div class="step-section">
                <div class="step-title">
                    <span class="step-num">3</span>
                    <span>Revisão e Pagamento</span>
                </div>
                
                <div class="summary-row">
                    <span>Mesas Selecionadas:</span>
                    <span id="summaryCount">0</span>
                </div>
                <div class="summary-row">
                    <span>Preço por Mesa:</span>
                    <span>R$ 100,00</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total a Pagar:</span>
                    <span id="summaryTotal">R$ 0,00</span>
                </div>

                <div class="payment-options">
                    <div>
                        <input type="radio" name="payment_method" id="pay_pix" class="payment-radio" checked>
                        <label for="pay_pix" class="payment-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 12h3v8h14v-8h3L12 2z"/></svg>
                            PIX QR Code
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="payment_method" id="pay_card" class="payment-radio">
                        <label for="pay_card" class="payment-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            Cartão de Crédito
                        </label>
                    </div>
                </div>

                <button class="pay-btn" id="btnPagar" disabled>FINALIZAR RESERVA E PAGAR</button>
            </div>
        </div>

    </div>

    <!-- JS Logic -->
    <script>
        const API_URL = 'api_mesas.php';
        let mesasReservadas = [];
        let mesasSelecionadas = [];
        let valorMesa = 100.00;

        // Máscara de telefone simples
        document.getElementById('telefone_comprador').addEventListener('input', function (e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });

        // Carrega o status das mesas do servidor
        async function carregarMesas() {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();
                
                if (data.success) {
                    mesasReservadas = data.mesas;
                    valorMesa = data.valor_mesa;
                    renderizarGrid();
                } else {
                    alert('Erro ao carregar o mapa de mesas.');
                }
            } catch (error) {
                console.error(error);
                alert('Erro na comunicação com o servidor.');
            }
        }

        // Renderiza o grid de mesas no HTML
        function renderizarGrid() {
            const grid = document.getElementById('salonGrid');
            grid.innerHTML = '';

            let mesaContador = 1;

            // Loop para as 7 linhas e 16 colunas
            for (let row = 1; row <= 7; row++) {
                for (let col = 1; col <= 16; col++) {
                    // Verifica se a célula atual está dentro do Paço Central
                    // Paço Central: linhas 3, 4, 5 e colunas 7, 8, 9, 10
                    if (row >= 3 && row <= 5 && col >= 7 && col <= 10) {
                        // Renderiza o bloco central apenas uma vez na célula inicial dele
                        if (row === 3 && col === 7) {
                            const paco = document.createElement('div');
                            paco.className = 'paco-central';
                            paco.innerHTML = '<strong>PAÇO CENTRAL</strong><span>10,00 m²<br>(3,16m x 3,16m)</span>';
                            grid.appendChild(paco);
                        }
                        continue;
                    }

                    // Se não for Paço Central, é uma mesa
                    const mesaNum = mesaContador++;
                    const mesaObj = mesasReservadas.find(m => m.numero === mesaNum);
                    
                    const mesaElement = document.createElement('div');
                    mesaElement.className = 'table-item';
                    mesaElement.dataset.numero = mesaNum;
                    mesaElement.innerText = mesaNum;

                    if (mesaObj) {
                        // Reservada ou Paga
                        mesaElement.classList.add('sold');
                    } else {
                        // Disponível
                        mesaElement.classList.add('available');
                        
                        // Clique para selecionar
                        mesaElement.addEventListener('click', () => toggleMesa(mesaNum));
                    }

                    grid.appendChild(mesaElement);
                }
            }
        }

        // Seleciona / Deseleciona uma mesa
        function toggleMesa(numero) {
            const index = mesasSelecionadas.indexOf(numero);
            const element = document.querySelector(`.table-item[data-numero="${numero}"]`);

            if (index > -1) {
                // Remove da seleção
                mesasSelecionadas.splice(index, 1);
                element.classList.remove('selected');
            } else {
                // Adiciona à seleção
                mesasSelecionadas.push(numero);
                element.classList.add('selected');
            }

            atualizarPainelLateral();
        }

        // Atualiza a barra lateral com as mesas e totais
        function atualizarPainelLateral() {
            const listContainer = document.getElementById('selectedList');
            const summaryCount = document.getElementById('summaryCount');
            const summaryTotal = document.getElementById('summaryTotal');
            const btnPagar = document.getElementById('btnPagar');

            if (mesasSelecionadas.length === 0) {
                listContainer.innerHTML = `
                    <div class="empty-selected-msg">
                        Clique nas mesas desejadas no mapa para selecioná-las
                    </div>
                `;
                summaryCount.innerText = '0';
                summaryTotal.innerText = 'R$ 0,00';
                btnPagar.disabled = true;
                return;
            }

            // Renderiza os cards das selecionadas
            listContainer.innerHTML = '';
            mesasSelecionadas.forEach(num => {
                const item = document.createElement('div');
                item.className = 'selected-item';
                item.innerHTML = `
                    <span>Mesa #${num}</span>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span>R$ ${valorMesa.toFixed(2).replace('.', ',')}</span>
                        <button class="remove-btn" onclick="toggleMesa(${num})">&times;</button>
                    </div>
                `;
                listContainer.appendChild(item);
            });

            // Totais
            const total = mesasSelecionadas.length * valorMesa;
            summaryCount.innerText = mesasSelecionadas.length;
            summaryTotal.innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
            btnPagar.disabled = false;
        }

        // Envia o formulário e redireciona ao Mercado Pago
        document.getElementById('btnPagar').addEventListener('click', async () => {
            const nome = document.getElementById('nome_comprador').value.trim();
            const email = document.getElementById('email_comprador').value.trim();
            const telefone = document.getElementById('telefone_comprador').value.trim();

            if (!nome || !email || !telefone) {
                alert('Por favor, preencha todos os campos do Passo 1 (Cadastro Simples).');
                return;
            }

            const btn = document.getElementById('btnPagar');
            btn.disabled = true;
            btn.innerText = 'PROCESSANDO...';

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type: application/json' },
                    body: JSON.stringify({
                        nome,
                        email,
                        telefone,
                        mesas: mesasSelecionadas
                    })
                });

                const result = await response.json();

                if (result.success && result.checkout_url) {
                    // Redireciona para o link de checkout do Mercado Pago
                    window.location.href = result.checkout_url;
                } else {
                    alert(result.message || 'Ocorreu um erro ao processar a reserva.');
                    btn.disabled = false;
                    btn.innerText = 'FINALIZAR RESERVA E PAGAR';
                }
            } catch (error) {
                console.error(error);
                alert('Erro na comunicação com o servidor. Tente novamente.');
                btn.disabled = false;
                btn.innerText = 'FINALIZAR RESERVA E PAGAR';
            }
        });

        // Inicialização
        carregarMesas();
    </script>
</body>
</html>

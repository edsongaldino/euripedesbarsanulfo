<?php
session_start();
require_once("db.php");

$status = $_GET['status'] ?? '';
$ref = $_GET['ref'] ?? '';
$msg_alerta = '';
$alert_class = '';

// Processa login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao_login'] ?? '') === 'entrar') {
    $email = protege_campo($_POST['email_usuario'] ?? '');
    $senha_bruta = $_POST['senha_usuario'] ?? '';
    
    // Usuário padrão
    if ($email === 'secretaria' && $senha_bruta === 'festajunina26@') {
        $_SESSION["key_acesso"] = md5(KEY_SESSAO);
        $_SESSION["email_usuario_acesso"] = 'secretaria';
        $_SESSION["nome_usuario_acesso"] = 'Secretaria';
        header("Location: admin.php");
        exit;
    }
    
    $senha = md5(protege_campo($senha_bruta));
    
    $conexao = conecta_mysql();
    if ($conexao) {
        $sql = "SELECT usuario.codigo_usuario, usuario.email_usuario, participante.nome_participante 
                FROM usuario 
                JOIN participante ON usuario.codigo_participante = participante.codigo_participante 
                WHERE usuario.email_usuario = '$email' AND usuario.senha_usuario = '$senha' LIMIT 1";
        $result = mysqli_query($conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["key_acesso"] = md5(KEY_SESSAO);
            $_SESSION["email_usuario_acesso"] = $row["email_usuario"];
            $_SESSION["nome_usuario_acesso"] = $row["nome_participante"];
            header("Location: admin.php");
            exit;
        } else {
            $msg_alerta = "E-mail ou senha incorretos. Tente novamente.";
            $alert_class = "alert-danger";
        }
    }
}

if ($status === 'success') {
    $msg_alerta = "Sua reserva foi concluída com sucesso! O pagamento está sendo processado.";
    $alert_class = "alert-success";
} elseif ($status === 'failure') {
    $msg_alerta = "Ocorreu um erro no processamento do pagamento. Tente novamente.";
    $alert_class = "alert-danger";
} elseif ($status === 'pending') {
    $msg_alerta = "Pagamento pendente de aprovação. Suas mesas estão pré-reservadas.";
    $alert_class = "alert-warning";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festa Junina 2026 - Reservas de Mesas</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Styles -->
    <style>
        :root {
            --primary: #1b365d;
            --primary-hover: #3a86c8;
            --primary-glow: rgba(27, 54, 93, 0.15);
            --accent: #79b496;
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --border-color: #e9ecef;
            --text-dark: #212529;
            --text-muted: #6c757d;
            
            /* Table States */
            --color-avail: #70e000;
            --color-sold: #adb5bd;
            --color-sel: #ff9f1c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            width: 100%;
        }

        /* Top Navbar */
        nav {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            height: 48px;
            width: auto;
        }

        .logo-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.1;
        }

        .logo-title span {
            display: block;
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--text-muted);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--primary-hover);
            font-weight: 500;
        }

        .btn-entrar {
            border: 1px solid var(--primary);
            color: var(--primary);
            background: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-entrar:hover {
            background: rgba(27, 54, 93, 0.05);
            border-color: var(--primary-hover);
            color: var(--primary-hover);
        }

        /* Steps Progress Bar */
        .steps-container {
            max-width: 900px;
            margin: 30px auto;
            width: 100%;
            padding: 0 20px;
            position: relative;
        }

        .steps-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .steps-line {
            position: absolute;
            top: 15px;
            left: 5%;
            right: 5%;
            height: 2px;
            background: #dee2e6;
            z-index: 0;
        }

        .steps-line-progress {
            position: absolute;
            top: 15px;
            left: 5%;
            width: 0%; 
            height: 2px;
            background: var(--primary);
            z-index: 0;
            transition: width 0.3s ease;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-body);
            padding: 0 10px;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .step-item.active {
            color: var(--primary);
            font-weight: 600;
        }

        .step-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #dee2e6;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            transition: background 0.3s;
        }

        .step-item.active .step-num {
            background: var(--primary);
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            padding: 0 20px 40px 20px;
            flex: 1;
            min-width: 0;
        }

        /* New Layout Classes */
        .step-mesas {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .step-checkout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 30px;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .checkout-forms {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .checkout-summary {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
        }

        .card-title svg {
            width: 20px;
            height: 20px;
            color: var(--primary);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            border: 1px solid #ced4da;
            background-color: #ffffff;
            padding: 12px 16px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            color: var(--text-dark);
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .checkbox-group input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .btn-action {
            width: 100%;
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .btn-action:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-action:disabled {
            background: #dee2e6;
            color: #adb5bd;
            cursor: not-allowed;
            transform: none;
        }

        .btn-pay {
            width: 100%;
            background: #22c55e;
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-pay:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .btn-pay:disabled {
            background: #86efac;
            cursor: not-allowed;
            transform: none;
        }

        /* Resumo Card */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .summary-total {
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary);
        }

        .btn-outline {
            width: 100%;
            border: 1px solid var(--primary);
            color: var(--primary);
            background: none;
            padding: 12px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
        }

        .btn-outline:hover {
            background: rgba(27, 54, 93, 0.05);
            border-color: var(--primary-hover);
            color: var(--primary-hover);
        }

        .btn-outline:disabled {
            border-color: #dee2e6;
            color: #adb5bd;
            cursor: not-allowed;
            background: none;
        }

        /* Card Sobre o Evento */
        .about-card p {
            font-size: 0.85rem;
            line-height: 1.6;
            color: var(--text-muted);
        }

        .about-card a {
            color: var(--primary-hover);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }

        /* Right Content Area is now removed */

        /* Alert Callout */
        .callout-alert {
            background: rgba(27, 54, 93, 0.05);
            border: 1px solid rgba(27, 54, 93, 0.1);
            color: var(--primary);
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Legenda */
        .legend-bar {
            display: flex;
            gap: 25px;
            margin-bottom: 10px;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-dot {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        .legend-dot.avail { background: var(--color-avail); }
        .legend-dot.sold { background: var(--color-sold); }
        .legend-dot.sel { background: var(--color-sel); }

        /* Map Container Card */
        .map-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .grid-scroll-area {
            width: 100%;
            overflow-x: auto;
            padding: 15px 0;
            display: flex;
            justify-content: center;
        }

        /* Salon Grid Layout */
        .salon-grid {
            display: grid;
            grid-template-columns: repeat(16, 58px);
            grid-template-rows: repeat(7, 58px);
            gap: 14px;
            position: relative;
            background: #fafafb;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            min-width: fit-content;
        }

        /* Corners - Plants */
        .corner-plant {
            position: absolute;
            font-size: 1.2rem;
            pointer-events: none;
            z-index: 10;
        }

        .plant-tl { top: 6px; left: 6px; }
        .plant-tr { top: 6px; right: 6px; }
        .plant-bl { bottom: 6px; left: 6px; }
        .plant-br { bottom: 6px; right: 6px; }

        /* Palco Central Overlay */
        .palco-central {
            grid-column: 7 / span 4;
            grid-row: 1 / span 3;
            background: #fdfaf6;
            border: 2px dashed #e2b45c;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #6c757d;
            font-size: 0.75rem;
            position: relative;
            pointer-events: none;
            box-shadow: inset 0 0 10px rgba(226, 180, 92, 0.05);
        }

        .palco-central strong {
            color: #4a3e20;
            font-size: 0.85rem;
            margin-bottom: 2px;
        }

        /* Interactive Round Table */
        .round-table {
            width: 58px;
            height: 58px;
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Central Table Top */
        .table-top {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-dark);
            z-index: 2;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        /* Chairs positioned around the table top */
        .chair {
            position: absolute;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid rgba(0, 0, 0, 0.15);
            z-index: 1;
            transition: all 0.2s;
        }

        .chair-top { top: 1px; left: 22px; }
        .chair-bottom { bottom: 1px; left: 22px; }
        .chair-left { left: 1px; top: 22px; }
        .chair-right { right: 1px; top: 22px; }

        /* Available Style (Green) */
        .round-table.available .table-top {
            background-color: var(--color-avail);
            border-color: #55b200;
            color: #ffffff;
        }
        .round-table.available .chair {
            background-color: var(--color-avail);
            border-color: #55b200;
        }
        .round-table.available:hover {
            transform: scale(1.12);
            z-index: 5;
        }

        /* Selected Style (Orange) */
        .round-table.selected .table-top {
            background-color: var(--color-sel);
            border-color: #e07a00;
            color: #ffffff;
        }
        .round-table.selected .chair {
            background-color: var(--color-sel);
            border-color: #e07a00;
        }
        .round-table.selected:hover {
            transform: scale(1.12);
            z-index: 5;
        }

        /* Sold Style (Grey) */
        .round-table.sold {
            cursor: not-allowed;
        }
        .round-table.sold .table-top {
            background-color: var(--color-sold);
            border-color: #9299a0;
            color: #ffffff;
        }
        .round-table.sold .chair {
            background-color: var(--color-sold);
            border-color: #9299a0;
        }

        /* Disabled Map State (Before registration completed) */
        .salon-grid.disabled {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Map Bottom Action Bar */
        .map-action-bar {
            position: sticky;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-color);
            padding: 20px 24px;
            margin: 20px -24px -24px -24px;
            width: calc(100% + 48px);
            z-index: 100;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
            border-radius: 0 0 16px 16px;
        }

        .map-action-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .map-action-title svg {
            width: 20px;
            height: 20px;
            color: var(--primary);
        }

        .map-actions-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-clear {
            border: 1px solid #ced4da;
            background: none;
            color: var(--text-dark);
            padding: 10px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            background: #f1f3f5;
        }

        .btn-view-summary {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-view-summary:hover {
            background: var(--primary-hover);
        }

        /* Mobile Scroll Prompt */
        .mobile-scroll-indicator {
            display: none;
            text-align: center;
            font-size: 0.8rem;
            color: var(--primary-hover);
            margin-bottom: 8px;
            font-weight: 500;
            animation: pulseScroll 1.5s infinite alternate;
        }

        @keyframes pulseScroll {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Footer */
        footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .footer-contacts {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .footer-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--primary-hover);
        }

        /* Payment Form Panel */
        .payment-panel {
            display: none;
        }

        .payment-option-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .payment-radio {
            display: none;
        }

        .payment-box {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .payment-radio:checked + .payment-box {
            border-color: var(--primary);
            background: rgba(27, 54, 93, 0.05);
            color: var(--primary);
        }

        /* Login Modal Stylings */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-close {
            position: absolute;
            top: 20px; right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .step-checkout {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 0 auto;
            }
            .container {
                padding: 0 15px 30px 15px;
            }
            .steps-container {
                margin: 20px auto;
            }
            .mobile-scroll-indicator {
                display: block;
            }
            .grid-scroll-area {
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 12px 15px;
            }
            .logo-img {
                height: 38px;
            }
            .logo-title {
                font-size: 1.05rem;
            }
            .logo-title span {
                display: none;
            }
            .steps-container {
                display: none; 
            }
            .card {
                padding: 20px;
                border-radius: 12px;
            }
            .card-title {
                font-size: 1rem;
                margin-bottom: 15px;
            }
            .form-control {
                padding: 10px 14px;
                font-size: 0.85rem;
            }
            .form-group label {
                font-size: 0.75rem;
                margin-bottom: 4px;
            }
            .btn-action {
                padding: 12px;
                font-size: 0.9rem;
                margin-top: 15px;
            }
            .checkbox-group {
                font-size: 0.8rem;
                margin-top: 12px;
            }
            .map-card {
                padding: 20px 15px;
                border-radius: 12px;
            }
            .legend-bar {
                gap: 15px;
                font-size: 0.8rem;
            }
            .legend-dot {
                width: 16px;
                height: 16px;
            }

            /* Salon Grid mobile resizing */
            .salon-grid {
                grid-template-columns: repeat(16, 44px);
                grid-template-rows: repeat(7, 44px);
                gap: 8px;
                padding: 15px;
            }
            .round-table {
                width: 44px;
                height: 44px;
            }
            .table-top {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
                border-width: 1.5px;
            }
            .chair {
                width: 10px;
                height: 10px;
                border-width: 1px;
            }
            .chair-top { top: 0px; left: 17px; }
            .chair-bottom { bottom: 0px; left: 17px; }
            .chair-left { left: 0px; top: 17px; }
            .chair-right { right: 0px; top: 17px; }

            .palco-central {
                font-size: 0.6rem;
            }
            .palco-central strong {
                font-size: 0.7rem;
            }

            footer {
                flex-direction: column;
                align-items: center;
                padding: 20px;
                text-align: center;
            }
            .footer-contacts {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            nav {
                padding: 10px 12px;
            }
            .logo-area {
                gap: 8px;
            }
            .logo-img {
                height: 32px;
            }
            .logo-title {
                font-size: 0.95rem;
            }
            .nav-actions {
                gap: 10px;
            }
            .secure-text {
                display: none;
            }
            .secure-badge {
                gap: 0;
            }
            .btn-entrar {
                padding: 6px 14px;
                font-size: 0.8rem;
            }
            .card {
                padding: 16px;
            }
            .form-control {
                padding: 9px 12px;
            }
            .map-action-bar {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
                text-align: center;
            }
            .map-action-title {
                justify-content: center;
            }
            .map-actions-buttons {
                flex-direction: column;
            }
            .btn-clear, .btn-view-summary {
                width: 100%;
                padding: 12px;
            }
        }

        /* Venda de mesas encerrada */
        .encerramento-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 190px);
            padding: 50px 20px;
        }

        .encerramento-card {
            width: 100%;
            max-width: 650px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 55px 45px;
            text-align: center;
            box-shadow: 0 10px 35px rgba(27, 54, 93, 0.08);
        }

        .encerramento-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 25px;
            border-radius: 50%;
            background: rgba(27, 54, 93, 0.07);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .encerramento-card h1 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .encerramento-card p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        .encerramento-card strong {
            color: var(--primary);
        }

        .encerramento-destaque {
            max-width: 520px;
            margin: 0 auto;
        }

        .encerramento-divisor {
            width: 60px;
            height: 3px;
            background: var(--accent);
            border-radius: 10px;
            margin: 28px auto;
        }

        .encerramento-final {
            color: var(--primary);
            font-size: 1rem;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            .encerramento-container {
                min-height: calc(100vh - 160px);
                padding: 30px 15px;
            }

            .encerramento-card {
                padding: 40px 22px;
                border-radius: 16px;
            }

            .encerramento-card h1 {
                font-size: 1.5rem;
            }

            .encerramento-card p {
                font-size: 0.9rem;
            }
        }

    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav>
        <div class="logo-area">
            <img src="logo.png" alt="Sociedade Espírita Eurípedes Barsanulfo" class="logo-img">
            <div class="logo-title">
                Festa Junina 2026
                <span>Educandário Espírita Maria de Nazaré</span>
            </div>
        </div>
        <div class="nav-actions">
            <div class="secure-badge">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span class="secure-text">Ambiente seguro</span>
            </div>
            <button class="btn-entrar" id="btnShowLogin">Entrar</button>
        </div>
    </nav>

    <!-- Venda de mesas encerrada -->
    <main class="container encerramento-container">

        <?php if (!empty($msg_alerta)): ?>
            <div class="alert <?php echo $alert_class; ?>" style="margin-bottom: 20px;">
                <?php echo htmlspecialchars($msg_alerta); ?>
            </div>
        <?php endif; ?>

        <section class="encerramento-card" aria-labelledby="titulo-encerramento">
            <div class="encerramento-icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>

            <h1 id="titulo-encerramento">Venda de Mesas Encerrada</h1>

            <p>
                As vendas de mesas para a
                <strong>Festa Junina 2026</strong> foram encerradas.
            </p>

            <p class="encerramento-destaque">
                Agradecemos a todos que participaram e contribuíram
                com o Educandário Espírita Maria de Nazaré.
            </p>

            <div class="encerramento-divisor" aria-hidden="true"></div>

            <span class="encerramento-final">
                Esperamos você para celebrar conosco! 🌽 🎉
            </span>
        </section>

    </main>

    <!-- Login Modal -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-card">
            <button class="modal-close" id="btnCloseLogin">&times;</button>
            <div class="modal-title">Faça seu login</div>
            
            <form action="index.php" method="POST">
                <input type="hidden" name="acao_login" value="entrar">
                <div class="form-group">
                    <label for="email_usuario">E-mail</label>
                    <input type="email" id="email_usuario" name="email_usuario" class="form-control" required placeholder="seu@email.com">
                </div>
                <div class="form-group">
                    <label for="senha_usuario">Senha</label>
                    <input type="password" id="senha_usuario" name="senha_usuario" class="form-control" required placeholder="Sua senha">
                </div>
                <button type="submit" class="btn-action" style="margin-top: 10px;">Entrar</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-contacts">
            <a href="tel:65996038552" class="footer-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                (65) 99603-8552
            </a>
            <a href="mailto:educandario@euripedesbarsanulfo.org.br" class="footer-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                educandario@euripedesbarsanulfo.org.br
            </a>
            <a href="https://instagram.com/euripedesbarsanulfovg" target="_blank" class="footer-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                @euripedesbarsanulfovg
            </a>
        </div>
        <div>
            &copy; 2026 Festa Junina - Educandário Espírita Maria de Nazaré. Todos os direitos reservados.
        </div>
    </footer>

    <!-- Login Modal Controls -->
    <script>
        const modal = document.getElementById('loginModal');
        const btnShowLogin = document.getElementById('btnShowLogin');
        const btnCloseLogin = document.getElementById('btnCloseLogin');

        btnShowLogin.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        btnCloseLogin.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>

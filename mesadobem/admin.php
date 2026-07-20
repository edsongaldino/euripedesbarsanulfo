<?php
session_start();
require_once("db.php");

// Verificação de autenticação do administrador
if (!isset($_SESSION["key_acesso"]) || $_SESSION["key_acesso"] !== md5(KEY_SESSAO)) {
    header("Location: index.php");
    exit;
}

$conexao = conecta_mysql();
$codigo_evento = (int)CODIGO_EVENTO_ATIVO;

// Processar Ações (Aprovação / Cancelamento) via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id_reserva = (int)($_POST['id_reserva'] ?? 0);
    
    if ($id_reserva > 0) {
        if ($acao === 'confirmar') {
            $sql_update = "UPDATE reserva_mesa SET codigo_situacao = 2 WHERE codigo_reserva = '$id_reserva'";
            mysqli_query($conexao, $sql_update);
        } elseif ($acao === 'cancelar') {
            // Pode alterar para status 3 ou excluir o registro para liberar a mesa
            $sql_delete = "DELETE FROM reserva_mesa WHERE codigo_reserva = '$id_reserva'";
            mysqli_query($conexao, $sql_delete);
        }
        
        header("Location: admin.php");
        exit;
    }
}

// Filtros e busca
$busca = protege_campo($_GET['busca'] ?? '');
$filtro_status = $_GET['status'] ?? '';

$sql = "SELECT codigo_reserva, numero_mesa, nome_participante, email_participante, telefone_participante, codigo_situacao, data_reserva, valor_reserva 
        FROM reserva_mesa 
        WHERE codigo_evento = '$codigo_evento'";

if (!empty($busca)) {
    $sql .= " AND (nome_participante LIKE '%$busca%' OR email_participante LIKE '%$busca%' OR numero_mesa = '$busca')";
}

if ($filtro_status !== '') {
    $status_val = (int)$filtro_status;
    $sql .= " AND codigo_situacao = '$status_val'";
}

$sql .= " ORDER BY numero_mesa ASC, data_reserva DESC";
$result = mysqli_query($conexao, $sql);

// Contagem rápida para resumo de vendas
$sql_stats = "SELECT 
    COUNT(CASE WHEN codigo_situacao = 2 THEN 1 END) as total_pagas,
    COUNT(CASE WHEN codigo_situacao = 1 THEN 1 END) as total_pendentes,
    SUM(CASE WHEN codigo_situacao = 2 THEN valor_reserva ELSE 0 END) as total_faturamento
    FROM reserva_mesa WHERE codigo_evento = '$codigo_evento'";
$query_stats = mysqli_query($conexao, $sql_stats);
$stats = mysqli_fetch_assoc($query_stats);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesa do Bem - Painel de Controle</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Styles -->
    <style>
        :root {
            --primary: #1b365d;
            --primary-hover: #3a86c8;
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --border-color: #e9ecef;
            --text-dark: #212529;
            --text-muted: #6c757d;
            
            /* Status Colors */
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
        }

        nav {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            height: 44px;
            width: auto;
        }

        .logo-title {
            font-size: 1.25rem;
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
            gap: 20px;
        }

        .user-welcome {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .btn-logout {
            border: 1px solid #ced4da;
            color: var(--text-muted);
            background: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #f1f3f5;
            color: var(--text-dark);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 25px;
            flex: 1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-info h3 {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(27, 54, 93, 0.05);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Workspace Card */
        .work-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .work-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .work-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* Filters and Search */
        .filters-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-input {
            border: 1px solid #ced4da;
            padding: 10px 14px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.85rem;
            width: 250px;
        }

        .select-filter {
            border: 1px solid #ced4da;
            padding: 10px 14px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.85rem;
            background: #ffffff;
        }

        .btn-filter {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-filter:hover {
            background: var(--primary-hover);
        }

        /* Reservations Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            background: #f8f9fa;
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:hover td {
            background-color: #fafbfd;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending {
            background: rgba(255, 159, 28, 0.15);
            color: #d07d00;
        }

        .badge-confirmed {
            background: rgba(112, 224, 0, 0.15);
            color: #538d00;
        }

        .badge-canceled {
            background: rgba(230, 57, 70, 0.15);
            color: #e63946;
        }

        /* Action Buttons */
        .actions-cell {
            display: flex;
            gap: 8px;
        }

        .btn-table {
            border: 1px solid transparent;
            padding: 6px 12px;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-confirm {
            border-color: #70e000;
            color: #55b200;
            background: none;
        }

        .btn-confirm:hover {
            background: rgba(112, 224, 0, 0.05);
        }

        .btn-cancel {
            border-color: #e63946;
            color: #e63946;
            background: none;
        }

        .btn-cancel:hover {
            background: rgba(230, 57, 70, 0.05);
        }

        .empty-row {
            text-align: center;
            color: var(--text-muted);
            padding: 30px;
        }

        /* Go public button */
        .btn-view-site {
            background: none;
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 10px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view-site:hover {
            background: rgba(27, 54, 93, 0.05);
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav>
        <div class="logo-area">
            <img src="logo.png" alt="Sociedade Espírita Eurípedes Barsanulfo" class="logo-img">
            <div class="logo-title">
                Mesa do Bem
                <span>Painel Administrativo</span>
            </div>
        </div>
        <div class="nav-actions">
            <span class="user-welcome">Olá, <?php echo htmlspecialchars($_SESSION["nome_usuario_acesso"] ?? 'Admin'); ?></span>
            <a href="index.php" class="btn-view-site">Ver Site</a>
            <a href="logout.php" class="btn-logout">Sair</a>
        </div>
    </nav>

    <!-- Main Workspace -->
    <div class="container">
        
        <!-- Stats Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Mesas Confirmadas</h3>
                    <p><?php echo (int)($stats['total_pagas'] ?? 0); ?></p>
                </div>
                <div class="stat-icon">✅</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Aguardando Pagamento</h3>
                    <p><?php echo (int)($stats['total_pendentes'] ?? 0); ?></p>
                </div>
                <div class="stat-icon">⏳</div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Faturamento Confirmado</h3>
                    <p>R$ <?php echo number_format((float)($stats['total_faturamento'] ?? 0), 2, ',', '.'); ?></p>
                </div>
                <div class="stat-icon">💰</div>
            </div>
        </div>

        <!-- Reservations List Card -->
        <div class="work-card">
            <div class="work-header">
                <span class="work-title">Gerenciamento de Reservas</span>
                
                <!-- Filter Form -->
                <form method="GET" class="filters-form">
                    <input type="text" name="busca" class="search-input" value="<?php echo htmlspecialchars($busca); ?>" placeholder="Buscar comprador ou mesa...">
                    <select name="status" class="select-filter">
                        <option value="">Status: Todos</option>
                        <option value="1" <?php if ($filtro_status === '1') echo 'selected'; ?>>Pendente</option>
                        <option value="2" <?php if ($filtro_status === '2') echo 'selected'; ?>>Confirmado</option>
                        <option value="3" <?php if ($filtro_status === '3') echo 'selected'; ?>>Cancelado</option>
                    </select>
                    <button type="submit" class="btn-filter">Filtrar</button>
                </form>
            </div>

            <!-- Table of reservations -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Mesa</th>
                            <th>Comprador</th>
                            <th>WhatsApp</th>
                            <th>E-mail</th>
                            <th>Valor</th>
                            <th>Data Reserva</th>
                            <th>Situação</th>
                            <th style="width: 180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">Mesa #<?php echo (int)$row['numero_mesa']; ?></td>
                                    <td style="font-weight: 600;"><?php echo htmlspecialchars($row['nome_participante']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefone_participante']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email_participante']); ?></td>
                                    <td>R$ <?php echo number_format((float)$row['valor_reserva'], 2, ',', '.'); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['data_reserva'])); ?></td>
                                    <td>
                                        <?php if ((int)$row['codigo_situacao'] === 1): ?>
                                            <span class="badge badge-pending">Pendente</span>
                                        <?php elseif ((int)$row['codigo_situacao'] === 2): ?>
                                            <span class="badge badge-confirmed">Confirmado</span>
                                        <?php else: ?>
                                            <span class="badge badge-canceled">Cancelado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if ((int)$row['codigo_situacao'] === 1): ?>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Confirmar pagamento desta mesa manualmente?');">
                                                <input type="hidden" name="acao" value="confirmar">
                                                <input type="hidden" name="id_reserva" value="<?php echo $row['codigo_reserva']; ?>">
                                                <button type="submit" class="btn-table btn-confirm">Aprovar</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente CANCELAR/EXCLUIR esta reserva? A mesa voltará a ficar disponível para venda imediatamente.');">
                                            <input type="hidden" name="acao" value="cancelar">
                                            <input type="hidden" name="id_reserva" value="<?php echo $row['codigo_reserva']; ?>">
                                            <button type="submit" class="btn-table btn-cancel">Cancelar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty-row">Nenhuma reserva encontrada com os filtros selecionados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
<?php fecha_mysql($conexao); ?>

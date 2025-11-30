<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$mensagem = "";
$tipo_mensagem = "";

// ==================== RESOLVER ALERTA ====================
if (isset($_POST['resolver'])) {
    $alerta_id = intval($_POST['alerta_id']);
    $usuario_id = $_SESSION['id'];
    $observacao = trim($_POST['observacao'] ?? '');

    $stmt = $conexao->prepare("UPDATE alertas SET status='resolvido', resolvido_por=?, 
                               data_resolucao=NOW(), observacao_resolucao=? WHERE id=?");
    $stmt->bind_param("isi", $usuario_id, $observacao, $alerta_id);

    if ($stmt->execute()) {
        $mensagem = "Alerta marcado como resolvido!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao resolver alerta.";
        $tipo_mensagem = "error";
    }
    $stmt->close();
}

// ==================== EXCLUIR ALERTA ====================
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    $stmt = $conexao->prepare("DELETE FROM alertas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensagem = "Alerta excluído com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao excluir alerta.";
        $tipo_mensagem = "error";
    }
    $stmt->close();
}

// ==================== BUSCAR ALERTAS ====================
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filtro_status = isset($_GET['status']) ? $_GET['status'] : 'pendente';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$sql = "SELECT a.*, s.nome as sensor_nome, s.codigo as sensor_codigo, 
        u.nome as resolvido_por_nome 
        FROM alertas a 
        LEFT JOIN sensores s ON a.sensor_id = s.id 
        LEFT JOIN usuarios u ON a.resolvido_por = u.id 
        WHERE 1=1";

if (!empty($filtro_tipo)) {
    $sql .= " AND a.tipo = '" . $conexao->real_escape_string($filtro_tipo) . "'";
}

if (!empty($filtro_status)) {
    $sql .= " AND a.status = '" . $conexao->real_escape_string($filtro_status) . "'";
}

if (!empty($busca)) {
    $busca_escapada = $conexao->real_escape_string($busca);
    $sql .= " AND (a.titulo LIKE '%$busca_escapada%' 
              OR a.descricao LIKE '%$busca_escapada%' 
              OR s.nome LIKE '%$busca_escapada%')";
}

$sql .= " ORDER BY a.data_hora DESC";
$resultado = $conexao->query($sql);

// Contar alertas por tipo
$count_critico = $conexao->query("SELECT COUNT(*) as total FROM alertas WHERE tipo='critico' AND status='pendente'")->fetch_assoc()['total'];
$count_aviso = $conexao->query("SELECT COUNT(*) as total FROM alertas WHERE tipo='aviso' AND status='pendente'")->fetch_assoc()['total'];
$count_info = $conexao->query("SELECT COUNT(*) as total FROM alertas WHERE tipo='info' AND status='pendente'")->fetch_assoc()['total'];
$count_resolvidos = $conexao->query("SELECT COUNT(*) as total FROM alertas WHERE status='resolvido' AND DATE(data_resolucao) = CURDATE()")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alertas - Sistema Ferroviário</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #a79f9fff 0%, #332e2eff 100%);
            color: white;
            padding: 20px 0;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .sidebar-header h2 {
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
            gap: 12px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }

        .sidebar-menu a .icon {
            font-size: 1.3em;
            width: 25px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            max-width: 1400px;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: black;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-card .label {
            color: #666;
            font-size: 0.9em;
        }

        .stat-card.critical .number {
            color: #e53e3e;
        }

        .stat-card.warning .number {
            color: #dd6b20;
        }

        .stat-card.info .number {
            color: #3182ce;
        }

        .stat-card.resolved .number {
            color: #38a169;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
        }

        .filters input,
        .filters select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1em;
        }

        .mensagem {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .mensagem.success {
            background: #d4edda;
            color: #155724;
        }

        .mensagem.error {
            background: #f8d7da;
            color: #721c24;
        }

        .alertas-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .alerta-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            align-items: start;
        }

        .alerta-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .alerta-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8em;
        }

        .alerta-icon.critical {
            background: #fff5f5;
            color: #e53e3e;
        }

        .alerta-icon.aviso {
            background: #fffaf0;
            color: #dd6b20;
        }

        .alerta-icon.info {
            background: #ebf8ff;
            color: #3182ce;
        }

        .alerta-icon.resolved {
            background: #f0fff4;
            color: #38a169;
        }

        .alerta-content {
            flex: 1;
        }

        .alerta-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .alerta-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }

        .alerta-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .badge-critico {
            background: #fff5f5;
            color: #e53e3e;
        }

        .badge-aviso {
            background: #fffaf0;
            color: #dd6b20;
        }

        .badge-info {
            background: #ebf8ff;
            color: #3182ce;
        }

        .badge-resolvido {
            background: #f0fff4;
            color: #38a169;
        }

        .alerta-description {
            color: #666;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .alerta-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 0.9em;
            color: #999;
        }

        .alerta-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-resolver {
            background: #38a169;
            color: white;
        }

        .btn-excluir {
            background: #e53e3e;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
        }

        .modal-content textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            min-height: 100px;
            font-family: inherit;
            margin: 15px 0;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .alerta-card {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>🚆 Sistema Ferroviário</h2>
            <p>Painel Administrativo</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="gerenciarsensores.php"><span class="icon">🚂</span> Gerenciar Sensores</a></li>
            <li><a href="cadastrarsensores.php"><span class="icon">🛤️</span> Cadastrar Sensores</a></li>
            <li><a href="gerenciarestações.php"><span class="icon">🚉</span> Gerenciar Estações</a></li>
            <li><a href="cadastrarestações.php"><span class="icon">🗺️</span> Cadastrar Estações</a></li>
            <li><a href="gerenciartrens.php"><span class="icon">🚂</span> Gerenciar Trens</a></li>
            <li><a href="cadastrartrem.php"><span class="icon">➕</span> Cadastrar Trem</a></li>
            <li><a href="alertas.php"><span class="icon">🚨</span> Alertas</a></li>
            <li><a href="gerenciaritinerários.php"><span class="icon">📡</span> Gerenciar Itinerários</a></li>
            <li><a href="geraçãorelátorios.php"><span class="icon">📄</span> Relatórios</a></li>
            <li><a href="perfil.php" class="active"><span class="icon">👤</span> Meu Perfil</a></li>
            <li><a href="sobre.php"><span class="icon">ℹ️</span> Sobre</a></li>
            <li><a href="../php/login.php"><span class="icon">🚪</span> Sair</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <h1>🚨 Gerenciamento de Alertas</h1>
            <p style="color: #666; margin-top: 5px;">Monitoramento e controle de alertas do sistema</p>
        </header>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Estatísticas -->
        <div class="stats-bar">
            <div class="stat-card critical">
                <div class="number"><?php echo $count_critico; ?></div>
                <div class="label">Críticos</div>
            </div>
            <div class="stat-card warning">
                <div class="number"><?php echo $count_aviso; ?></div>
                <div class="label">Avisos</div>
            </div>
            <div class="stat-card info">
                <div class="number"><?php echo $count_info; ?></div>
                <div class="label">Informativos</div>
            </div>
            <div class="stat-card resolved">
                <div class="number"><?php echo $count_resolvidos; ?></div>
                <div class="label">Resolvidos Hoje</div>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" class="filters">
            <input type="text" name="busca" placeholder="🔍 Buscar alertas..."
                value="<?php echo htmlspecialchars($busca); ?>">
            <select name="tipo" onchange="this.form.submit()">
                <option value="">Todas as Prioridades</option>
                <option value="critico" <?php echo $filtro_tipo === 'critico' ? 'selected' : ''; ?>>Crítico</option>
                <option value="aviso" <?php echo $filtro_tipo === 'aviso' ? 'selected' : ''; ?>>Aviso</option>
                <option value="info" <?php echo $filtro_tipo === 'info' ? 'selected' : ''; ?>>Informativo</option>
            </select>
            <select name="status" onchange="this.form.submit()">
                <option value="pendente" <?php echo $filtro_status === 'pendente' ? 'selected' : ''; ?>>Pendentes</option>
                <option value="resolvido" <?php echo $filtro_status === 'resolvido' ? 'selected' : ''; ?>>Resolvidos
                </option>
                <option value="">Todos</option>
            </select>
        </form>

        <!-- Lista de Alertas -->
        <div class="alertas-list">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($alerta = $resultado->fetch_assoc()): ?>
                    <div class="alerta-card" style="<?php echo $alerta['status'] === 'resolvido' ? 'opacity: 0.7;' : ''; ?>">
                        <div
                            class="alerta-icon <?php echo $alerta['status'] === 'resolvido' ? 'resolved' : $alerta['tipo']; ?>">
                            <?php
                            if ($alerta['status'] === 'resolvido') {
                                echo '✅';
                            } elseif ($alerta['tipo'] === 'critico') {
                                echo '🔥';
                            } elseif ($alerta['tipo'] === 'aviso') {
                                echo '⚡';
                            } else {
                                echo 'ℹ️';
                            }
                            ?>
                        </div>
                        <div class="alerta-content">
                            <div class="alerta-header">
                                <div class="alerta-title"><?php echo htmlspecialchars($alerta['titulo']); ?></div>
                                <span
                                    class="alerta-badge badge-<?php echo $alerta['status'] === 'resolvido' ? 'resolvido' : $alerta['tipo']; ?>">
                                    <?php echo strtoupper($alerta['status'] === 'resolvido' ? 'RESOLVIDO' : $alerta['tipo']); ?>
                                </span>
                            </div>
                            <p class="alerta-description">
                                <?php echo htmlspecialchars($alerta['descricao']); ?>
                            </p>
                            <div class="alerta-meta">
                                <span>🔡 Sensor: <?php echo htmlspecialchars($alerta['sensor_codigo']); ?></span>
                                <?php if ($alerta['valor_leitura']): ?>
                                    <span>📊 Valor: <?php echo number_format($alerta['valor_leitura'], 2); ?></span>
                                <?php endif; ?>
                                <span>⏰ <?php echo date('d/m/Y H:i', strtotime($alerta['data_hora'])); ?></span>
                                <?php if ($alerta['status'] === 'resolvido'): ?>
                                    <span>✅ Por: <?php echo htmlspecialchars($alerta['resolvido_por_nome']); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($alerta['status'] === 'resolvido' && $alerta['observacao_resolucao']): ?>
                                <p
                                    style="margin-top: 10px; padding: 10px; background: #f0f0f0; border-radius: 5px; font-size: 0.9em;">
                                    <strong>Observação:</strong> <?php echo htmlspecialchars($alerta['observacao_resolucao']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="alerta-actions">
                            <?php if ($alerta['status'] !== 'resolvido'): ?>
                                <button class="btn btn-resolver" onclick="abrirModal(<?php echo $alerta['id']; ?>)">✔️
                                    Resolver</button>
                            <?php endif; ?>
                            <a href="?excluir=<?php echo $alerta['id']; ?>" class="btn btn-excluir"
                                onclick="return confirm('Tem certeza que deseja excluir este alerta?')">🗑️ Excluir</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: white; padding: 40px;">Nenhum alerta encontrado.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Resolver Alerta -->
    <div id="modalResolver" class="modal">
        <div class="modal-content">
            <h2>Resolver Alerta</h2>
            <form method="POST">
                <input type="hidden" name="alerta_id" id="alerta_id">
                <label>Observação (opcional):</label>
                <textarea name="observacao" placeholder="Descreva como o problema foi resolvido..."></textarea>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-excluir" onclick="fecharModal()">Cancelar</button>
                    <button type="submit" name="resolver" class="btn btn-resolver">✔️ Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id) {
            document.getElementById('alerta_id').value = id;
            document.getElementById('modalResolver').style.display = 'block';
        }

        function fecharModal() {
            document.getElementById('modalResolver').style.display = 'none';
        }

        window.onclick = function (event) {
            const modal = document.getElementById('modalResolver');
            if (event.target == modal) {
                fecharModal();
            }
        }
    </script>
</body>

</html>
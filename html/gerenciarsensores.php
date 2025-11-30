<?php
require "../php/conexao.php";
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$mensagem = "";
$tipo_mensagem = "";

// ==================== EXCLUIR SENSOR ====================
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    $stmt = $conexao->prepare("DELETE FROM sensores WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensagem = "Sensor excluído com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao excluir sensor: " . $conexao->error;
        $tipo_mensagem = "error";
    }
    $stmt->close();
}

// ==================== ATUALIZAR STATUS ====================
if (isset($_POST['atualizar_status'])) {
    $id = intval($_POST['sensor_id']);
    $novo_status = $_POST['novo_status'];

    $stmt = $conexao->prepare("UPDATE sensores SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_status, $id);

    if ($stmt->execute()) {
        $mensagem = "Status atualizado com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao atualizar status.";
        $tipo_mensagem = "error";
    }
    $stmt->close();
}

// ==================== BUSCAR SENSORES ====================
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$sql = "SELECT s.*, t.nome as trem_nome, e.nome as estacao_nome 
        FROM sensores s 
        LEFT JOIN trens t ON s.trem_id = t.id 
        LEFT JOIN estacoes e ON s.estacao_id = e.id 
        WHERE 1=1";

if (!empty($filtro_status)) {
    $sql .= " AND s.status = '" . $conexao->real_escape_string($filtro_status) . "'";
}

if (!empty($busca)) {
    $busca_escapada = $conexao->real_escape_string($busca);
    $sql .= " AND (s.nome LIKE '%$busca_escapada%' 
              OR s.codigo LIKE '%$busca_escapada%' 
              OR s.tipo LIKE '%$busca_escapada%' 
              OR s.localizacao LIKE '%$busca_escapada%')";
}

$sql .= " ORDER BY s.data_cadastro DESC";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Sensores - Sistema Ferroviário</title>

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
            transition: transform 0.3s ease;
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

        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: black;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        h1 {
            color: black;
            font-size: 2em;
        }

        .btn-novo {
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-novo:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        .search-bar input,
        .search-bar select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .search-bar input:focus,
        .search-bar select:focus {
            outline: none;
            border-color: #667eea;
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
            border: 1px solid #c3e6cb;
        }

        .mensagem.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .sensores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .sensor-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sensor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
        }

        .sensor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .sensor-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .status-online {
            background: #d4edda;
            color: #155724;
        }

        .status-offline {
            background: #f8d7da;
            color: #721c24;
        }

        .status-manutencao {
            background: #fff3cd;
            color: #856404;
        }

        .status-erro {
            background: #f5c6cb;
            color: #721c24;
        }

        .sensor-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.3em;
        }

        .sensor-info {
            margin: 15px 0;
            color: #666;
        }

        .sensor-info p {
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sensor-info strong {
            min-width: 80px;
            color: black;
        }

        .sensor-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95em;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-editar {
            background: #667eea;
            color: white;
        }

        .btn-editar:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-excluir {
            background: #e53e3e;
            color: white;
        }

        .btn-excluir:hover {
            background: #c53030;
            transform: translateY(-2px);
        }

        .empty-state {
            background: white;
            padding: 60px 20px;
            border-radius: 15px;
            text-align: center;
            color: #999;
            font-size: 1.2em;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .menu-toggle {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }

            header {
                flex-direction: column;
                align-items: stretch;
            }

            h1 {
                font-size: 1.5em;
            }

            .search-bar {
                grid-template-columns: 1fr;
            }

            .sensores-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

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
            <li><a href="gerenciartrens.php" class="active"><span class="icon">🚂</span> Gerenciar Trens</a></li>
            <li><a href="cadastrartrem.php"><span class="icon">➕</span> Cadastrar Trem</a></li>
            <li><a href="alertas.php"><span class="icon">🚨</span> Alertas</a></li>
            <li><a href="gerenciaritinerários.php"><span class="icon">🔡</span> Gerenciar Itinerários</a></li>
            <li><a href="geraçãorelátorios.php"><span class="icon">📄</span> Relatórios</a></li>
            <li><a href="sobre.php"><span class="icon">ℹ️</span> Sobre</a></li>
            <li><a href="rotas.php"><span class="icon">🗺️</span> Rotas</a></li>
            <li><a href="../php/login.php"><span class="icon">👤</span> Sair</a></li>
        </ul>
    </aside>


    <main class="main-content">
        <div class="container">
            <header>
                <h1>🔡 Gerenciar Sensores</h1>
                <a href="cadastrarsensores.php" class="btn-novo">➕ Novo Sensor</a>
            </header>

            <?php if (!empty($mensagem)): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="GET" class="search-bar">
                <input type="text" name="busca"
                    placeholder="🔍 Buscar sensores por nome, código, tipo ou localização..."
                    value="<?php echo htmlspecialchars($busca); ?>">
                <select name="status" onchange="this.form.submit()">
                    <option value="">Todos os Status</option>
                    <option value="online" <?php echo $filtro_status === 'online' ? 'selected' : ''; ?>>Online</option>
                    <option value="offline" <?php echo $filtro_status === 'offline' ? 'selected' : ''; ?>>Offline</option>
                    <option value="manutencao" <?php echo $filtro_status === 'manutencao' ? 'selected' : ''; ?>>Em
                        Manutenção</option>
                    <option value="erro" <?php echo $filtro_status === 'erro' ? 'selected' : ''; ?>>Erro</option>
                </select>
            </form>

            <div class="sensores-grid">
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($sensor = $resultado->fetch_assoc()): ?>
                        <div class="sensor-card">
                            <span class="sensor-status status-<?php echo $sensor['status']; ?>">
                                ● <?php echo ucfirst($sensor['status']); ?>
                            </span>

                            <h3><?php echo htmlspecialchars($sensor['nome']); ?></h3>

                            <div class="sensor-info">
                                <p><strong>Código:</strong> <?php echo htmlspecialchars($sensor['codigo']); ?></p>
                                <p><strong>Tipo:</strong> <?php echo ucfirst($sensor['tipo']); ?></p>
                                <p><strong>Local:</strong> <?php echo htmlspecialchars($sensor['localizacao']); ?></p>
                                <?php if ($sensor['trem_nome']): ?>
                                    <p><strong>Trem:</strong> <?php echo htmlspecialchars($sensor['trem_nome']); ?></p>
                                <?php endif; ?>
                                <?php if ($sensor['estacao_nome']): ?>
                                    <p><strong>Estação:</strong> <?php echo htmlspecialchars($sensor['estacao_nome']); ?></p>
                                <?php endif; ?>
                                <?php if ($sensor['ultima_leitura']): ?>
                                    <p><strong>Última Leitura:</strong>
                                        <?php echo number_format($sensor['ultima_leitura'], 2); ?>
                                        <?php echo htmlspecialchars($sensor['unidade_medida'] ?? ''); ?>
                                    </p>
                                <?php endif; ?>
                                <p><strong>Tópico MQTT:</strong> <?php echo htmlspecialchars($sensor['topico_mqtt']); ?></p>
                            </div>

                            <div class="sensor-actions">
                                <a href="cadastrarsensores.php?editar=<?php echo $sensor['id']; ?>" class="btn btn-editar">✏️
                                    Editar</a>
                                <a href="?excluir=<?php echo $sensor['id']; ?>" class="btn btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este sensor?')">
                                    🗑️ Excluir
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <p>📭 Nenhum sensor encontrado.</p>
                        <p style="font-size: 0.9em; margin-top: 10px;">
                            <a href="cadastrarsensores.php" style="color: #667eea;">Clique aqui para cadastrar o primeiro
                                sensor</a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const currentPage = window.location.pathname.split('/').pop();
            const links = document.querySelectorAll('.sidebar-menu a');

            links.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>
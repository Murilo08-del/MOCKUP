<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$mensagem = "";
$tipo_mensagem = "";

// ==================== EXCLUIR ITINERÁRIO ====================
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    $stmt = $conexao->prepare("DELETE FROM itinerarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensagem = "Itinerário excluído com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao excluir itinerário: " . $conexao->error;
        $tipo_mensagem = "error";
    }
    $stmt->close();
}

// ==================== BUSCAR ITINERÁRIOS ====================
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$sql = "SELECT i.*, t.nome as trem_nome, t.codigo as trem_codigo 
        FROM itinerarios i 
        LEFT JOIN trens t ON i.trem_id = t.id 
        WHERE 1=1";

if (!empty($filtro_status)) {
    $sql .= " AND i.status = '" . $conexao->real_escape_string($filtro_status) . "'";
}

if (!empty($busca)) {
    $busca_escapada = $conexao->real_escape_string($busca);
    $sql .= " AND (i.nome LIKE '%$busca_escapada%' 
              OR i.descricao LIKE '%$busca_escapada%' 
              OR t.nome LIKE '%$busca_escapada%')";
}

$sql .= " ORDER BY i.data_criacao DESC";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Itinerários - Sistema Ferroviário</title>

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
            background: gray;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .search-filter {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        .search-filter input,
        .search-filter select {
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

        .itinerarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .itinerario-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .itinerario-card:hover {
            transform: translateY(-5px);
        }

        .itinerario-header {
            background: gray;
            color: white;
            padding: 20px;
        }

        .itinerario-header h3 {
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        .itinerario-body {
            padding: 20px;
        }

        .itinerario-info p {
            margin: 10px 0;
            color: #666;
        }

        .itinerario-info strong {
            color: black;
            min-width: 100px;
            display: inline-block;
        }

        .itinerario-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .status-ativo {
            background: #d4edda;
            color: #155724;
        }

        .status-inativo {
            background: #f8d7da;
            color: #721c24;
        }

        .status-planejado {
            background: #fff3cd;
            color: #856404;
        }

        .itinerario-actions {
            display: flex;
            gap: 10px;
            padding-top: 15px;
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
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-editar {
            background: #667eea;
            color: white;
        }

        .btn-excluir {
            background: #e53e3e;
            color: white;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .itinerarios-grid {
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
            <h1>🗺️ Gerenciar Itinerários</h1>
            <a href="cadastroitinerário.php" class="btn-novo">➕ Novo Itinerário</a>
        </header>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="search-filter">
            <input type="text" name="busca" placeholder="🔍 Buscar itinerários..."
                value="<?php echo htmlspecialchars($busca); ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">Todos os Status</option>
                <option value="ativo" <?php echo $filtro_status === 'ativo' ? 'selected' : ''; ?>>Ativo</option>
                <option value="inativo" <?php echo $filtro_status === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                <option value="planejado" <?php echo $filtro_status === 'planejado' ? 'selected' : ''; ?>>Planejado
                </option>
            </select>
        </form>

        <div class="itinerarios-grid">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($itinerario = $resultado->fetch_assoc()): ?>
                    <div class="itinerario-card">
                        <div class="itinerario-header">
                            <h3><?php echo htmlspecialchars($itinerario['nome']); ?></h3>
                            <p>Trem: <?php echo htmlspecialchars($itinerario['trem_codigo']); ?></p>
                        </div>
                        <div class="itinerario-body">
                            <span class="itinerario-status status-<?php echo $itinerario['status']; ?>">
                                ● <?php echo ucfirst($itinerario['status']); ?>
                            </span>

                            <div class="itinerario-info">
                                <p><strong>📍 Distância:</strong> <?php echo $itinerario['distancia_total']; ?> km</p>
                                <p><strong>⏱️ Duração:</strong> <?php echo $itinerario['duracao_total']; ?> min</p>
                                <p><strong>🛤️ Rotas:</strong> <?php echo $itinerario['numero_rotas']; ?></p>
                                <?php if ($itinerario['descricao']): ?>
                                    <p><strong>📝 Descrição:</strong> <?php echo htmlspecialchars($itinerario['descricao']); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="itinerario-actions">
                                <a href="cadastroitinerário.php?editar=<?php echo $itinerario['id']; ?>"
                                    class="btn btn-editar">✏️ Editar</a>
                                <a href="?excluir=<?php echo $itinerario['id']; ?>" class="btn btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este itinerário?')">🗑️ Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align: center; color: white;">Nenhum itinerário encontrado.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
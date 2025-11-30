<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$mensagem = "";
$tipo_mensagem = "";

// ==================== EXCLUIR TREM ====================
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    // Verificar dependências
    $check_sensores = $conexao->query("SELECT COUNT(*) as total FROM sensores WHERE trem_id = $id");
    $check_itinerarios = $conexao->query("SELECT COUNT(*) as total FROM itinerarios WHERE trem_id = $id");

    $sensores = $check_sensores->fetch_assoc()['total'];
    $itinerarios = $check_itinerarios->fetch_assoc()['total'];

    if ($sensores > 0 || $itinerarios > 0) {
        $mensagem = "Não é possível excluir este trem. Há $sensores sensores e $itinerarios itinerários vinculados.";
        $tipo_mensagem = "error";
    } else {
        $stmt = $conexao->prepare("DELETE FROM trens WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $mensagem = "Trem excluído com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao excluir trem: " . $conexao->error;
            $tipo_mensagem = "error";
        }
        $stmt->close();
    }
}

// ==================== BUSCAR TRENS ====================
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$sql = "SELECT * FROM trens WHERE 1=1";

if (!empty($filtro_status)) {
    $sql .= " AND status = '" . $conexao->real_escape_string($filtro_status) . "'";
}

if (!empty($busca)) {
    $busca_escapada = $conexao->real_escape_string($busca);
    $sql .= " AND (nome LIKE '%$busca_escapada%' 
              OR codigo LIKE '%$busca_escapada%' 
              OR tipo LIKE '%$busca_escapada%' 
              OR modelo LIKE '%$busca_escapada%')";
}

$sql .= " ORDER BY data_cadastro DESC";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Trens - Sistema Ferroviário</title>

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
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
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

        .trens-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .trem-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .trem-card:hover {
            transform: translateY(-5px);
        }

        .trem-header {
            background: gray;
            color: white;
            padding: 20px;
        }

        .trem-header h3 {
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        .trem-body {
            padding: 20px;
        }

        .trem-info p {
            margin: 10px 0;
            color: #666;
        }

        .trem-info strong {
            color: black;
            min-width: 120px;
            display: inline-block;
        }

        .trem-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .status-operando {
            background: #d4edda;
            color: #155724;
        }

        .status-manutencao {
            background: #fff3cd;
            color: #856404;
        }

        .status-inativo {
            background: #f8d7da;
            color: #721c24;
        }

        .status-em_viagem {
            background: #d1ecf1;
            color: #0c5460;
        }

        .trem-actions {
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

            .trens-grid {
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
        <header>
            <h1>🚂 Gerenciar Trens</h1>
            <a href="cadastrartrem.php" class="btn-novo">➕ Novo Trem</a>
        </header>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="search-filter">
            <input type="text" name="busca" placeholder="🔍 Buscar trens..."
                value="<?php echo htmlspecialchars($busca); ?>">
            <select name="status" onchange="this.form.submit()">
                <option value="">Todos os Status</option>
                <option value="operando" <?php echo $filtro_status === 'operando' ? 'selected' : ''; ?>>Operando</option>
                <option value="manutencao" <?php echo $filtro_status === 'manutencao' ? 'selected' : ''; ?>>Em Manutenção
                </option>
                <option value="inativo" <?php echo $filtro_status === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                <option value="em_viagem" <?php echo $filtro_status === 'em_viagem' ? 'selected' : ''; ?>>Em Viagem
                </option>
            </select>
        </form>

        <div class="trens-grid">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($trem = $resultado->fetch_assoc()): ?>
                    <div class="trem-card">
                        <div class="trem-header">
                            <h3><?php echo htmlspecialchars($trem['nome']); ?></h3>
                            <p>Código: <?php echo htmlspecialchars($trem['codigo']); ?></p>
                        </div>
                        <div class="trem-body">
                            <span class="trem-status status-<?php echo $trem['status']; ?>">
                                ● <?php echo ucfirst(str_replace('_', ' ', $trem['status'])); ?>
                            </span>

                            <div class="trem-info">
                                <p><strong>Tipo:</strong> <?php echo ucfirst($trem['tipo']); ?></p>
                                <p><strong>Modelo:</strong> <?php echo htmlspecialchars($trem['modelo']); ?></p>
                                <p><strong>Capacidade:</strong> <?php echo number_format($trem['capacidade_passageiros']); ?>
                                    passageiros</p>
                                <p><strong>Vel. Máxima:</strong> <?php echo number_format($trem['velocidade_maxima']); ?> km/h
                                </p>
                                <?php if ($trem['ultima_manutencao']): ?>
                                    <p><strong>Última Manutenção:</strong>
                                        <?php echo date('d/m/Y', strtotime($trem['ultima_manutencao'])); ?></p>
                                <?php endif; ?>
                                <p><strong>KM Rodados:</strong> <?php echo number_format($trem['km_rodados'], 0, ',', '.'); ?>
                                    km</p>
                            </div>

                            <div class="trem-actions">
                                <a href="cadastrartrem.php?editar=<?php echo $trem['id']; ?>" class="btn btn-editar">✏️
                                    Editar</a>
                                <a href="?excluir=<?php echo $trem['id']; ?>" class="btn btn-excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este trem?')">🗑️ Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align: center; color: white;">Nenhum trem encontrado.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
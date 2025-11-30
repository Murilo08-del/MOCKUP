<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$mensagem = "";
$tipo_mensagem = "";

// Capturar mensagens da sessão
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipo_mensagem = $_SESSION['tipo_mensagem'];
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
}

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
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$sql = "SELECT * FROM trens WHERE 1=1";

if (!empty($filtro_status)) {
    $sql .= " AND status = '" . $conexao->real_escape_string($filtro_status) . "'";
}

if (!empty($filtro_tipo)) {
    $sql .= " AND tipo = '" . $conexao->real_escape_string($filtro_tipo) . "'";
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

// Estatísticas
$stats_total = $conexao->query("SELECT COUNT(*) as total FROM trens")->fetch_assoc()['total'];
$stats_operando = $conexao->query("SELECT COUNT(*) as total FROM trens WHERE status='operando'")->fetch_assoc()['total'];
$stats_manutencao = $conexao->query("SELECT COUNT(*) as total FROM trens WHERE status='manutencao'")->fetch_assoc()['total'];
$stats_inativos = $conexao->query("SELECT COUNT(*) as total FROM trens WHERE status='inativo'")->fetch_assoc()['total'];
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px 30px;
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
            color: #333;
            font-size: 2em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-novo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-novo:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-voltar {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.95em;
            transition: all 0.3s ease;
        }

        .btn-voltar:hover {
            background: #5a6268;
        }

        /* Estatísticas */
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
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
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

        .stat-card.total .number {
            color: #667eea;
        }

        .stat-card.operating .number {
            color: #38a169;
        }

        .stat-card.maintenance .number {
            color: #dd6b20;
        }

        .stat-card.inactive .number {
            color: #e53e3e;
        }

        /* Filtros */
        .search-filter {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .search-filter input,
        .search-filter select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .search-filter input:focus,
        .search-filter select:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-filtrar {
            flex: 1;
            background: #667eea;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-filtrar:hover {
            background: #5568d3;
        }

        .btn-limpar {
            background: #e0e0e0;
            color: #666;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-limpar:hover {
            background: #d0d0d0;
        }

        /* Mensagens */
        .mensagem {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mensagem.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .mensagem.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Grid de Trens */
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
            transition: all 0.3s ease;
        }

        .trem-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .trem-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .trem-header h3 {
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        .trem-header .codigo {
            opacity: 0.9;
            font-size: 0.95em;
        }

        .trem-body {
            padding: 20px;
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

        .trem-info {
            margin: 15px 0;
        }

        .trem-info p {
            margin: 10px 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .trem-info strong {
            color: #333;
            min-width: 120px;
        }

        .trem-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item .value {
            font-size: 1.5em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-item .label {
            font-size: 0.8em;
            color: #666;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
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
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
        }

        .empty-state .icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: stretch;
            }

            h1 {
                font-size: 1.5em;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .trens-grid {
                grid-template-columns: 1fr;
            }

            .trem-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div>
                <h1>🚂 Gerenciar Trens</h1>
                <p style="color: #666; margin-top: 5px;">Controle completo da frota de trens</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="dashboard.php" class="btn-voltar">← Voltar</a>
                <a href="cadastrartrem.php" class="btn-novo">➕ Novo Trem</a>
            </div>
        </header>

        <!-- Estatísticas -->
        <div class="stats-bar">
            <div class="stat-card total">
                <div class="number"><?php echo $stats_total; ?></div>
                <div class="label">Total de Trens</div>
            </div>
            <div class="stat-card operating">
                <div class="number"><?php echo $stats_operando; ?></div>
                <div class="label">Em Operação</div>
            </div>
            <div class="stat-card maintenance">
                <div class="number"><?php echo $stats_manutencao; ?></div>
                <div class="label">Em Manutenção</div>
            </div>
            <div class="stat-card inactive">
                <div class="number"><?php echo $stats_inativos; ?></div>
                <div class="label">Inativos</div>
            </div>
        </div>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <form method="GET" class="search-filter">
            <div class="filter-row">
                <input type="text" name="busca" placeholder="🔍 Buscar por nome, código, tipo ou modelo..."
                    value="<?php echo htmlspecialchars($busca); ?>">
                <select name="tipo">
                    <option value="">Todos os Tipos</option>
                    <option value="expresso" <?php echo $filtro_tipo === 'expresso' ? 'selected' : ''; ?>>Expresso
                    </option>
                    <option value="regional" <?php echo $filtro_tipo === 'regional' ? 'selected' : ''; ?>>Regional
                    </option>
                    <option value="metropolitano" <?php echo $filtro_tipo === 'metropolitano' ? 'selected' : ''; ?>>
                        Metropolitano</option>
                    <option value="luxo" <?php echo $filtro_tipo === 'luxo' ? 'selected' : ''; ?>>Luxo</option>
                    <option value="carga" <?php echo $filtro_tipo === 'carga' ? 'selected' : ''; ?>>Carga</option>
                </select>
                <select name="status">
                    <option value="">Todos os Status</option>
                    <option value="operando" <?php echo $filtro_status === 'operando' ? 'selected' : ''; ?>>Operando
                    </option>
                    <option value="manutencao" <?php echo $filtro_status === 'manutencao' ? 'selected' : ''; ?>>Em
                        Manutenção</option>
                    <option value="inativo" <?php echo $filtro_status === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                    <option value="em_viagem" <?php echo $filtro_status === 'em_viagem' ? 'selected' : ''; ?>>Em Viagem
                    </option>
                </select>
            </div>
            <div class="filter-buttons">
                <button type="submit" class="btn-filtrar">🔍 Filtrar</button>
                <a href="gerenciartrens.php" class="btn-limpar">🔄 Limpar Filtros</a>
            </div>
        </form>

        <!-- Grid de Trens -->
        <div class="trens-grid">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($trem = $resultado->fetch_assoc()): ?>
                    <div class="trem-card">
                        <div class="trem-header">
                            <h3><?php echo htmlspecialchars($trem['nome']); ?></h3>
                            <p class="codigo">Código: <?php echo htmlspecialchars($trem['codigo']); ?></p>
                        </div>
                        <div class="trem-body">
                            <span class="trem-status status-<?php echo $trem['status']; ?>">
                                ● <?php echo ucfirst(str_replace('_', ' ', $trem['status'])); ?>
                            </span>

                            <div class="trem-info">
                                <p><strong>🚂 Tipo:</strong> <?php echo ucfirst($trem['tipo']); ?></p>
                                <p><strong>🏭 Modelo:</strong> <?php echo htmlspecialchars($trem['modelo']); ?></p>
                                <?php if ($trem['fabricante']): ?>
                                    <p><strong>🏢 Fabricante:</strong> <?php echo htmlspecialchars($trem['fabricante']); ?></p>
                                <?php endif; ?>
                                <?php if ($trem['ano_fabricacao']): ?>
                                    <p><strong>📅 Ano:</strong> <?php echo $trem['ano_fabricacao']; ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="trem-stats">
                                <div class="stat-item">
                                    <div class="value"><?php echo number_format($trem['capacidade_passageiros']); ?></div>
                                    <div class="label">Passageiros</div>
                                </div>
                                <div class="stat-item">
                                    <div class="value"><?php echo number_format($trem['velocidade_maxima']); ?></div>
                                    <div class="label">km/h máx</div>
                                </div>
                                <div class="stat-item">
                                    <div class="value"><?php echo number_format($trem['km_rodados'], 0, ',', '.'); ?></div>
                                    <div class="label">km rodados</div>
                                </div>
                                <div class="stat-item">
                                    <div class="value">
                                        <?php
                                        if ($trem['ultima_manutencao']) {
                                            echo date('d/m/Y', strtotime($trem['ultima_manutencao']));
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </div>
                                    <div class="label">Últ. Manutenção</div>
                                </div>
                            </div>

                            <div class="trem-actions">
                                <a href="cadastrartrem.php?editar=<?php echo $trem['id']; ?>" class="btn btn-editar">
                                    ✏️ Editar
                                </a>
                                <a href="?excluir=<?php echo $trem['id']; ?>" class="btn btn-excluir"
                                    onclick="return confirm('⚠️ Tem certeza que deseja excluir o trem <?php echo htmlspecialchars($trem['nome']); ?>?\n\nEsta ação não pode ser desfeita!')">
                                    🗑️ Excluir
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">🚂</div>
                    <h3>Nenhum trem encontrado</h3>
                    <p>
                        <?php if (!empty($busca) || !empty($filtro_status) || !empty($filtro_tipo)): ?>
                            Nenhum resultado para os filtros aplicados.
                            <a href="gerenciartrens.php" style="color: #667eea;">Limpar filtros</a>
                        <?php else: ?>
                            Comece cadastrando o primeiro trem da frota.
                            <a href="cadastrartrem.php" style="color: #667eea;">Cadastrar agora</a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-submit ao mudar filtros (opcional)
        document.querySelectorAll('.search-filter select').forEach(select => {
            select.addEventListener('change', function () {
                // Comentado para permitir múltiplos filtros antes de buscar
                // this.form.submit();
            });
        });

        // Confirmação mais amigável para exclusão
        document.querySelectorAll('.btn-excluir').forEach(btn => {
            btn.addEventListener('click', function (e) {
                const tremNome = this.closest('.trem-card').querySelector('h3').textContent;
                if (!confirm(`⚠️ ATENÇÃO!\n\nDeseja realmente excluir o trem "${tremNome}"?\n\nEsta ação NÃO pode ser desfeita!`)) {
                    e.preventDefault();
                }
            });
        });

        // Animação de entrada dos cards
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.trem-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 50);
            });
        });
    </script>
</body>

</html>
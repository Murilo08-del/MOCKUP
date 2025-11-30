<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

// ==================== BUSCAR ESTATÍSTICAS REAIS DO BANCO ====================

// Contar trens ativos
$trens_ativos = $conexao->query("SELECT COUNT(*) as total FROM trens WHERE status IN ('operando', 'em_viagem')")->fetch_assoc()['total'];

// Contar estações
$total_estacoes = $conexao->query("SELECT COUNT(*) as total FROM estacoes WHERE status='ativa'")->fetch_assoc()['total'];

// Contar rotas ativas
$rotas_ativas = $conexao->query("SELECT COUNT(*) as total FROM rotas WHERE status='ativa'")->fetch_assoc()['total'];

// Contar alertas pendentes
$alertas_pendentes = $conexao->query("SELECT COUNT(*) as total FROM alertas WHERE status='pendente'")->fetch_assoc()['total'];

// Contar manutenções agendadas
$manutencoes_agendadas = $conexao->query("SELECT COUNT(*) as total FROM manutencoes WHERE status='agendada'")->fetch_assoc()['total'];

// Contar sensores ativos
$sensores_ativos = $conexao->query("SELECT COUNT(*) as total FROM sensores WHERE status='online'")->fetch_assoc()['total'];
$total_sensores = $conexao->query("SELECT COUNT(*) as total FROM sensores")->fetch_assoc()['total'];
$percentual_sensores = $total_sensores > 0 ? round(($sensores_ativos / $total_sensores) * 100) : 0;

// Buscar últimos alertas
$alertas_recentes = $conexao->query("SELECT a.*, s.nome as sensor_nome 
                                     FROM alertas a 
                                     LEFT JOIN sensores s ON a.sensor_id = s.id 
                                     WHERE a.status='pendente' 
                                     ORDER BY a.data_hora DESC 
                                     LIMIT 5");

// Buscar leituras mais recentes dos sensores
$sensores_tempo_real = $conexao->query("SELECT tipo, ultima_leitura, unidade_medida, status 
                                        FROM sensores 
                                        WHERE ultima_leitura IS NOT NULL 
                                        ORDER BY data_ultima_leitura DESC 
                                        LIMIT 4");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Ferroviário</title>

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

        .sidebar-header p {
            font-size: 0.85em;
            opacity: 0.8;
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
            background: gray;
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
        }

        h1 {
            color: black;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 1.1em;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .stat-card h3 {
            color: black;
            font-size: 1em;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 3em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .stat-change {
            font-size: 0.9em;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .stat-change.positive {
            background: #d4edda;
            color: #155724;
        }

        .stat-change.negative {
            background: #f8d7da;
            color: #721c24;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .chart-card h2 {
            color: black;
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .alerts-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .alerts-section h2 {
            color: black;
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .alert-item {
            padding: 15px;
            border-left: 4px solid;
            margin-bottom: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-item.critico {
            background: #fff5f5;
            border-color: #e53e3e;
        }

        .alert-item.aviso {
            background: #fffaf0;
            border-color: #dd6b20;
        }

        .alert-item.info {
            background: #ebf8ff;
            border-color: #3182ce;
        }

        .alert-time {
            color: #999;
            font-size: 0.9em;
        }

        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .sensor-card {
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .sensor-card h4 {
            font-size: 0.9em;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .sensor-value {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sensor-status {
            font-size: 0.8em;
            opacity: 0.8;
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

            h1 {
                font-size: 1.8em;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 2.5em;
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
        <div class="container">
            <header>
                <h1>🚆 Dashboard - Sistema Ferroviário</h1>
                <p class="subtitle">Visão geral do sistema em tempo real</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>🚂 Trens Ativos</h3>
                    <div class="stat-value"><?php echo $trens_ativos; ?></div>
                    <span class="stat-change positive">Em operação</span>
                </div>

                <div class="stat-card">
                    <h3>🏛 Estações</h3>
                    <div class="stat-value"><?php echo $total_estacoes; ?></div>
                    <span class="stat-change positive">Operacionais</span>
                </div>

                <div class="stat-card">
                    <h3>🛤️ Rotas Ativas</h3>
                    <div class="stat-value"><?php echo $rotas_ativas; ?></div>
                    <span class="stat-change positive">Disponíveis</span>
                </div>

                <div class="stat-card">
                    <h3>⚠️ Alertas Pendentes</h3>
                    <div class="stat-value"><?php echo $alertas_pendentes; ?></div>
                    <span class="stat-change <?php echo $alertas_pendentes > 0 ? 'negative' : 'positive'; ?>">
                        <?php echo $alertas_pendentes > 0 ? 'Requer atenção' : 'Tudo ok'; ?>
                    </span>
                </div>

                <div class="stat-card">
                    <h3>🔧 Manutenções Agendadas</h3>
                    <div class="stat-value"><?php echo $manutencoes_agendadas; ?></div>
                    <span class="stat-change positive">Programadas</span>
                </div>

                <div class="stat-card">
                    <h3>🔡 Sensores Ativos</h3>
                    <div class="stat-value"><?php echo $sensores_ativos; ?></div>
                    <span class="stat-change positive"><?php echo $percentual_sensores; ?>% online</span>
                </div>
            </div>

            <div class="chart-card">
                <h2>🔡 Monitoramento de Sensores em Tempo Real</h2>
                <div class="sensor-grid">
                    <?php if ($sensores_tempo_real && $sensores_tempo_real->num_rows > 0): ?>
                        <?php while ($sensor = $sensores_tempo_real->fetch_assoc()): ?>
                            <div class="sensor-card">
                                <h4><?php echo ucfirst($sensor['tipo']); ?></h4>
                                <div class="sensor-value">
                                    <?php echo number_format($sensor['ultima_leitura'], 1); ?>
                                    <?php echo $sensor['unidade_medida']; ?>
                                </div>
                                <div class="sensor-status">● <?php echo ucfirst($sensor['status']); ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="sensor-card">
                            <h4>Temperatura</h4>
                            <div class="sensor-value" id="temperatura">--</div>
                            <div class="sensor-status">● Aguardando dados</div>
                        </div>
                        <div class="sensor-card">
                            <h4>Umidade</h4>
                            <div class="sensor-value" id="umidade">--</div>
                            <div class="sensor-status">● Aguardando dados</div>
                        </div>
                        <div class="sensor-card">
                            <h4>Luminosidade</h4>
                            <div class="sensor-value" id="luminosidade">--</div>
                            <div class="sensor-status">● Aguardando dados</div>
                        </div>
                        <div class="sensor-card">
                            <h4>Presença</h4>
                            <div class="sensor-value" id="presenca">--</div>
                            <div class="sensor-status">● Aguardando dados</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alerts-section">
                <h2>🚨 Alertas Recentes</h2>

                <?php if ($alertas_recentes && $alertas_recentes->num_rows > 0): ?>
                    <?php while ($alerta = $alertas_recentes->fetch_assoc()): ?>
                        <div class="alert-item <?php echo $alerta['tipo']; ?>">
                            <div>
                                <strong><?php echo htmlspecialchars($alerta['titulo']); ?></strong>
                                <p><?php echo htmlspecialchars($alerta['descricao']); ?></p>
                            </div>
                            <span class="alert-time">
                                <?php
                                $diff = time() - strtotime($alerta['data_hora']);
                                if ($diff < 60)
                                    echo "há " . $diff . " segundos";
                                elseif ($diff < 3600)
                                    echo "há " . floor($diff / 60) . " minutos";
                                else
                                    echo "há " . floor($diff / 3600) . " horas";
                                ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <p>✅ Nenhum alerta pendente no momento</p>
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

        // Animação dos números ao carregar
        function animarNumeros() {
            const stats = document.querySelectorAll('.stat-value');
            stats.forEach(stat => {
                const text = stat.textContent.trim();
                if (text === '' || isNaN(parseInt(text))) return;

                const finalValue = parseInt(text);
                let currentValue = 0;
                const increment = finalValue / 50;

                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(currentValue);
                    }
                }, 20);
            });
        }

        window.addEventListener('load', animarNumeros);

        // Auto-atualizar a cada 30 segundos
        setTimeout(function () {
            location.reload();
        }, 30000);
    </script>
</body>

</html>
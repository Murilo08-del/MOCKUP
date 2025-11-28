<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Ferroviário</title>

    <style>
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        /* celular */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        body {
            display: flex;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            transition: margin-left 0.3s ease;
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
        }
    </style>


    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
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
            <li><a href="alertas.php"><span class="icon">🚨</span> Alertas</a></li>
            <li><a href="gerenciaritinerários.php"><span class="icon">📡</span> Gerenciar Itinerários</a></li>
            <li><a href="cadastroitinerário.php"><span class="icon">🔧</span> Cadastrar Itinerários</a></li>
            <li><a href="geraçãorelátorios.php"><span class="icon">📄</span> Geração de Relatórios</a></li>
            <li><a href="sobre.php"><span class="icon">ℹ️</span> Sobre o Sistema</a></li>
            <li><a href="rotas.php"><span class="icon">🗺️</span> Rotas com Mapa Interativo</a></li>
            <li><a href="../login.php"><span class="icon">👤</span> Sair</a></li>
        </ul>
    </aside>


    <!-- celular -->
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

    <!-- JAVASCRIPT DA SIDEBAR -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Fechar sidebar ao clicar fora (mobile)
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Marcar link ativo automaticamente
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
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #667eea;
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
            color: #667eea;
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

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .chart-card h2 {
            color: #667eea;
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
            color: #667eea;
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

        .alert-item.critical {
            background: #fff5f5;
            border-color: #e53e3e;
        }

        .alert-item.warning {
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            h1 {
                font-size: 1.8em;
            }

            .stats-grid,
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 2.5em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>🚆 Dashboard - Sistema Ferroviário</h1>
            <p class="subtitle">Visão geral do sistema em tempo real</p>
        </header>


        <div class="stats-grid">
            <div class="stat-card">
                <h3>🚂 Trens Ativos</h3>
                <div class="stat-value" id="trenosAtivos">12</div>
                <span class="stat-change positive">↑ 2 hoje</span>
            </div>

            <div class="stat-card">
                <h3>📍 Estações</h3>
                <div class="stat-value" id="totalEstacoes">8</div>
                <span class="stat-change positive">100% operacional</span>
            </div>

            <div class="stat-card">
                <h3>🛤️ Rotas Ativas</h3>
                <div class="stat-value" id="rotasAtivas">15</div>
                <span class="stat-change positive">↑ 1 esta semana</span>
            </div>

            <div class="stat-card">
                <h3>⚠️ Alertas Pendentes</h3>
                <div class="stat-value" id="alertasPendentes">3</div>
                <span class="stat-change negative">↑ 1 nova</span>
            </div>

            <div class="stat-card">
                <h3>🔧 Manutenções Agendadas</h3>
                <div class="stat-value" id="manutencoesAgendadas">5</div>
                <span class="stat-change positive">Em dia</span>
            </div>

            <div class="stat-card">
                <h3>📡 Sensores Ativos</h3>
                <div class="stat-value" id="sensoresAtivos">24</div>
                <span class="stat-change positive">96% online</span>
            </div>
        </div>

        <div class="chart-card">
            <h2>📡 Monitoramento de Sensores em Tempo Real</h2>
            <div class="sensor-grid">
                <div class="sensor-card">
                    <h4>Temperatura</h4>
                    <div class="sensor-value" id="temperatura">23.5°C</div>
                    <div class="sensor-status">● Online</div>
                </div>
                <div class="sensor-card">
                    <h4>Umidade</h4>
                    <div class="sensor-value" id="umidade">65%</div>
                    <div class="sensor-status">● Online</div>
                </div>
                <div class="sensor-card">
                    <h4>Luminosidade</h4>
                    <div class="sensor-value" id="luminosidade">1250</div>
                    <div class="sensor-status">● Online</div>
                </div>
                <div class="sensor-card">
                    <h4>Presença</h4>
                    <div class="sensor-value" id="presenca">Detectada</div>
                    <div class="sensor-status">● Online</div>
                </div>
            </div>
        </div>

        <div class="alerts-section">
            <h2>🚨 Alertas Recentes</h2>

            <div class="alert-item critical">
                <div>
                    <strong>Temperatura crítica - Trem #007</strong>
                    <p>Motor atingiu 95°C. Requer inspeção imediata.</p>
                </div>
                <span class="alert-time">há 5 min</span>
            </div>

            <div class="alert-item warning">
                <div>
                    <strong>Manutenção preventiva - Estação Central</strong>
                    <p>Próxima revisão agendada para 28/11/2024.</p>
                </div>
                <span class="alert-time">há 2 horas</span>
            </div>

            <div class="alert-item info">
                <div>
                    <strong>Nova rota cadastrada - Linha Azul</strong>
                    <p>Rota São Paulo → Campinas ativada com sucesso.</p>
                </div>
                <span class="alert-time">há 5 horas</span>
            </div>
        </div>
    </div>

    <script>
        // Simulação de atualização em tempo real
        function atualizarSensores() {
            document.getElementById('temperatura').textContent = (20 + Math.random() * 10).toFixed(1) + '°C';
            document.getElementById('umidade').textContent = (50 + Math.random() * 30).toFixed(0) + '%';
            document.getElementById('luminosidade').textContent = Math.floor(1000 + Math.random() * 1000);
            document.getElementById('presenca').textContent = Math.random() > 0.5 ? 'Detectada' : 'Ausente';
        }

        // Atualizar a cada 3 segundos
        setInterval(atualizarSensores, 3000);

        // Animação dos números ao carregar
        function animarNumeros() {
            const stats = document.querySelectorAll('.stat-value');
            stats.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
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
    </script>
</body>

</html>
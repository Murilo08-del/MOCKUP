<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alertas - Sistema Ferroviário</title>
    <!-- ==================== SIDEBAR - COPIAR EM TODAS AS PÁGINAS ==================== -->
    <style>
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

        /* MOBILE TOGGLE */
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

        /* AJUSTAR CONTEÚDO PRINCIPAL */
        body {
            display: flex;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        /* RESPONSIVE */
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

    <!-- MOBILE MENU TOGGLE -->
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
    <!-- ==================== FIM DA SIDEBAR ==================== -->


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
            transition: border-color 0.3s ease;
        }

        .filters input:focus,
        .filters select:focus {
            outline: none;
            border-color: #667eea;
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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

        .alerta-icon.warning {
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

        .badge-critical {
            background: #fff5f5;
            color: #e53e3e;
        }

        .badge-warning {
            background: #fffaf0;
            color: #dd6b20;
        }

        .badge-info {
            background: #ebf8ff;
            color: #3182ce;
        }

        .badge-resolved {
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

        .alerta-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
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

        .btn-resolver:hover {
            background: #2f855a;
            transform: translateY(-2px);
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

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5em;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .alerta-card {
                grid-template-columns: 1fr;
            }

            .alerta-actions {
                flex-direction: row;
                width: 100%;
            }

            .stats-bar {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>🚨 Gerenciamento de Alertas</h1>
            <p style="color: #666; margin-top: 5px;">Monitoramento e controle de alertas do sistema</p>
        </header>

        <!-- Estatísticas -->
        <div class="stats-bar">
            <div class="stat-card critical">
                <div class="number" id="countCritical">3</div>
                <div class="label">Críticos</div>
            </div>
            <div class="stat-card warning">
                <div class="number" id="countWarning">5</div>
                <div class="label">Avisos</div>
            </div>
            <div class="stat-card info">
                <div class="number" id="countInfo">8</div>
                <div class="label">Informativos</div>
            </div>
            <div class="stat-card resolved">
                <div class="number" id="countResolved">24</div>
                <div class="label">Resolvidos Hoje</div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters">
            <input type="text" id="searchInput" placeholder="🔍 Buscar alertas...">
            <select id="filterPrioridade">
                <option value="">Todas as Prioridades</option>
                <option value="critical">Crítico</option>
                <option value="warning">Aviso</option>
                <option value="info">Informativo</option>
            </select>
            <select id="filterStatus">
                <option value="pendente">Pendentes</option>
                <option value="resolvido">Resolvidos</option>
                <option value="">Todos</option>
            </select>
        </div>

        <!-- Lista de Alertas -->
        <div class="alertas-list" id="alertasList">
            <!-- Alerta 1 - Crítico -->
            <div class="alerta-card" data-prioridade="critical" data-status="pendente">
                <div class="alerta-icon critical">🔥</div>
                <div class="alerta-content">
                    <div class="alerta-header">
                        <div class="alerta-title">Temperatura Crítica - Motor Trem #007</div>
                        <span class="alerta-badge badge-critical">CRÍTICO</span>
                    </div>
                    <p class="alerta-description">
                        Sensor DHT11 detectou temperatura de 95°C no motor principal. Limite máximo: 80°C. Requer
                        inspeção imediata para evitar danos ao equipamento.
                    </p>
                    <div class="alerta-meta">
                        <span>📡 Sensor: TEMP-007</span>
                        <span>🚂 Trem: #007</span>
                        <span>⏰ há 5 minutos</span>
                    </div>
                </div>
                <div class="alerta-actions">
                    <button class="btn btn-resolver" onclick="resolverAlerta(1)">✔️ Resolver</button>
                    <button class="btn btn-excluir" onclick="excluirAlerta(1)">🗑️ Excluir</button>
                </div>
            </div>

            <!-- Alerta 2 - Crítico -->
            <div class="alerta-card" data-prioridade="critical" data-status="pendente">
                <div class="alerta-icon critical">⚠️</div>
                <div class="alerta-content">
                    <div class="alerta-header">
                        <div class="alerta-title">Sensor de Pressão Offline</div>
                        <span class="alerta-badge badge-critical">CRÍTICO</span>
                    </div>
                    <p class="alerta-description">
                        Sensor BMP180 na Estação Norte não responde há 2 horas. Sistema de monitoramento de pressão
                        comprometido.
                    </p>
                    <div class="alerta-meta">
                        <span>📡 Sensor: PRESS-006</span>
                        <span>🚉 Estação Norte</span>
                        <span>⏰ há 2 horas</span>
                    </div>
                </div>
                <div class="alerta-actions">
                    <button class="btn btn-resolver" onclick="resolverAlerta(2)">✔️ Resolver</button>
                    <button class="btn btn-excluir" onclick="excluirAlerta(2)">🗑️ Excluir</button>
                </div>
            </div>

            <!-- Alerta 3 - Aviso -->
            <div class="alerta-card" data-prioridade="warning" data-status="pendente">
                <div class="alerta-icon warning">⚡</div>
                <div class="alerta-content">
                    <div class="alerta-header">
                        <div class="alerta-title">Manutenção Preventiva Agendada</div>
                        <span class="alerta-badge badge-warning">AVISO</span>
                    </div>
                    <p class="alerta-description">
                        Manutenção preventiva do Trem #003 agendada para 28/11/2024. Verificar disponibilidade de
                        técnicos e peças de reposição.
                    </p>
                    <div class="alerta-meta">
                        <span>🚂 Trem: #003</span>
                        <span>📅 28/11/2024</span>
                        <span>⏰ há 3 horas</span>
                    </div>
                </div>
                <div class="alerta-actions">
                    <button class="btn btn-resolver" onclick="resolverAlerta(3)">✔️ Resolver</button>
                    <button class="btn btn-excluir" onclick="excluirAlerta(3)">🗑️ Excluir</button>
                </div>
            </div>

            <!-- Alerta 4 - Aviso -->
            <div class="alerta-card" data-prioridade="warning" data-status="pendente">
                <div class="alerta-icon warning">💡</div>
                <div class="alerta-content">
                    <div class="alerta-header">
                        <div class="alerta-title">Luminosidade Baixa - Linha Azul KM 15</div>
                        <span class="alerta-badge badge-warning">AVISO</span>
                    </div>
                    <p class="alerta-description">
                        Sensor LDR detectou luminosidade abaixo de 300 lux. Possível falha na iluminação da via ou
                        condições climáticas adversas.
                    </p>
                    <div class="alerta-meta">
                        <span>📡 Sensor: LDR-003</span>
                        <span>🛤️ Linha Azul - KM 15</span>
                        <span>⏰ há 1 hora</span>
                    </div>
                </div>
                <div class="alerta-actions">
                    <button class="btn btn-resolver" onclick="resolverAlerta(4)">✔️ Resolver</button>
                    <button class="btn btn-excluir" onclick="excluirAlerta(4)">🗑️ Excluir</button>
                </div>
            </div>

            <!-- Alerta 5 - Informativo -->
            <div class="alerta-card" data-prioridade="info" data-status="pendente">
                <div class="alerta-icon info">ℹ️</div>
                <div class="alerta-content">
                    <div class="alerta-header">
                        <div class="alerta-title">Nova Rota Cadastrada</div>
                        <span class="alerta-badge badge-info">INFO</span>
                    </div>
                    <p class="alerta-description">
                        Rota "São Paulo → Campinas" foi cadastrada com sucesso no sistema. Todas as estações
                        intermediárias foram validadas.
                    </p>
                    <div class="alerta-meta">
                        <span>🛤️ Rota: Linha Azul</span>
                        <span>⏰ há 5 horas</span>
                    </div>
                </div>
                <div class="alerta-actions">
                    <button class="btn btn-resolver" onclick="resolverAlerta(5)">✔️ OK</button>
                    <button class="btn btn-excluir" onclick="excluirAlerta(5)">🗑️ Excluir</button>
                </div>
            </div>

            <!-- Alerta 6 - Resolvido -->
            <div class="alerta-card" data-prioridade="info" data-status="resolvido" style="opacity: 0.7;">
                <div class="alerta-icon resolved">✅</div>
                <div class="alerta-content">
                    <div class="alerta-header">
                        <div class="alerta-title">Umidade Normalizada - Estação Central</div>
                        <span class="alerta-badge badge-resolved">RESOLVIDO</span>
                    </div>
                    <p class="alerta-description">
                        Umidade retornou aos níveis normais (55-70%). Problema identificado como variação climática
                        temporária.
                    </p>
                    <div class="alerta-meta">
                        <span>📡 Sensor: UMID-002</span>
                        <span>✅ Resolvido por: Admin João</span>
                        <span>⏰ há 6 horas</span>
                    </div>
                </div>
                <div class="alerta-actions">
                    <button class="btn btn-excluir" onclick="excluirAlerta(6)">🗑️ Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função de busca e filtro
        function filtrarAlertas() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const prioridadeFiltro = document.getElementById('filterPrioridade').value;
            const statusFiltro = document.getElementById('filterStatus').value;
            const cards = document.querySelectorAll('.alerta-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const prioridade = card.getAttribute('data-prioridade');
                const status = card.getAttribute('data-status');

                const matchSearch = text.includes(searchTerm);
                const matchPrioridade = !prioridadeFiltro || prioridade === prioridadeFiltro;
                const matchStatus = !statusFiltro || status === statusFiltro;

                if (matchSearch && matchPrioridade && matchStatus) {
                    card.style.display = 'grid';
                } else {
                    card.style.display = 'none';
                }
            });

            atualizarContadores();
        }

        document.getElementById('searchInput').addEventListener('input', filtrarAlertas);
        document.getElementById('filterPrioridade').addEventListener('change', filtrarAlertas);
        document.getElementById('filterStatus').addEventListener('change', filtrarAlertas);

        function resolverAlerta(id) {
            if (confirm('Marcar este alerta como resolvido?')) {
                // Aqui você faria a requisição para o backend
                alert(`✅ Alerta #${id} marcado como resolvido!`);
                location.reload();
            }
        }

        function excluirAlerta(id) {
            if (confirm('Tem certeza que deseja excluir este alerta?')) {
                alert(`Alerta #${id} excluído com sucesso!`);
                location.reload();
            }
        }

        function atualizarContadores() {
            const cards = document.querySelectorAll('.alerta-card');
            let critical = 0, warning = 0, info = 0, resolved = 0;

            cards.forEach(card => {
                if (card.style.display !== 'none') {
                    const prioridade = card.getAttribute('data-prioridade');
                    const status = card.getAttribute('data-status');

                    if (status === 'resolvido') {
                        resolved++;
                    } else if (prioridade === 'critical') {
                        critical++;
                    } else if (prioridade === 'warning') {
                        warning++;
                    } else if (prioridade === 'info') {
                        info++;
                    }
                }
            });

            document.getElementById('countCritical').textContent = critical;
            document.getElementById('countWarning').textContent = warning;
            document.getElementById('countInfo').textContent = info;
            document.getElementById('countResolved').textContent = resolved;
        }

        // Atualizar contadores ao carregar
        atualizarContadores();
    </script>
</body>

</html>
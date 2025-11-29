<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Itinerários - Sistema Ferroviário</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        h1 {
            color: #667eea;
            font-size: 2em;
        }

        .btn-novo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        }

        .search-bar input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #667eea;
        }

        .itinerarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
        }

        .itinerario-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .itinerario-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .itinerario-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .itinerario-header h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
        }

        .itinerario-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
            opacity: 0.9;
        }

        .itinerario-body {
            padding: 20px;
        }

        .rotas-timeline {
            position: relative;
            padding-left: 30px;
            margin: 20px 0;
        }

        .rotas-timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }

        .rota-item {
            position: relative;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .rota-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .rota-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 13px;
            height: 13px;
            background: white;
            border: 3px solid #667eea;
            border-radius: 50%;
        }

        .rota-numero {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .rota-nome {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .rota-detalhes {
            font-size: 0.9em;
            color: #666;
        }

        .itinerario-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
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
            font-size: 0.85em;
            color: #666;
            margin-top: 5px;
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
            header {
                flex-direction: column;
                align-items: stretch;
            }

            h1 {
                font-size: 1.5em;
            }

            .itinerarios-grid {
                grid-template-columns: 1fr;
            }

            .itinerario-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>🗺️ Gerenciar Itinerários</h1>
            <a href="cadastrar-itinerario.html" class="btn-novo">➕ Novo Itinerário</a>
        </header>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="🔍 Buscar itinerários por nome, origem ou destino...">
        </div>

        <div class="itinerarios-grid" id="itinerariosGrid">
            <!-- Itinerário 1 -->
            <div class="itinerario-card">
                <div class="itinerario-header">
                    <h3>Expresso São Paulo - Campinas</h3>
                    <div class="itinerario-info">
                        <span>🚂 Trem: #007</span>
                        <span>⏱️ 2h 30min</span>
                    </div>
                </div>
                <div class="itinerario-body">
                    <span class="itinerario-status status-ativo">● Ativo</span>

                    <div class="rotas-timeline">
                        <div class="rota-item">
                            <span class="rota-numero">Rota 1</span>
                            <div class="rota-nome">São Paulo Central → Jundiaí</div>
                            <div class="rota-detalhes">📍 45 km • ⏱️ 35 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 2</span>
                            <div class="rota-nome">Jundiaí → Várzea Paulista</div>
                            <div class="rota-detalhes">📍 30 km • ⏱️ 25 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 3</span>
                            <div class="rota-nome">Várzea Paulista → Campinas</div>
                            <div class="rota-detalhes">📍 40 km • ⏱️ 30 min</div>
                        </div>
                    </div>

                    <div class="itinerario-stats">
                        <div class="stat-item">
                            <div class="value">3</div>
                            <div class="label">Rotas</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">115</div>
                            <div class="label">km Total</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">4</div>
                            <div class="label">Estações</div>
                        </div>
                    </div>

                    <div class="itinerario-actions">
                        <button class="btn btn-editar" onclick="editarItinerario(1)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirItinerario(1)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>

            <!-- Itinerário 2 -->
            <div class="itinerario-card">
                <div class="itinerario-header">
                    <h3>Rota Litorânea Santos - Guarujá</h3>
                    <div class="itinerario-info">
                        <span>🚂 Trem: #003</span>
                        <span>⏱️ 1h 15min</span>
                    </div>
                </div>
                <div class="itinerario-body">
                    <span class="itinerario-status status-ativo">● Ativo</span>

                    <div class="rotas-timeline">
                        <div class="rota-item">
                            <span class="rota-numero">Rota 1</span>
                            <div class="rota-nome">Santos Central → Praia Grande</div>
                            <div class="rota-detalhes">📍 20 km • ⏱️ 20 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 2</span>
                            <div class="rota-nome">Praia Grande → São Vicente</div>
                            <div class="rota-detalhes">📍 15 km • ⏱️ 18 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 3</span>
                            <div class="rota-nome">São Vicente → Guarujá</div>
                            <div class="rota-detalhes">📍 25 km • ⏱️ 25 min</div>
                        </div>
                    </div>

                    <div class="itinerario-stats">
                        <div class="stat-item">
                            <div class="value">3</div>
                            <div class="label">Rotas</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">60</div>
                            <div class="label">km Total</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">4</div>
                            <div class="label">Estações</div>
                        </div>
                    </div>

                    <div class="itinerario-actions">
                        <button class="btn btn-editar" onclick="editarItinerario(2)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirItinerario(2)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>

            <!-- Itinerário 3 - Planejado -->
            <div class="itinerario-card">
                <div class="itinerario-header">
                    <h3>Interestadual SP - RJ</h3>
                    <div class="itinerario-info">
                        <span>🚂 Trem: #012</span>
                        <span>⏱️ 6h 00min</span>
                    </div>
                </div>
                <div class="itinerario-body">
                    <span class="itinerario-status status-planejado">● Em Planejamento</span>

                    <div class="rotas-timeline">
                        <div class="rota-item">
                            <span class="rota-numero">Rota 1</span>
                            <div class="rota-nome">São Paulo → Guarulhos</div>
                            <div class="rota-detalhes">📍 35 km • ⏱️ 30 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 2</span>
                            <div class="rota-nome">Guarulhos → Taubaté</div>
                            <div class="rota-detalhes">📍 90 km • ⏱️ 1h 15min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 3</span>
                            <div class="rota-nome">Taubaté → Aparecida</div>
                            <div class="rota-detalhes">📍 45 km • ⏱️ 35 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 4</span>
                            <div class="rota-nome">Aparecida → Resende</div>
                            <div class="rota-detalhes">📍 60 km • ⏱️ 50 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 5</span>
                            <div class="rota-nome">Resende → Rio de Janeiro</div>
                            <div class="rota-detalhes">📍 150 km • ⏱️ 2h 00min</div>
                        </div>
                    </div>

                    <div class="itinerario-stats">
                        <div class="stat-item">
                            <div class="value">5</div>
                            <div class="label">Rotas</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">380</div>
                            <div class="label">km Total</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">6</div>
                            <div class="label">Estações</div>
                        </div>
                    </div>

                    <div class="itinerario-actions">
                        <button class="btn btn-editar" onclick="editarItinerario(3)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirItinerario(3)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>

            <!-- Itinerário 4 - Inativo -->
            <div class="itinerario-card">
                <div class="itinerario-header">
                    <h3>Rota Noturna Metropolitana</h3>
                    <div class="itinerario-info">
                        <span>🚂 Trem: #005</span>
                        <span>⏱️ 3h 20min</span>
                    </div>
                </div>
                <div class="itinerario-body">
                    <span class="itinerario-status status-inativo">● Inativo</span>

                    <div class="rotas-timeline">
                        <div class="rota-item">
                            <span class="rota-numero">Rota 1</span>
                            <div class="rota-nome">Centro → Zona Leste</div>
                            <div class="rota-detalhes">📍 28 km • ⏱️ 35 min</div>
                        </div>

                        <div class="rota-item">
                            <span class="rota-numero">Rota 2</span>
                            <div class="rota-nome">Zona Leste → Zona Norte</div>
                            <div class="rota-detalhes">📍 32 km • ⏱️ 40 min</div>
                        </div>
                    </div>

                    <div class="itinerario-stats">
                        <div class="stat-item">
                            <div class="value">2</div>
                            <div class="label">Rotas</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">60</div>
                            <div class="label">km Total</div>
                        </div>
                        <div class="stat-item">
                            <div class="value">3</div>
                            <div class="label">Estações</div>
                        </div>
                    </div>

                    <div class="itinerario-actions">
                        <button class="btn btn-editar" onclick="editarItinerario(4)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirItinerario(4)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função de busca
        document.getElementById('searchInput').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.itinerario-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function editarItinerario(id) {
            window.location.href = `cadastrar-itinerario.html?id=${id}`;
        }

        function excluirItinerario(id) {
            if (confirm('Tem certeza que deseja excluir este itinerário? Esta ação não pode ser desfeita.')) {
                alert(`Itinerário #${id} excluído com sucesso!`);
                // Aqui você faria a requisição DELETE para o backend
                location.reload();
            }
        }
    </script>
</body>

</html>
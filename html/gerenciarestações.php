<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Estações - Sistema Ferroviário</title>

    <style>
        /* SIDEBAR UNIVERSAL */
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
            transition: border-color 0.3s ease;
        }

        .search-filter input:focus,
        .search-filter select:focus {
            outline: none;
            border-color: #667eea;
        }

        .estacoes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .estacao-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .estacao-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .estacao-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .estacao-header h3 {
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        .estacao-codigo {
            opacity: 0.9;
            font-size: 0.9em;
        }

        .estacao-body {
            padding: 20px;
        }

        .estacao-info {
            margin-bottom: 15px;
        }

        .estacao-info p {
            margin: 10px 0;
            color: #666;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .estacao-info strong {
            color: #667eea;
            min-width: 90px;
        }

        .estacao-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-box .number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-box .label {
            font-size: 0.85em;
            color: #666;
            margin-top: 5px;
        }

        .estacao-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .status-ativa {
            background: #d4edda;
            color: #155724;
        }

        .status-inativa {
            background: #f8d7da;
            color: #721c24;
        }

        .status-manutencao {
            background: #fff3cd;
            color: #856404;
        }

        .estacao-actions {
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

            .search-filter {
                grid-template-columns: 1fr;
            }

            .estacoes-grid {
                grid-template-columns: 1fr;
            }

            .estacao-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>🚉 Gerenciar Estações</h1>
            <a href="cadastrar-estacao.html" class="btn-novo">➕ Nova Estação</a>
        </header>

        <div class="search-filter">
            <input type="text" id="searchInput" placeholder="🔍 Buscar estações por nome ou localização...">
            <select id="filterStatus">
                <option value="">Todos os Status</option>
                <option value="ativa">Ativa</option>
                <option value="inativa">Inativa</option>
                <option value="manutencao">Em Manutenção</option>
            </select>
        </div>

        <div class="estacoes-grid" id="estacoesGrid">
            <!-- Estação 1 -->
            <div class="estacao-card">
                <div class="estacao-header">
                    <h3>Estação Central</h3>
                    <p class="estacao-codigo">Código: EST-001</p>
                </div>
                <div class="estacao-body">
                    <span class="estacao-status status-ativa">● Ativa</span>

                    <div class="estacao-info">
                        <p><strong>📍 Cidade:</strong> São Paulo</p>
                        <p><strong>📫 Endereço:</strong> Praça da Sé, Centro</p>
                        <p><strong>📞 Telefone:</strong> (11) 3000-1000</p>
                        <p><strong>👥 Capacidade:</strong> 5.000 pessoas</p>
                    </div>

                    <div class="estacao-stats">
                        <div class="stat-box">
                            <div class="number">12</div>
                            <div class="label">Plataformas</div>
                        </div>
                        <div class="stat-box">
                            <div class="number">8</div>
                            <div class="label">Rotas</div>
                        </div>
                    </div>

                    <div class="estacao-actions">
                        <button class="btn btn-editar" onclick="editarEstacao(1)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirEstacao(1)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>

            <!-- Estação 2 -->
            <div class="estacao-card">
                <div class="estacao-header">
                    <h3>Estação Norte</h3>
                    <p class="estacao-codigo">Código: EST-002</p>
                </div>
                <div class="estacao-body">
                    <span class="estacao-status status-ativa">● Ativa</span>

                    <div class="estacao-info">
                        <p><strong>📍 Cidade:</strong> São Paulo</p>
                        <p><strong>📫 Endereço:</strong> Av. Santos Dumont, 1500</p>
                        <p><strong>📞 Telefone:</strong> (11) 3000-2000</p>
                        <p><strong>👥 Capacidade:</strong> 3.000 pessoas</p>
                    </div>

                    <div class="estacao-stats">
                        <div class="stat-box">
                            <div class="number">8</div>
                            <div class="label">Plataformas</div>
                        </div>
                        <div class="stat-box">
                            <div class="number">5</div>
                            <div class="label">Rotas</div>
                        </div>
                    </div>

                    <div class="estacao-actions">
                        <button class="btn btn-editar" onclick="editarEstacao(2)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirEstacao(2)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>

            <!-- Estação 3 -->
            <div class="estacao-card">
                <div class="estacao-header">
                    <h3>Estação Campinas</h3>
                    <p class="estacao-codigo">Código: EST-003</p>
                </div>
                <div class="estacao-body">
                    <span class="estacao-status status-ativa">● Ativa</span>

                    <div class="estacao-info">
                        <p><strong>📍 Cidade:</strong> Campinas</p>
                        <p><strong>📫 Endereço:</strong> Centro, Campinas</p>
                        <p><strong>📞 Telefone:</strong> (19) 3000-3000</p>
                        <p><strong>👥 Capacidade:</strong> 2.500 pessoas</p>
                    </div>

                    <div class="estacao-stats">
                        <div class="stat-box">
                            <div class="number">6</div>
                            <div class="label">Plataformas</div>
                        </div>
                        <div class="stat-box">
                            <div class="number">4</div>
                            <div class="label">Rotas</div>
                        </div>
                    </div>

                    <div class="estacao-actions">
                        <button class="btn btn-editar" onclick="editarEstacao(3)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirEstacao(3)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>

            <!-- Estação 4 - Em Manutenção -->
            <div class="estacao-card">
                <div class="estacao-header">
                    <h3>Estação Sul</h3>
                    <p class="estacao-codigo">Código: EST-004</p>
                </div>
                <div class="estacao-body">
                    <span class="estacao-status status-manutencao">● Em Manutenção</span>

                    <div class="estacao-info">
                        <p><strong>📍 Cidade:</strong> Santos</p>
                        <p><strong>📫 Endereço:</strong> Av. Conselheiro Nébias, 200</p>
                        <p><strong>📞 Telefone:</strong> (13) 3000-4000</p>
                        <p><strong>👥 Capacidade:</strong> 1.800 pessoas</p>
                    </div>

                    <div class="estacao-stats">
                        <div class="stat-box">
                            <div class="number">4</div>
                            <div class="label">Plataformas</div>
                        </div>
                        <div class="stat-box">
                            <div class="number">3</div>
                            <div class="label">Rotas</div>
                        </div>
                    </div>

                    <div class="estacao-actions">
                        <button class="btn btn-editar" onclick="editarEstacao(4)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirEstacao(4)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função de busca
        document.getElementById('searchInput').addEventListener('input', function (e) {
            filtrarEstacoes();
        });

        document.getElementById('filterStatus').addEventListener('change', function (e) {
            filtrarEstacoes();
        });

        function filtrarEstacoes() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
            const cards = document.querySelectorAll('.estacao-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const matchSearch = text.includes(searchTerm);
                const matchStatus = !statusFilter || text.includes(statusFilter);

                if (matchSearch && matchStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function editarEstacao(id) {
            window.location.href = `editar-estacao.html?id=${id}`;
        }

        function excluirEstacao(id) {
            if (confirm('Tem certeza que deseja excluir esta estação? Esta ação afetará as rotas vinculadas.')) {
                alert(`Estação #${id} excluída com sucesso!`);
                // Aqui você faria a requisição DELETE para o backend
            }
        }
    </script>
</body>

</html>
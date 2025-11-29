<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Trens - Sistema Ferroviário</title>
    
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

        body {
            display: flex;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            min-height: 100vh;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
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
            margin: 0;
        }

        .btn-novo {
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .trem-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
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

        .trem-codigo {
            opacity: 0.9;
            font-size: 0.9em;
        }

        .trem-body {
            padding: 20px;
        }

        .trem-info p {
            margin: 10px 0;
            color: #666;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .trem-info strong {
            color: black;
            min-width: 120px;
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

            .search-filter {
                grid-template-columns: 1fr;
            }

            .trens-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
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
            <li><a href="gerenciartrens.php" class="active"><span class="icon">🚂</span> Gerenciar Trens</a></li>
            <li><a href="alertas.php"><span class="icon">🚨</span> Alertas</a></li>
            <li><a href="gerenciaritinerários.php"><span class="icon">🔡</span> Gerenciar Itinerários</a></li>
            <li><a href="cadastroitinerário.php"><span class="icon">🔧</span> Cadastrar Itinerários</a></li>
            <li><a href="geraçãorelátorios.php"><span class="icon">📄</span> Geração de Relatórios</a></li>
            <li><a href="sobre.php"><span class="icon">ℹ️</span> Sobre o Sistema</a></li>
            <li><a href="rotas.php"><span class="icon">🗺️</span> Rotas com Mapa Interativo</a></li>
            <li><a href="../login.php"><span class="icon">👤</span> Sair</a></li>
        </ul>
    </aside>

    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

    <main class="main-content">
        <div class="container">
            <header>
                <h1>🚂 Gerenciar Trens</h1>
                <span class="btn-novo">➕ Novo Trem</span>
            </header>

            <div class="search-filter">
                <input type="text" id="searchInput" placeholder="🔍 Buscar trens por nome, código ou tipo...">
                <select id="filterStatus">
                    <option value="">Todos os Status</option>
                    <option value="operando">Operando</option>
                    <option value="manutencao">Em Manutenção</option>
                    <option value="inativo">Inativo</option>
                    <option value="em_viagem">Em Viagem</option>
                </select>
            </div>

            <div class="trens-grid" id="trensGrid">
                <!-- Trem 1 -->
                <div class="trem-card">
                    <div class="trem-header">
                        <h3>Expresso Central</h3>
                        <p class="trem-codigo">Código: TRM-007</p>
                    </div>
                    <div class="trem-body">
                        <span class="trem-status status-operando">● Operando</span>

                        <div class="trem-info">
                            <p><strong>Tipo:</strong> Expresso</p>
                            <p><strong>Modelo:</strong> EMU-500</p>
                            <p><strong>Capacidade:</strong> 450 passageiros</p>
                            <p><strong>Vel. Máxima:</strong> 120 km/h</p>
                            <p><strong>Última Manutenção:</strong> 20/11/2024</p>
                            <p><strong>KM Rodados:</strong> 125.340 km</p>
                        </div>

                        <div class="trem-actions">
                            <button class="btn btn-editar" onclick="editarTrem(1)">✏️ Editar</button>
                            <button class="btn btn-excluir" onclick="excluirTrem(1)">🗑️ Excluir</button>
                        </div>
                    </div>
                </div>

                <!-- Trem 2 -->
                <div class="trem-card">
                    <div class="trem-header">
                        <h3>Regional Sul</h3>
                        <p class="trem-codigo">Código: TRM-003</p>
                    </div>
                    <div class="trem-body">
                        <span class="trem-status status-operando">● Operando</span>

                        <div class="trem-info">
                            <p><strong>Tipo:</strong> Regional</p>
                            <p><strong>Modelo:</strong> DMU-300</p>
                            <p><strong>Capacidade:</strong> 350 passageiros</p>
                            <p><strong>Vel. Máxima:</strong> 100 km/h</p>
                            <p><strong>Última Manutenção:</strong> 15/11/2024</p>
                            <p><strong>KM Rodados:</strong> 98.720 km</p>
                        </div>

                        <div class="trem-actions">
                            <button class="btn btn-editar" onclick="editarTrem(2)">✏️ Editar</button>
                            <button class="btn btn-excluir" onclick="excluirTrem(2)">🗑️ Excluir</button>
                        </div>
                    </div>
                </div>

                <!-- Trem 3 -->
                <div class="trem-card">
                    <div class="trem-header">
                        <h3>Metro Norte</h3>
                        <p class="trem-codigo">Código: TRM-005</p>
                    </div>
                    <div class="trem-body">
                        <span class="trem-status status-em_viagem">● Em Viagem</span>

                        <div class="trem-info">
                            <p><strong>Tipo:</strong> Metropolitano</p>
                            <p><strong>Modelo:</strong> METRO-400</p>
                            <p><strong>Capacidade:</strong> 600 passageiros</p>
                            <p><strong>Vel. Máxima:</strong> 80 km/h</p>
                            <p><strong>Última Manutenção:</strong> 10/11/2024</p>
                            <p><strong>KM Rodados:</strong> 156.890 km</p>
                        </div>

                        <div class="trem-actions">
                            <button class="btn btn-editar" onclick="editarTrem(3)">✏️ Editar</button>
                            <button class="btn btn-excluir" onclick="excluirTrem(3)">🗑️ Excluir</button>
                        </div>
                    </div>
                </div>

                <!-- Trem 4 - Em Manutenção -->
                <div class="trem-card">
                    <div class="trem-header">
                        <h3>Luxo Internacional</h3>
                        <p class="trem-codigo">Código: TRM-012</p>
                    </div>
                    <div class="trem-body">
                        <span class="trem-status status-manutencao">● Em Manutenção</span>

                        <div class="trem-info">
                            <p><strong>Tipo:</strong> Luxo</p>
                            <p><strong>Modelo:</strong> LUXE-200</p>
                            <p><strong>Capacidade:</strong> 200 passageiros</p>
                            <p><strong>Vel. Máxima:</strong> 150 km/h</p>
                            <p><strong>Última Manutenção:</strong> 22/11/2024</p>
                            <p><strong>KM Rodados:</strong> 87.450 km</p>
                        </div>

                        <div class="trem-actions">
                            <button class="btn btn-editar" onclick="editarTrem(4)">✏️ Editar</button>
                            <button class="btn btn-excluir" onclick="excluirTrem(4)">🗑️ Excluir</button>
                        </div>
                    </div>
                </div>
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

        document.getElementById('searchInput').addEventListener('input', filtrarTrens);
        document.getElementById('filterStatus').addEventListener('change', filtrarTrens);

        function filtrarTrens() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
            const cards = document.querySelectorAll('.trem-card');

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

        function editarTrem(id) {
            alert(`Editar trem #${id} - Funcionalidade em desenvolvimento`);
        }

        function excluirTrem(id) {
            if (confirm('Tem certeza que deseja excluir este trem? Esta ação não pode ser desfeita.')) {
                alert(`Trem #${id} excluído com sucesso!`);
                location.reload();
            }
        }
    </script>
</body>
</html>
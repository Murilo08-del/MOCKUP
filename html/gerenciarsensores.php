<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Sensores - Sistema Ferroviário</title>

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
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
            min-width: 80px;
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
        }

        .empty-state h2 {
            color: #667eea;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: stretch;
            }

            h1 {
                font-size: 1.5em;
            }

            .sensores-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Menu toggle (mobile) -->
    <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu">☰</button>

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


    <main class="main-content">
        <div class="container">
            <header>
                <h1>📡 Gerenciar Sensores</h1>
                <a href="cadastrar-sensor.html" class="btn-novo">➕ Novo Sensor</a>
            </header>

            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="🔍 Buscar sensores por nome, tipo ou localização...">
            </div>

            <div class="sensores-grid" id="sensoresGrid">
                <!-- Sensor 1 -->
                <div class="sensor-card">
                    <span class="sensor-status status-online">● Online</span>
                    <h3>Sensor de Temperatura #001</h3>
                    <div class="sensor-info">
                        <p><strong>Tipo:</strong> DHT11</p>
                        <p><strong>Local:</strong> Trem #007 - Motor</p>
                        <p><strong>Valor:</strong> 23.5°C</p>
                        <p><strong>Última leitura:</strong> há 2 min</p>
                        <p><strong>Tópico MQTT:</strong> sensores/temp/001</p>
                    </div>
                    <div class="sensor-actions">
                        <button class="btn btn-editar" onclick="editarSensor(1)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirSensor(1)">🗑️ Excluir</button>
                    </div>
                </div>

                <!-- Sensor 2 -->
                <div class="sensor-card">
                    <span class="sensor-status status-online">● Online</span>
                    <h3>Sensor de Umidade #002</h3>
                    <div class="sensor-info">
                        <p><strong>Tipo:</strong> DHT11</p>
                        <p><strong>Local:</strong> Estação Central</p>
                        <p><strong>Valor:</strong> 65%</p>
                        <p><strong>Última leitura:</strong> há 1 min</p>
                        <p><strong>Tópico MQTT:</strong> sensores/umid/002</p>
                    </div>
                    <div class="sensor-actions">
                        <button class="btn btn-editar" onclick="editarSensor(2)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirSensor(2)">🗑️ Excluir</button>
                    </div>
                </div>

                <!-- Sensor 3 -->
                <div class="sensor-card">
                    <span class="sensor-status status-online">● Online</span>
                    <h3>Sensor de Luminosidade #003</h3>
                    <div class="sensor-info">
                        <p><strong>Tipo:</strong> LDR</p>
                        <p><strong>Local:</strong> Linha Azul - KM 15</p>
                        <p><strong>Valor:</strong> 1250 lux</p>
                        <p><strong>Última leitura:</strong> há 30 seg</p>
                        <p><strong>Tópico MQTT:</strong> sensores/luz/003</p>
                    </div>
                    <div class="sensor-actions">
                        <button class="btn btn-editar" onclick="editarSensor(3)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirSensor(3)">🗑️ Excluir</button>
                    </div>
                </div>

                <!-- Sensor 4 -->
                <div class="sensor-card">
                    <span class="sensor-status status-online">● Online</span>
                    <h3>Sensor de Presença #004</h3>
                    <div class="sensor-info">
                        <p><strong>Tipo:</strong> HC-SR04 (Ultrassônico)</p>
                        <p><strong>Local:</strong> Trem #003 - Cabine</p>
                        <p><strong>Valor:</strong> Detectada</p>
                        <p><strong>Última leitura:</strong> há 15 seg</p>
                        <p><strong>Tópico MQTT:</strong> sensores/presenca/004</p>
                    </div>
                    <div class="sensor-actions">
                        <button class="btn btn-editar" onclick="editarSensor(4)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirSensor(4)">🗑️ Excluir</button>
                    </div>
                </div>

                <!-- Sensor 5 - Offline -->
                <div class="sensor-card">
                    <span class="sensor-status status-offline">● Offline</span>
                    <h3>Sensor de Velocidade #005</h3>
                    <div class="sensor-info">
                        <p><strong>Tipo:</strong> GPS</p>
                        <p><strong>Local:</strong> Trem #012</p>
                        <p><strong>Valor:</strong> N/A</p>
                        <p><strong>Última leitura:</strong> há 2 horas</p>
                        <p><strong>Tópico MQTT:</strong> sensores/velocidade/005</p>
                    </div>
                    <div class="sensor-actions">
                        <button class="btn btn-editar" onclick="editarSensor(5)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirSensor(5)">🗑️ Excluir</button>
                    </div>
                </div>

                <!-- Sensor 6 - Manutenção -->
                <div class="sensor-card">
                    <span class="sensor-status status-manutencao">● Manutenção</span>
                    <h3>Sensor de Pressão #006</h3>
                    <div class="sensor-info">
                        <p><strong>Tipo:</strong> BMP180</p>
                        <p><strong>Local:</strong> Estação Norte</p>
                        <p><strong>Valor:</strong> Em manutenção</p>
                        <p><strong>Última leitura:</strong> há 1 dia</p>
                        <p><strong>Tópico MQTT:</strong> sensores/pressao/006</p>
                    </div>
                    <div class="sensor-actions">
                        <button class="btn btn-editar" onclick="editarSensor(6)">✏️ Editar</button>
                        <button class="btn btn-excluir" onclick="excluirSensor(6)">🗑️ Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Função de busca
        document.getElementById('searchInput').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.sensor-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function editarSensor(id) {
            // Redirecionar para página de edição
            window.location.href = `editar-sensor.html?id=${id}`;
        }

        function excluirSensor(id) {
            if (confirm('Tem certeza que deseja excluir este sensor?')) {
                // Aqui você faria a requisição para o backend
                alert(`Sensor #${id} excluído com sucesso!`);
                // Recarregar a página ou remover o card do DOM
            }
        }

        // Simulação de atualização em tempo real
        setInterval(() => {
            const valores = document.querySelectorAll('.sensor-info p:nth-child(3)');
            valores.forEach(valor => {
                if (valor.textContent.includes('°C')) {
                    const temp = (20 + Math.random() * 10).toFixed(1);
                    valor.innerHTML = `<strong>Valor:</strong> ${temp}°C`;
                } else if (valor.textContent.includes('%')) {
                    const umid = (50 + Math.random() * 30).toFixed(0);
                    valor.innerHTML = `<strong>Valor:</strong> ${umid}%`;
                } else if (valor.textContent.includes('lux')) {
                    const luz = Math.floor(1000 + Math.random() * 1000);
                    valor.innerHTML = `<strong>Valor:</strong> ${luz} lux`;
                }
            });
        }, 5000);

        // Script do menu (toggle para mobile e fechamento ao clicar fora ou em um link)
        (function () {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
            }

            menuToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleSidebar();
            });

            // Fechar sidebar ao clicar em link (útil em mobile)
            const links = document.querySelectorAll('.sidebar-menu a');
            links.forEach(link => link.addEventListener('click', function () {
                sidebar.classList.remove('active');
            }));

            // Fechar quando clicar fora (mobile)
            document.addEventListener('click', function (event) {
                const isClickInside = sidebar.contains(event.target) || menuToggle.contains(event.target);
                if (!isClickInside) {
                    sidebar.classList.remove('active');
                }
            });
        })();
    </script>
</body>

</html>
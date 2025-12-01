<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas - Mapa Interativo - Sistema Ferroviário</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            display: flex;
            min-height: 100vh;
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

        /* Menu (celular) */
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

        /* Conteúdo principal */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-novo:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .map-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        #map {
            width: 100%;
            height: 500px;
            border-radius: 10px;
        }

        .rotas-lista {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .rotas-lista h2 {
            color: black;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .rota-item {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .rota-item:hover {
            border-color: #667eea;
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .rota-item.active {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .rota-item h3 {
            color: #333;
            margin-bottom: 8px;
            font-size: 1.1em;
        }

        .rota-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 0.9em;
            color: #666;
        }

        .rota-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legenda {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .legenda h3 {
            color: black;
            margin-bottom: 15px;
            font-size: 1.1em;
        }

        .legenda-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .legenda-cor {
            width: 30px;
            height: 4px;
            border-radius: 2px;
        }

        .tools-panel {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .tools-panel h3 {
            color: black;
            margin-bottom: 15px;
        }

        .tool-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tool-btn {
            padding: 10px 20px;
            border: 2px solid gray;
            background: white;
            color: black;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .tool-btn:hover,
        .tool-btn.active {
            background: #667eea;
            color: white;
        }

        /* Responsivo */
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
                padding: 80px 15px 15px;
            }

            h1 {
                font-size: 1.5em;
            }

            #map {
                height: 350px;
            }

            header {
                flex-direction: column;
                align-items: stretch;
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

    <!-- Menu (celular) -->
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Conteúdo principal -->
    <main class="main-content">
        <header>
            <h1>🛤️ Rotas - Mapa Interativo</h1>
            <a href="cadastrar-rota.html" class="btn-novo">➕ Nova Rota</a>
        </header>

        <!-- Ferramentas do Mapa -->
        <div class="tools-panel">
            <h3>🛠️ Ferramentas de Desenho</h3>
            <div class="tool-buttons">
                <button class="tool-btn" onclick="ativarModoDesenho()">✏️ Desenhar Rota</button>
                <button class="tool-btn" onclick="limparMapa()">🗑️ Limpar</button>
                <button class="tool-btn" onclick="salvarRota()">💾 Salvar Rota</button>
                <button class="tool-btn" onclick="centralizarMapa()">🎯 Centralizar</button>
            </div>
        </div>

        <!-- Mapa Interativo -->
        <div class="map-container">
            <div id="map"></div>

            <div class="legenda">
                <h3>📌 Legenda</h3>
                <div class="legenda-item">
                    <div class="legenda-cor" style="background: #3388ff;"></div>
                    <span>Rota Ativa</span>
                </div>
                <div class="legenda-item">
                    <div class="legenda-cor" style="background: #ff3333;"></div>
                    <span>Rota em Manutenção</span>
                </div>
                <div class="legenda-item">
                    <div class="legenda-cor" style="background: #33cc33;"></div>
                    <span>Nova Rota (desenho)</span>
                </div>
                <div class="legenda-item">
                    <div class="legenda-cor"
                        style="background: #ff9933; width: 15px; height: 15px; border-radius: 50%;"></div>
                    <span>Estações</span>
                </div>
            </div>
        </div>

        <!-- Lista de Rotas -->
        <div class="rotas-lista">
            <h2>📋 Rotas Cadastradas</h2>

            <div class="rota-item" onclick="mostrarRotaNoMapa(1)">
                <h3>São Paulo → Jundiaí</h3>
                <div class="rota-info">
                    <span>📍 45 km</span>
                    <span>⏱️ 35 min</span>
                    <span>🚂 Linha Azul</span>
                    <span style="color: #38a169;">● Ativa</span>
                </div>
            </div>

            <div class="rota-item" onclick="mostrarRotaNoMapa(2)">
                <h3>Jundiaí → Campinas</h3>
                <div class="rota-info">
                    <span>📍 40 km</span>
                    <span>⏱️ 30 min</span>
                    <span>🚂 Linha Azul</span>
                    <span style="color: #38a169;">● Ativa</span>
                </div>
            </div>

            <div class="rota-item" onclick="mostrarRotaNoMapa(3)">
                <h3>Santos → Praia Grande</h3>
                <div class="rota-info">
                    <span>📍 20 km</span>
                    <span>⏱️ 20 min</span>
                    <span>🚂 Linha Verde</span>
                    <span style="color: #38a169;">● Ativa</span>
                </div>
            </div>

            <div class="rota-item" onclick="mostrarRotaNoMapa(4)">
                <h3>Estação Central → Zona Norte</h3>
                <div class="rota-info">
                    <span>📍 28 km</span>
                    <span>⏱️ 35 min</span>
                    <span>🚂 Linha Vermelha</span>
                    <span style="color: #dd6b20;">● Em Manutenção</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Inicializar o mapa centrado em São Paulo
        const map = L.map('map').setView([-23.5505, -46.6333], 10);

        // Adicionar camada do OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);

        // Dados das rotas
        const rotas = {
            1: {
                nome: "São Paulo → Jundiaí",
                coords: [
                    [-23.5505, -46.6333],  // São Paulo
                    [-23.1900, -46.8900]   // Jundiaí
                ],
                cor: '#3388ff'
            },
            2: {
                nome: "Jundiaí → Campinas",
                coords: [
                    [-23.1900, -46.8900],  // Jundiaí
                    [-22.9099, -47.0626]   // Campinas
                ],
                cor: '#3388ff'
            },
            3: {
                nome: "Santos → Praia Grande",
                coords: [
                    [-23.9608, -46.3334],  // Santos
                    [-24.0059, -46.4126]   // Praia Grande
                ],
                cor: '#3388ff'
            },
            4: {
                nome: "Estação Central → Zona Norte",
                coords: [
                    [-23.5505, -46.6333],
                    [-23.4700, -46.6300]
                ],
                cor: '#ff3333'
            }
        };

        // Adicionar estações como marcadores
        const estacoes = [
            { nome: "Estação Central SP", coords: [-23.5505, -46.6333] },
            { nome: "Estação Jundiaí", coords: [-23.1900, -46.8900] },
            { nome: "Estação Campinas", coords: [-22.9099, -47.0626] },
            { nome: "Estação Santos", coords: [-23.9608, -46.3334] },
            { nome: "Estação Praia Grande", coords: [-24.0059, -46.4126] }
        ];

        estacoes.forEach(estacao => {
            const marker = L.circleMarker(estacao.coords, {
                radius: 8,
                fillColor: '#ff9933',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            marker.bindPopup(`<b>🚉 ${estacao.nome}</b>`);
        });

        // Camada para desenhos
        let drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        let modoDesenho = false;
        let pontos = [];

        function mostrarRotaNoMapa(rotaId) {
            // Limpar rotas anteriores
            drawnItems.clearLayers();

            // Remover classe active de todos os itens
            document.querySelectorAll('.rota-item').forEach(item => {
                item.classList.remove('active');
            });

            // Adicionar classe active ao item clicado
            event.currentTarget.classList.add('active');

            const rota = rotas[rotaId];

            // Desenhar a rota
            const polyline = L.polyline(rota.coords, {
                color: rota.cor,
                weight: 5,
                opacity: 0.7
            }).addTo(drawnItems);

            polyline.bindPopup(`<b>${rota.nome}</b>`);

            // Centralizar mapa na rota
            map.fitBounds(polyline.getBounds());
        }

        function ativarModoDesenho() {
            modoDesenho = !modoDesenho;
            pontos = [];

            if (modoDesenho) {
                alert('✏️ Modo de desenho ativado! Clique no mapa para adicionar pontos da rota.');
                document.querySelector('.tool-btn').classList.add('active');

                map.on('click', function (e) {
                    if (modoDesenho) {
                        pontos.push([e.latlng.lat, e.latlng.lng]);

                        // Adicionar marcador
                        L.circleMarker([e.latlng.lat, e.latlng.lng], {
                            radius: 5,
                            fillColor: '#33cc33',
                            color: '#fff',
                            weight: 2,
                            fillOpacity: 0.8
                        }).addTo(drawnItems);

                        // Desenhar linha se houver mais de um ponto
                        if (pontos.length > 1) {
                            L.polyline(pontos, {
                                color: '#33cc33',
                                weight: 5,
                                opacity: 0.7
                            }).addTo(drawnItems);
                        }
                    }
                });
            } else {
                document.querySelector('.tool-btn').classList.remove('active');
                map.off('click');
            }
        }

        function limparMapa() {
            drawnItems.clearLayers();
            pontos = [];
            alert('🗑️ Mapa limpo!');
        }

        function salvarRota() {
            if (pontos.length < 2) {
                alert('⚠️ Desenhe uma rota com pelo menos 2 pontos!');
                return;
            }

            const nome = prompt('Digite o nome da rota:');
            if (nome) {
                console.log('Salvando rota:', {
                    nome: nome,
                    coordenadas: pontos
                });
                alert(`✅ Rota "${nome}" salva com sucesso!`);
                modoDesenho = false;
                pontos = [];
            }
        }

        function centralizarMapa() {
            map.setView([-23.5505, -46.6333], 10);
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Fechar sidebar ao clicar fora (celular)
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Ajustar tamanho do mapa quando a janela é redimensionada
        window.addEventListener('resize', function () {
            map.invalidateSize();
        });
    </script>
</body>

</html>
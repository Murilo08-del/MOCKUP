<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geração de Relatórios - Sistema Ferroviário</title>
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
            background: black;
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
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
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
            font-size: 2em;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
        }

        .relatorios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .relatorio-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .relatorio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .relatorio-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .relatorio-card h3 {
            color: #333;
            font-size: 1.3em;
            margin-bottom: 10px;
        }

        .relatorio-card p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .filtros {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .filtros label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .filtros select,
        .filtros input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95em;
            margin-bottom: 10px;
        }

        .filtros select:focus,
        .filtros input:focus {
            outline: none;
            border-color: #667eea;
        }

        .date-range {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-gerar {
            background: gray;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-gerar:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-exportar {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-exportar button {
            flex: 1;
            padding: 10px;
            border: 2px solid gray;
            background: white;
            color: black;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-exportar button:hover {
            background: #667eea;
            color: white;
        }

        .resultado-relatorio {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .resultado-relatorio.show {
            display: block;
        }

        .resultado-relatorio h2 {
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        .stats-resumo {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-box .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-box .label {
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5em;
            }

            .relatorios-grid {
                grid-template-columns: 1fr;
            }

            .date-range {
                grid-template-columns: 1fr;
            }

            .btn-exportar {
                flex-direction: column;
            }

            table {
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>📊 Geração de Relatórios</h1>
            <p class="subtitle">Gere relatórios detalhados do sistema ferroviário</p>
        </header>

        <div class="relatorios-grid">
            <!-- Relatório de Sensores -->
            <div class="relatorio-card">
                <div class="relatorio-icon">📡</div>
                <h3>Relatório de Sensores</h3>
                <p>Visualize dados de todos os sensores, incluindo status, leituras e alertas gerados.</p>

                <div class="filtros">
                    <label>Período:</label>
                    <div class="date-range">
                        <input type="date" id="dataSensoresInicio" value="2024-11-01">
                        <input type="date" id="dataSensoresFim" value="2024-11-24">
                    </div>
                    <label>Status:</label>
                    <select id="statusSensores">
                        <option value="todos">Todos</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="manutencao">Em Manutenção</option>
                    </select>
                </div>

                <button class="btn-gerar" onclick="gerarRelatorioSensores()">📄 Gerar Relatório</button>
                <div class="btn-exportar">
                    <button onclick="exportarPDF('sensores')">📄 PDF</button>
                    <button onclick="exportarCSV('sensores')">📊 CSV</button>
                </div>
            </div>

            <!-- Relatório de Rotas -->
            <div class="relatorio-card">
                <div class="relatorio-icon">🛤️</div>
                <h3>Relatório de Rotas</h3>
                <p>Analise o desempenho das rotas, frequência de uso e tempos de percurso.</p>

                <div class="filtros">
                    <label>Período:</label>
                    <div class="date-range">
                        <input type="date" id="dataRotasInicio" value="2024-11-01">
                        <input type="date" id="dataRotasFim" value="2024-11-24">
                    </div>
                    <label>Tipo:</label>
                    <select id="tipoRotas">
                        <option value="todas">Todas as Rotas</option>
                        <option value="ativas">Apenas Ativas</option>
                        <option value="mais-usadas">Mais Utilizadas</option>
                    </select>
                </div>

                <button class="btn-gerar" onclick="gerarRelatorioRotas()">📄 Gerar Relatório</button>
                <div class="btn-exportar">
                    <button onclick="exportarPDF('rotas')">📄 PDF</button>
                    <button onclick="exportarCSV('rotas')">📊 CSV</button>
                </div>
            </div>

            <!-- Relatório de Itinerários -->
            <div class="relatorio-card">
                <div class="relatorio-icon">🗺️</div>
                <h3>Relatório de Itinerários</h3>
                <p>Acompanhe itinerários completos, incluindo todas as rotas e tempos totais.</p>

                <div class="filtros">
                    <label>Período:</label>
                    <div class="date-range">
                        <input type="date" id="dataItinerariosInicio" value="2024-11-01">
                        <input type="date" id="dataItinerariosFim" value="2024-11-24">
                    </div>
                    <label>Status:</label>
                    <select id="statusItinerarios">
                        <option value="todos">Todos</option>
                        <option value="ativo">Ativos</option>
                        <option value="planejado">Planejados</option>
                        <option value="inativo">Inativos</option>
                    </select>
                </div>

                <button class="btn-gerar" onclick="gerarRelatorioItinerarios()">📄 Gerar Relatório</button>
                <div class="btn-exportar">
                    <button onclick="exportarPDF('itinerarios')">📄 PDF</button>
                    <button onclick="exportarCSV('itinerarios')">📊 CSV</button>
                </div>
            </div>

            <!-- Relatório de Manutenções -->
            <div class="relatorio-card">
                <div class="relatorio-icon">🔧</div>
                <h3>Relatório de Manutenções</h3>
                <p>Visualize histórico de manutenções realizadas e agendadas dos trens.</p>

                <div class="filtros">
                    <label>Período:</label>
                    <div class="date-range">
                        <input type="date" id="dataManutencoesInicio" value="2024-11-01">
                        <input type="date" id="dataManutencoesFim" value="2024-11-24">
                    </div>
                    <label>Tipo:</label>
                    <select id="tipoManutencao">
                        <option value="todas">Todas</option>
                        <option value="preventiva">Preventiva</option>
                        <option value="corretiva">Corretiva</option>
                        <option value="emergencial">Emergencial</option>
                    </select>
                </div>

                <button class="btn-gerar" onclick="gerarRelatorioManutencoes()">📄 Gerar Relatório</button>
                <div class="btn-exportar">
                    <button onclick="exportarPDF('manutencoes')">📄 PDF</button>
                    <button onclick="exportarCSV('manutencoes')">📊 CSV</button>
                </div>
            </div>

            <!-- Relatório de Trens -->
            <div class="relatorio-card">
                <div class="relatorio-icon">🚂</div>
                <h3>Relatório de Trens</h3>
                <p>Informações completas sobre a frota de trens e seu status operacional.</p>

                <div class="filtros">
                    <label>Status:</label>
                    <select id="statusTrens">
                        <option value="todos">Todos</option>
                        <option value="ativo">Em Operação</option>
                        <option value="manutencao">Em Manutenção</option>
                        <option value="inativo">Inativos</option>
                    </select>
                    <label>Tipo:</label>
                    <select id="tipoTrens">
                        <option value="todos">Todos os Tipos</option>
                        <option value="expresso">Expresso</option>
                        <option value="regional">Regional</option>
                        <option value="metropolitano">Metropolitano</option>
                    </select>
                </div>

                <button class="btn-gerar" onclick="gerarRelatorioTrens()">📄 Gerar Relatório</button>
                <div class="btn-exportar">
                    <button onclick="exportarPDF('trens')">📄 PDF</button>
                    <button onclick="exportarCSV('trens')">📊 CSV</button>
                </div>
            </div>

            <!-- Relatório de Estações -->
            <div class="relatorio-card">
                <div class="relatorio-icon">🚉</div>
                <h3>Relatório de Estações</h3>
                <p>Dados sobre todas as estações, capacidade e movimentação.</p>

                <div class="filtros">
                    <label>Período:</label>
                    <div class="date-range">
                        <input type="date" id="dataEstacoesInicio" value="2024-11-01">
                        <input type="date" id="dataEstacoesFim" value="2024-11-24">
                    </div>
                    <label>Ordenar por:</label>
                    <select id="ordenarEstacoes">
                        <option value="nome">Nome</option>
                        <option value="capacidade">Capacidade</option>
                        <option value="movimentacao">Movimentação</option>
                    </select>
                </div>

                <button class="btn-gerar" onclick="gerarRelatorioEstacoes()">📄 Gerar Relatório</button>
                <div class="btn-exportar">
                    <button onclick="exportarPDF('estacoes')">📄 PDF</button>
                    <button onclick="exportarCSV('estacoes')">📊 CSV</button>
                </div>
            </div>
        </div>

        <!-- Área de Resultado -->
        <div class="resultado-relatorio" id="resultadoRelatorio">
            <h2 id="tituloRelatorio">Relatório Gerado</h2>

            <div class="stats-resumo" id="statsResumo"></div>

            <div id="conteudoRelatorio"></div>
        </div>
    </div>

    <script>
        function gerarRelatorioSensores() {
            mostrarResultado('Relatório de Sensores', `
                <div class="stats-resumo">
                    <div class="stat-box">
                        <div class="number">24</div>
                        <div class="label">Total de Sensores</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">23</div>
                        <div class="label">Online</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">1</div>
                        <div class="label">Offline</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">156</div>
                        <div class="label">Alertas Gerados</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Localização</th>
                            <th>Status</th>
                            <th>Última Leitura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#001</td>
                            <td>Sensor Temperatura</td>
                            <td>DHT11</td>
                            <td>Trem #007</td>
                            <td>🟢 Online</td>
                            <td>23.5°C - há 2 min</td>
                        </tr>
                        <tr>
                            <td>#002</td>
                            <td>Sensor Umidade</td>
                            <td>DHT11</td>
                            <td>Estação Central</td>
                            <td>🟢 Online</td>
                            <td>65% - há 1 min</td>
                        </tr>
                        <tr>
                            <td>#003</td>
                            <td>Sensor Luminosidade</td>
                            <td>LDR</td>
                            <td>Linha Azul KM15</td>
                            <td>🟢 Online</td>
                            <td>1250 lux - há 30 seg</td>
                        </tr>
                        <tr>
                            <td>#004</td>
                            <td>Sensor Presença</td>
                            <td>HC-SR04</td>
                            <td>Trem #003</td>
                            <td>🟢 Online</td>
                            <td>Detectada - há 15 seg</td>
                        </tr>
                    </tbody>
                </table>
            `);
        }

        function gerarRelatorioRotas() {
            mostrarResultado('Relatório de Rotas', `
                <div class="stats-resumo">
                    <div class="stat-box">
                        <div class="number">15</div>
                        <div class="label">Total de Rotas</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">12</div>
                        <div class="label">Rotas Ativas</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">845</div>
                        <div class="label">km Totais</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">1.234</div>
                        <div class="label">Viagens/Mês</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Rota</th>
                            <th>Origem → Destino</th>
                            <th>Distância</th>
                            <th>Tempo Médio</th>
                            <th>Viagens/Dia</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R-001</td>
                            <td>São Paulo → Jundiaí</td>
                            <td>45 km</td>
                            <td>35 min</td>
                            <td>24</td>
                            <td>🟢 Ativa</td>
                        </tr>
                        <tr>
                            <td>R-002</td>
                            <td>Jundiaí → Campinas</td>
                            <td>40 km</td>
                            <td>30 min</td>
                            <td>20</td>
                            <td>🟢 Ativa</td>
                        </tr>
                        <tr>
                            <td>R-003</td>
                            <td>Santos → Praia Grande</td>
                            <td>20 km</td>
                            <td>20 min</td>
                            <td>32</td>
                            <td>🟢 Ativa</td>
                        </tr>
                    </tbody>
                </table>
            `);
        }

        function gerarRelatorioItinerarios() {
            mostrarResultado('Relatório de Itinerários', `
                <div class="stats-resumo">
                    <div class="stat-box">
                        <div class="number">8</div>
                        <div class="label">Itinerários Ativos</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">3.5</div>
                        <div class="label">Horas Médias</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">2.340</div>
                        <div class="label">km Percorridos</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Itinerário</th>
                            <th>Trem</th>
                            <th>Nº Rotas</th>
                            <th>Distância Total</th>
                            <th>Duração</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Expresso SP-Campinas</td>
                            <td>#007</td>
                            <td>3</td>
                            <td>115 km</td>
                            <td>2h 30min</td>
                            <td>🟢 Ativo</td>
                        </tr>
                        <tr>
                            <td>Rota Litorânea</td>
                            <td>#003</td>
                            <td>3</td>
                            <td>60 km</td>
                            <td>1h 15min</td>
                            <td>🟢 Ativo</td>
                        </tr>
                    </tbody>
                </table>
            `);
        }

        function gerarRelatorioManutencoes() {
            mostrarResultado('Relatório de Manutenções', `
                <div class="stats-resumo">
                    <div class="stat-box">
                        <div class="number">15</div>
                        <div class="label">Realizadas</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">5</div>
                        <div class="label">Agendadas</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">R$ 45k</div>
                        <div class="label">Custo Total</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Trem</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Custo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>20/11/2024</td>
                            <td>#007</td>
                            <td>Preventiva</td>
                            <td>Troca de óleo</td>
                            <td>R$ 2.500</td>
                            <td>✅ Concluída</td>
                        </tr>
                        <tr>
                            <td>28/11/2024</td>
                            <td>#003</td>
                            <td>Preventiva</td>
                            <td>Revisão geral</td>
                            <td>R$ 8.000</td>
                            <td>📅 Agendada</td>
                        </tr>
                    </tbody>
                </table>
            `);
        }

        function gerarRelatorioTrens() {
            mostrarResultado('Relatório de Trens', `
                <div class="stats-resumo">
                    <div class="stat-box">
                        <div class="number">12</div>
                        <div class="label">Total de Trens</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">10</div>
                        <div class="label">Em Operação</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">2</div>
                        <div class="label">Manutenção</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Capacidade</th>
                            <th>Status</th>
                            <th>Última Manutenção</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#007</td>
                            <td>Expresso Central</td>
                            <td>Expresso</td>
                            <td>450 pass.</td>
                            <td>🟢 Operando</td>
                            <td>20/11/2024</td>
                        </tr>
                        <tr>
                            <td>#003</td>
                            <td>Regional Sul</td>
                            <td>Regional</td>
                            <td>350 pass.</td>
                            <td>🟢 Operando</td>
                            <td>15/11/2024</td>
                        </tr>
                    </tbody>
                </table>
            `);
        }

        function gerarRelatorioEstacoes() {
            mostrarResultado('Relatório de Estações', `
                <div class="stats-resumo">
                    <div class="stat-box">
                        <div class="number">8</div>
                        <div class="label">Total Estações</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">8</div>
                        <div class="label">Operacionais</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">25k</div>
                        <div class="label">Passageiros/Dia</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Cidade</th>
                            <th>Plataformas</th>
                            <th>Capacidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>EST-001</td>
                            <td>Estação Central</td>
                            <td>São Paulo</td>
                            <td>12</td>
                            <td>5.000</td>
                            <td>🟢 Ativa</td>
                        </tr>
                        <tr>
                            <td>EST-002</td>
                            <td>Estação Norte</td>
                            <td>São Paulo</td>
                            <td>8</td>
                            <td>3.000</td>
                            <td>🟢 Ativa</td>
                        </tr>
                    </tbody>
                </table>
            `);
        }

        function mostrarResultado(titulo, conteudo) {
            document.getElementById('tituloRelatorio').textContent = titulo;
            document.getElementById('conteudoRelatorio').innerHTML = conteudo;
            document.getElementById('resultadoRelatorio').classList.add('show');

            // Scroll suave até o resultado
            document.getElementById('resultadoRelatorio').scrollIntoView({ behavior: 'smooth' });
        }

        function exportarPDF(tipo) {
            alert(`📄 Exportando relatório de ${tipo} para PDF...\n\nEm produção, aqui seria feita a geração do PDF.`);
        }

        function exportarCSV(tipo) {
            alert(`📊 Exportando relatório de ${tipo} para CSV...\n\nEm produção, aqui seria feita a geração do CSV.`);
        }
    </script>
</body>

</html>
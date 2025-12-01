<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Sistema Ferroviário</title>
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
            background: black;
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

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        h1 {
            color: black;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.8em;
        }

        h3 {
            color: #333;
            font-size: 1.3em;
            margin: 20px 0 10px;
        }

        p {
            line-height: 1.8;
            margin-bottom: 15px;
            text-align: justify;
        }

        .hero-section {
            text-align: center;
            padding: 40px 0;
            background: gray;
            color: white;
            border-radius: 15px;
            margin-bottom: 40px;
        }

        .hero-section h1 {
            color: white;
            font-size: 3em;
            margin-bottom: 10px;
        }

        .hero-section p {
            color: white;
            font-size: 1.2em;
            opacity: 0.9;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .feature-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            color: black;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #666;
            font-size: 0.95em;
            text-align: center;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .team-member {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
        }

        .team-member .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: white;
        }

        .team-member h3 {
            color: #333;
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .team-member .role {
            font-weight: 600;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .team-member p {
            color: #999;
            font-size: 0.85em;
            text-align: center;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
        }

        .tech-badge {
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
        }

        .stat-box .number {
            font-size: 3em;
            font-weight: bold;
            color: black;
            margin-bottom: 5px;
        }

        .stat-box .label {
            color: #666;
            font-size: 0.9em;
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
                padding: 80px 20px 20px;
            }

            .hero-section h1 {
                font-size: 2em;
            }

            h1 {
                font-size: 2em;
            }

            .content-card {
                padding: 25px;
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


    <!-- celular -->
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Menu (celular) -->

    <!-- Conteúdo principal -->
    <main class="main-content">
        <div class="hero-section">
            <h1>🚆 Sistema Ferroviário Inteligente</h1>
            <p>Gerenciamento completo de trens, rotas e sensores IoT</p>
        </div>

        <div class="content-card">
            <h2>📖 Sobre o Projeto</h2>
            <p>
                O Sistema Ferroviário Inteligente é uma plataforma completa de gerenciamento e monitoramento
                de operações ferroviárias, desenvolvida como projeto integrador das disciplinas do curso técnico.
                O sistema integra tecnologias modernas de IoT (Internet das Coisas) com uma interface web intuitiva,
                permitindo o controle em tempo real de trens, rotas, estações e sensores distribuídos pela rede
                ferroviária.
            </p>
            <p>
                Utilizando o protocolo MQTT para comunicação entre dispositivos, o sistema é capaz de receber
                dados de sensores ESP32 instalados nos trens e estações, processando informações de temperatura,
                umidade, luminosidade e presença. Esses dados são analisados em tempo real para gerar alertas
                automáticos e subsidiar decisões operacionais.
            </p>

            <h2>🎯 Objetivos do Sistema</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Monitoramento em Tempo Real</h3>
                    <p>Acompanhe dados de sensores e status operacional instantaneamente</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚨</div>
                    <h3>Alertas Inteligentes</h3>
                    <p>Sistema de notificações automáticas baseado em limites configuráveis</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🗺️</div>
                    <h3>Gestão de Rotas</h3>
                    <p>Planejamento e otimização de itinerários com múltiplas rotas</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Manutenção Preventiva</h3>
                    <p>Controle de manutenções agendadas e históricas</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📄</div>
                    <h3>Relatórios Detalhados</h3>
                    <p>Geração de relatórios completos exportáveis em PDF e CSV</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Multi-usuário</h3>
                    <p>Perfis diferenciados para funcionários e usuários comuns</p>
                    http://localhost:1010/SA/MOCKUP-5/html/manutencao.html
                </div>
            </div>

            <h2>💻 Tecnologias Utilizadas</h2>
            <div class="tech-stack">
                <span class="tech-badge">HTML5</span>
                <span class="tech-badge">CSS3</span>
                <span class="tech-badge">JavaScript</span>
                <span class="tech-badge">Node.js</span>
                <span class="tech-badge">MySQL/PostgreSQL</span>
                <span class="tech-badge">MQTT Protocol</span>
                <span class="tech-badge">ESP32</span>
                <span class="tech-badge">Arduino IDE</span>
                <span class="tech-badge">HiveMQ Broker</span>
                <span class="tech-badge">Leaflet.js</span>
                <span class="tech-badge">Git/GitHub</span>
            </div>

            <h2>📊 Estatísticas do Sistema</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="number">17</div>
                    <div class="label">Funcionalidades</div>
                </div>
                <div class="stat-box">
                    <div class="number">12</div>
                    <div class="label">Trens Cadastrados</div>
                </div>
                <div class="stat-box">
                    <div class="number">24</div>
                    <div class="label">Sensores IoT</div>
                </div>
                <div class="stat-box">
                    <div class="number">8</div>
                    <div class="label">Estações</div>
                </div>
                <div class="stat-box">
                    <div class="number">15</div>
                    <div class="label">Rotas Ativas</div>
                </div>
            </div>

            <h2>👥 Equipe de Desenvolvimento</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="avatar">👨‍💻</div>
                    <h3>João Silva</h3>
                    <p class="role">Desenvolvedor Full Stack</p>
                    <p>Backend e integração IoT</p>
                </div>
                <div class="team-member">
                    <div class="avatar">👩‍💻</div>
                    <h3>Maria Santos</h3>
                    <p class="role">Desenvolvedora Frontend</p>
                    <p>Interface e UX/UI</p>
                </div>
                <div class="team-member">
                    <div class="avatar">👨‍🔧</div>
                    <h3>Pedro Costa</h3>
                    <p class="role">Especialista IoT</p>
                    <p>Sensores e Hardware</p>
                </div>
                <div class="team-member">
                    <div class="avatar">👩‍💼</div>
                    <h3>Ana Oliveira</h3>
                    <p class="role">Analista de Dados</p>
                    <p>Relatórios e Analytics</p>
                </div>
            </div>

            <h2>🎓 Contexto Acadêmico</h2>
            <p>
                Este projeto foi desenvolvido como atividade integradora final do curso técnico,
                reunindo conhecimentos adquiridos ao longo do semestre em disciplinas como:
            </p>
            <ul style="color: #666; line-height: 2; margin-left: 30px;">
                <li><strong>Programação Web:</strong> Desenvolvimento frontend e backend</li>
                <li><strong>Banco de Dados:</strong> Modelagem e implementação de SGBDs</li>
                <li><strong>Internet das Coisas (IoT):</strong> Integração com sensores ESP32</li>
                <li><strong>Redes de Computadores:</strong> Protocolo MQTT e comunicação</li>
                <li><strong>Engenharia de Software:</strong> Metodologias ágeis e versionamento</li>
            </ul>

            <h2>📞 Contato e Suporte</h2>
            <p>
                Para dúvidas, sugestões ou reportar problemas, entre em contato através dos canais:
            </p>
            <ul style="color: #666; line-height: 2; margin-left: 30px;">
                <li>📧 Email: suporte@sistemaferroviario.com.br</li>
                <li>💬 GitHub: github.com/seu-usuario/projeto-ferroviario</li>
                <li>📱 Telefone: (11) 3000-0000</li>
            </ul>

            <h2>📜 Licença</h2>
            <p>
                Este projeto é distribuído sob a licença MIT. Você é livre para usar, modificar e distribuir
                o código, desde que mantenha os créditos aos autores originais.
            </p>

            <div style="text-align: center; margin-top: 40px; padding-top: 30px; border-top: 2px solid #f0f0f0;">
                <p style="color: #999; font-size: 0.9em;">
                    © 2024 Sistema Ferroviário Inteligente. Desenvolvido por alunos do curso técnico.<br>
                    Versão 1.0.0 - Última atualização: Novembro 2024
                </p>
            </div>
        </div>
    </main>

    <script>
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
</body>

</html>
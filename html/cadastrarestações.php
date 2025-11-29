<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Estação - Sistema Ferroviário</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #667eea;
            font-size: 2.2em;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .form-section h2 {
            color: #667eea;
            font-size: 1.3em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-salvar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-salvar:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-cancelar {
            background: #e0e0e0;
            color: #666;
        }

        .btn-cancelar:hover {
            background: #d0d0d0;
            transform: translateY(-2px);
        }

        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-box p {
            color: #555;
            font-size: 0.95em;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 25px;
            }

            h1 {
                font-size: 1.8em;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-card">
            <h1>🚉 Cadastrar Nova Estação</h1>
            <p class="subtitle">Adicione uma nova estação ao sistema ferroviário</p>

            <div class="info-box">
                <p>💡 <strong>Dica:</strong> Preencha todos os campos obrigatórios (*) para garantir o funcionamento
                    correto do sistema.</p>
            </div>

            <form id="formCadastro">
                <!-- Seção: Informações Básicas -->
                <div class="form-section">
                    <h2>📋 Informações Básicas</h2>

                    <div class="form-group">
                        <label for="nome">Nome da Estação *</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Estação Central" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código da Estação *</label>
                            <input type="text" id="codigo" name="codigo" placeholder="Ex: EST-001" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="ativa">Ativa</option>
                                <option value="inativa">Inativa</option>
                                <option value="manutencao">Em Manutenção</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Seção: Localização -->
                <div class="form-section">
                    <h2>📍 Localização</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cidade">Cidade *</label>
                            <input type="text" id="cidade" name="cidade" placeholder="Ex: São Paulo" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado *</label>
                            <select id="estado" name="estado" required>
                                <option value="">Selecione...</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="endereco">Endereço Completo *</label>
                        <input type="text" id="endereco" name="endereco" placeholder="Ex: Praça da Sé, Centro" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" id="latitude" name="latitude" placeholder="Ex: -23.5505">
                        </div>

                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" id="longitude" name="longitude" placeholder="Ex: -46.6333">
                        </div>
                    </div>
                </div>

                <!-- Seção: Contato -->
                <div class="form-section">
                    <h2>📞 Contato</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" placeholder="(11) 3000-0000">
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" placeholder="estacao@exemplo.com">
                        </div>
                    </div>
                </div>

                <!-- Seção: Capacidade e Estrutura -->
                <div class="form-section">
                    <h2>🏗️ Capacidade e Estrutura</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="capacidade">Capacidade de Pessoas *</label>
                            <input type="number" id="capacidade" name="capacidade" placeholder="Ex: 5000" min="0"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="plataformas">Número de Plataformas *</label>
                            <input type="number" id="plataformas" name="plataformas" placeholder="Ex: 8" min="1"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="acessibilidade">
                            <input type="checkbox" id="acessibilidade" name="acessibilidade"
                                style="width: auto; margin-right: 8px;">
                            Possui acessibilidade para pessoas com deficiência
                        </label>
                    </div>
                </div>

                <!-- Seção: Observações -->
                <div class="form-section">
                    <h2>📝 Observações</h2>

                    <div class="form-group">
                        <label for="observacoes">Observações/Notas</label>
                        <textarea id="observacoes" name="observacoes"
                            placeholder="Informações adicionais sobre a estação..."></textarea>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn btn-cancelar" onclick="window.location.href='estacoes.html'">✖️
                        Cancelar</button>
                    <button type="submit" class="btn btn-salvar">✔️ Cadastrar Estação</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('formCadastro').addEventListener('submit', function (e) {
            e.preventDefault();

            // Coletar dados do formulário
            const dadosEstacao = {
                nome: document.getElementById('nome').value,
                codigo: document.getElementById('codigo').value,
                status: document.getElementById('status').value,
                cidade: document.getElementById('cidade').value,
                estado: document.getElementById('estado').value,
                endereco: document.getElementById('endereco').value,
                latitude: document.getElementById('latitude').value,
                longitude: document.getElementById('longitude').value,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                capacidade: document.getElementById('capacidade').value,
                plataformas: document.getElementById('plataformas').value,
                acessibilidade: document.getElementById('acessibilidade').checked,
                observacoes: document.getElementById('observacoes').value
            };

            // Aqui você faria a requisição POST para o backend
            console.log('Dados da estação:', dadosEstacao);

            // Simulação de salvamento
            alert('✅ Estação cadastrada com sucesso!');

            // Redirecionar para a lista de estações
            window.location.href = 'gerenciarestações.php';
        });

        // Auto-gerar código da estação
        document.getElementById('nome').addEventListener('blur', function (e) {
            const codigoInput = document.getElementById('codigo');
            if (!codigoInput.value) {
                const nome = e.target.value.toUpperCase().substring(0, 3);
                const numero = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                codigoInput.value = `EST-${numero}`;
            }
        });

        // Formatação de telefone
        document.getElementById('telefone').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });
    </script>
</body>

</html>
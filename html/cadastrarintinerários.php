<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Itinerário - Sistema Ferroviário</title>
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
            max-width: 900px;
            margin: 0 auto;
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

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .rotas-list {
            margin-top: 20px;
        }

        .rota-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            position: relative;
            border-left: 4px solid #667eea;
        }

        .rota-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .rota-numero {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .btn-remover-rota {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85em;
        }

        .btn-remover-rota:hover {
            background: #c53030;
        }

        .rota-info {
            font-size: 0.95em;
            color: #666;
        }

        .btn-adicionar-rota {
            background: #38a169;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-adicionar-rota:hover {
            background: #2f855a;
            transform: translateY(-2px);
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

        .resumo-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .resumo-box h3 {
            color: #667eea;
            margin-bottom: 15px;
        }

        .resumo-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .resumo-stat {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }

        .resumo-stat .value {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .resumo-stat .label {
            font-size: 0.85em;
            color: #666;
            margin-top: 5px;
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

            .resumo-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h1>🗺️ Cadastrar Novo Itinerário</h1>
            <p class="subtitle">Crie uma viagem composta por múltiplas rotas</p>

            <div class="info-box">
                <p>💡 <strong>Dica:</strong> Um itinerário é composto por várias rotas sequenciais. Adicione as rotas na ordem que serão percorridas.</p>
            </div>

            <form id="formCadastro">
                <!-- Informações Básicas -->
                <div class="form-section">
                    <h2>📋 Informações Básicas</h2>

                    <div class="form-group">
                        <label for="nome">Nome do Itinerário *</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Expresso São Paulo - Campinas" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="trem">Trem Designado *</label>
                            <select id="trem" name="trem" required>
                                <option value="">Selecione...</option>
                                <option value="1">Trem #001 - Expresso</option>
                                <option value="2">Trem #003 - Regional</option>
                                <option value="3">Trem #005 - Metropolitano</option>
                                <option value="4">Trem #007 - Internacional</option>
                                <option value="5">Trem #012 - Luxo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                                <option value="planejado">Em Planejamento</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" placeholder="Descreva o itinerário..." rows="3"></textarea>
                    </div>
                </div>

                <!-- Rotas do Itinerário -->
                <div class="form-section">
                    <h2>🛤️ Rotas do Itinerário</h2>

                    <div class="rotas-list" id="rotasList">
                        <!-- Rotas serão adicionadas aqui dinamicamente -->
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rotaSelect">Selecionar Rota</label>
                            <select id="rotaSelect">
                                <option value="">Escolha uma rota...</option>
                                <option value="1" data-origem="São Paulo" data-destino="Jundiaí" data-distancia="45" data-duracao="35">São Paulo → Jundiaí (45km, 35min)</option>
                                <option value="2" data-origem="Jundiaí" data-destino="Várzea Paulista" data-distancia="30" data-duracao="25">Jundiaí → Várzea Paulista (30km, 25min)</option>
                                <option value="3" data-origem="Várzea Paulista" data-destino="Campinas" data-distancia="40" data-duracao="30">Várzea Paulista → Campinas (40km, 30min)</option>
                                <option value="4" data-origem="Santos" data-destino="Praia Grande" data-distancia="20" data-duracao="20">Santos → Praia Grande (20km, 20min)</option>
                                <option value="5" data-origem="Praia Grande" data-destino="São Vicente" data-distancia="15" data-duracao="18">Praia Grande → São Vicente (15km, 18min)</option>
                            </select>
                        </div>
                        <div style="display: flex; align-items: flex-end;">
                            <button type="button" class="btn-adicionar-rota" onclick="adicionarRota()">➕ Adicionar Rota</button>
                        </div>
                    </div>
                </div>

                <!-- Resumo -->
                <div class="resumo-box">
                    <h3>📊 Resumo do Itinerário</h3>
                    <div class="resumo-stats">
                        <div class="resumo-stat">
                            <div class="value" id="totalRotas">0</div>
                            <div class="label">Rotas</div>
                        </div>
                        <div class="resumo-stat">
                            <div class="value" id="distanciaTotal">0</div>
                            <div class="label">km Total</div>
                        </div>
                        <div class="resumo-stat">
                            <div class="value" id="duracaoTotal">0</div>
                            <div class="label">min Total</div>
                        </div>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn btn-cancelar" onclick="window.location.href='itinerarios.html'">✖️ Cancelar</button>
                    <button type="submit" class="btn btn-salvar">✔️ Cadastrar Itinerário</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let rotasAdicionadas = [];
        let contadorRotas = 0;

        function adicionarRota() {
            const select = document.getElementById('rotaSelect');
            const option = select.options[select.selectedIndex];
            
            if (!option.value) {
                alert('Por favor, selecione uma rota!');
                return;
            }

            const rota = {
                id: option.value,
                ordem: ++contadorRotas,
                origem: option.dataset.origem,
                destino: option.dataset.destino,
                distancia: parseInt(option.dataset.distancia),
                duracao: parseInt(option.dataset.duracao)
            };

            rotasAdicionadas.push(rota);
            renderizarRotas();
            atualizarResumo();
            select.value = '';
        }

        function removerRota(ordem) {
            rotasAdicionadas = rotasAdicionadas.filter(r => r.ordem !== ordem);
            renderizarRotas();
            atualizarResumo();
        }

        function renderizarRotas() {
            const container = document.getElementById('rotasList');
            
            if (rotasAdicionadas.length === 0) {
                container.innerHTML = '<p style="color: #999; text-align: center; padding: 20px;">Nenhuma rota adicionada ainda. Selecione rotas acima.</p>';
                return;
            }

            container.innerHTML = rotasAdicionadas.map(rota => `
                <div class="rota-item">
                    <div class="rota-item-header">
                        <span class="rota-numero">Rota ${rota.ordem}</span>
                        <button type="button" class="btn-remover-rota" onclick="removerRota(${rota.ordem})">✖️ Remover</button>
                    </div>
                    <div class="rota-info">
                        <strong>${rota.origem} → ${rota.destino}</strong><br>
                        📍 ${rota.distancia} km • ⏱️ ${rota.duracao} min
                    </div>
                </div>
            `).join('');
        }

        function atualizarResumo() {
            const totalRotas = rotasAdicionadas.length;
            const distanciaTotal = rotasAdicionadas.reduce((sum, r) => sum + r.distancia, 0);
            const duracaoTotal = rotasAdicionadas.reduce((sum, r) => sum + r.duracao, 0);

            document.getElementById('totalRotas').textContent = totalRotas;
            document.getElementById('distanciaTotal').textContent = distanciaTotal;
            document.getElementById('duracaoTotal').textContent = duracaoTotal;
        }

        document.getElementById('formCadastro').addEventListener('submit', function(e) {
            e.preventDefault();

            if (rotasAdicionadas.length === 0) {
                alert('❌ Adicione pelo menos uma rota ao itinerário!');
                return;
            }

            const dadosItinerario = {
                nome: document.getElementById('nome').value,
                trem: document.getElementById('trem').value,
                status: document.getElementById('status').value,
                descricao: document.getElementById('descricao').value,
                rotas: rotasAdicionadas
            };

            console.log('Dados do itinerário:', dadosItinerario);
            alert('✅ Itinerário cadastrado com sucesso!');
            window.location.href = 'gerenciaritinerários.php';
        });

        // Inicializar
        renderizarRotas();
    </script>
</body>
</html>
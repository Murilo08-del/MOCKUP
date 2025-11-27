<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Sensor - Sistema Ferroviário</title>
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
            max-width: 700px;
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

        .form-group {
            margin-bottom: 25px;
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
            <h1>📡 Cadastrar Novo Sensor</h1>
            <p class="subtitle">Adicione um novo sensor ao sistema de monitoramento</p>

            <div class="info-box">
                <p>💡 <strong>Dica:</strong> Certifique-se de que o sensor está fisicamente instalado e conectado antes de cadastrá-lo no sistema.</p>
            </div>

            <form id="formCadastro">
                <div class="form-group">
                    <label for="nome">Nome do Sensor *</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Sensor de Temperatura #007" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipo">Tipo de Sensor *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="dht11">DHT11 (Temp/Umid)</option>
                            <option value="dht22">DHT22 (Temp/Umid)</option>
                            <option value="ldr">LDR (Luminosidade)</option>
                            <option value="hcsr04">HC-SR04 (Ultrassônico)</option>
                            <option value="bmp180">BMP180 (Pressão)</option>
                            <option value="gps">GPS (Localização)</option>
                            <option value="mpu6050">MPU6050 (Acelerômetro)</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Inicial *</label>
                        <select id="status" name="status" required>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="manutencao">Em Manutenção</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="localizacao">Localização *</label>
                    <input type="text" id="localizacao" name="localizacao" placeholder="Ex: Trem #007 - Motor Principal" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="topico">Tópico MQTT *</label>
                        <input type="text" id="topico" name="topico" placeholder="Ex: sensores/temp/007" required>
                    </div>

                    <div class="form-group">
                        <label for="unidade">Unidade de Medida</label>
                        <input type="text" id="unidade" name="unidade" placeholder="Ex: °C, %, lux, cm">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="valorMinimo">Valor Mínimo (Alerta)</label>
                        <input type="number" id="valorMinimo" name="valorMinimo" placeholder="Ex: 0" step="0.1">
                    </div>

                    <div class="form-group">
                        <label for="valorMaximo">Valor Máximo (Alerta)</label>
                        <input type="number" id="valorMaximo" name="valorMaximo" placeholder="Ex: 100" step="0.1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição/Observações</label>
                    <textarea id="descricao" name="descricao" placeholder="Informações adicionais sobre o sensor..."></textarea>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn btn-cancelar" onclick="window.location.href='sensores.html'">✖️ Cancelar</button>
                    <button type="submit" class="btn btn-salvar">✔️ Cadastrar Sensor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('formCadastro').addEventListener('submit', function(e) {
            e.preventDefault();

            // Coletar dados do formulário
            const dadosSensor = {
                nome: document.getElementById('nome').value,
                tipo: document.getElementById('tipo').value,
                status: document.getElementById('status').value,
                localizacao: document.getElementById('localizacao').value,
                topico: document.getElementById('topico').value,
                unidade: document.getElementById('unidade').value,
                valorMinimo: document.getElementById('valorMinimo').value,
                valorMaximo: document.getElementById('valorMaximo').value,
                descricao: document.getElementById('descricao').value
            };

            // Aqui você faria a requisição POST para o backend
            console.log('Dados do sensor:', dadosSensor);

            // Simulação de salvamento
            alert('✅ Sensor cadastrado com sucesso!');
            
            // Redirecionar para a lista de sensores
            window.location.href = 'analisestemporealgestor.php';
        });

        // Auto-completar tópico MQTT baseado no tipo
        document.getElementById('tipo').addEventListener('change', function(e) {
            const tipo = e.target.value;
            const topicoInput = document.getElementById('topico');
            const unidadeInput = document.getElementById('unidade');
            
            if (tipo === 'dht11' || tipo === 'dht22') {
                topicoInput.value = 'sensores/temp/';
                unidadeInput.value = '°C';
            } else if (tipo === 'ldr') {
                topicoInput.value = 'sensores/luz/';
                unidadeInput.value = 'lux';
            } else if (tipo === 'hcsr04') {
                topicoInput.value = 'sensores/presenca/';
                unidadeInput.value = 'cm';
            } else if (tipo === 'bmp180') {
                topicoInput.value = 'sensores/pressao/';
                unidadeInput.value = 'hPa';
            } else if (tipo === 'gps') {
                topicoInput.value = 'sensores/gps/';
                unidadeInput.value = 'km/h';
            }
        });
    </script>
</body>
</html>
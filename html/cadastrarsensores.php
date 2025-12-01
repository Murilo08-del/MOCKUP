<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$mensagem = "";
$tipo_mensagem = "";
$editando = false;
$sensor = null;

// ==================== MODO EDIÇÃO ====================
if (isset($_GET['editar'])) {
    $editando = true;
    $id = intval($_GET['editar']);
    
    $stmt = $conexao->prepare("SELECT * FROM sensores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $sensor = $resultado->fetch_assoc();
    $stmt->close();
    
    if (!$sensor) {
        header("Location: gerenciarsensores.php");
        exit;
    }
}

// ==================== PROCESSAR FORMULÁRIO ====================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $codigo = trim($_POST["codigo"] ?? "");
    $tipo = trim($_POST["tipo"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $localizacao = trim($_POST["localizacao"] ?? "");
    $topico_mqtt = trim($_POST["topico_mqtt"] ?? "");
    $unidade_medida = trim($_POST["unidade_medida"] ?? "");
    $valor_minimo = !empty($_POST["valor_minimo"]) ? floatval($_POST["valor_minimo"]) : null;
    $valor_maximo = !empty($_POST["valor_maximo"]) ? floatval($_POST["valor_maximo"]) : null;
    $status = $_POST["status"] ?? "offline";
    $trem_id = !empty($_POST["trem_id"]) ? intval($_POST["trem_id"]) : null;
    $estacao_id = !empty($_POST["estacao_id"]) ? intval($_POST["estacao_id"]) : null;
    $descricao = trim($_POST["descricao"] ?? "");
    
    // Validações
    if (empty($nome) || empty($codigo) || empty($tipo) || empty($localizacao) || empty($topico_mqtt)) {
        $mensagem = "Preencha todos os campos obrigatórios.";
        $tipo_mensagem = "error";
    } else {
        if (isset($_POST['id_edicao'])) {
            // ATUALIZAR
            $id_edicao = intval($_POST['id_edicao']);
            
            $stmt = $conexao->prepare("UPDATE sensores SET nome=?, codigo=?, tipo=?, modelo=?, localizacao=?, 
                                       topico_mqtt=?, unidade_medida=?, valor_minimo=?, valor_maximo=?, 
                                       status=?, trem_id=?, estacao_id=?, descricao=? WHERE id=?");
            $stmt->bind_param("sssssssddsiisi", $nome, $codigo, $tipo, $modelo, $localizacao, $topico_mqtt, 
                             $unidade_medida, $valor_minimo, $valor_maximo, $status, $trem_id, $estacao_id, 
                             $descricao, $id_edicao);
            
            if ($stmt->execute()) {
                header("Location: gerenciarsensores.php");
                exit;
            } else {
                $mensagem = "Erro ao atualizar sensor: " . $conexao->error;
                $tipo_mensagem = "error";
            }
        } else {
            // INSERIR NOVO
            // Verificar se código já existe
            $stmt = $conexao->prepare("SELECT id FROM sensores WHERE codigo = ?");
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows > 0) {
                $mensagem = "Já existe um sensor com este código.";
                $tipo_mensagem = "error";
            } else {
                $stmt = $conexao->prepare("INSERT INTO sensores (nome, codigo, tipo, modelo, localizacao, topico_mqtt, 
                                          unidade_medida, valor_minimo, valor_maximo, status, trem_id, estacao_id, descricao) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssddsiis", $nome, $codigo, $tipo, $modelo, $localizacao, $topico_mqtt, 
                                 $unidade_medida, $valor_minimo, $valor_maximo, $status, $trem_id, $estacao_id, $descricao);
                
                if ($stmt->execute()) {
                    header("Location: gerenciarsensores.php");
                    exit;
                } else {
                    $mensagem = "Erro ao cadastrar sensor: " . $conexao->error;
                    $tipo_mensagem = "error";
                }
            }
        }
    }
}

// Buscar trens e estações para os selects
$trens = $conexao->query("SELECT id, codigo, nome FROM trens ORDER BY codigo");
$estacoes = $conexao->query("SELECT id, codigo, nome FROM estacoes ORDER BY codigo");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editando ? 'Editar' : 'Cadastrar'; ?> Sensor - Sistema Ferroviário</title>

    
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
            background: gray;
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

    <!-- JAVASCRIPT DA SIDEBAR -->
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
            color: black;
            font-size: 2.2em;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
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

        .mensagem {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .mensagem.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensagem.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        label .required {
            color: red;
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
            background: gray;
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
            <h1>🔡 <?php echo $editando ? 'Editar' : 'Cadastrar Novo'; ?> Sensor</h1>
            <p class="subtitle"><?php echo $editando ? 'Atualize as informações do sensor' : 'Adicione um novo sensor ao sistema de monitoramento'; ?></p>

            <?php if (!$editando): ?>
            <div class="info-box">
                <p>💡 <strong>Dica:</strong> Certifique-se de que o sensor está fisicamente instalado e conectado antes de cadastrá-lo no sistema.</p>
            </div>
            <?php endif; ?>

            <?php if (!empty($mensagem)): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_edicao" value="<?php echo $sensor['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nome">Nome do Sensor <span class="required">*</span></label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Sensor de Temperatura #007" 
                           value="<?php echo $sensor['nome'] ?? ''; ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="codigo">Código <span class="required">*</span></label>
                        <input type="text" id="codigo" name="codigo" placeholder="Ex: SENS-001" 
                               value="<?php echo $sensor['codigo'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Sensor <span class="required">*</span></label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="temperatura" <?php echo ($sensor['tipo'] ?? '') === 'temperatura' ? 'selected' : ''; ?>>Temperatura</option>
                            <option value="umidade" <?php echo ($sensor['tipo'] ?? '') === 'umidade' ? 'selected' : ''; ?>>Umidade</option>
                            <option value="luminosidade" <?php echo ($sensor['tipo'] ?? '') === 'luminosidade' ? 'selected' : ''; ?>>Luminosidade</option>
                            <option value="presenca" <?php echo ($sensor['tipo'] ?? '') === 'presenca' ? 'selected' : ''; ?>>Presença</option>
                            <option value="velocidade" <?php echo ($sensor['tipo'] ?? '') === 'velocidade' ? 'selected' : ''; ?>>Velocidade</option>
                            <option value="pressao" <?php echo ($sensor['tipo'] ?? '') === 'pressao' ? 'selected' : ''; ?>>Pressão</option>
                            <option value="acelerometro" <?php echo ($sensor['tipo'] ?? '') === 'acelerometro' ? 'selected' : ''; ?>>Acelerômetro</option>
                            <option value="gps" <?php echo ($sensor['tipo'] ?? '') === 'gps' ? 'selected' : ''; ?>>GPS</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <input type="text" id="modelo" name="modelo" placeholder="Ex: DHT11, LDR, HC-SR04" 
                               value="<?php echo $sensor['modelo'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Status Inicial <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="online" <?php echo ($sensor['status'] ?? 'offline') === 'online' ? 'selected' : ''; ?>>Online</option>
                            <option value="offline" <?php echo ($sensor['status'] ?? 'offline') === 'offline' ? 'selected' : ''; ?>>Offline</option>
                            <option value="manutencao" <?php echo ($sensor['status'] ?? '') === 'manutencao' ? 'selected' : ''; ?>>Em Manutenção</option>
                            <option value="erro" <?php echo ($sensor['status'] ?? '') === 'erro' ? 'selected' : ''; ?>>Erro</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="localizacao">Localização <span class="required">*</span></label>
                    <input type="text" id="localizacao" name="localizacao" placeholder="Ex: Trem #007 - Motor Principal" 
                           value="<?php echo $sensor['localizacao'] ?? ''; ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="trem_id">Trem</label>
                        <select id="trem_id" name="trem_id">
                            <option value="">Nenhum</option>
                            <?php while ($trem = $trens->fetch_assoc()): ?>
                                <option value="<?php echo $trem['id']; ?>" 
                                    <?php echo ($sensor['trem_id'] ?? '') == $trem['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($trem['codigo'] . ' - ' . $trem['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estacao_id">Estação</label>
                        <select id="estacao_id" name="estacao_id">
                            <option value="">Nenhuma</option>
                            <?php while ($estacao = $estacoes->fetch_assoc()): ?>
                                <option value="<?php echo $estacao['id']; ?>" 
                                    <?php echo ($sensor['estacao_id'] ?? '') == $estacao['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($estacao['codigo'] . ' - ' . $estacao['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="topico_mqtt">Tópico MQTT <span class="required">*</span></label>
                        <input type="text" id="topico_mqtt" name="topico_mqtt" placeholder="Ex: sensores/temp/007" 
                               value="<?php echo $sensor['topico_mqtt'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="unidade_medida">Unidade de Medida</label>
                        <input type="text" id="unidade_medida" name="unidade_medida" placeholder="Ex: °C, %, lux, cm" 
                               value="<?php echo $sensor['unidade_medida'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="valor_minimo">Valor Mínimo (Alerta)</label>
                        <input type="number" id="valor_minimo" name="valor_minimo" placeholder="Ex: 0" step="0.01" 
                               value="<?php echo $sensor['valor_minimo'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="valor_maximo">Valor Máximo (Alerta)</label>
                        <input type="number" id="valor_maximo" name="valor_maximo" placeholder="Ex: 100" step="0.01" 
                               value="<?php echo $sensor['valor_maximo'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição/Observações</label>
                    <textarea id="descricao" name="descricao" placeholder="Informações adicionais sobre o sensor..."><?php echo $sensor['descricao'] ?? ''; ?></textarea>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn btn-cancelar" onclick="window.location.href='gerenciarsensores.php'">✖️ Cancelar</button>
                    <button type="submit" class="btn btn-salvar">✔️ <?php echo $editando ? 'Atualizar' : 'Cadastrar'; ?> Sensor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-completar tópico MQTT baseado no tipo
        document.getElementById('tipo').addEventListener('change', function (e) {
            const tipo = e.target.value;
            const topicoInput = document.getElementById('topico_mqtt');
            const unidadeInput = document.getElementById('unidade_medida');
            
            // Só preencher se estiver vazio
            if (!topicoInput.value && tipo) {
                let topico = 'sensores/';
                let unidade = '';
                
                switch(tipo) {
                    case 'temperatura':
                        topico += 'temp/';
                        unidade = '°C';
                        break;
                    case 'umidade':
                        topico += 'umid/';
                        unidade = '%';
                        break;
                    case 'luminosidade':
                        topico += 'luz/';
                        unidade = 'lux';
                        break;
                    case 'presenca':
                        topico += 'presenca/';
                        unidade = 'cm';
                        break;
                    case 'velocidade':
                        topico += 'velocidade/';
                        unidade = 'km/h';
                        break;
                    case 'pressao':
                        topico += 'pressao/';
                        unidade = 'hPa';
                        break;
                    case 'gps':
                        topico += 'gps/';
                        unidade = 'lat/lng';
                        break;
                }
                
                topicoInput.value = topico;
                if (!unidadeInput.value) {
                    unidadeInput.value = unidade;
                }
            }
        });
    </script>
</body>
</html>
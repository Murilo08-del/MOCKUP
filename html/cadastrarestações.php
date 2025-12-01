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
$estacao = null;

// ==================== MODO EDIÇÃO ====================
if (isset($_GET['editar'])) {
    $editando = true;
    $id = intval($_GET['editar']);

    $stmt = $conexao->prepare("SELECT * FROM estacoes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $estacao = $resultado->fetch_assoc();
    $stmt->close();

    if (!$estacao) {
        header("Location: gerenciarestações.php");
        exit;
    }
}

// ==================== PROCESSAR FORMULÁRIO ====================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $codigo = trim($_POST["codigo"] ?? "");
    $cidade = trim($_POST["cidade"] ?? "");
    $estado = trim($_POST["estado"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");
    $latitude = !empty($_POST["latitude"]) ? floatval($_POST["latitude"]) : null;
    $longitude = !empty($_POST["longitude"]) ? floatval($_POST["longitude"]) : null;
    $capacidade = intval($_POST["capacidade"] ?? 0);
    $num_plataformas = intval($_POST["plataformas"] ?? 0);
    $acessibilidade = isset($_POST["acessibilidade"]) ? 1 : 0;
    $telefone = trim($_POST["telefone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $status = $_POST["status"] ?? "ativa";
    $observacoes = trim($_POST["observacoes"] ?? "");

    // Validações
    if (empty($nome) || empty($codigo) || empty($cidade) || empty($estado) || empty($endereco)) {
        $mensagem = "Preencha todos os campos obrigatórios.";
        $tipo_mensagem = "error";
    } else {
        if (isset($_POST['id_edicao'])) {
            // ATUALIZAR
            $id_edicao = intval($_POST['id_edicao']);

            $stmt = $conexao->prepare("UPDATE estacoes SET nome=?, codigo=?, cidade=?, estado=?, endereco=?, 
                                       latitude=?, longitude=?, capacidade=?, num_plataformas=?, acessibilidade=?, 
                                       telefone=?, email=?, status=?, observacoes=? WHERE id=?");
            $stmt->bind_param(
                "sssssddiiissssi",
                $nome,
                $codigo,
                $cidade,
                $estado,
                $endereco,
                $latitude,
                $longitude,
                $capacidade,
                $num_plataformas,
                $acessibilidade,
                $telefone,
                $email,
                $status,
                $observacoes,
                $id_edicao
            );

            if ($stmt->execute()) {
                header("Location: gerenciarestações.php");
                exit;
            } else {
                $mensagem = "Erro ao atualizar estação: " . $conexao->error;
                $tipo_mensagem = "error";
            }
        } else {
            // INSERIR NOVO
            // Verificar se código já existe
            $stmt = $conexao->prepare("SELECT id FROM estacoes WHERE codigo = ?");
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $mensagem = "Já existe uma estação com este código.";
                $tipo_mensagem = "error";
            } else {
                $stmt = $conexao->prepare("INSERT INTO estacoes (nome, codigo, cidade, estado, endereco, latitude, 
                                          longitude, capacidade, num_plataformas, acessibilidade, telefone, email, 
                                          status, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(
                    "sssssddiiiisss",
                    $nome,
                    $codigo,
                    $cidade,
                    $estado,
                    $endereco,
                    $latitude,
                    $longitude,
                    $capacidade,
                    $num_plataformas,
                    $acessibilidade,
                    $telefone,
                    $email,
                    $status,
                    $observacoes
                );

                if ($stmt->execute()) {
                    header("Location: gerenciarestações.php");
                    exit;
                } else {
                    $mensagem = "Erro ao cadastrar estação: " . $conexao->error;
                    $tipo_mensagem = "error";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editando ? 'Editar' : 'Cadastrar'; ?> Estação - Sistema Ferroviário</title>

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

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .form-section h2 {
            color: black;
            font-size: 1.3em;
            margin-bottom: 20px;
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
        }

        .mensagem.error {
            background: #f8d7da;
            color: #721c24;
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

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
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

        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
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
            <h1>🚉 <?php echo $editando ? 'Editar' : 'Cadastrar Nova'; ?> Estação</h1>
            <p class="subtitle">
                <?php echo $editando ? 'Atualize as informações da estação' : 'Adicione uma nova estação ao sistema ferroviário'; ?>
            </p>

            <?php if (!$editando): ?>
                <div class="info-box">
                    <p>💡 <strong>Dica:</strong> Preencha todos os campos obrigatórios (*) para garantir o funcionamento
                        correto do sistema.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensagem)): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_edicao" value="<?php echo $estacao['id']; ?>">
                <?php endif; ?>

                <!-- Seção: Informações Básicas -->
                <div class="form-section">
                    <h2>📋 Informações Básicas</h2>

                    <div class="form-group">
                        <label for="nome">Nome da Estação <span class="required">*</span></label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Estação Central"
                            value="<?php echo $estacao['nome'] ?? ''; ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo">Código da Estação <span class="required">*</span></label>
                            <input type="text" id="codigo" name="codigo" placeholder="Ex: EST-001"
                                value="<?php echo $estacao['codigo'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status <span class="required">*</span></label>
                            <select id="status" name="status" required>
                                <option value="ativa" <?php echo ($estacao['status'] ?? 'ativa') === 'ativa' ? 'selected' : ''; ?>>Ativa</option>
                                <option value="inativa" <?php echo ($estacao['status'] ?? '') === 'inativa' ? 'selected' : ''; ?>>Inativa</option>
                                <option value="manutencao" <?php echo ($estacao['status'] ?? '') === 'manutencao' ? 'selected' : ''; ?>>Em Manutenção</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Seção: Localização -->
                <div class="form-section">
                    <h2>📍 Localização</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cidade">Cidade <span class="required">*</span></label>
                            <input type="text" id="cidade" name="cidade" placeholder="Ex: São Paulo"
                                value="<?php echo $estacao['cidade'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado <span class="required">*</span></label>
                            <select id="estado" name="estado" required>
                                <option value="">Selecione...</option>
                                <option value="AC" <?php echo ($estacao['estado'] ?? '') === 'AC' ? 'selected' : ''; ?>>
                                    Acre</option>
                                <option value="AL" <?php echo ($estacao['estado'] ?? '') === 'AL' ? 'selected' : ''; ?>>
                                    Alagoas</option>
                                <option value="AP" <?php echo ($estacao['estado'] ?? '') === 'AP' ? 'selected' : ''; ?>>
                                    Amapá</option>
                                <option value="AM" <?php echo ($estacao['estado'] ?? '') === 'AM' ? 'selected' : ''; ?>>
                                    Amazonas</option>
                                <option value="BA" <?php echo ($estacao['estado'] ?? '') === 'BA' ? 'selected' : ''; ?>>
                                    Bahia</option>
                                <option value="CE" <?php echo ($estacao['estado'] ?? '') === 'CE' ? 'selected' : ''; ?>>
                                    Ceará</option>
                                <option value="DF" <?php echo ($estacao['estado'] ?? '') === 'DF' ? 'selected' : ''; ?>>
                                    Distrito Federal</option>
                                <option value="ES" <?php echo ($estacao['estado'] ?? '') === 'ES' ? 'selected' : ''; ?>>
                                    Espírito Santo</option>
                                <option value="GO" <?php echo ($estacao['estado'] ?? '') === 'GO' ? 'selected' : ''; ?>>
                                    Goiás</option>
                                <option value="MA" <?php echo ($estacao['estado'] ?? '') === 'MA' ? 'selected' : ''; ?>>
                                    Maranhão</option>
                                <option value="MT" <?php echo ($estacao['estado'] ?? '') === 'MT' ? 'selected' : ''; ?>>
                                    Mato Grosso</option>
                                <option value="MS" <?php echo ($estacao['estado'] ?? '') === 'MS' ? 'selected' : ''; ?>>
                                    Mato Grosso do Sul</option>
                                <option value="MG" <?php echo ($estacao['estado'] ?? '') === 'MG' ? 'selected' : ''; ?>>
                                    Minas Gerais</option>
                                <option value="PA" <?php echo ($estacao['estado'] ?? '') === 'PA' ? 'selected' : ''; ?>>
                                    Pará</option>
                                <option value="PB" <?php echo ($estacao['estado'] ?? '') === 'PB' ? 'selected' : ''; ?>>
                                    Paraíba</option>
                                <option value="PR" <?php echo ($estacao['estado'] ?? '') === 'PR' ? 'selected' : ''; ?>>
                                    Paraná</option>
                                <option value="PE" <?php echo ($estacao['estado'] ?? '') === 'PE' ? 'selected' : ''; ?>>
                                    Pernambuco</option>
                                <option value="PI" <?php echo ($estacao['estado'] ?? '') === 'PI' ? 'selected' : ''; ?>>
                                    Piauí</option>
                                <option value="RJ" <?php echo ($estacao['estado'] ?? '') === 'RJ' ? 'selected' : ''; ?>>
                                    Rio de Janeiro</option>
                                <option value="RN" <?php echo ($estacao['estado'] ?? '') === 'RN' ? 'selected' : ''; ?>>
                                    Rio Grande do Norte</option>
                                <option value="RS" <?php echo ($estacao['estado'] ?? '') === 'RS' ? 'selected' : ''; ?>>
                                    Rio Grande do Sul</option>
                                <option value="RO" <?php echo ($estacao['estado'] ?? '') === 'RO' ? 'selected' : ''; ?>>
                                    Rondônia</option>
                                <option value="RR" <?php echo ($estacao['estado'] ?? '') === 'RR' ? 'selected' : ''; ?>>
                                    Roraima</option>
                                <option value="SC" <?php echo ($estacao['estado'] ?? '') === 'SC' ? 'selected' : ''; ?>>
                                    Santa Catarina</option>
                                <option value="SP" <?php echo ($estacao['estado'] ?? '') === 'SP' ? 'selected' : ''; ?>>
                                    São Paulo</option>
                                <option value="SE" <?php echo ($estacao['estado'] ?? '') === 'SE' ? 'selected' : ''; ?>>
                                    Sergipe</option>
                                <option value="TO" <?php echo ($estacao['estado'] ?? '') === 'TO' ? 'selected' : ''; ?>>
                                    Tocantins</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="endereco">Endereço Completo <span class="required">*</span></label>
                        <input type="text" id="endereco" name="endereco" placeholder="Ex: Praça da Sé, Centro"
                            value="<?php echo $estacao['endereco'] ?? ''; ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="number" step="0.0000001" id="latitude" name="latitude"
                                placeholder="Ex: -23.5505" value="<?php echo $estacao['latitude'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="number" step="0.0000001" id="longitude" name="longitude"
                                placeholder="Ex: -46.6333" value="<?php echo $estacao['longitude'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Seção: Contato -->
                <div class="form-section">
                    <h2>📞 Contato</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" id="telefone" name="telefone" placeholder="(11) 3000-0000"
                                value="<?php echo $estacao['telefone'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" placeholder="estacao@exemplo.com"
                                value="<?php echo $estacao['email'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Seção: Capacidade e Estrutura -->
                <div class="form-section">
                    <h2>🏗️ Capacidade e Estrutura</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="capacidade">Capacidade de Pessoas <span class="required">*</span></label>
                            <input type="number" id="capacidade" name="capacidade" placeholder="Ex: 5000" min="0"
                                value="<?php echo $estacao['capacidade'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="plataformas">Número de Plataformas <span class="required">*</span></label>
                            <input type="number" id="plataformas" name="plataformas" placeholder="Ex: 8" min="1"
                                value="<?php echo $estacao['num_plataformas'] ?? ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="acessibilidade" name="acessibilidade" <?php echo ($estacao['acessibilidade'] ?? false) ? 'checked' : ''; ?>>
                        <label for="acessibilidade" style="margin: 0;">
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
                            placeholder="Informações adicionais sobre a estação..."><?php echo $estacao['observacoes'] ?? ''; ?></textarea>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn btn-cancelar"
                        onclick="window.location.href='gerenciarestações.php'">✖️ Cancelar</button>
                    <button type="submit" class="btn btn-salvar">✔️ <?php echo $editando ? 'Atualizar' : 'Cadastrar'; ?>
                        Estação</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-gerar código da estação
        document.getElementById('nome').addEventListener('blur', function (e) {
            const codigoInput = document.getElementById('codigo');
            if (!codigoInput.value) {
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
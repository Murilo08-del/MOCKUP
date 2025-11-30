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
$trem = null;

// ==================== MODO EDIÇÃO ====================
if (isset($_GET['editar'])) {
    $editando = true;
    $id = intval($_GET['editar']);

    $stmt = $conexao->prepare("SELECT * FROM trens WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $trem = $resultado->fetch_assoc();
    $stmt->close();

    if (!$trem) {
        header("Location: gerenciartrens.php");
        exit;
    }
}

// ==================== PROCESSAR FORMULÁRIO ====================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $codigo = trim($_POST["codigo"] ?? "");
    $tipo = trim($_POST["tipo"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $fabricante = trim($_POST["fabricante"] ?? "");
    $ano_fabricacao = !empty($_POST["ano_fabricacao"]) ? intval($_POST["ano_fabricacao"]) : null;
    $capacidade_passageiros = !empty($_POST["capacidade_passageiros"]) ? intval($_POST["capacidade_passageiros"]) : null;
    $capacidade_carga = !empty($_POST["capacidade_carga"]) ? floatval($_POST["capacidade_carga"]) : null;
    $velocidade_maxima = !empty($_POST["velocidade_maxima"]) ? floatval($_POST["velocidade_maxima"]) : null;
    $consumo_medio = !empty($_POST["consumo_medio"]) ? floatval($_POST["consumo_medio"]) : null;
    $ultima_manutencao = !empty($_POST["ultima_manutencao"]) ? $_POST["ultima_manutencao"] : null;
    $proxima_manutencao = !empty($_POST["proxima_manutencao"]) ? $_POST["proxima_manutencao"] : null;
    $km_rodados = !empty($_POST["km_rodados"]) ? floatval($_POST["km_rodados"]) : 0;
    $status = $_POST["status"] ?? "inativo";
    $observacoes = trim($_POST["observacoes"] ?? "");

    // Validações
    if (empty($nome) || empty($codigo) || empty($tipo)) {
        $mensagem = "Preencha todos os campos obrigatórios (Nome, Código e Tipo).";
        $tipo_mensagem = "error";
    } else {
        if (isset($_POST['id_edicao'])) {
            // ATUALIZAR TREM EXISTENTE
            $id_edicao = intval($_POST['id_edicao']);

            $stmt = $conexao->prepare("UPDATE trens SET nome=?, codigo=?, tipo=?, modelo=?, fabricante=?, 
                                       ano_fabricacao=?, capacidade_passageiros=?, capacidade_carga=?, 
                                       velocidade_maxima=?, consumo_medio=?, ultima_manutencao=?, 
                                       proxima_manutencao=?, km_rodados=?, status=?, observacoes=? 
                                       WHERE id=?");
            $stmt->bind_param(
                "sssssiddddssdsi",
                $nome,
                $codigo,
                $tipo,
                $modelo,
                $fabricante,
                $ano_fabricacao,
                $capacidade_passageiros,
                $capacidade_carga,
                $velocidade_maxima,
                $consumo_medio,
                $ultima_manutencao,
                $proxima_manutencao,
                $km_rodados,
                $status,
                $observacoes,
                $id_edicao
            );

            if ($stmt->execute()) {
                $_SESSION['mensagem'] = "Trem atualizado com sucesso!";
                $_SESSION['tipo_mensagem'] = "success";
                header("Location: gerenciartrens.php");
                exit;
            } else {
                $mensagem = "Erro ao atualizar trem: " . $conexao->error;
                $tipo_mensagem = "error";
            }
            $stmt->close();
        } else {
            // INSERIR NOVO TREM
            $stmt = $conexao->prepare("SELECT id FROM trens WHERE codigo = ?");
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $mensagem = "Já existe um trem com este código.";
                $tipo_mensagem = "error";
            } else {
                $stmt = $conexao->prepare("INSERT INTO trens (nome, codigo, tipo, modelo, fabricante, 
                                          ano_fabricacao, capacidade_passageiros, capacidade_carga, 
                                          velocidade_maxima, consumo_medio, ultima_manutencao, 
                                          proxima_manutencao, km_rodados, status, observacoes) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(
                    "sssssiddddssdss",
                    $nome,
                    $codigo,
                    $tipo,
                    $modelo,
                    $fabricante,
                    $ano_fabricacao,
                    $capacidade_passageiros,
                    $capacidade_carga,
                    $velocidade_maxima,
                    $consumo_medio,
                    $ultima_manutencao,
                    $proxima_manutencao,
                    $km_rodados,
                    $status,
                    $observacoes
                );

                if ($stmt->execute()) {
                    $_SESSION['mensagem'] = "Trem cadastrado com sucesso!";
                    $_SESSION['tipo_mensagem'] = "success";
                    header("Location: gerenciartrens.php");
                    exit;
                } else {
                    $mensagem = "Erro ao cadastrar trem: " . $conexao->error;
                    $tipo_mensagem = "error";
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editando ? 'Editar' : 'Cadastrar'; ?> Trem - Sistema Ferroviário</title>

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
            background: gray;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

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
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #d6651aff;
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
            border-color: #d6651aff;
            box-shadow: 0 0 0 3px rgba(214, 101, 26, 0.1);
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

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
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
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-salvar {
            background: gray;
            color: white;
        }

        .btn-salvar:hover {
            background: black;
            transform: translateY(-2px);
        }

        .btn-cancelar {
            background: #e0e0e0;
            color: #666;
        }

        .btn-cancelar:hover {
            background: #d0d0d0;
            transform: translateY(-2px);
        }

        .input-with-unit {
            position: relative;
        }

        .input-with-unit input {
            padding-right: 60px;
        }

        .input-with-unit .unit {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-weight: 600;
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

            .form-card {
                padding: 25px;
            }

            h1 {
                font-size: 1.8em;
            }

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
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
            <li><a href="cadastrartrem.php"><span class="icon">➕</span> Cadastrar Trem</a></li>
            <li><a href="alertas.php"><span class="icon">🚨</span> Alertas</a></li>
            <li><a href="gerenciaritinerários.php"><span class="icon">📡</span> Gerenciar Itinerários</a></li>
            <li><a href="geraçãorelátorios.php"><span class="icon">📄</span> Relatórios</a></li>
            <li><a href="sobre.php"><span class="icon">ℹ️</span> Sobre</a></li>
            <li><a href="rotas.php"><span class="icon">🗺️</span> Rotas</a></li>
            <li><a href="../php/login.php"><span class="icon">👤</span> Sair</a></li>
        </ul>
    </aside>

    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

    <div class="main-content">
        <div class="container">
            <div class="form-card">
                <h1>🚂 <?php echo $editando ? 'Editar' : 'Cadastrar Novo'; ?> Trem</h1>
                <p class="subtitle">
                    <?php echo $editando ? 'Atualize as informações do trem' : 'Adicione um novo trem à frota do sistema'; ?>
                </p>

                <?php if (!$editando): ?>
                        <div class="info-box">
                            <p>💡 <strong>Dica:</strong> Preencha todos os campos obrigatórios (*) para garantir o registro completo do trem no sistema.</p>
                        </div>
                <?php endif; ?>

                <?php if (!empty($mensagem)): ?>
                        <div class="mensagem <?php echo $tipo_mensagem; ?>">
                            <?php echo htmlspecialchars($mensagem); ?>
                        </div>
                <?php endif; ?>

                <form method="POST" id="formTrem">
                    <?php if ($editando): ?>
                            <input type="hidden" name="id_edicao" value="<?php echo $trem['id']; ?>">
                    <?php endif; ?>

                    <div class="form-section">
                        <h2>📋 Informações Básicas</h2>

                        <div class="form-group">
                            <label for="nome">Nome do Trem <span class="required">*</span></label>
                            <input type="text" id="nome" name="nome" placeholder="Ex: Expresso Central"
                                value="<?php echo htmlspecialchars($trem['nome'] ?? ''); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="codigo">Código do Trem <span class="required">*</span></label>
                                <input type="text" id="codigo" name="codigo" placeholder="Ex: TRM-001"
                                    value="<?php echo htmlspecialchars($trem['codigo'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="tipo">Tipo de Trem <span class="required">*</span></label>
                                <select id="tipo" name="tipo" required>
                                    <option value="">Selecione...</option>
                                    <option value="expresso" <?php echo ($trem['tipo'] ?? '') === 'expresso' ? 'selected' : ''; ?>>Expresso</option>
                                    <option value="regional" <?php echo ($trem['tipo'] ?? '') === 'regional' ? 'selected' : ''; ?>>Regional</option>
                                    <option value="metropolitano" <?php echo ($trem['tipo'] ?? '') === 'metropolitano' ? 'selected' : ''; ?>>Metropolitano</option>
                                    <option value="luxo" <?php echo ($trem['tipo'] ?? '') === 'luxo' ? 'selected' : ''; ?>>Luxo</option>
                                    <option value="carga" <?php echo ($trem['tipo'] ?? '') === 'carga' ? 'selected' : ''; ?>>Carga</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <input type="text" id="modelo" name="modelo" placeholder="Ex: EMU-500"
                                    value="<?php echo htmlspecialchars($trem['modelo'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="fabricante">Fabricante</label>
                                <input type="text" id="fabricante" name="fabricante" placeholder="Ex: Siemens"
                                    value="<?php echo htmlspecialchars($trem['fabricante'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ano_fabricacao">Ano de Fabricação</label>
                                <input type="number" id="ano_fabricacao" name="ano_fabricacao" placeholder="Ex: 2020"
                                    min="1900" max="2030" value="<?php echo $trem['ano_fabricacao'] ?? ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="status">Status do Trem <span class="required">*</span></label>
                                <select id="status" name="status" required>
                                    <option value="operando" <?php echo ($trem['status'] ?? 'inativo') === 'operando' ? 'selected' : ''; ?>>Operando</option>
                                    <option value="manutencao" <?php echo ($trem['status'] ?? '') === 'manutencao' ? 'selected' : ''; ?>>Em Manutenção</option>
                                    <option value="inativo" <?php echo ($trem['status'] ?? 'inativo') === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                                    <option value="em_viagem" <?php echo ($trem['status'] ?? '') === 'em_viagem' ? 'selected' : ''; ?>>Em Viagem</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>⚙️ Capacidade e Desempenho</h2>

                        <div class="form-row-3">
                            <div class="form-group">
                                <label for="capacidade_passageiros">Capacidade (Passageiros)</label>
                                <input type="number" id="capacidade_passageiros" name="capacidade_passageiros"
                                    placeholder="450" min="0" value="<?php echo $trem['capacidade_passageiros'] ?? ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="capacidade_carga">Capacidade de Carga (ton)</label>
                                <input type="number" step="0.01" id="capacidade_carga" name="capacidade_carga"
                                    placeholder="50.00" min="0" value="<?php echo $trem['capacidade_carga'] ?? ''; ?>">
                            </div>

                            <div class="form-group input-with-unit">
                                <label for="velocidade_maxima">Velocidade Máxima</label>
                                <input type="number" step="0.01" id="velocidade_maxima" name="velocidade_maxima"
                                    placeholder="120" min="0" value="<?php echo $trem['velocidade_maxima'] ?? ''; ?>">
                                <span class="unit">km/h</span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group input-with-unit">
                                <label for="consumo_medio">Consumo Médio</label>
                                <input type="number" step="0.01" id="consumo_medio" name="consumo_medio" placeholder="8.5"
                                    min="0" value="<?php echo $trem['consumo_medio'] ?? ''; ?>">
                                <span class="unit">L/km</span>
                            </div>

                            <div class="form-group input-with-unit">
                                <label for="km_rodados">Quilometragem Rodada</label>
                                <input type="number" step="0.01" id="km_rodados" name="km_rodados" placeholder="125340"
                                    min="0" value="<?php echo $trem['km_rodados'] ?? '0'; ?>">
                                <span class="unit">km</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>🔧 Manutenção</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ultima_manutencao">Última Manutenção</label>
                                <input type="date" id="ultima_manutencao" name="ultima_manutencao"
                                    value="<?php echo $trem['ultima_manutencao'] ?? ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="proxima_manutencao">Próxima Manutenção</label>
                                <input type="date" id="proxima_manutencao" name="proxima_manutencao"
                                    value="<?php echo $trem['proxima_manutencao'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>📝 Observações</h2>

                        <div class="form-group">
                            <label for="observacoes">Observações Adicionais</label>
                            <textarea id="observacoes" name="observacoes"
                                placeholder="Informações adicionais sobre o trem..."><?php echo htmlspecialchars($trem['observacoes'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="btn-container">
                        <a href="gerenciartrens.php" class="btn btn-cancelar">✖️ Cancelar</a>
                        <button type="submit" class="btn btn-salvar">
                            ✔️ <?php echo $editando ? 'Atualizar' : 'Cadastrar'; ?> Trem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        document.getElementById('nome').addEventListener('blur', function (e) {
            const codigoInput = document.getElementById('codigo');
            if (!codigoInput.value) {
                const numero = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                codigoInput.value = `TRM-${numero}`;
            }
        });

        document.getElementById('formTrem').addEventListener('submit', function (e) {
            const nome = document.getElementById('nome').value.trim();
            const codigo = document.getElementById('codigo').value.trim();
            const tipo = document.getElementById('tipo').value;

            if (!nome || !codigo || !tipo) {
                e.preventDefault();
                alert('⚠️ Preencha todos os campos obrigatórios (Nome, Código e Tipo)!');
                return false;
            }

            const ultimaManutencao = document.getElementById('ultima_manutencao').value;
            const proximaManutencao = document.getElementById('proxima_manutencao').value;

            if (ultimaManutencao && proximaManutencao) {
                if (new Date(proximaManutencao) <= new Date(ultimaManutencao)) {
                    e.preventDefault();
                    alert('⚠️ A próxima manutenção deve ser posterior à última manutenção!');
                    return false;
                }
            }
        });

        document.getElementById('tipo').addEventListener('change', function (e) {
            const tipo = e.target.value;
            const capacidadeInput = document.getElementById('capacidade_passageiros');
            const cargaInput = document.getElementById('capacidade_carga');

            if (tipo === 'carga') {
                capacidadeInput.value = '0';
                capacidadeInput.disabled = true;
                cargaInput.disabled = false;
                cargaInput.focus();
            } else {
                capacidadeInput.disabled = false;
                cargaInput.disabled = (tipo !== 'carga');
            }
        });
    </script>
</body>

</html>
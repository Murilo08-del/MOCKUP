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
            // Verificar se código já existe
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
                    "sssssiddddssds",
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
            max-width: 900px;
            width: 100%;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #333;
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
    <div class="container">
        <div class="form-card">
            <h1>🚂 <?php echo $editando ? 'Editar' : 'Cadastrar Novo'; ?> Trem</h1>
            <p class="subtitle">
                <?php echo $editando ? 'Atualize as informações do trem' : 'Adicione um novo trem à frota do sistema'; ?>
            </p>

            <?php if (!$editando): ?>
                <div class="info-box">
                    <p>💡 <strong>Dica:</strong> Preencha todos os campos obrigatórios (*) para garantir o registro completo
                        do trem no sistema.</p>
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

                <!-- Seção: Informações Básicas -->
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
                                <option value="luxo" <?php echo ($trem['tipo'] ?? '') === 'luxo' ? 'selected' : ''; ?>>
                                    Luxo</option>
                                <option value="carga" <?php echo ($trem['tipo'] ?? '') === 'carga' ? 'selected' : ''; ?>>
                                    Carga</option>
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

                <!-- Seção: Capacidade e Desempenho -->
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

                <!-- Seção: Manutenção -->
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

                <!-- Seção: Observações -->
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

    <script>
        // Auto-gerar código do trem
        document.getElementById('nome').addEventListener('blur', function (e) {
            const codigoInput = document.getElementById('codigo');
            if (!codigoInput.value) {
                const numero = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                codigoInput.value = `TRM-${numero}`;
            }
        });

        // Validação do formulário
        document.getElementById('formTrem').addEventListener('submit', function (e) {
            const nome = document.getElementById('nome').value.trim();
            const codigo = document.getElementById('codigo').value.trim();
            const tipo = document.getElementById('tipo').value;

            if (!nome || !codigo || !tipo) {
                e.preventDefault();
                alert('⚠️ Preencha todos os campos obrigatórios (Nome, Código e Tipo)!');
                return false;
            }

            // Validar datas de manutenção
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

        // Atualizar placeholder baseado no tipo
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
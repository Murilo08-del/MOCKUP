<?php
session_start();
require "../php/conexao.php";

$mensagem = "";
$tipo_mensagem = "";
$linha_selecionada = null;

// Selecionar linha para editar
if (isset($_GET['linha_id']) || isset($_POST['linha_id'])) {
    $linha_id = isset($_GET['linha_id']) ? $_GET['linha_id'] : $_POST['linha_id'];
    $stmt = $conexao->prepare("SELECT l.*, f.nome as ferrovia_nome FROM linhas l INNER JOIN ferrovias f ON l.ferrovia_id = f.id WHERE l.id = ?");
    $stmt->bind_param("i", $linha_id);
    $stmt->execute();
    $linha_selecionada = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ATUALIZAR STATUS DA LINHA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["atualizar_status"])) {
    $linha_id = $_POST["linha_id"];
    $ativa = $_POST["status"] == 'ativa' ? 1 : 0;

    $stmt = $conexao->prepare("UPDATE linhas SET ativa = ? WHERE id = ?");
    $stmt->bind_param("ii", $ativa, $linha_id);

    if ($stmt->execute()) {
        $mensagem = "Status atualizado com sucesso!";
        $tipo_mensagem = "success";
    }
    $stmt->close();

    // Recarregar dados da linha
    $stmt = $conexao->prepare("SELECT l.*, f.nome as ferrovia_nome FROM linhas l INNER JOIN ferrovias f ON l.ferrovia_id = f.id WHERE l.id = ?");
    $stmt->bind_param("i", $linha_id);
    $stmt->execute();
    $linha_selecionada = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ADICIONAR ESTAÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar_estacao"])) {
    $linha_id = $_POST["linha_id"];
    $nome_estacao = trim($_POST["nome_estacao"]);
    $ordem = $_POST["ordem"];
    $tempo_parada = $_POST["tempo_parada"];

    $stmt = $conexao->prepare("INSERT INTO estacoes (linha_id, nome_estacao, ordem, tempo_parada_minutos) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $linha_id, $nome_estacao, $ordem, $tempo_parada);

    if ($stmt->execute()) {
        $mensagem = "Estação adicionada com sucesso!";
        $tipo_mensagem = "success";
    }
    $stmt->close();
}

// REMOVER ESTAÇÃO
if (isset($_GET["remover_estacao"])) {
    $id = $_GET["remover_estacao"];
    $stmt = $conexao->prepare("DELETE FROM estacoes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensagem = "Estação removida com sucesso!";
        $tipo_mensagem = "success";
    }
    $stmt->close();
}

// ADICIONAR HORÁRIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar_horario"])) {
    $linha_id = $_POST["linha_id"];
    $horario = $_POST["horario"];
    $tipo = $_POST["tipo_horario"];
    $dias = isset($_POST["dias_semana"]) ? implode(",", $_POST["dias_semana"]) : 'seg,ter,qua,qui,sex';

    $stmt = $conexao->prepare("INSERT INTO horarios (linha_id, horario, tipo, dias_semana) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $linha_id, $horario, $tipo, $dias);

    if ($stmt->execute()) {
        $mensagem = "Horário adicionado com sucesso!";
        $tipo_mensagem = "success";
    }
    $stmt->close();
}

// REMOVER HORÁRIO
if (isset($_GET["remover_horario"])) {
    $id = $_GET["remover_horario"];
    $stmt = $conexao->prepare("DELETE FROM horarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $mensagem = "Horário removido com sucesso!";
        $tipo_mensagem = "success";
    }
    $stmt->close();
}

// Buscar todas as linhas para o select
$linhas = $conexao->query("SELECT l.*, f.nome as ferrovia_nome FROM linhas l INNER JOIN ferrovias f ON l.ferrovia_id = f.id ORDER BY f.nome, l.nome");

// Se uma linha está selecionada, buscar estações e horários
$estacoes = [];
$horarios = [];
if ($linha_selecionada) {
    $stmt = $conexao->prepare("SELECT * FROM estacoes WHERE linha_id = ? ORDER BY ordem ASC");
    $stmt->bind_param("i", $linha_selecionada['id']);
    $stmt->execute();
    $estacoes = $stmt->get_result();
    $stmt->close();

    $stmt = $conexao->prepare("SELECT * FROM horarios WHERE linha_id = ? ORDER BY horario ASC");
    $stmt->bind_param("i", $linha_selecionada['id']);
    $stmt->execute();
    $horarios = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/editarlinhasgestor.css">
    <title>Editar Linhas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <style>

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: #818181;
            display: block;
            transition: 0.3s;
            color: black;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .close-btn {
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 20px;
            color: black
        }

        .top-bar {
            background: gray
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .open-btn {
            font-size: 28px;
            cursor: pointer;
            background-color: transparent;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .open-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            flex-grow: 1;
            text-align: center;
        }

        body {
            background: #e28c50;
            min-height: 100vh;
            padding-bottom: 80px;
        }
        
        .container-custom {
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .section-header {
            background: #d6691bff;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 25px 0 15px 0;
            font-weight: bold;
        }
        
        .item-list {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid black
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .status-radio {
            display: flex;
            gap: 30px;
            margin: 15px 0;
        }
        
        .status-radio label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: center;
            gap: 15px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <header class="top-bar">
        <button class="open-btn" onclick="openSidebar()">☰</button>

        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidebar()">X</a>
            <a href="gestormonitoramento.php">Manutenção</a>
            <a href="analisestemporealgestor.html">Análises em Tempo Real</a>
            <a href="gestaoderotas.php">Rotas</a>
            <a href="editarlinhasgestor.php">Editar Linhas</a>
            <a href="contatogestor.html">Contato</a>
        </div>

        <div class="logo"><i class="fas fa-train"></i> MiniTrilhos</div>
    </header>

    <main class="container-custom">
        <h1 class="text-center mb-4"><i class="fas fa-edit"></i> Editar Linha</h1>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Seleção de Linha -->
        <div class="mb-4">
            <h5><i class="fas fa-search"></i> Selecione uma Linha</h5>
            <form method="GET" action="">
                <div class="input-group">
                    <select name="linha_id" class="form-select" required onchange="this.form.submit()">
                        <option value="">-- Escolha uma linha --</option>
                        <?php while ($linha = $linhas->fetch_assoc()): ?>
                            <option value="<?= $linha['id'] ?>" <?= ($linha_selecionada && $linha['id'] == $linha_selecionada['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($linha['ferrovia_nome']) ?> - <?= htmlspecialchars($linha['nome']) ?> (<?= $linha['codigo'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($linha_selecionada): ?>
        
            <!-- Informações Gerais -->
            <div class="section-header">
                <i class="fas fa-info-circle"></i> Informações Gerais
            </div>
        
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Nome da Linha:</strong> <?= htmlspecialchars($linha_selecionada['nome']) ?></p>
                    <p><strong>Código:</strong> <span class="badge" style="background-color: <?= $linha_selecionada['cor'] ?>"><?= htmlspecialchars($linha_selecionada['codigo']) ?></span></p>
                    <p><strong>Ferrovia:</strong> <?= htmlspecialchars($linha_selecionada['ferrovia_nome']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Horário de Operação:</strong> <?= date('H:i', strtotime($linha_selecionada['horario_inicio'])) ?> - <?= date('H:i', strtotime($linha_selecionada['horario_fim'])) ?></p>
                    <p><strong>Intervalo:</strong> <?= $linha_selecionada['intervalo_minutos'] ?> minutos</p>
                </div>
            </div>

            <!-- Status da Linha -->
            <form method="POST">
                <input type="hidden" name="linha_id" value="<?= $linha_selecionada['id'] ?>">
                <div class="mb-4">
                    <h6><strong>Status da Linha:</strong></h6>
                    <div class="status-radio">
                        <label>
                            <input type="radio" name="status" value="ativa" <?= $linha_selecionada['ativa'] ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>✅ Ativa</span>
                        </label>
                        <label>
                            <input type="radio" name="status" value="inativa" <?= !$linha_selecionada['ativa'] ? 'checked' : '' ?> onchange="this.form.submit()">
                            <span>❌ Inativa</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="atualizar_status" value="1">
            </form>

            <!-- Estações -->
            <div class="section-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-map-marker-alt"></i> Estações</span>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarEstacao">
                    <i class="fas fa-plus"></i> Adicionar
                </button>
            </div>

            <?php if ($estacoes->num_rows > 0): ?>
                    <?php while ($estacao = $estacoes->fetch_assoc()): ?>
                        <div class="item-list">
                            <div>
                                <strong><?= $estacao['ordem'] ?>. <?= htmlspecialchars($estacao['nome_estacao']) ?></strong>
                                <br>
                                <small class="text-muted">Tempo de parada: <?= $estacao['tempo_parada_minutos'] ?> min</small>
                            </div>
                            <a href="?linha_id=<?= $linha_selecionada['id'] ?>&remover_estacao=<?= $estacao['id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Tem certeza que deseja remover esta estação?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    <?php endwhile; ?>
            <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-map-marker" style="font-size: 48px;"></i>
                        <p>Nenhuma estação cadastrada</p>
                    </div>
            <?php endif; ?>

            <!-- Horários -->
            <div class="section-header d-flex justify-content-between align-items-center">
                <span><i class="far fa-clock"></i> Horários</span>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarHorario">
                    <i class="fas fa-plus"></i> Adicionar
                </button>
            </div>

            <?php if ($horarios->num_rows > 0): ?>
                    <?php while ($horario = $horarios->fetch_assoc()): ?>
                        <div class="item-list">
                            <div>
                                <strong><?= date('H:i', strtotime($horario['horario'])) ?></strong>
                                <span class="badge bg-info"><?= ucfirst($horario['tipo']) ?></span>
                                <br>
                                <small class="text-muted">Dias: <?= str_replace(',', ', ', strtoupper($horario['dias_semana'])) ?></small>
                            </div>
                            <a href="?linha_id=<?= $linha_selecionada['id'] ?>&remover_horario=<?= $horario['id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Tem certeza que deseja remover este horário?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    <?php endwhile; ?>
            <?php else: ?>
                    <div class="empty-state">
                        <i class="far fa-clock" style="font-size: 48px;"></i>
                        <p>Nenhum horário cadastrado</p>
                    </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-arrow-up" style="font-size: 48px;"></i>
                <p>Selecione uma linha acima para começar a editar</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal Adicionar Estação -->
    <div class="modal fade" id="modalAdicionarEstacao" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-map-marker-alt"></i> Adicionar Estação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="linha_id" value="<?= $linha_selecionada['id'] ?? '' ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Estação</label>
                            <input type="text" class="form-control" name="nome_estacao" placeholder="Ex: Estação Central" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ordem na Linha</label>
                                    <input type="number" class="form-control" name="ordem" min="1" value="<?= ($estacoes->num_rows ?? 0) + 1 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tempo de Parada (min)</label>
                                    <input type="number" class="form-control" name="tempo_parada" min="1" max="10" value="2" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_estacao" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Horário -->
    <div class="modal fade" id="modalAdicionarHorario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="far fa-clock"></i> Adicionar Horário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="linha_id" value="<?= $linha_selecionada['id'] ?? '' ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Horário</label>
                                    <input type="time" class="form-control" name="horario" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-select" name="tipo_horario" required>
                                        <option value="partida">Partida</option>
                                        <option value="chegada">Chegada</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dias da Semana</label>
                            <div class="d-flex flex-wrap gap-2">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="seg" checked>
                                    <span class="form-check-label">Seg</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="ter" checked>
                                    <span class="form-check-label">Ter</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="qua" checked>
                                    <span class="form-check-label">Qua</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="qui" checked>
                                    <span class="form-check-label">Qui</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="sex" checked>
                                    <span class="form-check-label">Sex</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="sab">
                                    <span class="form-check-label">Sáb</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_semana[]" value="dom">
                                    <span class="form-check-label">Dom</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_horario" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer com botões -->
    <?php if ($linha_selecionada): ?>
        <div class="btn-footer">
            <a href="gestaoderotas.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <a href="editarlinhasgestor.php" class="btn btn-success btn-lg">
                <i class="fas fa-check"></i> Concluir Edição
            </a>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function openSidebar() {
            document.getElementById("mySidebar").style.width = "280px";
            
            let overlay = document.getElementById("sidebarOverlay");
            if (!overlay) {
                overlay = document.createElement("div");
                overlay.id = "sidebarOverlay";
                overlay.className = "sidebar-overlay";
                overlay.onclick = closeSidebar;
                overlay.style.cssText = "display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9998;";
                document.body.appendChild(overlay);
            }
            overlay.style.display = "block";
        }

        function closeSidebar() {
            document.getElementById("mySidebar").style.width = "0";
            
            const overlay = document.getElementById("sidebarOverlay");
            if (overlay) {
                overlay.style.display = "none";
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

        // Fechar alertas automaticamente
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>

</html>
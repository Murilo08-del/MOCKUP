<?php
session_start();
require "../php/conexao.php";

// Verifica se o usuário está conectado (opcional, dependendo do seu sistema)
// if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
//     header("Location: ../php/login.php");
//     exit;
// }

$mensagem = "";
$tipo_mensagem = "";

// ADICIONAR INSPEÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar_inspecao"])) {
    $nome = trim($_POST["nome"]);
    $status = $_POST["status"];
    $data = $_POST["data_inspecao"];
    $observacoes = trim($_POST["observacoes"]);

    $stmt = $conexao->prepare("INSERT INTO inspecoes (nome, status, data_inspecao, observacoes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $status, $data, $observacoes);
    
    if ($stmt->execute()) {
        $mensagem = "Inspeção adicionada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao adicionar inspeção!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// EDITAR INSPEÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar_inspecao"])) {
    $id = $_POST["id"];
    $nome = trim($_POST["nome"]);
    $status = $_POST["status"];
    $data = $_POST["data_inspecao"];
    $observacoes = trim($_POST["observacoes"]);

    $stmt = $conexao->prepare("UPDATE inspecoes SET nome = ?, status = ?, data_inspecao = ?, observacoes = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nome, $status, $data, $observacoes, $id);
    
    if ($stmt->execute()) {
        $mensagem = "Inspeção atualizada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao atualizar inspeção!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// REMOVER INSPEÇÃO
if (isset($_GET["remover_inspecao"])) {
    $id = $_GET["remover_inspecao"];
    $stmt = $conexao->prepare("DELETE FROM inspecoes WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = "Inspeção removida com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao remover inspeção!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// ADICIONAR ALERTA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar_alerta"])) {
    $titulo = trim($_POST["titulo"]);
    $descricao = trim($_POST["descricao"]);
    $prioridade = $_POST["prioridade"];
    $data = $_POST["data_alerta"];

    $stmt = $conexao->prepare("INSERT INTO alertas_manutencao (titulo, descricao, prioridade, data_alerta) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $descricao, $prioridade, $data);
    
    if ($stmt->execute()) {
        $mensagem = "Alerta adicionado com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao adicionar alerta!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// REMOVER ALERTA
if (isset($_GET["remover_alerta"])) {
    $id = $_GET["remover_alerta"];
    $stmt = $conexao->prepare("DELETE FROM alertas_manutencao WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = "Alerta removido com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao remover alerta!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// BUSCAR TODAS AS INSPEÇÕES
$inspecoes = $conexao->query("SELECT * FROM inspecoes ORDER BY data_inspecao DESC");

// BUSCAR TODOS OS ALERTAS
$alertas = $conexao->query("SELECT * FROM alertas_manutencao WHERE resolvido = FALSE ORDER BY data_alerta DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/gestormonitoramento.css">
    <title>Monitoramento de Manutenção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <style>
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-concluida { background: #28a745; color: white; }
        .status-pendente { background: #ffc107; color: black; }
        .status-urgente { background: #dc3545; color: white; }
        
        .btn-action {
            margin: 2px;
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .inspecao-card {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .modal-backdrop.show {
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <header class="top-bar">
        <button class="open-btn" onclick="openSidebar()">☰</button>

        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidebar()">×</a>
            <a href="gestormonitoramento.php">Manutenção</a>
            <a href="analisestemporealgestor.html">Análises em Tempo Real</a>
            <a href="gestaoderotas.html">Rotas</a>
            <a href="editarlinhasgestor.html">Editar Linhas</a>
            <a href="contatogestor.html">Contato</a>
        </div>

        <div class="logo"><i class="fas fa-train"></i> MiniTrilhos</div>
    </header>

    <main>
        <section id="cor">
            <div class="texto">
                <h1>Monitoramento de Manutenção</h1>
            </div>

            <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <section class="caixa">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="inspe"><b>Inspeções</b></div>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarInspecao">
                        <i class="fas fa-plus"></i> Adicionar Inspeção
                    </button>
                </div>

                <?php while ($inspecao = $inspecoes->fetch_assoc()): ?>
                <div class="inspecao-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5><?= htmlspecialchars($inspecao['nome']) ?></h5>
                            <span class="status-badge status-<?= strtolower($inspecao['status']) ?>">
                                <?= htmlspecialchars($inspecao['status']) ?>
                            </span>
                            <p class="mt-2 mb-1"><small><i class="far fa-calendar"></i> <?= date('d/m/Y', strtotime($inspecao['data_inspecao'])) ?></small></p>
                            <?php if ($inspecao['observacoes']): ?>
                            <p class="text-muted"><small><?= htmlspecialchars($inspecao['observacoes']) ?></small></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-action btn-sm" onclick="editarInspecao(<?= htmlspecialchars(json_encode($inspecao)) ?>)">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <a href="?remover_inspecao=<?= $inspecao['id'] ?>" class="btn btn-danger btn-action btn-sm" onclick="return confirm('Tem certeza que deseja remover esta inspeção?')">
                                <i class="fas fa-trash"></i> Remover
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="manute"><b>Alertas de Manutenção</b></div>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarAlerta">
                        <i class="fas fa-plus"></i> Adicionar Alerta
                    </button>
                </div>

                <?php while ($alerta = $alertas->fetch_assoc()): ?>
                <div class="inspecao-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6><?= htmlspecialchars($alerta['titulo']) ?></h6>
                            <?php if ($alerta['descricao']): ?>
                            <p class="text-muted mb-1"><small><?= htmlspecialchars($alerta['descricao']) ?></small></p>
                            <?php endif; ?>
                            <p class="mb-0"><small><i class="far fa-calendar"></i> <?= date('d/m/Y', strtotime($alerta['data_alerta'])) ?></small></p>
                        </div>
                        <div>
                            <a href="?remover_alerta=<?= $alerta['id'] ?>" class="btn btn-danger btn-action btn-sm" onclick="return confirm('Tem certeza que deseja remover este alerta?')">
                                <i class="fas fa-trash"></i> Remover
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </section>
        </section>
    </main>

    <!-- Modal Adicionar Inspeção -->
    <div class="modal fade" id="modalAdicionarInspecao" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Inspeção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Inspeção</label>
                            <input type="text" class="form-control" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="Pendente">Pendente</option>
                                <option value="Concluída">Concluída</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-control" name="data_inspecao" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_inspecao" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Inspeção -->
    <div class="modal fade" id="modalEditarInspecao" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Inspeção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Inspeção</label>
                            <input type="text" class="form-control" name="nome" id="edit_nome" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="Pendente">Pendente</option>
                                <option value="Concluída">Concluída</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-control" name="data_inspecao" id="edit_data" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" id="edit_observacoes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_inspecao" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Alerta -->
    <div class="modal fade" id="modalAdicionarAlerta" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Título do Alerta</label>
                            <input type="text" class="form-control" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prioridade</label>
                            <select class="form-select" name="prioridade" required>
                                <option value="Baixa">Baixa</option>
                                <option value="Média">Média</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-control" name="data_alerta" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_alerta" class="btn btn-warning">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/gestormonitoramento.js"></script>
    
    <script>
        function editarInspecao(inspecao) {
            document.getElementById('edit_id').value = inspecao.id;
            document.getElementById('edit_nome').value = inspecao.nome;
            document.getElementById('edit_status').value = inspecao.status;
            document.getElementById('edit_data').value = inspecao.data_inspecao;
            document.getElementById('edit_observacoes').value = inspecao.observacoes || '';
            
            var modal = new bootstrap.Modal(document.getElementById('modalEditarInspecao'));
            modal.show();
        }

        function openSidebar() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeSidebar() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
</body>

</html>
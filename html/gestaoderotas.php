<?php
session_start();
require "../php/conexao.php";

$mensagem = "";
$tipo_mensagem = "";

// ADICIONAR FERROVIA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar_ferrovia"])) {
    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    
    $stmt = $conexao->prepare("INSERT INTO ferrovias (nome, descricao) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $descricao);
    
    if ($stmt->execute()) {
        $mensagem = "Ferrovia adicionada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao adicionar ferrovia!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// EDITAR FERROVIA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar_ferrovia"])) {
    $id = $_POST["id"];
    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    
    $stmt = $conexao->prepare("UPDATE ferrovias SET nome = ?, descricao = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nome, $descricao, $id);
    
    if ($stmt->execute()) {
        $mensagem = "Ferrovia atualizada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao atualizar ferrovia!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// ATIVAR/DESATIVAR FERROVIA
if (isset($_GET["toggle_ferrovia"])) {
    $id = $_GET["toggle_ferrovia"];
    $stmt = $conexao->prepare("UPDATE ferrovias SET ativa = NOT ativa WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = "Status da ferrovia alterado!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao alterar status!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// ADICIONAR LINHA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["adicionar_linha"])) {
    $ferrovia_id = $_POST["ferrovia_id"];
    $nome = trim($_POST["nome"]);
    $codigo = trim($_POST["codigo"]);
    $horario_inicio = $_POST["horario_inicio"];
    $horario_fim = $_POST["horario_fim"];
    $intervalo_minutos = $_POST["intervalo_minutos"];
    $cor = $_POST["cor"];
    
    $stmt = $conexao->prepare("INSERT INTO linhas (ferrovia_id, nome, codigo, horario_inicio, horario_fim, intervalo_minutos, cor) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $ferrovia_id, $nome, $codigo, $horario_inicio, $horario_fim, $intervalo_minutos, $cor);
    
    if ($stmt->execute()) {
        $mensagem = "Linha adicionada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao adicionar linha!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// EDITAR LINHA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar_linha"])) {
    $id = $_POST["id"];
    $nome = trim($_POST["nome"]);
    $codigo = trim($_POST["codigo"]);
    $horario_inicio = $_POST["horario_inicio"];
    $horario_fim = $_POST["horario_fim"];
    $intervalo_minutos = $_POST["intervalo_minutos"];
    $cor = $_POST["cor"];
    
    $stmt = $conexao->prepare("UPDATE linhas SET nome = ?, codigo = ?, horario_inicio = ?, horario_fim = ?, intervalo_minutos = ?, cor = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nome, $codigo, $horario_inicio, $horario_fim, $intervalo_minutos, $cor, $id);
    
    if ($stmt->execute()) {
        $mensagem = "Linha atualizada com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao atualizar linha!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// ATIVAR/DESATIVAR LINHA
if (isset($_GET["toggle_linha"])) {
    $id = $_GET["toggle_linha"];
    $stmt = $conexao->prepare("UPDATE linhas SET ativa = NOT ativa WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = "Status da linha alterado!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao alterar status!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// REMOVER LINHA
if (isset($_GET["remover_linha"])) {
    $id = $_GET["remover_linha"];
    $stmt = $conexao->prepare("DELETE FROM linhas WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensagem = "Linha removida com sucesso!";
        $tipo_mensagem = "success";
    } else {
        $mensagem = "Erro ao remover linha!";
        $tipo_mensagem = "danger";
    }
    $stmt->close();
}

// BUSCAR TODAS AS FERROVIAS COM SUAS LINHAS
$ferrovias = $conexao->query("SELECT * FROM ferrovias ORDER BY nome ASC");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/gestaoderotas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Gestão de Rotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    
    <style>
        body {
            background: #e28c50;
            min-height: 100vh;
            padding-bottom: 50px;
        }
        
        .container-custom {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .ferrovia-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .ferrovia-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .ferrovia-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .ferrovia-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .status-ativa {
            background: #28a745;
            color: white;
        }
        
        .status-inativa {
            background: #dc3545;
            color: white;
        }
        
        .linha-item {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            border-left: 5px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .linha-info {
            flex: 1;
        }
        
        .linha-nome {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .linha-detalhes {
            font-size: 13px;
            color: #666;
        }
        
        .linha-actions {
            display: flex;
            gap: 8px;
        }
        
        .btn-action {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .cor-preview {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .page-title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .btn-add-main {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 1000;
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
            <a href="gestaoderotas.php">Rotas</a>
            <a href="editarlinhasgestor.html">Editar Linhas</a>
            <a href="contatogestor.html">Contato</a>
        </div>

        <div class="logo"><i class="fas fa-train"></i> MiniTrilhos</div>
    </header>

    <main class="container-custom">
        <h1 class="page-title"><i class="fas fa-route"></i> Gestão de Rotas Ferroviárias</h1>

        <?php if ($mensagem): ?>
        <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php while ($ferrovia = $ferrovias->fetch_assoc()): ?>
            <?php
            // Buscar linhas desta ferrovia
            $stmt = $conexao->prepare("SELECT * FROM linhas WHERE ferrovia_id = ? ORDER BY codigo ASC");
            $stmt->bind_param("i", $ferrovia['id']);
            $stmt->execute();
            $linhas = $stmt->get_result();
            ?>
            
        <div class="ferrovia-card">
            <div class="ferrovia-header">
                <div>
                    <h2 class="ferrovia-title">
                        <i class="fas fa-train"></i> <?= htmlspecialchars($ferrovia['nome']) ?>
                    </h2>
                    <?php if ($ferrovia['descricao']): ?>
                    <p class="text-muted mb-0"><?= htmlspecialchars($ferrovia['descricao']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="status-badge status-<?= $ferrovia['ativa'] ? 'ativa' : 'inativa' ?>">
                        <?= $ferrovia['ativa'] ? 'Ativa' : 'Inativa' ?>
                    </span>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-subway"></i> Linhas</h5>
                <div>
                    <button class="btn btn-sm btn-success" onclick="abrirModalAdicionarLinha(<?= $ferrovia['id'] ?>)">
                        <i class="fas fa-plus"></i> Adicionar Linha
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="editarFerrovia(<?= htmlspecialchars(json_encode($ferrovia)) ?>)">
                        <i class="fas fa-edit"></i> Editar Ferrovia
                    </button>
                    <a href="?toggle_ferrovia=<?= $ferrovia['id'] ?>" class="btn btn-sm btn-<?= $ferrovia['ativa'] ? 'warning' : 'success' ?>">
                        <i class="fas fa-power-off"></i> <?= $ferrovia['ativa'] ? 'Desativar' : 'Ativar' ?>
                    </a>
                </div>
            </div>

            <?php if ($linhas->num_rows > 0): ?>
                <?php while ($linha = $linhas->fetch_assoc()): ?>
                <div class="linha-item" style="border-left-color: <?= htmlspecialchars($linha['cor']) ?>">
                    <div class="linha-info">
                        <div class="linha-nome">
                            <span class="cor-preview" style="background-color: <?= htmlspecialchars($linha['cor']) ?>"></span>
                            <?= htmlspecialchars($linha['nome']) ?> 
                            <span class="badge bg-secondary"><?= htmlspecialchars($linha['codigo']) ?></span>
                            <?php if (!$linha['ativa']): ?>
                            <span class="badge bg-danger">Inativa</span>
                            <?php endif; ?>
                        </div>
                        <div class="linha-detalhes">
                            <i class="far fa-clock"></i> <?= date('H:i', strtotime($linha['horario_inicio'])) ?> - <?= date('H:i', strtotime($linha['horario_fim'])) ?> 
                            | <i class="fas fa-stopwatch"></i> Intervalo: <?= $linha['intervalo_minutos'] ?> min
                        </div>
                    </div>
                    <div class="linha-actions">
                        <button class="btn btn-sm btn-primary btn-action" onclick="editarLinha(<?= htmlspecialchars(json_encode($linha)) ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="?toggle_linha=<?= $linha['id'] ?>" class="btn btn-sm btn-<?= $linha['ativa'] ? 'warning' : 'success' ?> btn-action" title="<?= $linha['ativa'] ? 'Desativar' : 'Ativar' ?>">
                            <i class="fas fa-power-off"></i>
                        </a>
                        <a href="?remover_linha=<?= $linha['id'] ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Tem certeza que deseja remover esta linha?')" title="Remover">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox" style="font-size: 48px;"></i>
                    <p>Nenhuma linha cadastrada para esta ferrovia</p>
                </div>
            <?php endif; ?>
            
            <?php $stmt->close(); ?>
        </div>
        <?php endwhile; ?>
        
        <?php if ($ferrovias->num_rows == 0): ?>
        <div class="ferrovia-card">
            <div class="empty-state">
                <i class="fas fa-train" style="font-size: 64px;"></i>
                <h3>Nenhuma ferrovia cadastrada</h3>
                <p>Clique no botão abaixo para adicionar sua primeira ferrovia</p>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <!-- Botão flutuante para adicionar ferrovia -->
    <button class="btn btn-success btn-add-main" data-bs-toggle="modal" data-bs-target="#modalAdicionarFerrovia" title="Adicionar Ferrovia">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal Adicionar Ferrovia -->
    <div class="modal fade" id="modalAdicionarFerrovia" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-train"></i> Adicionar Nova Ferrovia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Ferrovia</label>
                            <input type="text" class="form-control" name="nome" placeholder="Ex: Ferrovia Central" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao" rows="3" placeholder="Descreva a rota e principais características"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_ferrovia" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Ferrovia -->
    <div class="modal fade" id="modalEditarFerrovia" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Ferrovia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_ferrovia_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Ferrovia</label>
                            <input type="text" class="form-control" name="nome" id="edit_ferrovia_nome" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" name="descricao" id="edit_ferrovia_descricao" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_ferrovia" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Linha -->
    <div class="modal fade" id="modalAdicionarLinha" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-subway"></i> Adicionar Nova Linha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="ferrovia_id" id="add_linha_ferrovia_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nome da Linha</label>
                                    <input type="text" class="form-control" name="nome" placeholder="Ex: Linha A - Expressa" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" name="codigo" placeholder="Ex: LA" maxlength="10" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Início</label>
                                    <input type="time" class="form-control" name="horario_inicio" value="05:00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fim</label>
                                    <input type="time" class="form-control" name="horario_fim" value="23:00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Intervalo (min)</label>
                                    <input type="number" class="form-control" name="intervalo_minutos" value="15" min="1" max="120" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cor da Linha</label>
                            <input type="color" class="form-control form-control-color" name="cor" value="#667eea" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_linha" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Linha -->
    <div class="modal fade" id="modalEditarLinha" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Linha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="edit_linha_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nome da Linha</label>
                                    <input type="text" class="form-control" name="nome" id="edit_linha_nome" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" name="codigo" id="edit_linha_codigo" maxlength="10" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Início</label>
                                    <input type="time" class="form-control" name="horario_inicio" id="edit_linha_inicio" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fim</label>
                                    <input type="time" class="form-control" name="horario_fim" id="edit_linha_fim" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Intervalo (min)</label>
                                    <input type="number" class="form-control" name="intervalo_minutos" id="edit_linha_intervalo" min="1" max="120" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cor da Linha</label>
                            <input type="color" class="form-control form-control-color" name="cor" id="edit_linha_cor" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar_linha" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/gestaoderotas.js"></script>
    
    <script>
        function openSidebar() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeSidebar() {
            document.getElementById("mySidebar").style.width = "0";
        }

        function editarFerrovia(ferrovia) {
            document.getElementById('edit_ferrovia_id').value = ferrovia.id;
            document.getElementById('edit_ferrovia_nome').value = ferrovia.nome;
            document.getElementById('edit_ferrovia_descricao').value = ferrovia.descricao || '';
            
            var modal = new bootstrap.Modal(document.getElementById('modalEditarFerrovia'));
            modal.show();
        }

        function abrirModalAdicionarLinha(ferroviaId) {
            document.getElementById('add_linha_ferrovia_id').value = ferroviaId;
            
            var modal = new bootstrap.Modal(document.getElementById('modalAdicionarLinha'));
            modal.show();
        }

        function editarLinha(linha) {
            document.getElementById('edit_linha_id').value = linha.id;
            document.getElementById('edit_linha_nome').value = linha.nome;
            document.getElementById('edit_linha_codigo').value = linha.codigo;
            document.getElementById('edit_linha_inicio').value = linha.horario_inicio;
            document.getElementById('edit_linha_fim').value = linha.horario_fim;
            document.getElementById('edit_linha_intervalo').value = linha.intervalo_minutos;
            document.getElementById('edit_linha_cor').value = linha.cor;
            
            var modal = new bootstrap.Modal(document.getElementById('modalEditarLinha'));
            modal.show();
        }

        // Fechar alertas automaticamente após 5 segundos
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
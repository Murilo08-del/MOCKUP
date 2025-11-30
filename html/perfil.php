<?php
require "../php/conexao.php";
session_start();

if (!isset($_SESSION['conectado']) || $_SESSION['conectado'] !== true) {
    header("Location: ../php/login.php");
    exit;
}

$usuario_id = $_SESSION['id'];
$mensagem = "";
$tipo_mensagem = "";

// ==================== ATUALIZAR PERFIL ====================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['atualizar_perfil'])) {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    // Removido: telefone e cargo — não serão mais atualizados pelo perfil

    if (empty($nome) || empty($email)) {
        $mensagem = "Nome e email são obrigatórios.";
        $tipo_mensagem = "error";
    } else {
        $stmt = $conexao->prepare("UPDATE usuarios SET nome=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $nome, $email, $usuario_id);

        if ($stmt->execute()) {
            $_SESSION['nome'] = $nome;
            $mensagem = "Perfil atualizado com sucesso!";
            $tipo_mensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar perfil.";
            $tipo_mensagem = "error";
        }
        $stmt->close();
    }
}

// ==================== ALTERAR SENHA ====================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST["senha_atual"] ?? "";
    $nova_senha = $_POST["nova_senha"] ?? "";
    $confirmar_senha = $_POST["confirmar_senha"] ?? "";

    if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
        $mensagem = "Preencha todos os campos de senha.";
        $tipo_mensagem = "error";
    } elseif ($nova_senha !== $confirmar_senha) {
        $mensagem = "As senhas não coincidem.";
        $tipo_mensagem = "error";
    } else {
        // Verificar senha atual
        $stmt = $conexao->prepare("SELECT senha FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha_atual, $usuario['senha'])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $conexao->prepare("UPDATE usuarios SET senha=? WHERE id=?");
            $stmt->bind_param("si", $nova_senha_hash, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Senha alterada com sucesso!";
                $tipo_mensagem = "success";
            } else {
                $mensagem = "Erro ao alterar senha.";
                $tipo_mensagem = "error";
            }
        } else {
            $mensagem = "Senha atual incorreta.";
            $tipo_mensagem = "error";
        }
        $stmt->close();
    }
}

// Buscar dados do usuário
$stmt = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Sistema Ferroviário</title>

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
        }

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

        .main-content {
            margin-left: 250px;
            flex: 1;
            max-width: 1000px;
            margin-right: auto;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: black;
            font-size: 2em;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }

        .profile-sidebar {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            height: fit-content;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d6651aff 0%, #5b575fff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4em;
            color: white;
            margin: 0 auto 20px;
        }

        .profile-sidebar h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .profile-sidebar .role {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .stat-box .number {
            font-size: 2em;
            font-weight: bold;
            color: #d6651aff;
        }

        .stat-box .label {
            font-size: 0.8em;
            color: #666;
        }

        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
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

        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #d6651aff;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: gray;
            color: white;
        }

        .btn-primary:hover {
            background: black;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #666;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .profile-container {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
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

    <main class="main-content">
        <header>
            <h1>👤 Meu Perfil</h1>
            <p style="color: #666; margin-top: 5px;">Gerencie suas informações pessoais e preferências</p>
        </header>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <div class="profile-sidebar">
                <div class="avatar">
                    <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
                </div>
                <h2><?php echo htmlspecialchars($usuario['nome']); ?></h2>
                <!-- campo 'cargo' removido do perfil -->

                <div class="profile-stats">
                    <div class="stat-box">
                        <div class="number">24</div>
                        <div class="label">Ações</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">5</div>
                        <div class="label">Dias Ativos</div>
                    </div>
                </div>
            </div>

            <div class="profile-main">
                <div class="card">
                    <h3>📋 Informações Pessoais</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Nome Completo</label>
                            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>"
                                required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email"
                                    value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>

                            <!-- campo 'telefone' removido do formulário de perfil -->
                        </div>

                        <!-- campo 'cargo' removido do formulário de perfil -->

                        <button type="submit" name="atualizar_perfil" class="btn btn-primary">💾 Salvar
                            Alterações</button>
                    </form>
                </div>

                <div class="card">
                    <h3>🔒 Segurança</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Senha Atual</label>
                            <input type="password" name="senha_atual" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Nova Senha</label>
                                <input type="password" name="nova_senha" required>
                            </div>

                            <div class="form-group">
                                <label>Confirmar Nova Senha</label>
                                <input type="password" name="confirmar_senha" required>
                            </div>
                        </div>

                        <button type="submit" name="alterar_senha" class="btn btn-primary">🔐 Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
<?php
require "../php/conexao.php";
session_start();

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["registrar-se"])) { // verifica se o botão foi clicado
        $nome = trim($_POST["nome"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $senha = trim($_POST["senha"] ?? "");
        $confirmar_senha = trim($_POST["confirmar_senha"] ?? "");

        // Validação de campos vazios
        if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
            $erro = "Todos os campos são obrigatórios.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $senha)) {
            $erro = "Senha fraca. Use pelo menos 8 caracteres, incluindo maiúscula, minúscula, número e caractere especial.";
        } elseif ($senha !== $confirmar_senha) {
            $erro = "As senhas não coincidem.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "E-mail inválido.";
        } else {
            // Verifica se já existe usuário com o mesmo e-mail
            $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email  = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $erro = "E-mail já registrados. Tente outros.";
            } else {
                // Criptografa a senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Insere o novo usuário no banco
                $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?,?, ?)");
                $stmt->bind_param("sss", $nome, $email, $senha_hash);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit;
                } else {
                    $erro = "Erro ao registrar. Tente novamente.";
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
    <link rel="stylesheet" href="../css/crieconta.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>crieconta</title>
    <style>
        .alert-erro {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            margin: 20px auto;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            text-align: center;
            max-width: 600px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <header>
        <nav id="navbar">
            <div class="voltar">
                <b>
                    <a href="../php/login.php">
                        << </a>
                </b>
            </div>
        </nav>
    </header>
    <main>
        <div id="marcatitulo">
            <p>MiniTrilhos</p>
        </div>
        <section class="textlogin">
            <h1>CRIAR CONTA</h1>
        </section>
        <div class="imagem">
            <img src="../img/login-removebg-preview.png ">
        </div>
        </section>

        <form method="post" action="">
            <section id="conta">
                <div class="user">
                    <input name="nome" type="text" placeholder=" Nome completo" required>
                </div>
                <div class="user">
                    <input name="email" type="text" placeholder=" E-mail " required>
                </div>
                <div class="password">
                    <input name="senha" type="password" placeholder=" Senha" required>
                </div>
                <div class="password">
                    <input name="confirmar_senha" type="password" placeholder=" Senha" required>
                </div>
                <button name="registrar-se" class="entrar" type="submit">Criar Conta</button>
            </section>
        </form>
        <?php if ($erro): ?>
            <div class="alert-erro">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
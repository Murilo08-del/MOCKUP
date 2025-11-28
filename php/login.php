<?php
require "../php/conexao.php";
session_start();

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["login"])) { //verifica se o botão foi clicado
        $email = trim($_POST["E-mail"] ?? ""); //evita espaços vazios
        $senha = trim($_POST["Senha"] ?? "");

        // Verifica se o nome de usuário e senha estão corretos
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verifica se encontrou um usuário com as credenciais fornecidas
        if ($resultado->num_rows === 1) {
            $dados = $resultado->fetch_assoc();
            $senha_armazenada_rash = $dados["senha"];

            if (password_verify($senha, $senha_armazenada_rash)) {

                $_SESSION['id'] = $dados['id'];
                $_SESSION['email'] = $dados['email'];
                $_SESSION['senha'] = $dados['senha'];
                $_SESSION['nome'] = $dados['nome'];

                $_SESSION["conectado"] = true;
                header("location: ../html/dashboard.php");
                exit;
            } else {
                $erro = "Usuário ou senha inválidos";
            }
        } else {
            $erro = "Usuário não encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Login</title>

    <style>
        .mensagem-erro {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin: 20px auto;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <header>
        <nav id="navbar"></nav>

    </header>

    <main>
        <section id="marcatitulo">
            <p>MiniTrilhos</p>
        </section>

        <section class="textlogin">
            <h1>LOGIN</h1>
        </section>

        <div class="imagem">
            <img src="../img/login-removebg-preview.png ">
        </div>
        </section>

        <section id="conta">
            <div class="usuario">
                <p>Usuário</p>
            </div>
            <div class="user">
                <form method="post" action="">
                    <input name="E-mail" class="usuario-input" type="text" placeholder=" E-mail " required> <i
                        class="bi bi-person-fill"></i>
            </div>
            <div class="senha">
                <p>Senha</p>
            </div>
            <div class="password">
                <input name="Senha" type="password" placeholder=" Senha" required><i class="bi bi-shield-lock"></i>
            </div>
            <div class="enviar">
                <button class="entrar" type="submit" name="login">Entrar</button>
            </div>
            </form>

            <?php if ($erro): ?>
                <p class="mensagem-erro"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <div class="crieconta">
                <p>Não tem conta ainda? <a class="cor-link" href="crieconta.php">Crie agora!</a></p>
            </div>
        </section>
        </div>
    </main>

</body>


</html>
<?php
$nome_completo = "";
$telefone = "";
$email = "";
$mensagem = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["enviar_info"])) {
        $nome_completo = trim($_POST["nome_completo"] ?? "");
        $telefone = trim($_POST["telefone"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $mensagem = trim($_POST["mensagem"] ?? "");
        /*
                if (empty($origem) || empty($destino)) {
                    $erro = "Por favor, preencha todos os campos!";
                } else {
                    $sucesso = "Formulário enviado com sucesso!";

                }
                    */ // Usado para frente em testes
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contato.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Contato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <script src="../js/contato.js"></script>
</head>

<body>
    <header class="top-bar">

        <button class="open-btn" onclick="openSidebar()">☰</button>

        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidebar()">×</a>
            <a href="dashboard.php">Início</a>
            <a href="noticias.html">Notícia</a>
            <a href="Linhas.html">Linhas</a>
            <a href="meulocal.html">Meu Local</a>
            <a href="comochegar.html">Como Chegar</a>
            <a href="buscar.html">Buscar</a>
            <a href="contato.html">Contato</a>
        </div>


        <div class="logo"><i class="fas fa-train"></i> MiniTrilhos</div>


        <div class="icons">
            <a href="../html/meulocal.html">
                <i class="fas fa-map-marker-alt"></i>
            </a>
            <a href="../html/Linhas.html">
                <i class="fas fa-train"></i>
            </a>
            <a href="../html/buscar.html">
                <i class="fas fa-search"></i>
            </a>
        </div>
    </header>

    <main>
        <div class="titulo">
            <h1>Qual o motivo do seu contato?</h1>
        </div>


        <div class="flexivel">
            <label>
                <input type="radio" name="clicavel" value="comentario" required>
                <span style="font-size: 2.5vh;">Comentário</span>
            </label>
            <br>
            <label>
                <input type="radio" name="clicavel" value="pergunta" required>
                <span style="font-size: 2.5vh;">Pergunta</span>
            </label>
            </label>
            <br>
            <label>
                <input type="radio" name="clicavel" value="sugestao" required>
                <span style="font-size: 2.5vh;">Sugestão</span>
            </label>
            </label>
            <br>
            <label>
                <input type="radio" name="clicavel" value="reclamacao" required>
                <span style="font-size: 2.5vh;">Reclamação</span>
        </div>
    </main>

    <footer>
        <form method="POST" action="">
            <div class="caixa">
                <div>
                    <h2>Informações</h2>

                    <hr>
                </div>

                <div class="info">
                    <input name="nome_completo" type="text" placeholder="  Nome completo" required>
                </div>
                <div class="info">
                    <input name="telefone" type="text" placeholder="  Telefone" required>
                </div>
                <div class="info">
                    <input name="email" type="text" placeholder="  E-mail" required>
                </div>
                <div class="info">
                    <input name="mensagem" type="text" placeholder="  Mensagem" required>
                </div>

                <br>
                <div class="botao">
                    <a href="#">
                        <button name="enviar_info" class="enviar" type="submit">Enviar</button>
                    </a>
                </div>
            </div>
        </form>


    </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script src="../js/contato.js"></script>
</body>

</html>
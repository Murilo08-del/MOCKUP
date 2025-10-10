<?php
$erro = "";
$sucesso = "";
$como_achar = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["como_achar"])) {
        $origem = trim($_POST["origem"] ?? "");
        $destino = trim($_POST["destino"] ?? "");
        if (empty($origem) || empty($destino)) {
            $erro = "Por favor, preencha todos os campos!";
        } else {
            $sucesso = "Formulário enviado com sucesso!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/comochegar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Linhas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <script src="../js/comochegar.js"></script>
    <style>
        .alert-sucesso {
            background-color: #d4edda;
            color: #155724;
            padding: 15px 20px;
            margin: 20px auto;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            text-align: center;
            max-width: 600px;
            font-weight: 500;
        }

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
    <header class="top-bar">

        <button class="open-btn" onclick="openSidebar()">☰</button>

        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidebar()">×</a>
            <a href="dashboard.php">Início</a>
            <a href="noticias.html">Notícia</a>
            <a href="Linhas.php">Linhas</a>
            <a href="meulocal.php">Meu Local</a>
            <a href="comochegar.php">Como Chegar</a>
            <a href="buscar.php">Buscar</a>
            <a href="contato.php">Contato</a>
        </div>


        <div class="logo"><i class="fas fa-train"></i> MiniTrilhos</div>

        <div class="icons">
            <a href="../php/meulocal.php">
                <i class="fas fa-map-marker-alt"></i>
            </a>
            <a href="../php/Linhas.php">
                <i class="fas fa-train"></i>
            </a>
            <a href="../php/buscar.php">
                <i class="fas fa-search"></i>
            </a>
        </div>
    </header>

    <main>
        <form method="post" action="">
            <div class="caixa">
                <h1>Como chegar</h1>

                <div class="inserir">
                    <input type="text" placeholder=" Inserir origem" name="origem">
                </div>
                <div class="inserir2">
                    <input type="text" placeholder=" Inserir destino" name="destino">
                </div>

                <div class="botao">
                    <a href="#">
                        <button name="como_achar" class="enviar" type="submit">Enviar</button>
                    </a>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="alert-erro">
                        <strong> Erro:</strong> <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($sucesso)): ?>
                    <div class="alert-sucesso">
                        <strong> Sucesso:</strong> <?php echo htmlspecialchars($sucesso); ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <div class="linha">
            <p>____________________________________________________</p>
        </div>
        <div class="flexivel">
            <i class="bi bi-exclamation-circle-fill"> Itinerário</i>
        </div>
    </main>

    <footer>
        <div class="mapa">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14305.21780163144!2d-48.847432713382446!3d-26.31663938178757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94deb17917615d17%3A0xc4ff5570e603a778!2zU2VkZSBkYSBFc3Rhw6fDo28gZGEgTWVtw7NyaWEg8J-agg!5e0!3m2!1spt-BR!2sbr!4v1748962422860!5m2!1spt-BR!2sbr"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script src="../js/comochegar.js"></script>
</body>

</html>
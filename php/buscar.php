<?php
$pesquisar = "";
$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["pesquisar"])) {
        $pesquisar = trim($_POST["pesquisar_linha"] ?? "");

        if (empty($pesquisar)) {
            $erro = "Por favor, preencha o campo de busca!";
        } else {
            $sucesso = "Buscando por: " . htmlspecialchars($pesquisar);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/buscar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Buscar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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

        .pesquisa input[type="text"] {
            flex: 1;
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s ease;
        }

        .pesquisa input[type="text"]:focus {
            border-color: gray;
            box-shadow: 0 0 5px rgba(128, 128, 128, 0.3);
        }

        .lupa {
            background-color: gray;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            min-width: 45px;
            height: 45px;
        }

        .lupa:hover {
            background-color: #5a5a5a;
            transform: scale(1.05);
        }

        .lupa i {
            font-size: 18px;
        }
    </style>
    <script src="../js/buscar.js"></script>
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
        <form method="POST" action="">
            <div class="pesquisa">
                <input name="pesquisar_linha" type="text" placeholder="Pesquisar">
                <button class="lupa" type="submit" name="pesquisar">
                    <i class="bi bi-search"></i>
                </button>
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
        </form>
        </div>
        <div class="sugestão">
            <h1>Sugestão de Pesquisa</h1>
        </div>

        <hr>

        <br>

        <p>Locais ou Endereços</p>
        <p>Linhas de Trem</p>
        <p>Pontos de Parada</p>
    </main>

    <footer>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script src="../js/buscar.js"></script>
</body>

</html>
<?php
$pesquisar = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["pesquisar"])) {
        $pesquisar = trim($_POST["pesquisar_linha"] ?? "");
        /*
        if (empty($pesquisar)) {
            $erro = "Por favor, preencha todos os campos!";
        }
        */ // se precisar futuramente 
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Linhas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Linhas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        .pesquisa {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .pesquisa form {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
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

    <script src="../js/Linhas.js"></script>
</head>

<body>
    <header class="top-bar">

        <button class="open-btn" onclick="openSidebar()">☰</button>

        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidebar()">×</a>
            <a href="dashboard.php">Início</a>
            <a href="noticias.html">Notícia</a>
            <a href="Linhas.php">Linhas</a>
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
        <section id="cor">
            <div class="pesquisa">
                <form method="post" action="">
                    <input type="text" placeholder="Pesquisar Linhas" name="pesquisar_linha" required>
                    <button class="lupa" type="submit" name="pesquisar">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <br>
            <hr>

            <div>
                <label for="ferrovia-central"></label>
                <select id="ferrovia-central" required>
                    <option value="#">Ferrovia Central</option>
                    <option value="#">Linha A</option>
                    <option value="#">Linha B</option>
                    <option value="#">Linha C</option>
                    <option value="#">Linha D</option>
                </select>
            </div>

            <hr>

            <div>
                <label for="ferrovia-norte"></label>
                <select id="ferrovia-norte" required>
                    <option value="#">Ferrovia Norte</option>
                    <option value="#">Linha A</option>
                    <option value="#">Linha B</option>
                    <option value="#">Linha C</option>
                    <option value="#">Linha D</option>
                </select>
            </div>

            <hr>

            <div>
                <label for="ferrovia-sul"></label>
                <select id="ferrovia-sul" required>
                    <option value="#">Ferrovia Sul</option>
                    <option value="#">Linha A</option>
                    <option value="#">Linha B</option>
                    <option value="#">Linha C</option>
                    <option value="#">Linha D</option>
                </select>
            </div>

            <hr>

            <div>
                <label for="ferrovia-leste"></label>
                <select id="ferrovia-leste" required>
                    <option value="#">Ferrovia Leste</option>
                    <option value="#">Linha A</option>
                    <option value="#">Linha B</option>
                    <option value="#">Linha C</option>
                    <option value="#">Linha D</option>
                </select>
            </div>

            <hr>

            <div>
                <label for="ferrovia-oeste"></label>
                <select id="ferrovia-oeste" required>
                    <option value="#">Ferrovia Oeste</option>
                    <option value="#">Linha A</option>
                    <option value="#">Linha B</option>
                    <option value="#">Linha C</option>
                    <option value="#">Linha D</option>
                </select>
            </div>

            <hr>

        </section>
    </main>

    <footer>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script src="../js/Linhas.js"></script>
</body>

</html>
<?php
$pesquisar = "";
$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["pesquisar"])) {
        $pesquisar = trim($_POST["pesquisar_local"] ?? "");

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
    <link rel="stylesheet" href="../css/paradas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Paradas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        /* Estilos para mensagens de erro e sucesso */
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

        /* Estilos para o campo de busca */
        .como-chegar {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            padding: 2vh 20px;
        }

        .como-chegar input {
            flex: 1;
            max-width: 600px;
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s ease;
        }

        .como-chegar input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 5px rgba(255, 193, 7, 0.3);
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

    <script src="../js/paradas.js"></script>
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
        <div class="mapa">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14305.21780163144!2d-48.847432713382446!3d-26.31663938178757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94deb17917615d17%3A0xc4ff5570e603a778!2zU2VkZSBkYSBFc3Rhw6fDo28gZGEgTWVtw7NyaWEg8J-agg!5e0!3m2!1spt-BR!2sbr!4v1748962422860!5m2!1spt-BR!2sbr"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert-erro">
                <strong>Erro:</strong> <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="alert-sucesso">
                <strong> Sucesso:</strong> <?php echo htmlspecialchars($sucesso); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="como-chegar">
                <input name="pesquisar_local" type="text" placeholder=" Digite local ou endereço"
                    value="<?php echo htmlspecialchars($pesquisar); ?>">
                <button class="lupa" type="submit" name="pesquisar">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <div class="flexivel">
            <div class="partir-daqui">
                <i class="fas fa-map-marker-alt"></i>
                <p>Como <br>Chegar</p>

                <div class="aqui">
                    <a href="../php/comochegar.php"><span>A partir daqui</span></a>
                </div>

                <div class="aqui">
                    <a href="../php/comochegar.php"><span>Até aqui</span></a>
                </div>
            </div>
        </div>
        <hr>

        <div class="linhas">
            <a href="../php/meulocal.php">
                <p> Linhas </p>
            </a>
            <a href="../php/paradas.php">
                <p>Paradas</p>
            </a>
            <a href="../php/alertas.php">
                <p> Alertas</p>
            </a>
        </div>

        <hr>

        <!--Linhas A e B -->
        <section class="numero">
            <div class="flexivel">
                <div class="trem">
                    <i class="fas fa-train"></i>
                </div>
                <a href="#">
                    <h1>Ferrovia Central</h1>
                </a>
                <p> Plataforma 2 </p>
            </div>
        </section>

        <section>
            <div class="numero2">
                <p>1 min</p>
                <i class="bi bi-person-walking"></i>
            </div>
        </section>

        <section class="tempo">
            <i class="fas fa-train"></i>
            <p>CHEGADAS</p>
        </section>
        <hr>

        <section class="numero">
            <div class="flexivel">
                <div class="trem">
                    <i class="fas fa-train"></i>
                </div>

                <a href="#">
                    <h1>Ferrovia Central</h1>
                </a>
                <p> Plataforma 2 </p>
            </div>
        </section>

        <section>
            <div class="numero2">
                <p>2 min</p>
                <i class="bi bi-person-walking"></i>
            </div>
        </section>

        <section class="tempo">
            <i class="fas fa-train"></i>
            <p>CHEGADAS</p>
        </section>
    </main>

    <footer>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script src="../js/paradas.js"></script>
</body>

</html>
<?php
$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["buscar_rotas"])) {
        $origem = trim($_POST["origem"] ?? "");
        $destino = trim($_POST["destino"] ?? "");
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
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Geral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <header class="top-bar">
        <button class="open-btn" onclick="openSidebar()">☰</button>

        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidebar()">×</a>
            <a href="../html/dashboard.html">Início</a>
            <a href="../html/noticias.html">Notícia</a>
            <a href="../html/Linhas.html">Linhas</a>
            <a href="../html/meulocal.html">Meu Local</a>
            <a href="../html/comochegar.html">Como Chegar</a>
            <a href="../html/buscar.html">Buscar</a>
            <a href="../html/comochegar.html">Contato</a>
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
            <div class="tempo-real">
                <h1>Acompanhe seu trem <br> <b>em tempo real</b></h1>
            </div>

            <section class="como-chegar">
                <h3>🚩 COMO CHEGAR</h3>

                <form method="POST" action="">
                    <label>
                        <span>📍 De</span>
                        <input name="origem" type="text" placeholder="Inserir origem" required>
                    </label>

                    <label>
                        <span>📍 Para</span>
                        <input name="destino" type="text" placeholder="Inserir destino" required>
                    </label>

                    <label>
                        <span>📅 Data</span>
                        <input name="data" type="date" value="2025-05-09">
                    </label>

                    <label>
                        <span>Tipo</span>
                        <select name="tipo">
                            <option value="partida">Partida</option>
                            <option value="chegada">Chegada</option>
                        </select>
                    </label>

                    <label>
                        <span>⏰ Horário</span>
                        <input name="horario" type="time" value="10:58">
                    </label>

                    <button type="submit" name="buscar_rotas">Buscar rotas</button>
                </form>
            </section>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <script src="../js/dashboard.js"></script>
</body>

</html>
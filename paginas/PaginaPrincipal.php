<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        .selected {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <div class="main-container">
            <div class="slice">
                <div class="rectangle">
                    <div class="rectangle-1">
                        <div class="rh-logo">
                            <img src="../images/logo.png" alt="Logo">
                        </div>
                        <span class="for-all">For all</span>
                        <span class="gestao-recursos-humanos">Gestão de Recursos Humanos</span>
                        <div class="auth-buttons">
                            <button class="login-register">Login</button>
                            <button class="login-register">Registar-se</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <h1>Procura seu Futuro Emprego!</h1>
        <form method="POST" action="../php/ProcurarPorAreasELocalizacoes.php">
            <div class="button-container">
                <h3>Áreas:</h3>
                <div class="button-table">
                    <?php
                    include '../php/db.php'; // Arquivo de conexão com a base de dados
                    $query = "SELECT idarea, nome FROM areas";  // Alterado para buscar os ids e nomes
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<button type='button' class='button-white' data-id='" . $row['idarea'] . "' data-value='" . $row['nome'] . "'>" . $row['nome'] . "</button>";
                    }
                    ?>
                </div>

                <h3>Localizações:</h3>
                <div class="button-table">
                    <?php
                    $query = "SELECT idlocalizacao, nome FROM localizacoes";  // Alterado para buscar os ids e nomes
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<button type='button' class='button-white' data-id='" . $row['idlocalizacao'] . "' data-value='" . $row['nome'] . "'>" . $row['nome'] . "</button>";
                    }
                    mysqli_close($conn);
                    ?>
                </div>
            </div>

            <input type="hidden" name="areas" id="selectedAreas">
            <input type="hidden" name="localizacoes" id="selectedLocations">

            <div>
                <button type="submit" class="button-black">Procurar</button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buttons = document.querySelectorAll(".button-white");
            let selectedAreas = [];
            let selectedLocations = [];

            buttons.forEach(button => {
                button.addEventListener("click", function () {
                    const value = this.getAttribute("data-value");  // Nome da área ou localização
                    const id = this.getAttribute("data-id");  // ID da área ou localização
                    const category = this.closest(".button-container").querySelector("h3").innerText;

                    if (category.includes("Localizações")) {
                        if (selectedLocations.includes(value)) {
                            selectedLocations = selectedLocations.filter(item => item !== value);
                            this.classList.remove("selected");
                        } else {
                            selectedLocations.push(value);
                            this.classList.add("selected");
                        }
                    } else {
                        if (selectedAreas.includes(value)) {
                            selectedAreas = selectedAreas.filter(item => item !== value);
                            this.classList.remove("selected");
                        } else {
                            selectedAreas.push(value);
                            this.classList.add("selected");
                        }
                    }

                    // Atualiza os campos escondidos com os **nomes** selecionados
                    document.getElementById("selectedAreas").value = selectedAreas.join(",");
                    document.getElementById("selectedLocations").value = selectedLocations.join(",");
                });
            });
        });
    </script>
</body>
</html>

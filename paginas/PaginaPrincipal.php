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
        body {
            text-align: center;
        }
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .button-container {
            display: flex;
            justify-content: space-between; /* Distribui o espaço igualmente entre os elementos */
            width: 100%;
            gap: 10px;
            margin: 0px;
        }

        .button-container > div {
            flex: 1; /* Faz as duas colunas ocuparem o mesmo espaço */
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
        }

        .button-table {
            width: 80%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .button-table button {
            flex: 1 1 calc(33.33% - 10px);
            padding: 10px;
            text-align: center;
            border: 1px solid #333;
            background-color: white; 
            color: black; 
            cursor: pointer;
            transition: 0.3s;
            width: 80%; 
        }

        .button-table button:hover {
            background-color: #ddd;
        }

        .button-table button.selected {
            background-color: black; 
            color: white; 
        }


        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }

        .search-button {
            margin-top: 20px;
        }
        .button-procurar{
            display: flex;
            justify-content: center;
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
                        <button class="login-register" onclick="window.location.href='Login.php'">Login</button>
                        <button class="login-register" onclick="window.location.href='Registo.html'">Registar-se</button>
                    </div>
                </div>
            </div>
            <div class="rectangle-2">
                <div class="menu-item">
                    <a href="PaginaPrincipal.html">
                        <img src="../images/circle.png" alt="Circle Icon" />
                        Página Principal
                    </a>
                </div>
                <div class="menu-item">
                    <a href="SobreNos.html">
                        <img src="../images/circle.png" alt="Circle Icon" />
                        Sobre Nós
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

    <main>
        <h1>Procura seu Futuro Emprego!</h1>
        <form id="jobSearchForm" method="POST" action="../php/ProcurarPorAreasELocalizacoes.php">
            <div class="button-container">
                <div>
                    <h3>Áreas:</h3>
                    <div class="button-table" id="areas-container">
                        <?php
                        include '../php/db.php'; 
                        $query = "SELECT idarea, nome FROM areas";  
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<button type='button' class='button-white area-button' data-id='" . $row['idarea'] . "'>" . $row['nome'] . "</button>";
                        }
                        ?>
                        <p id="areaError" class="error-message">Selecione pelo menos uma área.</p>
                    </div>
                </div>
                <div>
                    <h3>Localizações:</h3>
                    <div class="button-table" id="localizacoes-container">
                        <?php
                        $query = "SELECT idlocalizacao, nome FROM localizacoes";  
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<button type='button' class='button-white localizacao-button' data-id='" . $row['idlocalizacao'] . "'>" . $row['nome'] . "</button>";
                        }
                        mysqli_close($conn);
                        ?>
                        <p id="locationError" class="error-message">Selecione pelo menos uma localização.</p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="areas" id="selectedAreas">
            <input type="hidden" name="localizacoes" id="selectedLocations">
            <div class="button-procurar" >
            <button type="submit" class="button-black search-button">Procurar</button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let selectedAreas = [];
            let selectedLocations = [];
            const areaError = document.getElementById("areaError");
            const locationError = document.getElementById("locationError");
            const form = document.getElementById("jobSearchForm");

            document.querySelectorAll(".area-button").forEach(button => {
                button.addEventListener("click", function () {
                    const id = this.getAttribute("data-id");
                    if (selectedAreas.includes(id)) {
                        selectedAreas = selectedAreas.filter(item => item !== id);
                        this.classList.remove("selected");
                    } else {
                        selectedAreas.push(id);
                        this.classList.add("selected");
                    }
                    document.getElementById("selectedAreas").value = selectedAreas.join(",");
                    areaError.style.display = "none";
                });
            });

            document.querySelectorAll(".localizacao-button").forEach(button => {
                button.addEventListener("click", function () {
                    const id = this.getAttribute("data-id");
                    if (selectedLocations.includes(id)) {
                        selectedLocations = selectedLocations.filter(item => item !== id);
                        this.classList.remove("selected");
                    } else {
                        selectedLocations.push(id);
                        this.classList.add("selected");
                    }
                    document.getElementById("selectedLocations").value = selectedLocations.join(",");
                    locationError.style.display = "none";
                });
            });

            form.addEventListener("submit", function (event) {
                let valid = true;
                if (selectedAreas.length === 0) {
                    areaError.style.display = "block";
                    valid = false;
                }
                if (selectedLocations.length === 0) {
                    locationError.style.display = "block";
                    valid = false;
                }
                if (!valid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
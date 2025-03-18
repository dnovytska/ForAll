<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o empregador está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Erro: Acesso não autorizado.");
}

$idempregador = $_SESSION['user_id'];

if (!filter_var($idempregador, FILTER_VALIDATE_INT)) {
    die("Erro: ID inválido.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recuperar o nome do empregador
$sql = "SELECT nome FROM empregadores WHERE idempregador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idempregador);
$stmt->execute();
$result = $stmt->get_result();

$user_name = "Usuário não encontrado";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['nome'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Página Principal</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/PaginaPrincipal.css" />
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

                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<div class="auth-buttons">';
                        echo '<button class="user-profile">' . htmlspecialchars($user_name) . '</button>';
                        echo '</div>';
                    } else {
                        echo '<div class="auth-buttons">';
                        echo '<button class="login-register" onclick="window.location.href=\'Login.php\'">Login</button>';
                        echo '<button class="login-register" onclick="window.location.href=\'Registo.html\'">Registar-se</button>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="rectangle-2">
            <?php
            // Verificar o tipo de usuário logado
            if (isset($_SESSION['role'])) {
                // Conectar ao banco de dados
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "psiforall";

                // Criar a conexão
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar se a conexão foi bem-sucedida
                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }

                // Recuperar o ID do usuário da sessão
                $user_id = $_SESSION['user_id'];

                // Exibir os itens do menu com base no tipo de usuário
                if ($_SESSION['role'] == 'candidato') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                } elseif ($_SESSION['role'] == 'empregador') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                    echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                } elseif ($_SESSION['role'] == 'admin') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="default.php"><img src="../images/circle.png" alt="Circle Icon" />default</a></div>';
                }

                // Fechar a conexão com o banco de dados
                $conn->close();
            } else {
                // Caso o usuário não esteja logado
                echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</ a></div>';
            }
            ?>
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
        <div class="button-procurar">
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

<footer>
    <p>&copy; 2023 For All. Todos os direitos reservados.</p>
</footer>
</body>
</html>
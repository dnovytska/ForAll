<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o base de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Inicializar variáveis
$user_name = "Usuário não encontrado";
$user_role = null;

// Verificar se o utilizador está logado
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['role'];

    // Recuperar o nome do utilizador com base no tipo de utilizador
    if ($user_role == 'candidato') {
        $sql = "SELECT nome FROM candidatos WHERE idcandidato = ?";
    } elseif ($user_role == 'empregador') {
        $sql = "SELECT nome FROM empregadores WHERE idempregador = ?";
    } elseif ($user_role == 'admin') {
        $sql = "SELECT nome FROM administradores WHERE idadministrador = ?";
    }

    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_name = $row['nome'];
        }
    }
}
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

                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <div class="auth-buttons">
                            <button class="user-profile"><?= htmlspecialchars($user_name) ?></button>
                        </div>
                    <?php else : ?>
                        <div class="auth-buttons">
                            <button class="login-register" onclick="window.location.href='Login.php'">Login</button>
                            <button class="login-register" onclick="window.location.href='Registo.php'">Registar-se</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rectangle-2">
                <?php
                // Exibir os itens do menu com base no tipo de utilizador
                if (isset($user_role)) {
                    if ($user_role == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($user_role == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($user_role == 'admin') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="ListarCandidaturasAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Candidaturas</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregosAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Empregos</a></div>';
                        echo '<div class="menu-item"><a href="VerCandidatos.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Candidatos</a></div>';
                        echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    }
                } else {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
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
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>
</body>
</html>
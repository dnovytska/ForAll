<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o empregador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    header("Location: login.php");
    exit();
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

// Buscar empregos do empregador
$sql_jobs = "SELECT idemprego, titulo, responsabilidades FROM empregos WHERE idempregador = ?";
$stmt_jobs = $conn->prepare($sql_jobs);
$stmt_jobs->bind_param("i", $idempregador);
$stmt_jobs->execute();
$result_jobs = $stmt_jobs->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Lista de Empregos</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        body {
            background-color: #FFFFFF;
            font-family: 'Inria Serif', serif;
            color: #473D3B;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            margin: 40px;
        }

        h2 {
            color: #22202A;
            font-size: 2.4em;
            margin-bottom: 30px;
            border-bottom: 2px solid #967D60;
            padding-bottom: 10px;
        }

        #job-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .job-listing {
            background-color: #F5F5F5;
            padding: 15px;
            border: 1px solid #7E7D85;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .job-listing:hover {
            background-color: #E5E5EC;
        }

        .job-title {
            font-weight: bold;
            font-size: 1.2em;
        }

        .job-actions {
            margin-top: 10px;
        }

        .job-actions a {
            margin-right: 15px;
            color: #22202A;
            text-decoration: none;
        }

        .job-actions a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #22202A;
            color: #E5E5EC;
            text-align: center;
            padding: 20px;
            margin-top: auto;
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
    <h2>Lista de Empregos</h2>
    <div id="job-container">
        <?php
        if ($result_jobs->num_rows > 0) {
            while ($row = $result_jobs->fetch_assoc()) {
                echo "<div class='job-listing'>";
                echo "<div class='job-title'><a href='detalhes_emprego.php?id=" . $row['idemprego'] . "'>" . htmlspecialchars($row['titulo']) . "</a></div>";
                echo "<div class='job-company'>Responsabilidades: " . htmlspecialchars($row['responsabilidades']) . "</div>";
                echo "<div class='job-actions'>";
                echo "<a href='EditarEmprego.php?id=" . $row['idemprego'] . "'>Editar</a>";
                echo "<a href='#' class='delete' onclick='confirmarExclusao(" . $row['idemprego'] . ")'>Apagar</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum emprego encontrado.</p>";
        }
        ?>
    </div>
</main>
<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>
</body>
</html>
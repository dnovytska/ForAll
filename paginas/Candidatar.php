<?php
session_start();
include '../php/db.php';

// Verificar se o utilizador está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: utilizador não está logado.");
}

// Verificar se o utilizador é um candidato
if ($_SESSION['role'] !== 'candidato') {
    die("Erro: apenas candidatos podem se candidatar a empregos.");
}

$idcandidato = $_SESSION['user_id'];

// Validar ID do emprego
if (!isset($_GET['emprego_id']) || empty($_GET['emprego_id'])) {
    die("Erro: ID do emprego não fornecido.");
}

$idemprego = $_GET['emprego_id'];

// Conectar ao base de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o candidato já se candidatou
$checkQuery = "SELECT * FROM candidaturas WHERE idcandidato = ? AND idemprego = ?";
$stmt_check = $conn->prepare($checkQuery);
$stmt_check->bind_param("ii", $idcandidato, $idemprego);
$stmt_check->execute();
$checkResult = $stmt_check->get_result();

if ($checkResult->num_rows > 0) {
    die("Você já se candidatou para este emprego.");
}

// Insere a candidatura
$insertQuery = "INSERT INTO candidaturas (idcandidato, idemprego, data_candidatura) VALUES (?, ?, NOW())";
$stmt_insert = $conn->prepare($insertQuery);
$stmt_insert->bind_param("ii", $idcandidato, $idemprego);

if (!$stmt_insert->execute()) {
    die("Erro ao candidatar-se: " . $stmt_insert->error);
}

// Recupera dados do emprego
$empregoQuery = "SELECT titulo FROM empregos WHERE idemprego = ?";
$stmt_emprego = $conn->prepare($empregoQuery);
$stmt_emprego->bind_param("i", $idemprego);
$stmt_emprego->execute();
$empregoResult = $stmt_emprego->get_result();
$empregoData = $empregoResult->fetch_assoc();
$tituloEmprego = $empregoData['titulo'];

// Fecha a conexão com o base de dados
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Candidatura Realizada</title>
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/ListarDados.css" />
    <style>
        body {
            font-family: 'Inria Serif', serif;
            background-color: #FFFFFF; /* Fundo branco */
            margin: 0;
            padding: 0;
            color: #22202A; /* Cor principal do texto */
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #FFFFFF; /* Fundo branco para o conteúdo */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            color: #22202A; /* Cor do título */
        }

        footer {
            background-color: #22202A; /* Cor do rodapé */
            color: #E5E5EC; /* Cor do texto no rodapé */
            text-align: center;
            padding: 25px;
            margin-top: 40px;
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
                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($_SESSION['role'] == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                        
                    } elseif ($_SESSION['role'] == 'admin') {
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
    <h2>Candidatura realizada com sucesso!</h2>
    <p>Você se candidatou à vaga: <strong><?php echo htmlspecialchars($tituloEmprego); ?></strong>.</p>
</main>


<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>

</body>
</html>
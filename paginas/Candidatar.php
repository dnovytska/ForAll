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

// Recuperar o nome do usuário
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuário';

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

// Buscar o nome do usuário logado se não estiver na sessão
if (!isset($_SESSION['user_name'])) {
    $query = "SELECT nome FROM candidatos WHERE idcandidato = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $user_name = $user_data['nome'] ?? 'Usuário';
    $stmt->close();
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
            background-color: #FFFFFF;
            margin: 0;
            padding: 0;
            color: #22202A;
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #FFFFFF;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            color: #22202A;
        }

        footer {
            background-color: #22202A;
            color: #E5E5EC;
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
                    <div class="menu-item"><a href="PaginaPrincipal.php">Página Principal</a></div>
                    <div class="menu-item"><a href="SobreNos.php">Sobre Nós</a></div>

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

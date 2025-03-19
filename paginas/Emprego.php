<?php
session_start(); // Iniciar sessão para verificar se o utilizador está logado
include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o ID do emprego foi passado pela URL
if (isset($_GET['id'])) {
    $jobId = $_GET['id'];

    // Buscar os dados do emprego
    $query = "SELECT * FROM empregos WHERE idemprego = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();

    // Buscar nome da área
    $areaQuery = "SELECT nome FROM areas WHERE idarea = " . $job['areas_idarea'];
    $areaResult = mysqli_query($conn, $areaQuery);
    $area = mysqli_fetch_assoc($areaResult)['nome'];

    // Buscar nome da localização
    $locationQuery = "SELECT nome FROM localizacoes WHERE idlocalizacao = " . $job['localizacoes_idlocalizacao'];
    $locationResult = mysqli_query($conn, $locationQuery);
    $location = mysqli_fetch_assoc($locationResult)['nome'];

    // Verificar se o utilizador já se candidatou a este emprego
    $alreadyApplied = false;
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $checkApplicationQuery = "SELECT * FROM candidaturas WHERE idcandidato = ? AND idemprego = ?";
        $stmt_check = $conn->prepare($checkApplicationQuery);
        $stmt_check->bind_param("ii", $userId, $jobId);
        $stmt_check->execute();
        $checkResult = $stmt_check->get_result();
        if ($checkResult->num_rows > 0) {
            $alreadyApplied = true;
        }
    }
} else {
    echo "Emprego não encontrado.";
    exit;
}

// Recuperar o nome do utilizador logado
$user_name = "Usuário não encontrado";
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
    <title>Detalhes do Emprego</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <style>
        body {
            font-family: 'Inria Serif', serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .job-details {
            font-size: 1.2em;
        }

        .apply-button {
            background-color: #333;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-size: 1em;
        }

        .apply-button:hover {
            background-color: #555;
        }

        .apply-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
    <h1><?php echo htmlspecialchars($job['titulo']); ?></h1>

    <div class="job-details">
    <p><strong>Responsabilidades:</strong> <?php echo htmlspecialchars($job['responsabilidades']); ?></p>
    <p><strong>Ordenado:</strong> <?php echo htmlspecialchars($job['ordenado']); ?></p>
        <p><strong>Área:</strong> <?php echo htmlspecialchars($area); ?></p>
        <p><strong>Localização:</strong> <?php echo htmlspecialchars($location); ?></p>
    </div>

    <?php if (isset($_SESSION['user_id'])) { ?>
        <?php if ($alreadyApplied): ?>
            <!-- Botão desabilitado se já se candidatou -->
            <button class="apply-button" disabled>Já Candidatado</button>
        <?php else: ?>
            <!-- Botão para candidatar-se -->
            <a href="Candidatar.php?emprego_id=<?php echo $job['idemprego']; ?>" class="apply-button">Candidatar-se</a>
        <?php endif; ?>
    <?php } else { ?>
        <!-- Botão de login caso o utilizador não esteja logado -->
        <a href="login.php" class="apply-button">Fazer Login para Candidatar-se</a>
    <?php } ?>
</main>

<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>

<?php
// Fechar a conexão com o base de dados
mysqli_close($conn);
?>
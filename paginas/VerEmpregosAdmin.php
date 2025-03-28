<?php
session_start(); // Iniciar sessão para verificar se o utilizador está logado
include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o utilizador é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redireciona para uma página de erro ou login caso o utilizador não seja admin
    header("Location: login.php");
    exit;
}

// Buscar todos os empregos
$query = "SELECT * FROM empregos";
$result = mysqli_query($conn, $query);

// Verificar se existem empregos cadastrados
if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    $message = "Não há empregos cadastrados.";
} else {
    $message = null; // Caso haja resultados, a mensagem é nula
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
        $result_user = $stmt->get_result();

        if ($result_user->num_rows > 0) {
            $row = $result_user->fetch_assoc();
            $user_name = $row['nome'];
        } else {
            // Caso o nome do utilizador não seja encontrado
            $user_name = "Nome não encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Empregos</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/header.css" /><style>
    body {
        font-family: 'Inria Serif', serif;
        background-color: #FFFFFF;
        margin: 0;
        padding: 0;
        color: #22202A; 
    }

    main {
        margin: 20px auto;
        padding: 20px;
        background-color: #FFFFFF;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #22202A;
        font-size: 2.2em;
    }

    .job-list {
        list-style-type: none;
        padding: 0;
    }

    .job-item {
        background-color: #E5E5EC;
        margin: 15px 0;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #7E7D85;
        transition: transform 0.2s ease;
    }

    .job-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .job-item h2 {
        margin: 0 0 10px 0;
        font-size: 1.6em;
        color: #967D60; /* Cor do título */
    }

    .job-item a {
        text-decoration: none;
        color: #22202A;
        transition: color 0.2s ease;
    }

    .job-item a:hover {
        color: #967D60;
        text-decoration: underline;
    }

    .job-item p {
        margin: 8px 0;
        color: #473D3B; /* Cor do texto secundário */
        line-height: 1.6;
    }

    .delete-button {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 20px;
        background-color: #967D60;
        color: #E5E5EC;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .delete-button:hover {
        background-color: #7E6D5A;
        text-decoration: none;
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
    <h1>Lista de Empregos</h1>

    <?php
    if (isset($message)) {
        echo "<p>$message</p>";
    }

    // Verificação se há resultados e dados a serem exibidos
    if (mysqli_num_rows($result) > 0) {
    ?>
        <ul class="job-list">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <li class="job-item">
                    <h2><a href="DetalhesEmprego.php?id=<?php echo $row['idemprego']; ?>"><?php echo htmlspecialchars($row['titulo']); ?></a></h2>
                    <p><strong>Responsabilidades:</strong> <?php echo htmlspecialchars($row['responsabilidades']); ?></p>
                    <p><strong>Competências:</strong> <?php echo htmlspecialchars($row['competencias']); ?></p>
                    <p><strong>Benefícios:</strong> <?php echo htmlspecialchars($row['beneficios']); ?></p>
                    <p><strong>Quantidade:</strong> <?php echo htmlspecialchars($row['quantidade']); ?></p>
                    <a href="ApagarEmprego.php?id=<?php echo $row['idemprego']; ?>" class="delete-button" 
                       onclick="return confirm('Tem certeza que deseja excluir este emprego?');">Apagar</a>
                </li>
            <?php } ?>
        </ul>
    <?php 
    } else {
        echo "<p>Nenhum emprego encontrado.</p>";
    }
    ?>
</main>

<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>

<?php
// Fechar a conexão com o base de dados
mysqli_close($conn);
?>
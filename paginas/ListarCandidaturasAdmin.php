<?php
session_start(); // Iniciar sessão para verificar se o usuário está logado
include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o usuário é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redireciona para uma página de erro ou login caso o usuário não seja admin
    header("Location: login.php");
    exit;
}

// Consultar todas as candidaturas
$query = "SELECT c.idcandidatura, u.nome AS candidato, e.titulo AS emprego, c.data_candidatura, c.status 
          FROM candidaturas c
          JOIN candidatos u ON c.idcandidato = u.idcandidato
          JOIN empregos e ON c.idemprego = e.idemprego";
$result = mysqli_query($conn, $query);

// Verificar se a consulta retornou algum resultado
if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

// Verificar se existem resultados
if (mysqli_num_rows($result) == 0) {
    $message = "Não há candidaturas registradas.";
} else {
    $message = null; // Caso haja resultados, a mensagem é nula
}

// Recuperar o nome do usuário logado
$user_name = "Usuário não encontrado";
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['role'];

    // Recuperar o nome do usuário com base no tipo de usuário
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
            // Caso o nome do usuário não seja encontrado
            $user_name = "Nome não encontrado";
        }
    }
} else {
    // Caso não haja dados na sessão
    $message = "Erro ao obter informações do usuário.";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Candidaturas</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/ListarDados.css" />
    <link rel="stylesheet" href="../css/header.css" />
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
                            <button class="login-register" onclick="window.location.href='Registo.html'">Registar-se</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rectangle-2">
                <?php
                // Exibir os itens do menu com base no tipo de usuário
                if (isset($user_role)) {
                    if ($user_role == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($user_role == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
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
    <h1>Lista de Candidaturas</h1>

    <?php
    if (isset($message)) {
        echo "<p>$message</p>";
    }

    // Verificação se há resultados e dados a serem exibidos
    if (mysqli_num_rows($result) > 0) {
    ?>
        <table class="candidaturas-table">
            <thead>
                <tr>
                    <th>ID Candidatura</th>
                    <th>Candidato</th>
                    <th>Emprego</th>
                    <th>Data de Candidatura</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['idcandidatura']; ?></td>
                        <td><?php echo htmlspecialchars($row['candidato']); ?></td>
                        <td><?php echo htmlspecialchars($row['emprego']); ?></td>
                        <td><?php echo htmlspecialchars($row['data_candidatura']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="../php/ApagarCandidatura.php?id=<?php echo $row['idcandidatura']; ?>"
                               class="delete-button" 
                               onclick="return confirm('Tem certeza de que deseja excluir esta candidatura?');">
                               Apagar
                            </a>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    <?php 
    } else {
        echo "<p>Nenhuma candidatura encontrada.</p>";
    }
    ?>
</main>

<footer>
    <p>&copy; 2023 For All. Todos os direitos reservados.</p>
</footer>

<?php
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
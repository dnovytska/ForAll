<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
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
                        if (isset($_SESSION['username'])) {
                            echo '<button class="user-profile">' . htmlspecialchars($_SESSION['username']) . '</button>';
                        }
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

                // Definir o nome do usuário com um valor padrão
                $user_name = "Usuário não encontrado";

                // Buscar o nome do usuário com base no tipo de usuário
                if ($_SESSION['role'] == 'candidato') {
                    // Buscar o nome na tabela 'candidatos'
                    $sql = "SELECT nome FROM candidatos WHERE idcandidato = '$user_id'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $user_name = $row['nome'];
                    }
                } elseif ($_SESSION['role'] == 'empregador') {
                    // Buscar o nome na tabela 'empregadores'
                    $sql = "SELECT nome FROM empregadores WHERE idempregador = '$user_id'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $user_name = $row['nome'];
                    }
                } elseif ($_SESSION['role'] == 'admin') {
                    // Buscar o nome na tabela 'administradores'
                    $sql = "SELECT nome FROM administradores WHERE idadmin = '$user_id'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $user_name = $row['nome'];
                    }
                }

                // Fechar a conexão com o banco de dados
                $conn->close();

                // Exibir os itens do menu com base no tipo de usuário
                if ($_SESSION['role'] == 'candidato') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                } elseif ($_SESSION['role'] == 'empregador') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                    echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                } elseif ($_SESSION['role'] == 'admin') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="default.php"><img src="../images/circle.png" alt="Circle Icon" />default</a></div>';
                }
            } else {
                // Caso o usuário não esteja logado
                echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
            }
            ?>
            </div>
        </div>
    </div>
</header>
<?php
session_start();
include '../php/db.php';  

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$query = "SELECT * FROM candidatos";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<p>Não há candidatos cadastrados.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Candidatos</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/ListarDados.css" />
</head>
<body>

<main>
    <h1>Lista de Candidatos</h1>

    <ul class="candidate-list">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <li class="candidate-item">
                <h2><a href="DetalhesCandidato.php?id=<?php echo $row['idcandidato']; ?>"><?php echo $row['nome']; ?></a></h2>
                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <p><strong>Telefone:</strong> <?php echo $row['telefone']; ?></p>

                <a href="../php/ApagarCandidato.php?id=<?php echo $row['idcandidato']; ?>" class="delete-button" 
                   onclick="return confirm('Tem certeza que deseja excluir este candidato?');">Apagar</a>
            </li>
        <?php } ?>
    </ul>

</main>

</body>
</html>

<?php
mysqli_close($conn);
?>

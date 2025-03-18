<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: usuário não está logado.");
}

$idcandidato = $_SESSION['user_id'];

// Validar ID do usuário
if (!filter_var($idcandidato, FILTER_VALIDATE_INT)) {
    die("Erro: ID do usuário inválido.");
}

// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar dados do candidato com prepared statement
$sql = "SELECT nome, email, telefone, data_nascimento FROM candidatos WHERE idcandidato = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idcandidato);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Utilizador não encontrado!");
}

$row = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Perfil</title>
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

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="auth-buttons">
                            <?php if (isset($_SESSION['username'])): ?>
                                <button class="user-profile"><?= htmlspecialchars($_SESSION['username']) ?></button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <button class="login-register" onclick="window.location.href='Login.php'">Login</button>
                            <button class="login-register" onclick="window.location.href='Registo.html'">Registar-se</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rectangle-2">
                <?php
                if (isset($_SESSION['role'])) {
                    $conn_menu = new mysqli($servername, $username, $password, $dbname);
                    if ($conn_menu->connect_error) {
                        die("Erro de conexão: " . $conn_menu->connect_error);
                    }

                    $user_id = $_SESSION['user_id'];
                    $user_name = "Usuário não encontrado";
                    $sql = "";

                    switch ($_SESSION['role']) {
                        case 'candidato':
                            $sql = "SELECT nome FROM candidatos WHERE idcandidato = ?";
                            break;
                        case 'empregador':
                            $sql = "SELECT nome FROM empregadores WHERE idempregador = ?";
                            break;
                        case 'admin':
                            $sql = "SELECT nome FROM administradores WHERE idadmin = ?";
                            break;
                    }

                    if (!empty($sql)) {
                        $stmt = $conn_menu->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $row_menu = $result->fetch_assoc();
                            $user_name = $row_menu['nome'];
                        }
                    }

                    $conn_menu->close();

                    // Itens do menu
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    
                    if ($_SESSION['role'] == 'candidato') {
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($_SESSION['role'] == 'empregador') {
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                    } elseif ($_SESSION['role'] == 'admin') {
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
    <div>
        <h1>Olá, <?= htmlspecialchars($row['nome']) ?></h1>
        <button class="button-black" onclick="window.location.href='EditarPerfilCandidato.php'">Editar Perfil</button>
    </div>
    <div>
        <div class="data-perfil">
            <p class="p-perfil"><strong>Nome:</strong> <?= htmlspecialchars($row['nome']) ?></p>
            <p class="p-perfil"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
            <p class="p-perfil"><strong>Data de Nascimento:</strong> <?= htmlspecialchars($row['data_nascimento']) ?></p>
            <p class="p-perfil"><strong>Telefone:</strong> <?= htmlspecialchars($row['telefone']) ?></p>
        </div>
        <div>
            <button class="button-white" onclick="window.location.href='../php/Logout.php'">Logout</button>
            <button class="button-black" onclick="confirmarExclusao()">Apagar Conta</button>
        </div>
    </div>
    <footer>
        <div class="rectangle-f"></div>
    </footer>
</main>

<script>
    function confirmarExclusao() {
        if (confirm("Tem certeza de que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
            window.location.href = "../php/ApagarContaCandidato.php";
        }
    }
</script>
</body>
</html>
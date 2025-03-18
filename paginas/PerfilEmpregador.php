<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o empregador está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Erro: Acesso não autorizado.");
}

$idempregador = $_SESSION['user_id'];

// Validar ID
if (!filter_var($idempregador, FILTER_VALIDATE_INT)) {
    die("Erro: ID inválido.");
}

// Configuração do banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar dados do empregador (Consulta principal)
$sql = "SELECT nome, email, telefone FROM empregadores WHERE idempregador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idempregador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Empregador não encontrado!");
}

$empregador = $result->fetch_assoc(); // Alterado o nome da variável para evitar conflito
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Perfil Empregador</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/PerfilEmpregador.css" />
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
                            <button class="user-profile"><?= htmlspecialchars($empregador['nome']) ?></button>
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
                    // Usar os dados já obtidos da consulta principal
                    $user_name = $empregador['nome'];
                    
                    // Menu para empregador
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                    echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                } else {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                }
                ?>
            </div>
        </div>
    </div>
</header>

<main>
    <div>
        <h1>Bem-vindo, <?= htmlspecialchars($empregador['nome']) ?></h1>
        <button class="button-black" onclick="window.location.href='EditarPerfilEmpregador.php'">Editar Perfil</button>
    </div>
    <div>
        <div class="data-perfil">
            <p class="p-perfil"><strong>Empregador:</strong> <?= htmlspecialchars($empregador['nome']) ?></p>
            <p class="p-perfil"><strong>Email:</strong> <?= htmlspecialchars($empregador['email']) ?></p>
            <p class="p-perfil"><strong>Telefone:</strong> <?= htmlspecialchars($empregador['telefone']) ?></p>
        </div>
        <div>
            <button class="button-white" onclick="window.location.href='../php/Logout.php'">Logout</button>
            <button class="button-black" onclick="confirmarExclusao()">Apagar Conta</button>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2023 For All. Todos os direitos reservados.</p>
</footer>

<script>
    function confirmarExclusao() {
        if (confirm("Tem certeza de que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
            window.location.href = "../php/ApagarContaEmpregador.php";
        }
    }
</script>
</body>
</html>
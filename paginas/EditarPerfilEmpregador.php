<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Erro: Acesso não autorizado.");
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

$sql = "SELECT nome, email, telefone FROM empregadores WHERE idempregador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idempregador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empregador = $result->fetch_assoc();
} else {
    die("Usuário não encontrado!");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>For All - Editar Perfil</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/globals.css">
    <link rel="stylesheet" href="../css/EditarPerfilEmpregador.css">
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
                            <button class="user-profile"><?= htmlspecialchars($empregador['nome'] ?? '') ?></button>
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
                if (isset($_SESSION['role'])) {
                    $conn_menu = new mysqli($servername, $username, $password, $dbname);
                    
                    if ($conn_menu->connect_error) {
                        die("Erro de conexão: " . $conn_menu->connect_error);
                    }

                    $sql_menu = "SELECT nome FROM empregadores WHERE idempregador = ?";
                    $stmt_menu = $conn_menu->prepare($sql_menu);
                    $stmt_menu->bind_param("i", $_SESSION['user_id']);
                    $stmt_menu->execute();
                    $result_menu = $stmt_menu->get_result();
                    
                    $user_name = "Usuário não encontrado";
                    if ($result_menu->num_rows > 0) {
                        $row_menu = $result_menu->fetch_assoc();
                        $user_name = $row_menu['nome'];
                    }

                    $conn_menu->close();

                    if ($_SESSION['role'] == 'empregador') {
                        echo '
                        <div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon">Página Principal</a></div>
                        <div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon">Sobre Nós</a></div>
                        <div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon">'.htmlspecialchars($user_name).'</a></div>
                        <div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon">Meus Empregos</a></div>
                        <div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon">Notificações</a></div>
                        <div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon">Criar Novo Emprego</a></div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</header>

<main>
    <h2>Editar Perfil</h2>
    <form action="../php/SalvarPerfilEmpregador.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($empregador['nome'] ?? '') ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($empregador['email'] ?? '') ?>" required>
        
        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($empregador['telefone'] ?? '') ?>" required>
        
        <button type="submit">Salvar</button>
        <button type="button" onclick="window.location.href='PerfilEmpregador.php'">Cancelar</button>
    </form>
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
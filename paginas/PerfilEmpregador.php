<?php
session_start();  // Iniciar a sessão para pegar o ID do usuário logado

// Verificar se o ID do usuário está armazenado na sessão
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: usuário não está logado ou o ID do usuário não foi encontrado na sessão.");
}

// Recuperar o ID do usuário da sessão
$idempregador = $_SESSION['user_id'];

// Garantir que o ID seja um número inteiro
if (!is_numeric($idempregador)) {
    die("Erro: ID do usuário inválido.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Exibir o ID do usuário para debug
// echo "ID do usuário logado: " . $idempregador . "<br>";  // Remover após o teste

$sql = "SELECT nome, email, telefone FROM empregadores WHERE idempregador = $idempregador";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Utilizador não encontrado!";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
                    </div>
                </div>
                <div class="rectangle-2">
                    <div class="menu-item">
                        <a href="PaginaPrincipal.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Página Principal
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="VerEmpregos.php">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Meus Empregos
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="notificacoes.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Notificações
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="SobreNos.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Sobre Nós
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="CriarEmprego.php">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Criar Novo Emprego
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="PerfilEmpregador.php">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            <?php echo htmlspecialchars($row['nome']); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div>
            <h1>Olá, <?php echo htmlspecialchars($row['nome']); ?></h1>
            <button class="button-black" onclick="window.location.href='EditarPerfilEmpregador.php'">Editar Perfil</button>
        </div>
        <div>
            <div class="data-perfil">
                <p class="p-perfil"><strong>Nome:</strong> <?php echo htmlspecialchars($row['nome']); ?></p>
                <p class="p-perfil"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                <p class="p-perfil"><strong>Número de Telefone:</strong> <?php echo htmlspecialchars($row['telefone']); ?></p>
            </div>
            <div>
                <button class="button-white" onclick="window.location.href='logout.php'">Logout</button>
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
                window.location.href = "../php/ApagarContaEmpregador.php"; // Ajustar para o script correto de exclusão
            }
        }
    </script>
</body>
</html>

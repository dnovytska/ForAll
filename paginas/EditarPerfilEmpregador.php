<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
$idempregador = 1; 

$sql = "SELECT nome, email, telefone FROM empregadores WHERE idempregador = $idempregador";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Usuário não encontrado!";
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
                    <span class="username">Username</span>
                </div>
            </div>
        </div>
    </header>
<body>

    <h2>Editar Perfil</h2><form action="../php/SalvarPerfilEmpregador.php" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
    
    <label for="telefone">Telefone:</label>
    <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" required>
    
    <button type="submit">Salvar</button>
    <button type="button" onclick="window.location.href='PerfilEmpregador.php'">Cancelar</button>
</form>


</body>
</html>

<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$idcandidato = 1;

// Buscar os dados do candidato
$sql = "SELECT nome, email, telefone FROM empregadores WHERE idempregador = $idcandidato";
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
    <link rel="stylesheet" href="../css/CV.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        input[type="text"],
        input[type="email"] {
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #473d3b; 
            border-radius: 5px; 
            font-size: 16px; 
        }
        label {
            font-family: "Inria Serif", sans-serif;
            font-size: 24px;
            color: #22202a;
            margin-top: 10px;
            display: block; 
        }
        .menu-option {
            display: flex;
            align-items: center;
            margin: 10px 0; 
        }
        .menu-option img {
            width: 24px;
            height: 24px; 
            margin-right: 10px; 
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
                    </div>
                </div>
                <div class="rectangle-2">
                    <div class="menu-item">
                        <a href="pagina_principal.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Página Principal
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="notificacoes.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Notificações
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="sobre_nos.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Sobre Nós
                        </a>
                    </div>
                    <span class="username"><?php echo htmlspecialchars($row['nome']); ?></span>
                </div>
            </div>
        </div>
    </header>
    <main>
        <img src="../images/circle.png" alt="Foto de perfil">
        <div>
            <h1>Olá, <?php echo htmlspecialchars($row['nome']); ?></h1>
            <button onclick="window.location.href='EditarPerfilEmpregador.php'">Editar Perfil</button>
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
                window.location.href = "../php/ApagarContaCandidato.php";
            }
        }
    </script>
</body>
</html>

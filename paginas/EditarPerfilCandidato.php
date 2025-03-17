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

// ID do candidato (pode ser obtido da sessão, mas por agora vamos definir manualmente)
$idcandidato = 1; // Ajusta conforme necessário

// Buscar os dados do candidato
$sql = "SELECT nome, email, telefone, data_nascimento, anos_expriencia, habilitacoes_academicas FROM candidatos WHERE idcandidato = $idcandidato";
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

    <h2>Editar Perfil</h2><form action="../php/SalvarPerfilCandidato.php" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
    
    <label for="telefone">Telefone:</label>
    <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" required>
    
    <label for="data_nascimento">Data de Nascimento:</label>
    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $row['data_nascimento']; ?>" required>
    
    <label for="habilitacoes">Habilitações Acadêmicas:</label>
    <select id="habilitacoes" name="habilitacoes">
        <option value="Ensino Médio" <?php if ($row['habilitacoes_academicas'] == 'Ensino Médio') echo 'selected'; ?>>Ensino Médio</option>
        <option value="Graduação" <?php if ($row['habilitacoes_academicas'] == 'Graduação') echo 'selected'; ?>>Graduação</option>
        <option value="Pós-graduação" <?php if ($row['habilitacoes_academicas'] == 'Pós-graduação') echo 'selected'; ?>>Pós-graduação</option>
        <option value="Mestrado" <?php if ($row['habilitacoes_academicas'] == 'Mestrado') echo 'selected'; ?>>Mestrado</option>
        <option value="Doutorado" <?php if ($row['habilitacoes_academicas'] == 'Doutorado') echo 'selected'; ?>>Doutorado</option>
    </select>
    
    <label for="experiencia">Anos de Experiência:</label>
    <input type="number" id="experiencia" name="experiencia" value="<?php echo $row['anos_expriencia']; ?>" required>
    
    <button type="submit">Salvar</button>
    <button type="button" onclick="window.location.href='PerfilCandidato.html'">Cancelar</button>
</form>


</body>
</html>

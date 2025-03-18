
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
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                } elseif ($_SESSION['role'] == 'empregador') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                    echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                } elseif ($_SESSION['role'] == 'admin') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="default.php"><img src="../images/circle.png" alt="Circle Icon" />default</a></div>';
                }
            } else {
                // Caso o usuário não esteja logado
                echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
            }
            ?>
            </div>
        </div>
    </div>
</header>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "psiforall";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o id do emprego foi passado na URL
if (isset($_GET['id'])) {
    $idemprego = $_GET['id'];

    // Buscar os dados do emprego com o ID fornecido
    $sql = "SELECT idemprego, titulo, responsabilidades, competencias, beneficios, quantidade, idempregador, is_ativo, areas_idarea, localizacoes_idlocalizacao, ordenado 
            FROM empregos WHERE idemprego = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idemprego);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o emprego foi encontrado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titulo = $row['titulo'];
        $responsabilidades = $row['responsabilidades'];
        $competencias = $row['competencias'];
        $beneficios = $row['beneficios'];
        $quantidade = $row['quantidade'];
        $idempregador = $row['idempregador'];
        $is_ativo = $row['is_ativo'];
        $areas_idarea = $row['areas_idarea'];
        $localizacoes_idlocalizacao = $row['localizacoes_idlocalizacao'];
        $ordenado = $row['ordenado'];
    } else {
        echo "Emprego não encontrado.";
        exit;
    }
} else {
    echo "ID do emprego não fornecido.";
    exit;
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_titulo = $_POST['titulo'];
    $novas_responsabilidades = $_POST['responsabilidades'];
    $novas_competencias = $_POST['competencias'];
    $novos_beneficios = $_POST['beneficios'];
    $nova_quantidade = $_POST['quantidade'];
    $novo_idempregador = $_POST['idempregador'];
    $novo_is_ativo = $_POST['is_ativo'];
    $novo_areas_idarea = $_POST['areas_idarea'];
    $novo_localizacoes_idlocalizacao = $_POST['localizacoes_idlocalizacao'];
    $novo_ordenado = $_POST['ordenado'];

    // Atualizar os dados no banco de dados
    $update_sql = "UPDATE empregos SET 
                    titulo = ?, 
                    responsabilidades = ?, 
                    competencias = ?, 
                    beneficios = ?, 
                    quantidade = ?, 
                    idempregador = ?, 
                    is_ativo = ?, 
                    areas_idarea = ?, 
                    localizacoes_idlocalizacao = ?, 
                    ordenado = ? 
                    WHERE idemprego = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssiisiisi", $novo_titulo, $novas_responsabilidades, $novas_competencias, $novos_beneficios, $nova_quantidade, $novo_idempregador, $novo_is_ativo, $novo_areas_idarea, $novo_localizacoes_idlocalizacao, $novo_ordenado, $idemprego);

    if ($update_stmt->execute()) {
        echo "Emprego atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o emprego.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Emprego</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/CV.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        main {
            padding: 20px;
            font-family: "Inria Serif", sans-serif;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
            margin: 0 auto;
        }
        input, textarea, select {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <!-- Inclua o seu cabeçalho aqui -->
    </header>
    <main>
        <h2>Editar Emprego</h2>
        <form method="POST" action="">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required />

            <label for="responsabilidades">Responsabilidades</label>
            <textarea id="responsabilidades" name="responsabilidades" rows="4" required><?php echo htmlspecialchars($responsabilidades); ?></textarea>

            <label for="competencias">Competências</label>
            <textarea id="competencias" name="competencias" rows="4"><?php echo htmlspecialchars($competencias); ?></textarea>

            <label for="beneficios">Benefícios</label>
            <textarea id="beneficios" name="beneficios" rows="4"><?php echo htmlspecialchars($beneficios); ?></textarea>

            <label for="quantidade">Quantidade</label>
            <input type="number" id="quantidade" name="quantidade" value="<?php echo htmlspecialchars($quantidade); ?>" required />

            <label for="idempregador">ID Empregador</label>
            <input type="number" id="idempregador" name="idempregador" value="<?php echo htmlspecialchars($idempregador); ?>" required />

            <label for="is_ativo">Ativo?</label>
            <select id="is_ativo" name="is_ativo">
                <option value="1" <?php echo $is_ativo == 1 ? 'selected' : ''; ?>>Sim</option>
                <option value="0" <?php echo $is_ativo == 0 ? 'selected' : ''; ?>>Não</option>
            </select>

            <label for="areas_idarea">Área</label>
            <input type="number" id="areas_idarea" name="areas_idarea" value="<?php echo htmlspecialchars($areas_idarea); ?>" required />

            <label for="localizacoes_idlocalizacao">Localização</label>
            <input type="number" id="localizacoes_idlocalizacao" name="localizacoes_idlocalizacao" value="<?php echo htmlspecialchars($localizacoes_idlocalizacao); ?>" required />

            <label for="ordenado">Ordenado</label>
            <input type="number" id="ordenado" name="ordenado" value="<?php echo htmlspecialchars($ordenado); ?>" required />

            <button type="submit">Atualizar Emprego</button>
        </form>
    </main>
    <?php $conn->close(); ?>
</body>
</html>

</body>
</html>
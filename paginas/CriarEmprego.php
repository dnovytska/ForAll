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

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recuperar o nome do empregador
$sql = "SELECT nome FROM empregadores WHERE idempregador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idempregador);
$stmt->execute();
$result = $stmt->get_result();

$user_name = "Usuário não encontrado";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['nome'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Criar Novo Emprego</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        body {
            background-color: #FFFFFF;
            font-family: 'Inria Serif', serif;
            color: #473D3B;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            margin: 40px;
        }

        h1 {
            color: #22202A;
            font-size: 2.4em;
            margin-bottom: 30px;
            border-bottom: 2px solid #967D60;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input, select, textarea {
            padding: 10px;
            border: 1px solid #7E7D85;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-black {
            background-color: #22202A;
            color: #E5E5EC;
        }

        .button-white {
            background-color: #E5E5EC;
            color: #22202A;
        }

        .button-black:hover {
            background-color: #7E7D85;
        }

        .button-white:hover {
            background-color: #D0D0D0;
        }

        footer {
            background-color: #22202A;
            color: #E5E5EC;
            text-align: center;
            padding: 20px;
            margin-top: auto;
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

                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <div class="auth-buttons">
                            <!-- O botão com o nome do utilizador foi removido -->
                        </div>
                    <?php else : ?>
                        <div class="auth-buttons">
                            <button class="login-register " onclick="window.location.href='Login.php'">Login</button>
                            <button class="login-register" onclick="window.location.href='Registo.php'">Registar-se</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rectangle-2">
                <?php
                // Exibir os itens do menu com base no tipo de utilizador
                if (isset($_SESSION['role'])) {
                    $user_role = $_SESSION['role'];
                    if ($user_role == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($user_role == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
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
    <h1>Criar Novo Emprego</h1>
    <form action="../php/GuardarEmprego.php" method="post">
        <input type="hidden" name="idempregador" value="<?= $idempregador ?>">

        <label for="titulo">Título de Emprego</label>
        <input type="text" id="titulo" name="titulo" placeholder="Digite o título do emprego" required />

        <label for="responsabilidades">Responsabilidades</label>
        <input type="text" id="responsabilidades" name="responsabilidades" placeholder="Digite as responsabilidades" required />

        <label for="competencias">Competências</label>
        <input type="text" id="competencias" name="competencias" placeholder="Digite as competências" required />

        <label for="beneficios">Benefícios</label>
        <input type="text" id="beneficios" name="beneficios" placeholder="Digite os benefícios" required />

        <label for="areas_idarea ">Área</label>
        <select id="areas_idarea" name="areas_idarea" required>
            <?php
            $conn = new mysqli('localhost', 'root', '', 'psiforall');
            $areasSql = "SELECT * FROM areas";
            $result = $conn->query($areasSql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idarea'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhuma área disponível</option>";
            }
            $conn->close(); 
            ?>
        </select>

        <label for="localizacoes_idlocalizacao">Localização</label>
        <select id="localizacoes_idlocalizacao" name="localizacoes_idlocalizacao" required>
            <?php
            $conn = new mysqli('localhost', 'root', '', 'psiforall');
            $localizacoesSql = "SELECT * FROM localizacoes";
            $result = $conn->query($localizacoesSql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idlocalizacao'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhuma localização disponível</option>";
            }
            $conn->close();
            ?>
        </select>

        <label for="ordenado">Ordenado</label>
        <input type="number" id="ordenado" name="ordenado" placeholder="Digite o ordenado" required />

        <label for="quantidade">Quantidade</label>
        <input type="number" id="quantidade" name="quantidade" placeholder="Digite a quantidade" required />

        <button class="button-black" type="submit">Salvar</button>
        <button class="button-white" type="button" onclick="window.location.href='PaginaPrincipal.php'">Cancelar</button>
    </form>
</main>

<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>
</body>
</html>
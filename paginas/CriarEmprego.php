<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o empregador está logado
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
    <link rel="stylesheet" href="../css/CriarEmprego.css" />
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
                            <button class="user-profile"><?= htmlspecialchars($user_name) ?></button>
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
                    // Lógica para exibir o menu com base no tipo de usuário
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
    <h1>Criar Novo Emprego</h1>
    <form action="../php/GuardarEmprego.php" method="post">
        <label for="titulo">Título de Emprego</label>
        <input type="text" id="titulo" name="titulo" placeholder="Digite o título do emprego" required />

        <label for="responsabilidades">Responsabilidades</label>
        <input type="text" id="responsabilidades" name="responsabilidades" placeholder="Digite as responsabilidades" required />

        <label for="competencias">Competências</label>
        <input type="text" id="competencias" name="competencias" placeholder="Digite as competências" required />

        <label for="beneficios">Benefícios</label>
        <input type="text" id="beneficios" name="beneficios" placeholder="Digite os benefícios" required />

        <label for="areas_idarea">Área</label>
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
            $conn->close (); 
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

        <label for="nome-empresa">Nome da Empresa</label>
        <input type="text" id="nome-empresa" name="nome-empresa" placeholder="Digite o nome da empresa" required />

        <button  class="button-black" type="submit">Salvar</button>
        <button class="button-white" type="button" onclick="window.location.href='PaginaPrincipal.php'">Cancelar</button>
    </form>
</main>

<footer>
    <p>&copy; 2023 For All. Todos os direitos reservados.</p>
</footer>
</body>
</html>
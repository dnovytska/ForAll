<?php
session_start(); // Iniciar a sessão no início do arquivo PHP

// Verificar se o usuário está logado e mostrar opções adequadas
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
} else {
    $user_id = null;
    $role = null;
}
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
    <link rel="stylesheet" href="../css/Login.css" />
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
                    if ($user_id) {
                        echo '<div class="auth-buttons">';
                        echo '<button class="user-profile">' . htmlspecialchars($_SESSION['username']) . '</button>';
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
                // Verificar o tipo de usuário logado e recuperar o nome
                if ($role) {
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

                    // Definir o nome do usuário com um valor padrão
                    $user_name = "Usuário não encontrado";

                    // Buscar o nome do usuário com base no tipo de usuário
                    if ($role == 'candidato') {
                        $sql = "SELECT nome FROM candidatos WHERE idcandidato = '$user_id'";
                    } elseif ($role == 'empregador') {
                        $sql = "SELECT nome FROM empregadores WHERE idempregador = '$user_id'";
                    } elseif ($role == 'admin') {
                        $sql = "SELECT nome FROM administradores WHERE idadmin = '$user_id'";
                    }

                    // Executar a consulta
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $user_name = $row['nome'];
                    }

                    // Fechar a conexão com o banco de dados
                    $conn->close();

                    // Exibir os itens do menu com base no tipo de usuário
                    if ($role == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($role == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    } elseif ($role == 'admin') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.html"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
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
<main>
    <section class="login-container">
        <h2>Login</h2>
        <form action="../php/Login.php" method="POST">
            <div>
                <label for="role">Escolha seu tipo de utilizador:</label>
                <div class="role-buttons">
                    <button type="button" id="role-candidato" onclick="selectRole('candidato')">Candidato</button>
                    <button type="button" id="role-empregador" onclick="selectRole('empregador')">Empregador</button>
                    <button type="button" id="role-admin" onclick="selectRole('admin')">Admin</button>
                </div>
                <input type="hidden" id="role" name="role" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>
        <p>Não tem uma conta? <a href="registro_candidato.html">Registre-se aqui</a></p>
    </section>
</main>

<script>
    function selectRole(role) {
        // Remover a classe 'selected' de todos os botões
        const buttons = document.querySelectorAll('.role-buttons button');
        buttons.forEach(button => button.classList.remove('selected'));

        // Adicionar a classe 'selected' ao botão clicado
        const selectedButton = document.getElementById('role-' + role);
        selectedButton.classList.add('selected');

        // Definir o valor do campo 'role' com o valor do botão selecionado
        document.getElementById('role').value = role;
    }
</script>
</body>
</html>

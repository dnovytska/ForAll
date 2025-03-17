<?php
session_start();
if (!isset($_SESSION['utilizador'])) {
    header("Location: login.php"); // Alterei para login.php em vez de index.html
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-PT"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="dashboard.php">Início</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="Login.php">Configurações</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <main>
        <h1>Bem-vindo, <?php echo $_SESSION['utilizador']; ?>!</h1>
        <p>Este é o seu painel de controlo. Pode aceder às suas configurações e informações aqui.</p>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Todos os direitos reservados. | Desenvolvido por Daryna e Iris</p>
    </footer>

</body>
</html>

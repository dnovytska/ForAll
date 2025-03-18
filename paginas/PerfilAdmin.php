<?php
session_start();
include '../php/db.php'; // Arquivo de conexão com a base de dados

// Verificar se o utilizador é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Recuperar o nome do utilizador logado
$user_name = "Usuário não encontrado";
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['role'];

    if ($user_role == 'admin') {
        $sql = "SELECT nome, email FROM administradores WHERE idadministrador = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result_user = $stmt->get_result();

        if ($result_user->num_rows > 0) {
            $admin = $result_user->fetch_assoc();
            $user_name = $admin['nome'];
        }
    }
}

// Fechar a conexão com o base de dados
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perfil Administrador</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/ListarDados.css" />
    <style>
        /* Estilos para a página de perfil do administrador */
        body {
            font-family: 'Inria Serif', serif;
            background-color: #FFFFFF; /* Fundo branco */
            margin: 0;
            padding: 0;
            color: #22202A; /* Cor principal do texto */
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #FFFFFF; /* Fundo branco para o conteúdo */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #22202A; /* Cor do título */
        }

        .data-perfil {
            margin-bottom: 20px;
        }

        .p-perfil {
            margin: 8px 0; /* Margem dos parágrafos */
            color: #473D3B; /* Cor do texto secundário */
            line-height: 1.6; /* Altura da linha */
        }

        .button-black, .button-white {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .button-black {
            background-color: #22202A; /* Cor do botão preto */
            color: #E5E5EC; /* Cor do texto do botão */
        }

        .button-white {
            background-color: #E5E5EC; /* Cor do botão branco */
            color: #22202A; /* Cor do texto do botão */
        }

        .button-black:hover {
            background-color: #7E7D85; /* Cor do botão ao passar o mouse */
        }

        .button-white:hover {
            background-color: #D0D0D0; /* Cor do botão ao passar o mouse */
        }

        footer {
            background-color: #22202A; /* Cor do rodapé */
            color: #E5E5EC; /* Cor do texto no rodapé */
            text-align: center;
            padding: 25px;
            margin-top: 40px;
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
                            <button class="user-profile"><?= htmlspecialchars($user_name) ?></button>
                        </div>
                    <?php else : ?>
                        <div class="auth-buttons">
                            <button class="login-register" onclick="window.location.href='Login.php'">Login</button>
                            <button class="login-register" onclick="window.location.href='Registo.php'">Registar-se</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rectangle-2">
                <?php
                // Exibir os itens do menu com base no tipo de utilizador
                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] == 'admin') {
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
    <h1>Perfil do Administrador</h1>

    <div class="data-perfil">
        <p class="p-perfil"><strong>Nome:</strong> <?= htmlspecialchars($admin['nome']) ?></p>
        <p class="p-perfil"><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
    </div>

    <div>
        <button class="button-black" onclick="window.location.href='../php/Logout.php'">Logout</button>
        <button class="button-white" onclick="confirmarExclusao()">Apagar Conta</button>
    </div>
</main>

<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>

<script>
    function confirmarExclusao() {
        if (confirm("Tem certeza de que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
            window.location.href = "../php/ApagarContaAdmin.php";
        }
    }
</script>
</body>
</html>
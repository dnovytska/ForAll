<?php
session_start(); // Iniciar sessão para verificar se o utilizador está logado
include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o utilizador é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redireciona para uma página de erro ou login caso o utilizador não seja admin
    header("Location: login.php");
    exit;
}

// Buscar todos os candidatos
$query = "SELECT * FROM candidatos";
$result = mysqli_query($conn, $query);

// Verificar se existem candidatos cadastrados
if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    $message = "Não há candidatos cadastrados.";
} else {
    $message = null; // Caso haja resultados, a mensagem é nula
}

// Recuperar o nome do utilizador logado
$user_name = "Usuário não encontrado";
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['role'];

    // Recuperar o nome do utilizador com base no tipo de utilizador
    if ($user_role == 'candidato') {
        $sql = "SELECT nome FROM candidatos WHERE idcandidato = ?";
    } elseif ($user_role == 'empregador') {
        $sql = "SELECT nome FROM empregadores WHERE idempregador = ?";
    } elseif ($user_role == 'admin') {
        $sql = "SELECT nome FROM administradores WHERE idadministrador = ?";
    }

    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result_user = $stmt->get_result();

        if ($result_user->num_rows > 0) {
            $row = $result_user->fetch_assoc();
            $user_name = $row['nome'];
        } else {
            // Caso o nome do utilizador não seja encontrado
            $user_name = "Nome não encontrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Candidatos</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/ListarDados.css" />
    <style>
        /* Estilos para a lista de candidatos */
        body {
            font-family: 'Inria Serif', serif;
            background-color: #FFFFFF; /* Fundo branco */
            margin: 0;
            padding: 0;
            color: #22202A; /* Cor principal do texto */
        }

        main {
            margin: 20px auto;
            padding: 20px;
            background-color: #FFFFFF;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #22202A; /* Cor do título */
        }

        .candidate-list {
            list-style-type: none; /* Remove os marcadores da lista */
            padding: 0; /* Remove o padding padrão */
        }

        .candidate-item {
            background-color: #E5E5EC; /* Cor de fundo para os itens da lista */
            margin: 15px 0; /* Espaçamento entre os itens */
            padding: 20px; /* Espaçamento interno */
            border-radius: 8px; /* Bordas arredondadas */
            border: 1px solid #7E7D85; /* Cor da borda */
            transition: transform 0.2s ease; /* Efeito de transição */
        }

        .candidate-item:hover {
            transform: translateY(-2px); /* Efeito de elevação ao passar o mouse */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ao passar o mouse */
        }

        .candidate-item h2 {
            margin: 0 0 10px 0; /* Margem do título */
            font-size: 1.6em; /* Tamanho da fonte do título */
            color: #967D60; /* Cor do título */
        }

        .candidate-item a {
            text-decoration: none; /* Remove o sublinhado */
            color: #22202A; /* Cor do texto do link */
            transition: color 0.2s ease; /* Efeito de transição na cor */
        }

        .candidate-item a:hover {
            color: #967D60; /* Cor do link ao passar o mouse */
            text-decoration: underline; /* Sublinha ao passar o mouse */
        }

        .candidate-item p {
            margin: 8px 0; /* Margem dos parágrafos */
            color: #473D3B; /* Cor do texto secundário */
            line-height: 1.6; /* Altura da linha */
        }

        .delete-button {
            display: inline-block; /* Exibe como bloco inline */
            margin-top: 15px; /* Margem superior */
            padding: 8px 20px; /* Padding do botão */
            background-color: #967D60; /* Cor de fundo do botão */
            color: #E5E5EC; /* Cor do texto do botão */
            border-radius: 4px; /* Bordas arredondadas */
            text-decoration: none; /* Remove o sublinhado */
            transition: background-color 0.2s ease; /* Efeito de transição na cor de fundo */
        }

        .delete-button:hover {
            background-color: #7E6D5A; /* Cor do botão ao passar o mouse */
            text-decoration: none; /* Remove o sublinhado ao passar o mouse */
        }

        footer {
            background-color: #22202A; /* Cor do rodapé */
            color: #E5E5EC; /* Cor do texto no rodapé */
            text-align: center; /* Centraliza o texto */
            padding: 25px; /* Padding do rodapé */
            margin-top: 40px; /* Margem superior */
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
                if (isset($user_role)) {
                    if ($user_role == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($user_role == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                    } elseif ($user_role == 'admin') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="ListarCandidaturasAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Candidaturas</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregosAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Empregos</a></div>';
                        echo '<div class="menu-item"><a href="VerCandidatos.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Candidatos</a></div>';
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
    <h1>Lista de Candidatos</h1>

    <?php
    // Buscar todos os candidatos
    $query = "SELECT * FROM candidatos";
    $result = mysqli_query($conn, $query);

    // Verificar se existem candidatos cadastrados
    if (!$result) {
        die("Erro na consulta: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 0) {
        $message = "Não há candidatos cadastrados.";
    } else {
        $message = null; // Caso haja resultados, a mensagem é nula
    }

    // Exibir mensagem se não houver candidatos
    if (isset($message)) {
        echo "<p>$message</p>";
    }

    // Exibir a lista de candidatos
    if (mysqli_num_rows($result) > 0) {
        echo '<ul class="candidate-list">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li class="candidate-item">';
            echo '<h2>' . htmlspecialchars($row['nome']) . '</h2>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
            echo '<p><strong>Telefone:</strong> ' . htmlspecialchars($row['telefone']) . '</p>';
            echo '<p><strong>Data de Nascimento:</strong> ' . htmlspecialchars($row['data_nascimento']) . '</p>';
            echo '<a href="../php/ApagarCandidato.php?id=' . $row['idcandidato'] . '" class="delete-button" onclick="return confirm(\'Tem certeza que deseja excluir este candidato?\');">Apagar</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    ?>
</main>

<footer>
    <p>&copy; 2025 For All. Todos os direitos reservados.</p>
</footer>

<?php
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>
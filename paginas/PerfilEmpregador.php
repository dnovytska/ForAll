<?php
session_start();
include '../php/db.php'; // Arquivo de conexão com a base de dados

// Verificar se o utilizador está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Erro: Acesso não autorizado.");
}

$idempregador = $_SESSION['user_id'];

// Validar ID
if (!filter_var($idempregador, FILTER_VALIDATE_INT)) {
    die("Erro: ID inválido.");
}

// Buscar dados do empregador (Consulta principal)
$sql = "SELECT nome, email, telefone FROM empregadores WHERE idempregador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idempregador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Empregador não encontrado!");
}

$empregador = $result->fetch_assoc(); // Alterado o nome da variável para evitar conflito
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Perfil Empregador</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/PerfilEmpregador.css" />
    <style>
        /* Estilos para a página de perfil do empregador */
        body {
            font-family: 'Inria Serif', serif;
            background-color: #FFFFFF; /* Fundo branco */
            margin: 0;
            padding: 0;
            color: #22202A; /* Cor principal do texto */
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
                    if ($_SESSION['role'] == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($_SESSION['role'] == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($empregador['nome']) . '</a></div>';
                    } elseif ($_SESSION['role'] == 'admin') {
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
    <h1>Bem-vindo, <?= htmlspecialchars($empregador['nome']) ?></h1>
    <button class="button-black" onclick="window.location.href='../paginas/EditarPerfilEmpregador.php'">Editar</button>
    <div class="data-perfil">
        <p class="p-perfil"><strong>Empregador:</strong> <?= htmlspecialchars($empregador['nome']) ?></p>
        <p class="p-perfil"><strong>Email:</strong> <?= htmlspecialchars($empregador['email']) ?></p>
        <p class="p-perfil"><strong>Telefone:</strong> <?= htmlspecialchars($empregador['telefone']) ?></p>
    </div>
    <div>
        <button class="button-black" onclick="window.location.href='../php/Logout.php'">Logout</button>
    </div>
</main>

<footer>
    <p>&copy; 2023 For All. Todos os direitos reservados.</p>
</footer>

</script>
</body>
</html>

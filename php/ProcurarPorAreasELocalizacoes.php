<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/SobreNos.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <link rel="stylesheet" href="../css/ProcurarPorAreasELocalizacoes.css" />
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
                // Exibir os itens do menu com base no tipo de usuário
                if (isset($user_role)) {
                    if ($user_role == 'candidato') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    } elseif ($user_role == 'empregador') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                        echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                    } elseif ($user_role == 'admin') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                        echo '<div class="menu-item"><a href="default.php"><img src="../images/circle.png" alt="Circle Icon" />default</a></div>';
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
    <?php
session_start();
include 'db.php';

// Capturar os IDs enviados pelo formulário
$areaIds = isset($_POST['areas']) ? explode(',', $_POST['areas']) : [];
$locationIds = isset($_POST['localizacoes']) ? explode(',', $_POST['localizacoes']) : [];

// Construção da query com filtros
$query = "SELECT * FROM empregos";
$conditions = [];

if (!empty($areaIds)) {
    $areaIdsString = implode(",", array_map('intval', $areaIds));
    $conditions[] = "areas_idarea IN ($areaIdsString)";
}

if (!empty($locationIds)) {
    $locationIdsString = implode(",", array_map('intval', $locationIds));
    $conditions[] = "localizacoes_idlocalizacao IN ($locationIdsString)";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$result = mysqli_query($conn, $query);

// Exibir resultados
if (mysqli_num_rows($result) > 0) {
    echo "<div class='job-list'>";
    while ($row = mysqli_fetch_assoc($result)) {
        // Buscar nome da área
        $areaQuery = "SELECT nome FROM areas WHERE idarea = " . $row['areas_idarea'];
        $areaResult = mysqli_query($conn, $areaQuery);
        $area = mysqli_fetch_assoc($areaResult)['nome'] ?? 'Desconhecido';

        // Buscar nome da localização
        $locationQuery = "SELECT nome FROM localizacoes WHERE idlocalizacao = " . $row['localizacoes_idlocalizacao'];
        $locationResult = mysqli_query($conn, $locationQuery);
        $location = mysqli_fetch_assoc($locationResult)['nome'] ?? 'Desconhecido';

        // Criando link para a página de detalhes
        $jobUrl = "../paginas/Emprego.php?id=" . $row['idemprego'];

        // Exibir informações do emprego
        echo "<div class='job-item' onclick='window.location=\"$jobUrl\"'>";
        echo "<h3>" . $row['titulo'] . "</h3>";
        echo "<p><strong>Área:</strong> " . $area . "</p>";
        echo "<p><strong>Localização:</strong> " . $location . "</p>";

        // Verificando se o usuário está logado
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            // Verificar se o usuário já se candidatou a este emprego
            $idcandidato = $_SESSION['user_id'];
            $idemprego = $row['idemprego'];

            // Verificar se já existe uma candidatura
            $checkQuery = "SELECT * FROM candidaturas WHERE idcandidato = ? AND idemprego = ?";
            $stmt = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($stmt, "ii", $idcandidato, $idemprego);
            mysqli_stmt_execute($stmt);
            $checkResult = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($checkResult) > 0) {
                // O candidato já se candidatou a este emprego
                echo "<div class='apply-button-container'>";
                echo "<button class='apply-button' disabled>Já se candidatou</button>";
                echo "</div>";
            } else {
                // O candidato ainda não se candidatou
                echo "<div class='apply-button-container'>";
                echo "<button class='apply-button' onclick='window.location.href=\"Candidatar.php?emprego_id=" . $row['idemprego'] . "\"'>Candidatar-se</button>";
                echo "</div>";
            }
        } else {
            // Se o usuário não está logado, redireciona para a página de login
            echo "<div class='apply-button-container'>";
            echo "<button class='apply-button' onclick='window.location.href=\"Login.php\"'>Candidatar-se (Necessário Login)</button>";
            echo "</div>";
        }

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p class='no-results'>Nenhum emprego encontrado com os critérios selecionados.</p>";
}

mysqli_close($conn);
?>




        <footer>
        <div class="rectangle-f"></div>
    </footer>
    </main>
</body>
</html>
~
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Empregos</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/ProcurarEmprego.css" />
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
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                } elseif ($_SESSION['role'] == 'empregador') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                    echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                } elseif ($_SESSION['role'] == 'admin') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="default.php"><img src="../images/circle.png" alt="Circle Icon" />default</a></div>';
                }
            } else {
                // Caso o usuário não esteja logado
                echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
            }
            ?>
            </div>
        </div>
    </div>
</header>
    <main>
        <section class="job-list">
            <?php
            $conn = new mysqli("localhost", "root", "", "forall");

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "
                SELECT 
                    e.titulo, 
                    e.responsabilidades, 
                    e.competencias, 
                    e.beneficios, 
                    e.quantidade, 
                    emp.nome AS nome_empregador, 
                    a.nome AS nome_area, 
                    l.nome AS nome_localizacao 
                FROM 
                    empregos e
                JOIN 
                    empregadores emp ON e.idempregador = emp.idempregador
                JOIN 
                    areas a ON e.areas_idarea = a.idarea
                JOIN 
                    localizacoes l ON e.localizacoes_idlocalizacao = l.idlocalizacao
                WHERE 
                    e.is_ativo = 1
            ";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<article class='job'>";
                    echo "<h2>Título: " . $row["titulo"] . "</h2>";
                    echo "<p>Responsabilidades: " . $row["responsabilidades"] . "</p>";
                    echo "<p>Competências: " . $row["competencias"] . "</p>";
                    echo "<p>Benefícios: " . $row["beneficios"] . "</p>";
                    echo "<p>Quantidade: " . $row["quantidade"] . "</p>";
                    echo "<p>Empregador: " . $row["nome_empregador"] . "</p>";
                    echo "<p>Área: " . $row["nome_area"] . "</p>";
                    echo "<p>Localização: " . $row["nome_localizacao"] . "</p>";
                    echo "</article>";
                }
            } else {
                echo "<p>Nenhum emprego encontrado.</p>";
            }

            $conn->close();
            ?>
        </section>
    </main>
</body>
</html>
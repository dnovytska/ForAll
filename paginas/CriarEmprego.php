<!DOCTYPE html>
<html lang="pt">
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
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.html"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilCandidato.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                } elseif ($_SESSION['role'] == 'empregador') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.html"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilEmpregador.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="VerEmpregos.php"><img src="../images/circle.png" alt="Circle Icon" />Meus Empregos</a></div>';
                    echo '<div class="menu-item"><a href="notificacoes.html"><img src="../images/circle.png" alt="Circle Icon" />Notificações</a></div>';
                    echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                } elseif ($_SESSION['role'] == 'admin') {
                    echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                    echo '<div class="menu-item"><a href="SobreNos.html"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                    echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
                    echo '<div class="menu-item"><a href="default.php"><img src="../images/circle.png" alt="Circle Icon" />default</a></div>';
                }
            } else {
                // Caso o usuário não esteja logado
                echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                echo '<div class="menu-item"><a href="SobreNos.html"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
            }
            ?>
            </div>
        </div>
    </div>
</header>
    <main>
        <div>
            <h1>Criar Novo Emprego</h1>
        </div>
        
        <div class="flex-row-f">
            <div class="ellipse"></div>
            <div class="design-unnamed"></div>
            
            <form action="../php/GuardarEmprego.php" method="post">

                <label for="titulo">Título de Emprego</label>
                <input type="text" id="titulo" name="titulo" class="nome-input" placeholder="Digite o título do emprego" required />

                <label for="responsabilidades">Responsabilidades</label>
                <input type="text" id="responsabilidades" name="responsabilidades" class="responsabilidades-input" placeholder="Digite as responsabilidades" required />
                
                <label for="competencias">Competências</label>
                <input type="text" id="competencias" name="competencias" class="competencias-input" placeholder="Digite as competências" required />
                
                <label for="beneficios">Benefícios</label>
                <input type="text" id="beneficios" name="beneficios" class="beneficios-input" placeholder="Digite os benefícios" required />
                
                <label for="areas_idarea">Área</label>
                <select id="areas_idarea" name="areas_idarea" class="area-input" required>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'psiforall'); 

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

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
                <select id="localizacoes_idlocalizacao" name="localizacoes_idlocalizacao" class="localizacao-input" required>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'psiforall'); 

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

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
                <input type="number" id="ordenado" name="ordenado" class="ordenado-input" placeholder="Digite o ordenado" required />

                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" class="quantidade-input" placeholder="Digite o quantidade" required />
                
                <label for="nome-empresa">Nome da Empresa</label>
                <input type="text" id="nome-empresa" name="nome-empresa" class="nome-empresa-input" placeholder="Digite o nome da empresa" required />

                <button type="submit" class="button-black">Salvar</button>
                <button type="button" class="button-black" onclick="window.location.href='PaginaPrincipal.html'">Cancelar</button>
            </form>
        </div>

        
        <footer>
            <div class="rectangle-f"></div>
        </footer>
    </main>
</body>
</html>

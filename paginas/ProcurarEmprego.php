<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All - Empregos</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/CV.css" />
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
                    <div class="auth-buttons">
                        <button class="login-register">Login</button>
                        <button class="login-register">Registar-se</button>
                    </div>
                </div>
            </div>
            <div class="rectangle-2">
                <div class="menu-item">
                    <a href="pagina_principal.html">
                        <img src="../images/circle.png" alt="Circle Icon" />
                        Página Principal
                    </a>
                </div>
                <div class="menu-item">
                    <a href="sobre_nos.html">
                        <img src="../images/circle.png" alt="Circle Icon" />
                        Sobre Nós
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
    <main>
        <section class="job-list">
            <?php
            // Conexão com o banco de dados
            $conn = new mysqli("localhost", "root", "", "forall");

            // Verifica a conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Consulta ao banco de dados com JOINs
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

            // Verifica se há resultados
            if ($result->num_rows > 0) {
                // Exibe os resultados
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

            // Fecha a conexão
            $conn->close();
            ?>
        </section>
    </main>
</body>
</html>
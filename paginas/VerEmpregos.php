<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "psiforall";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Busca os empregos no banco de dados
$sql = "SELECT idemprego, titulo, responsabilidades FROM empregos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>For All</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" />
    <link rel="stylesheet" href="../css/CV.css" />
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        main {
            padding: 20px;
            font-family: "Inria Serif", sans-serif;
        }
        .job-listing {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .job-title {
            font-size: 20px;
            font-weight: bold;
        }
        .job-company {
            font-size: 16px;
            color: #555;
        }
        .job-actions {
            margin-top: 10px;
        }
        .job-actions a {
            margin-right: 10px;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }
        .job-actions a.delete {
            background-color: #f44336;
        }
    </style>
    <script>
        // Função para confirmar a exclusão
        function confirmarExclusao(id) {
            // Confirmação usando uma caixa de diálogo do navegador
            var confirmar = confirm("Você tem certeza que deseja apagar este emprego?");
            if (confirmar) {
                // Se confirmado, redireciona para a página de exclusão com o ID do emprego
                window.location.href = "ApagarEmprego.php?id=" + id;
            }
        }
    </script>
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
                        <a href="meus_empregos.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Meus Empregos
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="notificacoes.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Notificações
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="sobre_nos.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Sobre Nós
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="criar_emprego.html">
                            <img src="../images/circle.png" alt="Circle Icon" />
                            Criar Novo Emprego
                        </a>
                    </div>
                    <span class="username">Username</span>
                </div>
            </div>
        </div>
    </header>
    <main>
        <h2>Lista de Empregos</h2>
        <div id="job-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='job-listing'>";
                    echo "<div class='job-title'><a href='detalhes_emprego.php?id=" . $row['idemprego'] . "'>" . htmlspecialchars($row['titulo']) . "</a></div>";
                    echo "<div class='job-company'>Responsabilidades: " . htmlspecialchars($row['responsabilidades']) . "</div>";
                    echo "<div class='job-actions'>";
                    echo "<a href='EditarEmprego.php?id=" . $row['idemprego'] . "'>Editar</a>";
                    // Alterado para usar o JavaScript
                    echo "<a href='#' class='delete' onclick='confirmarExclusao(" . $row['idemprego'] . ")'>Apagar</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhum emprego encontrado.</p>";
            }
            ?>
        </div>
    </main>
    <?php $conn->close(); ?>
</body>
</html>

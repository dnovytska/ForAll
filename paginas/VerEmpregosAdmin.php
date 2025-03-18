<?php
session_start();
include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o usuário é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redireciona para uma página de erro ou login caso o usuário não seja admin
    header("Location: login.php");
    exit;
}
// Buscar todos os empregos
$query = "SELECT * FROM empregos";
$result = mysqli_query($conn, $query);

// Verificar se existem empregos cadastrados
if (mysqli_num_rows($result) == 0) {
    echo "<p>Não há empregos cadastrados.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Empregos</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        body {
            font-family: "Inria Serif", sans-serif;
        }

        main {
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .job-list {
            list-style-type: none;
            padding: 0;
        }

        .job-item {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .job-item h2 {
            font-size: 20px;
            font-weight: bold;
        }

        .job-item p {
            margin: 5px 0;
        }

        .delete-button {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }

        .delete-button:hover {
            background-color: #d32f2f;
        }

        .delete-button:active {
            background-color: #b71c1c;
        }

    </style>
</head>
<body>

<main>
    <h1>Lista de Empregos</h1>

    <ul class="job-list">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <li class="job-item">
                <h2><a href="DetalhesEmprego.php?id=<?php echo $row['idemprego']; ?>"><?php echo $row['titulo']; ?></a></h2>
                <p><strong>Área:</strong> <?php echo $row['areas_idarea']; ?></p>  <!-- Exemplo -->
                <p><strong>Localização:</strong> <?php echo $row['localizacoes_idlocalizacao']; ?></p> <!-- Exemplo -->

                <a href="ApagarEmprego.php?id=<?php echo $row['idemprego']; ?>" class="delete-button" 
                   onclick="return confirm('Tem certeza que deseja excluir este emprego?');">Apagar</a>
            </li>
        <?php } ?>
    </ul>

</main>

</body>
</html>

<?php
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>

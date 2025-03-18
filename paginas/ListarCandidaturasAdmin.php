<?php
session_start();

// Verificar se o usuário é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redireciona para uma página de erro ou login caso o usuário não seja admin
    header("Location: login.php");
    exit;
}

include '../php/db.php';  // Arquivo de conexão com o banco de dados

// Consultar todas as candidaturas
$query = "SELECT c.idcandidatura, u.nome AS candidato, e.titulo AS emprego, c.data_candidatura 
          FROM candidaturas c
          JOIN candidatos u ON c.idcandidato = u.idcandidato
          JOIN empregos e ON c.idemprego = e.idemprego";
$result = mysqli_query($conn, $query);

// Verificar se a consulta retornou algum resultado
if (mysqli_num_rows($result) == 0) {
    $message = "Não há candidaturas registradas.";
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lista de Candidaturas</title>
    <link rel="stylesheet" href="../css/globals.css" />
    <style>
        body {
            font-family: 'Inria Serif', serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .candidaturas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .candidaturas-table th, .candidaturas-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

    </style>
</head>
<body>

<main>
    <h1>Lista de Candidaturas</h1>

    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <table class="candidaturas-table">
        <thead>
            <tr>
                <th>ID Candidatura</th>
                <th>Candidato</th>
                <th>Emprego</th>
                <th>Data de Candidatura</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['idcandidatura']; ?></td>
                    <td><?php echo $row['candidato']; ?></td>
                    <td><?php echo $row['emprego']; ?></td>
                    <td><?php echo $row['data_candidatura']; ?></td>
                    <td>
                        <a href="../php/ApagarCandidatura.php?id=<?php  echo $row['idcandidatura']; ?>"
                           class="delete-button" 
                           onclick="return confirm('Tem certeza de que deseja excluir esta candidatura?');">
                           Apagar
                        </a>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

</body>
</html>

<?php
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>

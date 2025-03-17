<?php
session_start();
include '../php/db.php';  // Arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Recupera o ID do emprego da URL
if (isset($_GET['emprego_id'])) {
    $emprego_id = $_GET['emprego_id'];

    // Consulta os detalhes do emprego
    $query = "SELECT * FROM empregos WHERE idemprego = $emprego_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $emprego = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Emprego não encontrado.</p>";
        exit();
    }
} else {
    echo "<p>ID de emprego não fornecido.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se o formulário for enviado, insere a candidatura no banco de dados
    $user_id = $_SESSION['user_id'];  // ID do usuário logado
    $status = 'pendente';  // Status da candidatura (pode ser "pendente", "aceito", "rejeitado")
    $data_candidatura = date('Y-m-d H:i:s');  // Data e hora da candidatura

    // Consulta para inserir os dados na tabela de candidaturas
    $query = "INSERT INTO candidaturas (idcandidato, idemprego, status, data_candidatura) 
              VALUES ($user_id, $emprego_id, '$status', '$data_candidatura')";

    if (mysqli_query($conn, $query)) {
        echo "<p>Você se candidatou com sucesso ao emprego!</p>";
    } else {
        echo "<p>Erro ao se candidatar: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatar-se ao Emprego</title>
    <link rel="stylesheet" href="../css/globals.css">
    <style>
        /* Estilo simples para o formulário */
        .form-container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-container h2 {
            text-align: center;
        }

        .form-container p {
            margin: 10px 0;
        }

        .form-container button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Detalhes do Emprego</h2>
        <p><strong>Título:</strong> <?php echo $emprego['titulo']; ?></p>
        <p><strong>Descrição:</strong> <?php echo $emprego['descricao']; ?></p>

        <h2>Se candidatar</h2>
        <form method="POST">
            <button type="submit">Candidatar-se</button>
        </form>
    </div>

</body>
</html>

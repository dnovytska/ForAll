<?php
session_start(); // Iniciar sessão para verificar se o usuário está logado

include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o ID do emprego foi passado pela URL
if (isset($_GET['id'])) {
    $jobId = $_GET['id'];

    // Buscar os dados do emprego
    $query = "SELECT * FROM empregos WHERE idemprego = $jobId";
    $result = mysqli_query($conn, $query);
    $job = mysqli_fetch_assoc($result);

    // Buscar nome da área
    $areaQuery = "SELECT nome FROM areas WHERE idarea = " . $job['areas_idarea'];
    $areaResult = mysqli_query($conn, $areaQuery);
    $area = mysqli_fetch_assoc($areaResult)['nome'];

    // Buscar nome da localização
    $locationQuery = "SELECT nome FROM localizacoes WHERE idlocalizacao = " . $job['localizacoes_idlocalizacao'];
    $locationResult = mysqli_query($conn, $locationQuery);
    $location = mysqli_fetch_assoc($locationResult)['nome'];

    // Verificar se o usuário já se candidatou a este emprego
    $alreadyApplied = false;
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $checkApplicationQuery = "SELECT * FROM candidaturas WHERE idcandidato = $userId AND idemprego = $jobId";
        $checkApplicationResult = mysqli_query($conn, $checkApplicationQuery);
        if (mysqli_num_rows($checkApplicationResult) > 0) {
            $alreadyApplied = true;
        }
    }
} else {
    echo "Emprego não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalhes do Emprego</title>
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

        .job-details {
            font-size: 1.2em;
        }

        .apply-button {
            background-color: #333;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-size: 1em;
        }

        .apply-button:hover {
            background-color: #555;
        }

        .apply-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<main>
    <h1><?php echo $job['titulo']; ?></h1>

    <div class="job-details">
        <p><strong>Descrição:</strong> <?php echo $job['descricao']; ?></p>
        <p><strong>Área:</strong> <?php echo $area; ?></p>
        <p><strong>Localização:</strong> <?php echo $location; ?></p>
    </div>

    <?php if (isset($_SESSION['user_id'])) { ?>
        <?php if ($alreadyApplied): ?>
            <!-- Botão desabilitado se já se candidatou -->
            <button class="apply-button" disabled>Já Candidatado</button>
        <?php else: ?>
            <!-- Botão para candidatar-se -->
            <a href="Candidatar.php?emprego_id=<?php echo $job['idemprego']; ?>" class="apply-button">Candidatar-se</a>
        <?php endif; ?>
    <?php } else { ?>
        <!-- Botão de login caso o usuário não esteja logado -->
        <a href="login.php" class="apply-button">Fazer Login para Candidatar-se</a>
    <?php } ?>

</main>

</body>
</html>

<?php
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>

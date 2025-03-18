<?php
session_start();
include '../php/db.php'; 

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: usuário não está logado.");
}

// Recupera o ID do candidato da sessão
$idcandidato = $_SESSION['user_id'];

// Recupera o ID do emprego da URL
if (isset($_GET['emprego_id'])) {
    $idemprego = $_GET['emprego_id'];
} else {
    die("Erro: ID do emprego não fornecido.");
}

// Verifica se o candidato já se candidatou a este emprego
$checkQuery = "SELECT * FROM candidaturas WHERE idcandidato = $idcandidato AND idemprego = $idemprego";
$checkResult = mysqli_query($conn, $checkQuery);

// Verifica se já existe uma candidatura
$alreadyApplied = mysqli_num_rows($checkResult) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatura</title>
</head>
<body>

    <h1>Detalhes do Emprego</h1>
    <!-- Exibindo as informações do emprego -->
    <div>
        <?php
            // Aqui você pode exibir as informações do emprego, como título, descrição, etc.
            // Exemplo:
            $empregoQuery = "SELECT * FROM empregos WHERE idemprego = $idemprego";
            $empregoResult = mysqli_query($conn, $empregoQuery);
            $empregoData = mysqli_fetch_assoc($empregoResult);
            echo "<h2>" . $empregoData['titulo'] . "</h2>";
            echo "<p>" . $empregoData['descricao'] . "</p>";
        ?>
    </div>

    <!-- Exibe o botão de candidatura -->
    <div>
        <?php if ($alreadyApplied): ?>
            <!-- Botão desabilitado se o candidato já se candidatou -->
            <button disabled>Candidatar-se (Já Candidatado)</button>
        <?php else: ?>
            <!-- Botão para se candidatar -->
            <button onclick="window.location.href='CandidatarSubmit.php?emprego_id=<?php echo $idemprego; ?>'">Candidatar-se</button>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>

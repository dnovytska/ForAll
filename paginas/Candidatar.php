<?php
session_start();
include '../php/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // PHPMailer

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: usuário não está logado.");
}

// Verifica se o usuário é um candidato
if ($_SESSION['role'] !== 'candidato') {
    die("Erro: apenas candidatos podem se candidatar a empregos.");
}

$idcandidato = $_SESSION['user_id'];

if (!isset($_GET['emprego_id']) || empty($_GET['emprego_id'])) {
    die("Erro: ID do emprego não fornecido.");
}

$idemprego = $_GET['emprego_id'];

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o candidato já se candidatou
$checkQuery = "SELECT * FROM candidaturas WHERE idcandidato = ? AND idemprego = ?";
$stmt_check = $conn->prepare($checkQuery);
$stmt_check->bind_param("ii", $idcandidato, $idemprego);
$stmt_check->execute();
$checkResult = $stmt_check->get_result();

if ($checkResult->num_rows > 0) {
    die("Você já se candidatou para este emprego.");
}

// Insere a candidatura
$insertQuery = "INSERT INTO candidaturas (idcandidato, idemprego, data_candidatura) VALUES (?, ?, NOW())";
$stmt_insert = $conn->prepare($insertQuery);
$stmt_insert->bind_param("ii", $idcandidato, $idemprego);

if (!$stmt_insert->execute()) {
    die("Erro ao candidatar-se: " . $stmt_insert->error);
}

// Recupera dados do candidato
$candidatoQuery = "SELECT nome, email FROM candidatos WHERE idcandidato = ?";
$stmt_candidato = $conn->prepare($candidatoQuery);
$stmt_candidato->bind_param("i", $idcandidato);
$stmt_candidato->execute();
$candidatoResult = $stmt_candidato->get_result();
$candidatoData = $candidatoResult->fetch_assoc();
$nomeCandidato = $candidatoData['nome'];
$emailCandidato = $candidatoData['email'];

// Recupera dados do emprego
$empregoQuery = "SELECT titulo, idempregador FROM empregos WHERE idemprego = ?";
$stmt_emprego = $conn->prepare($empregoQuery);
$stmt_emprego->bind_param("i", $idemprego);
$stmt_emprego->execute();
$empregoResult = $stmt_emprego->get_result();
$empregoData = $empregoResult->fetch_assoc();
$tituloEmprego = $empregoData['titulo'];
$idempregador = $empregoData['idempregador'];

// Recupera dados do empregador
$empregadorQuery = "SELECT email FROM empregadores WHERE idempregador = ?";
$stmt_empregador = $conn->prepare($empregadorQuery);
$stmt_empregador->bind_param("i", $idempregador);
$stmt_empregador->execute();
$empregadorResult = $stmt_empregador->get_result();
$empregadorData = $empregadorResult->fetch_assoc();
$emailEmpregador = $empregadorData['email'];

// Configuração do e-mail
$assunto = "Novo candidato para: $tituloEmprego";
$mensagem = "Olá,\n\nUm novo candidato se candidatou à vaga '$tituloEmprego'.\n\n"
    . "Nome: $nomeCandidato\nE-mail: $emailCandidato\n\n"
    . "Acesse sua conta para mais detalhes.\n\nEquipe ForAll";

// Enviar e-mail via PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'psiallfor@gmail.com'; 
    $mail->Password = '0638740350'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('psiallfor@gmail.com', 'ForAll');
    $mail->addAddress($emailEmpregador);  

    $mail->Subject = $assunto;
    $mail->Body = $mensagem;

    $mail->send();
    echo "Candidatura realizada com sucesso! O empregador foi notificado.";
} catch (Exception $e) {
    echo "Candidatura realizada, mas houve um erro ao enviar o e-mail: {$mail->ErrorInfo}";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> Candidatura Realizada</title>
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
                        echo '<div class="menu-item"><a href="CriarEmprego.php"><img src="../images/circle.png" alt="Circle Icon" />Criar Novo Emprego</a></div>';
                    } elseif ($user_role == 'admin') {
                        echo '<div class="menu-item"><a href="PaginaPrincipal.php"><img src="../images/circle.png" alt="Circle Icon" />Página Principal</a></div>';
                        echo '<div class="menu-item"><a href="SobreNos.php"><img src="../images/circle.png" alt="Circle Icon" />Sobre Nós</a></div>';
                        echo '<div class="menu-item"><a href="ListarCandidaturasAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Candidaturas</a></div>';
                        echo '<div class="menu-item"><a href="VerEmpregosAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Empregos</a></div>';
                        echo '<div class="menu-item"><a href="VerCandidatos.php"><img src="../images/circle.png" alt="Circle Icon" />Listar Candidatos</a></div>';
                        echo '<div class="menu-item"><a href="PerfilAdmin.php"><img src="../images/circle.png" alt="Circle Icon" />' . htmlspecialchars($user_name) . '</a></div>';
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
        <h2>Candidatura realizada com sucesso!</h2>
        <p>Você se candidatou à vaga: <strong><?php echo htmlspecialchars($tituloEmprego); ?></strong>.</p>
        <p>O empregador foi notificado por e-mail.</p>
    </main>
    <footer>
        <p>&copy; 2023 ForAll. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
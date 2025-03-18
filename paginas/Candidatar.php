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

$idcandidato = $_SESSION['user_id'];
var_dump($idcandidato); // Verifica se o ID do candidato está correto
echo "<br>";

if (!isset($_GET['emprego_id']) || empty($_GET['emprego_id'])) {
    die("Erro: ID do emprego não fornecido.");
}

$idemprego = $_GET['emprego_id'];
var_dump($idemprego); // Verifica se o ID do emprego está correto
echo "<br>";

// Verifica se o candidato já se candidatou
$checkQuery = "SELECT * FROM candidaturas WHERE idcandidato = $idcandidato AND idemprego = $idemprego";
$checkResult = mysqli_query($conn, $checkQuery);

if (!$checkResult) {
    die("Erro na consulta: " . mysqli_error($conn));
}

if (mysqli_num_rows($checkResult) > 0) {
    die("Você já se candidatou para este emprego.");
}

// Insere a candidatura
$insertQuery = "INSERT INTO candidaturas (idcandidato, idemprego, data_candidatura) VALUES ($idcandidato, $idemprego, NOW())";
if (!mysqli_query($conn, $insertQuery)) {
    die("Erro ao candidatar-se: " . mysqli_error($conn));
}

// Recupera dados do candidato
$candidatoQuery = "SELECT nome, email FROM candidatos WHERE idcandidato = $idcandidato";
$candidatoResult = mysqli_query($conn, $candidatoQuery);
if (!$candidatoResult) {
    die("Erro ao buscar candidato: " . mysqli_error($conn));
}

$candidatoData = mysqli_fetch_assoc($candidatoResult);
$nomeCandidato = $candidatoData['nome'];
$emailCandidato = $candidatoData['email'];
var_dump($nomeCandidato, $emailCandidato); // Verifica se os dados do candidato estão corretos
echo "<br>";

// Recupera dados do emprego
$empregoQuery = "SELECT titulo, idempregador FROM empregos WHERE idemprego = $idemprego";
$empregoResult = mysqli_query($conn, $empregoQuery);
if (!$empregoResult) {
    die("Erro ao buscar emprego: " . mysqli_error($conn));
}

$empregoData = mysqli_fetch_assoc($empregoResult);
$tituloEmprego = $empregoData['titulo'];
$idempregador = $empregoData['idempregador'];
var_dump($tituloEmprego, $idempregador); // Verifica se os dados do emprego estão corretos
echo "<br>";

// Recupera dados do empregador
$empregadorQuery = "SELECT email FROM empregadores WHERE idempregador = $idempregador";
$empregadorResult = mysqli_query($conn, $empregadorQuery);
if (!$empregadorResult) {
    die("Erro ao buscar empregador: " . mysqli_error($conn));
}

$empregadorData = mysqli_fetch_assoc($empregadorResult);
$emailEmpregador = $empregadorData['email'];
var_dump($emailEmpregador); // Verifica se o email do empregador está correto
echo "<br>";

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

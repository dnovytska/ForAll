<?php
session_start(); // Iniciar a sessão

// Verificar se o utilizador está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: Utilizador não está logado.");
}

// Recuperar o ID do candidato da sessão
$idcandidato = $_SESSION['user_id'];

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar e validar os dados
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $data_nascimento = isset($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : '';
    $anos_experiencia = isset($_POST['experiencia']) ? trim($_POST['experiencia']) : '';
    $habilitacoes_academicas = isset($_POST['habilitacoes_academicas']) ? trim($_POST['habilitacoes_academicas']) : '';

    // Verificar se os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($email) || empty($telefone) || empty($data_nascimento) || empty($anos_experiencia) || empty($habilitacoes_academicas)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    // Atualizar os dados no banco
    $sql = "UPDATE candidatos SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, anos_experiencia = ?, habilitacoes_academicas = ? WHERE idcandidato = ?";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param("ssssisi", $nome, $email, $telefone, $data_nascimento, $anos_experiencia, $habilitacoes_academicas, $idcandidato);

    // Executar a consulta
    if ($stmt->execute()) {
        header("Location: ../paginas/PerfilCandidato.php?msg=Perfil atualizado com sucesso!");
        exit();
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método de requisição inválido.";
}

$conn->close();
?>
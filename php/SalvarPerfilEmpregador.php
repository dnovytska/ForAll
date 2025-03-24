<?php
session_start(); // Iniciar a sessão

// Verificar se o utilizador está logado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erro: Utilizador não está logado.");
}

// Recuperar o ID do candidato da sessão
$idempregador = $_SESSION['user_id'];

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

    // Verificar se os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($email) || empty($telefone) ) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    // Atualizar os dados no banco
    $sql = "UPDATE empregadores SET nome = ?, email = ?, telefone = ? WHERE idempregador = ?";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param("sssi", $nome, $email, $telefone, $idempregador);

    // Executar a consulta
    if ($stmt->execute()) {
        header("Location: ../paginas/PerfilEmpregador.php?msg=Perfil atualizado com sucesso!");
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
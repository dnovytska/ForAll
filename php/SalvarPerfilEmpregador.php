<?php
session_start();

// Verificar se o usuário está logado como empregador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Acesso não autorizado");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Obter ID do empregador da sessão
$idempregador = $_SESSION['user_id'];

// Validar dados de entrada
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);

// Validações adicionais
if (empty($nome) || empty($email) || empty($telefone)) {
    die("Todos os campos são obrigatórios");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

// Atualização segura com prepared statement
$sql = "UPDATE empregadores 
        SET nome = ?, email = ?, telefone = ?
        WHERE idempregador = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $email, $telefone, $idempregador);

if ($stmt->execute()) {
    // Redirecionamento adequado
    header("Location: ../paginas/PerfilEmpregador.php?success=1");
    exit();
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
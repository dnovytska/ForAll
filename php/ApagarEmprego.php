<?php
session_start();
include '../php/db.php'; // Arquivo de conexão com a base de dados

// Verificar se o utilizador está logado e é empregador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Erro: Acesso não autorizado.");
}

// Verificar se o ID do emprego foi passado
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Erro: ID inválido.");
}

$idemprego = $_GET['id'];

// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "psiforall");

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Preparar a consulta para excluir o emprego
$sql = "DELETE FROM empregos WHERE idemprego = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idemprego);

if ($stmt->execute()) {
    echo "Emprego excluído com sucesso! <a href='../paginas/VerEmpregos.php'>Voltar</a>";
} else {
    echo "Erro ao excluir emprego: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
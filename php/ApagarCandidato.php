<?php
session_start();
include '../php/db.php'; // Arquivo de conexão com a base de dados

// Verificar se o utilizador está logado e é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Erro: Acesso não autorizado.");
}

// Verificar se o ID do candidato foi passado
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Erro: ID inválido.");
}

$idcandidato = $_GET['id'];

// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "psiforall");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Remover registros relacionados na tabela candidatos_areas
$sql_delete_areas = "DELETE FROM candidatos_areas WHERE idcandidato = ?";
$stmt_delete_areas = $conn->prepare($sql_delete_areas);
$stmt_delete_areas->bind_param("i", $idcandidato);
$stmt_delete_areas->execute();

// Agora, remover o candidato
$sql_delete_candidato = "DELETE FROM candidatos WHERE idcandidato = ?";
$stmt_delete_candidato = $conn->prepare($sql_delete_candidato);
$stmt_delete_candidato->bind_param("i", $idcandidato);

if ($stmt_delete_candidato->execute()) {
    echo "Candidato removido com sucesso.";
} else {
    echo "Erro ao remover candidato: " . $stmt_delete_candidato->error;
}

// Fechar a conexão
$stmt_delete_areas->close();
$stmt_delete_candidato->close();
$conn->close();
?>
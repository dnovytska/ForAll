<?php
session_start();

// Verificar se o utilizador é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redireciona para a página de login ou erro
    header("Location: login.php");
    exit;
}

include './db.php';  // Arquivo de conexão com a base de dados

// Verificar se o ID da candidatura foi passado
if (isset($_GET['id'])) {
    $candidaturaId = $_GET['id'];

    // Apagar a candidatura da base de dados
    $deleteQuery = "DELETE FROM candidaturas WHERE idcandidatura = $candidaturaId";
    if (mysqli_query($conn, $deleteQuery)) {
        // Redireciona para a página de lista de candidaturas com uma mensagem de sucesso
        header("Location: ../paginas/ListarCandidaturasAdmin.php");
    } else {
        // Caso haja erro ao excluir, redireciona com uma mensagem de erro
        header("Location: ./paginas/ListarCandidaturasAdmin.php");
    }
} else {
    // Se o ID não for fornecido, redireciona com erro
    header("Location: lista_candidaturas.php?error=2");
}

mysqli_close($conn);
?>

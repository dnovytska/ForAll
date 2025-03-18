<?php
session_start();
include '../php/db.php';  // Arquivo de conexão com a base de dados

// Verificar se o usuário é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Verificar se o ID do candidato foi passado
if (isset($_GET['id'])) {
    $idCandidato = $_GET['id'];

    // Verificar se o candidato existe
    $query = "SELECT * FROM candidatos WHERE idcandidato = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idCandidato);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Deletar o candidato
        $deleteQuery = "DELETE FROM candidatos WHERE idcandidato = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $idCandidato);
        $deleteStmt->execute();

        // Redirecionar de volta para a lista de candidatos
        header("Location: ../paginas/VerCandidatos.php");
        exit;
    } else {
        echo "Candidato não encontrado.";
    }
} else {
    echo "ID de candidato não especificado.";
}

mysqli_close($conn);
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "psiforall";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idemprego = $_GET['id'];

    $sql = "DELETE FROM empregos WHERE idemprego = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idemprego);

    if ($stmt->execute()) {
        echo "Emprego apagado com sucesso!";
    } else {
        echo "Erro ao apagar o emprego: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

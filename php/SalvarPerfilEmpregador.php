<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$idempregador = 1; 
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$sql = "UPDATE empregadores 
        SET nome = ?, email = ?, telefone = ?
        WHERE idempregador = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $email, $telefone, $idempregador);

if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso! <a href='../paginas/PerfilEmpregador.php'>Voltar</a>";
} else {
    echo "Erro ao atualizar o perfil: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

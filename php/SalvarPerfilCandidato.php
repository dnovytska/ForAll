<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Capturar os dados do formulário
$idcandidato = 1; // ID do candidato (ajustar conforme necessário)
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$data_nascimento = $_POST['data_nascimento'];
$anos_expriencia = $_POST['experiencia'];
$habilitacoes_academicas = $_POST['habilitacoes'];

// Atualizar os dados no banco
$sql = "UPDATE candidatos 
        SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, anos_expriencia = ?, habilitacoes_academicas = ?
        WHERE idcandidato = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssisi", $nome, $email, $telefone, $data_nascimento, $anos_expriencia, $habilitacoes_academicas, $idcandidato);

if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso! <a href='editar_perfil.php'>Voltar</a>";
} else {
    echo "Erro ao atualizar o perfil: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

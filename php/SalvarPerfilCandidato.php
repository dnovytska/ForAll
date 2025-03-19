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
// O ID do candidato deve ser passado via POST ou GET
$idcandidato = isset($_POST['idcandidato']) ? $_POST['idcandidato'] : 1; // Ajustar conforme necessário

// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $anos_experiencia = $_POST['experiencia'];
    $habilitacoes_academicas = $_POST['habilitacoes_academicas'];

    // Debug: Verifique se os dados estão corretos
    echo "Nome: $nome, Email: $email, Telefone: $telefone, Data de Nascimento: $data_nascimento, Anos de Experiência: $anos_experiencia, Habilitações Acadêmicas: $habilitacoes_academicas<br>";

    // Atualizar os dados no BASE DE DADOS
    $sql = "UPDATE candidatos 
            SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, anos_experiencia = ?, habilitacoes_academicas = ?
            WHERE idcandidato = ?";

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
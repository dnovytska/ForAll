<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root"; // Usuário padrão do MySQL no XAMPP
$password = ""; // Sem senha no XAMPP
$dbname = "psiforall"; // Nome da base de dados que você criou

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $titulo = $_POST['titulo'];
    $responsabilidades = $_POST['responsabilidades'];
    $competencias = $_POST['competencias'];
    $beneficios = $_POST['beneficios'];
    $quantidade = $_POST['quantidade']; // Novo campo quantidade
    $areas_idarea = $_POST['areas_idarea']; // Novo campo areas_idarea
    $localizacoes_idlocalizacao = $_POST['localizacoes_idlocalizacao']; // Novo campo localizacoes_idlocalizacao
    $ordenado = $_POST['ordenado'];
    $nome_empresa = $_POST['nome-empresa']; // Corrigir o nome para o campo correto

    // Garantir que o campo is_ativo tenha valor 1 (ativo)
    $is_ativo = 1; // Presumo que a vaga esteja ativa por padrão (se precisar de um checkbox, modifique isso)

    // Verificar se o idarea existe na tabela areas
    $checkAreaQuery = "SELECT idarea FROM areas WHERE idarea = '$areas_idarea'";
    $result = $conn->query($checkAreaQuery);

    if ($result->num_rows > 0) {
        // Se o idarea existe, insira os dados na tabela empregos
        $sql = "INSERT INTO empregos (titulo, responsabilidades, competencias, beneficios, quantidade, is_ativo, areas_idarea, localizacoes_idlocalizacao, ordenado, idempregador)
                VALUES ('$titulo', '$responsabilidades', '$competencias', '$beneficios', '$quantidade', '$is_ativo', '$areas_idarea', '$localizacoes_idlocalizacao', '$ordenado', 1)";

        if ($conn->query($sql) === TRUE) {
            echo "Novo emprego criado com sucesso!";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erro: O valor de areas_idarea ($areas_idarea) não existe na tabela areas.";
    }
}

// Fechar a conexão
$conn->close();
?>

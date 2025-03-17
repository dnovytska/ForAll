<?php
require_once "./db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); 
    $data_nascimento = $_POST["data_nascimento"];
    $anos_experiencia = $_POST["anos_experiencia"];
    $habilitacoes_academicas = $_POST["habilitacoes_academicas"];
    $is_ativo = 1; 

    $cv_nome = $_FILES["cv"]["name"];
    $cv_temp = $_FILES["cv"]["tmp_name"];
    $cv_destino = "../uploads/" . uniqid() . "_" . $cv_nome;

    if (move_uploaded_file($cv_temp, $cv_destino)) {
        $sql = "INSERT INTO candidatos (nome, email, telefone, password, data_nascimento, anos_experiencia, habilitacoes_academicas, PDF, is_ativo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssissi", $nome, $email, $telefone, $password, $data_nascimento, $anos_experiencia, $habilitacoes_academicas, $cv_destino, $is_ativo);

        if ($stmt->execute()) {
            echo "Registro realizado com sucesso! <a href='../paginas/Login.php'>Faça login</a>";
        } else {
            echo "Erro ao registrar: " . $stmt->error;
        }
    } else {
        echo "Erro no upload do currículo.";
    }
}
?>

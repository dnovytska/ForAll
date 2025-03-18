<?php
require_once "./db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);
    $password = trim($_POST["password"]);
    $data_nascimento = $_POST["data_nascimento"];
    $anos_experiencia = $_POST["anos_experiencia"];
    $habilitacoes_academicas = trim($_POST["habilitacoes_academicas"]);
    $is_ativo = 1; // A conta está ativa por padrão

    // Validação do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de e-mail inválido.";
        exit;
    }

    // Validar a senha (mínimo de 6 caracteres, por exemplo)
    if (strlen($password) < 6) {
        echo "A senha deve ter pelo menos 6 caracteres.";
        exit;
    }

    // Criptografar a senha
    $password_hash = password_hash($password, PASSWORD_DEFAULT); 

    // Verificar e validar o upload do arquivo (CV)
    if (isset($_FILES["cv"]) && $_FILES["cv"]["error"] == 0) {
        $cv_nome = $_FILES["cv"]["name"];
        $cv_temp = $_FILES["cv"]["tmp_name"];
        $cv_destino = "../uploads/" . uniqid() . "_" . $cv_nome;

        // Verificar o tipo de arquivo (apenas PDF)
        $allowed_types = ['application/pdf'];
        if (!in_array($_FILES['cv']['type'], $allowed_types)) {
            echo "O arquivo deve ser um PDF.";
            exit;
        }

        // Limitar o tamanho do arquivo (exemplo: máximo 5MB)
        if ($_FILES['cv']['size'] > 5 * 1024 * 1024) { // 5MB
            echo "O arquivo é muito grande. O tamanho máximo permitido é 5MB.";
            exit;
        }

        // Mover o arquivo para o diretório de uploads
        if (!move_uploaded_file($cv_temp, $cv_destino)) {
            echo "Erro ao fazer upload do currículo.";
            exit;
        }
    } else {
        echo "Nenhum arquivo foi enviado.";
        exit;
    }

    // Preparar o SQL para inserir os dados no banco de dados
    $sql = "INSERT INTO candidatos (nome, email, telefone, password, data_nascimento, anos_experiencia, habilitacoes_academicas, PDF, is_ativo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Erro ao preparar a consulta: " . $conn->error;
        exit;
    }

    // Vincula os parâmetros e executa
    $stmt->bind_param("sssssissi", $nome, $email, $telefone, $password_hash, $data_nascimento, $anos_experiencia, $habilitacoes_academicas, $cv_destino, $is_ativo);

    if ($stmt->execute()) {
        // Redirecionar para a página de login após sucesso
        header("Location: ../paginas/Login.php");
        exit;
    } else {
        echo "Erro ao registrar: " . $stmt->error;
        exit;
    }
}
?>

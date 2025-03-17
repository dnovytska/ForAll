<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulário de Contexto</title>
</head>
<body>
<h2>Formulário de Contexto</h2>
<form action="processar.php" method="POST">
<label for="nome">Nome:</label>
<input type="text" id="nome" name="nome" required>
<label for="email">E-mail:</label>
<input type="email" id="email" name="email" required>
<label for="mensagem">Mensagem:</label>
<textarea id="mensagem" name="mensagem" required></textarea>
<button type="submit">Enviar</button>
</form>
</body>
</html>
<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root"; // Altere conforme necessário
$password = ""; // Altere conforme necessário
$dbname = "formulario_db";
 
// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
 
// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mensagem = htmlspecialchars($_POST['mensagem']);
 
    // Inserir os dados no banco de dados
    $sql = "INSERT INTO respostas (nome, email, mensagem) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $mensagem);
 
    if ($stmt->execute()) {
        // Se salvar no banco com sucesso, enviar o e-mail
        $destinatario = "seuemail@exemplo.com"; // Altere para seu e-mail
        $assunto = "Nova Resposta do Formulário";
        $corpo_email = "Nome: $nome\nE-mail: $email\nMensagem:\n$mensagem\n";
        $headers = "From: $email\r\nReply-To: $email\r\n";
 
        if (mail($destinatario, $assunto, $corpo_email, $headers)) {
            echo "Dados salvos e e-mail enviado com sucesso!";
        } else {
            echo "Dados salvos, mas erro ao enviar e-mail.";
        }
    } else {
        echo "Erro ao salvar os dados no banco.";
    }
 
    $stmt->close();
}
 
$conn->close();
?>

<?php
session_start();
error_reporting(E_ALL); // Ativar relatório de erros
ini_set('display_errors', 1); // Mostrar erros

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($role)) {
        die("Por favor, selecione um tipo de utilizador.");
    }

    // Definir tabela e campo de ID conforme o role
    $table = '';
    $idField = '';
    switch ($role) {
        case 'candidato':
            $table = 'candidatos';
            $idField = 'idcandidato';
            break;
        case 'empregador':
            $table = 'empregadores';
            $idField = 'idempregador';
            break;
        case 'admin':
            $table = 'administradores';
            $idField = 'idadministrador'; // Verifique se o nome está correto no banco!
            break;
        default:
            die("Role inválido.");
    }

    // Buscar usuário pelo email
    $sql = "SELECT $idField, password, nome FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verificar a senha com o hash apenas para candidatos
        if ($role === 'candidato') {
            if (!password_verify($password, $user['password'])) {
                die("Credenciais inválidas."); // Mensagem genérica
            }
        } else {
            // Para empregador e admin, verificar se a senha é igual (sem hash)
            if ($user['password'] !== $password) {
                die("Credenciais inválidas."); // Mensagem genérica
            }
        }

        // Se a autenticação for bem-sucedida
        $_SESSION['user_id'] = $user[$idField];
        $_SESSION['role'] = $role;
        $_SESSION['username'] = $user['nome'];

        // Redirecionamento
        switch ($role) {
            case 'candidato':
                header("Location: ../paginas/PerfilCandidato.php");
                break;
            case 'empregador':
                header("Location: ../paginas/PerfilEmpregador.php");
                break;
            case 'admin':
                header("Location: ../paginas/ListarCandidaturasAdmin.php");
                break;
        }
        exit();
    } else {
        // Mensagem de erro se o usuário não for encontrado
        die("Usuário não encontrado."); // Mensagem de erro
    }
}
?>
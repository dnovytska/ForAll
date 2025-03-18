<?php
session_start();

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
        echo "Por favor, selecione um tipo de utilizador.";
        exit();
    }

    $table = '';
    $idField = ''; // Para armazenar o campo do ID
    if ($role == 'candidato') {
        $table = 'candidatos';
        $idField = 'idcandidato'; // Coluna correta para 'candidato'
    } elseif ($role == 'empregador') {
        $table = 'empregadores';
        $idField = 'idempregador'; // Coluna correta para 'empregador'
    } elseif ($role == 'admin') {
        $table = 'administradores';
        $idField = 'idadministrador'; // Coluna correta para 'admin'
    } else {
        echo "Role inválido.";
        exit();
    }

    // Preparar a consulta
    $sql = "SELECT * FROM $table WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Armazenar os dados do usuário e ID na sessão
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user[$idField];  // Atribui o ID correto conforme o tipo de usuário
        $_SESSION['role'] = $role;

        // Redireciona para a página apropriada
        if ($role == 'candidato') {
            header("Location: ../paginas/PerfilCandidato.php?id=" . $_SESSION['user_id']);
        } elseif ($role == 'empregador') {
            header("Location: ../paginas/PerfilEmpregador.php?id=" . $_SESSION['user_id']);
        } elseif ($role == 'admin') {
            header("Location: ../paginas/ListarCandidaturasAdmin.php");
        }
        exit();
    } else {
        echo "Credenciais inválidas.";
    }
}
?>

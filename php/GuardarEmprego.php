<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o empregador está logado
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'empregador') {
    die("Acesso não autorizado.");
}

// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psiforall";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar e sanitizar os dados
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $responsabilidades = $conn->real_escape_string($_POST['responsabilidades']);
    $competencias = $conn->real_escape_string($_POST['competencias']);
    $beneficios = $conn->real_escape_string($_POST['beneficios']);
    $quantidade = intval($_POST['quantidade']);
    $areas_idarea = intval($_POST['areas_idarea']);
    $localizacoes_idlocalizacao = intval($_POST['localizacoes_idlocalizacao']);
    $ordenado = floatval($_POST['ordenado']);
    $idempregador = $_SESSION['user_id'];
    $is_ativo = 1;

    // Usar prepared statement
    $sql = "INSERT INTO empregos (
            titulo, 
            responsabilidades, 
            competencias, 
            beneficios, 
            quantidade, 
            is_ativo, 
            areas_idarea, 
            localizacoes_idlocalizacao, 
            ordenado, 
            idempregador
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Erro na preparação da query: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssiiidii",
        $titulo,
        $responsabilidades,
        $competencias,
        $beneficios,
        $quantidade,
        $is_ativo,
        $areas_idarea,
        $localizacoes_idlocalizacao,
        $ordenado,
        $idempregador
    );
    // Executar a query
    if ($stmt->execute()) {
        header("Location: ../paginas/VerEmpregos.php");
        exit();
    } else {
        header("Location: ../paginas/CriarEmprego.php?error=1");
        exit();
    }

    $stmt->close();
}

// Fechar a conexão
$conn->close();
?>
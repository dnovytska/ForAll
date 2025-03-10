<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "forall");

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $localizacoes = $_POST['localizacoes'];

    $placeholders = implode(',', array_fill(0, count($localizacoes), '?'));
    $sql = "SELECT * FROM localizacoes WHERE nome IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param(str_repeat('s', count($localizacoes)), ...$localizacoes);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['resultados'] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Nenhum resultado encontrado.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../paginas/ProcurarEmprego.php");
    exit();
}
?>
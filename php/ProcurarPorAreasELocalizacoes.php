<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "forall");

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Captura as localidades selecionadas
    $localizacoes = $_POST['localizacoes'];

    // Prepara a consulta SQL
    $placeholders = implode(',', array_fill(0, count($localizacoes), '?'));
    $sql = "SELECT * FROM localizacoes WHERE nome IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    // Liga os parâmetros
    $stmt->bind_param(str_repeat('s', count($localizacoes)), ...$localizacoes);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se há resultados
    if ($result->num_rows > 0) {
        // Aqui você pode armazenar os resultados em uma sessão ou em um array
        // para usar na página ProcurarEmprego.html
        session_start();
        $_SESSION['resultados'] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Nenhum resultado encontrado.";
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();

    // Redireciona para a página ProcurarEmprego.html
    header("Location: ../paginas/ProcurarEmprego.php");
    exit();
}
?>
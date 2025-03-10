<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "sua_senha", "forall");

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta ao banco de dados com JOINs
$sql = "
    SELECT 
        e.titulo, 
        e.responsabilidades, 
        e.competencias, 
        e.beneficios, 
        e.quantidade, 
        emp.nome AS nome_empregador, 
        a.nome AS nome_area, 
        l.nome AS nome_localizacao 
    FROM 
        empregos e
    JOIN 
        empregadores emp ON e.idempregador = emp.idempregador
    JOIN 
        areas a ON e.areas_idarea = a.idarea
    JOIN 
        localizacoes l ON e.localizacoes_idlocalizacao = l.idlocalizacao
    WHERE 
        e.is_ativo = 1
";

$result = $conn->query($sql);
$empregos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $empregos[] = $row;
    }
}

// Fecha a conexão
$conn->close();

// Retorna os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($empregos);
?>
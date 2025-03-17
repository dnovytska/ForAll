<?php
define("DB_HOST", "localhost");  
define("DB_USER", "root");       
define("DB_PASS", "");           
define("DB_NAME", "psiforall");  

// Conexão com o banco
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>

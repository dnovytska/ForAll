<?php
define("DB_HOST", "localhost");  
define("DB_USER", "root");       
define("DB_PASS", "");           
define("DB_NAME", "psiforall");  

// Conexão com o base
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Erro na conexão com o base de dados: " . $conn->connect_error);
}
?>

<?php
// db.php - Ficheiro de conexão à base de dados

$servername = "localhost";
$username = "root";     // Utilizador padrão do XAMPP
$password = "";         // Senha padrão do XAMPP (vazia)
$dbname = "loja_madeira";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Definir charset para utf8 (para acentos funcionarem bem)
$conn->set_charset("utf8mb4");
?>
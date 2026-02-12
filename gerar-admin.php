<?php
require_once 'db.php';

$email = "admin17@gmail.com";
$password_plana = "Diogomiguel2000,";
$nome = "Diogo";

// Encriptar a password
$password_hash = password_hash($password_plana, PASSWORD_DEFAULT);

// Verificar se já existe
$check = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    echo "O admin já existe!";
} else {
    // Inserir com is_admin = 1
    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, password, is_admin) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("sss", $nome, $email, $password_hash);
    
    if ($stmt->execute()) {
        echo "Administrador criado com sucesso! Já podes apagar este ficheiro.";
    }
}
?>
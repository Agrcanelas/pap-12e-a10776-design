<?php
session_start();
require_once 'db.php';

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validações básicas
    if (empty($nome) || empty($email) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($password !== $confirm_password) {
        $erro = "As passwords não coincidem.";
    } else {
        // Verificar se o email já existe
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = "Este email já está registado.";
        } else {
            // Encriptar a password (MUITO IMPORTANTE)
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Inserir na base de dados
            $insert = $conn->prepare("INSERT INTO clientes (nome, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $nome, $email, $password_hash);

            if ($insert->execute()) {
                $sucesso = "Conta criada com sucesso! <a href='login.php'>Faça login aqui</a>.";
            } else {
                $erro = "Erro ao criar conta. Tente novamente.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar - Artesanato em Madeira</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css"> </head>
<body>
    <?php include 'header.php'; ?>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Criar Conta</h2>
            
            <?php if($erro): ?>
                <div class="alert error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <?php if($sucesso): ?>
                <div class="alert success"><?php echo $sucesso; ?></div>
            <?php else: ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirmar Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-auth">Registar</button>
            </form>
            <p class="auth-link">Já tem conta? <a href="login.php">Entrar</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
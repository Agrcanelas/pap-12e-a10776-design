<?php
session_start();
require_once 'db.php';

// Se já estiver logado, manda para a página inicial
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $erro = "Preencha o email e a password.";
    } else {
        // Buscar utilizador pelo email
        $stmt = $conn->prepare("SELECT id, nome, password, is_admin FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Adiciona o $is_admin aqui no bind_result
            $stmt->bind_result($id, $nome, $hashed_password, $is_admin);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $nome;
                // NOVIDADE: Guardar se é admin na sessão
                $_SESSION['is_admin'] = $is_admin; 
                
                header("Location: produtos.php");
                exit;
            
            } else {
                $erro = "Password incorreta.";
            }
        } else {
            $erro = "Não existe conta com esse email.";
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
    <title>Login - Artesanato em Madeira</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="forest-glow"></div> <?php include 'header.php'; ?>
    
    <div class="auth-container">
        <div class="auth-box">
            <h2>Bem-vindo de volta</h2>
            
            <?php if($erro): ?>
                <div class="alert error"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-auth">Entrar</button>
            </form>
            <p class="auth-link">Ainda não tem conta? <a href="registar.php">Criar conta</a></p>
        </div>
    </div>
</body>
</html>
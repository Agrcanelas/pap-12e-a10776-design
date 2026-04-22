<?php
session_start();
require_once 'db.php';
require_once 'lang.php';

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
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(__('registar_titulo')); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css"> 
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    </head>
<body>
    <?php include 'header.php'; ?>

    <div class="auth-container">
        <div class="auth-box">
            <h2><?php echo htmlspecialchars(__('criar_conta_h2')); ?></h2>
            
            <?php if($erro): ?>
                <div class="alert error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <?php if($sucesso): ?>
                <div class="alert success"><?php echo $sucesso; ?></div>
            <?php else: ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('nome_completo')); ?></label>
                    <input type="text" name="nome" required>
                </div>
                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('email')); ?></label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('password')); ?></label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('confirmar_password')); ?></label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn-auth"><?php echo htmlspecialchars(__('registar')); ?></button>
            </form>
            <p class="auth-link"><?php echo htmlspecialchars(__('ja_tem_conta')); ?> <a href="login.php"><?php echo htmlspecialchars(__('entrar')); ?></a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
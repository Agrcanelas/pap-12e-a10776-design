<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'lang.php';

// Segurança
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php"); 
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    
    $imagem = $_FILES['imagem']['name'];
    $target = "images/produtos/" . basename($imagem);

    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao, imagem, categoria) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $nome, $preco, $descricao, $imagem, $categoria);

    if ($stmt->execute()) {
        $novo_id = $conn->insert_id;

        // Criar tradução PT inicial (fallback/base)
        $stmt_tr = $conn->prepare("INSERT INTO produto_traducoes (produto_id, lang, nome, descricao) VALUES (?, 'pt', ?, ?)");
        $stmt_tr->bind_param("iss", $novo_id, $nome, $descricao);
        $stmt_tr->execute();

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target)) {
            $mensagem = "sucesso";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars(__('admin_new_title')); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    
    <style>
        /* Ajuste para o formulário combinar com o Santuário */
        .auth-box { background: #1a1d15; color: white; border: 1px solid rgba(255,204,51,0.2); }
        .auth-box h2 { color: #ffcc33; font-family: 'Playfair Display', serif; font-style: italic; }
        .form-group label { color: #ccc; }
        .form-group input, .form-group select, .form-group textarea { 
            background: #0a0c08; border: 1px solid #333; color: white; padding: 12px;
        }
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus { 
            background: #0a0c08 !important;  /* Força o fundo preto */
            color: white !important;         /* Força a letra branca */
            border-color: #ffcc33 !important; /* Mantém a borda dourada */
            outline: none;
        }
        .btn-auth { background: #ffcc33; color: #0a0c08; }
        .alert.success { background: #2d361e; color: #a3cf62; padding: 15px; margin-bottom: 20px; border-left: 5px solid #a3cf62; }
    </style>
</head>
<body class="dark-sanctuary">
    <div class="forest-glow"></div> <?php include 'header.php'; ?>
    

    <div class="auth-container">
        <div class="auth-box" style="max-width: 600px;">
            <h2>✨ <?php echo htmlspecialchars(__('admin_new_heading')); ?></h2>
            
            <?php if($mensagem == "sucesso"): ?>
                <div class="alert success">✔ <?php echo htmlspecialchars(__('admin_new_saved_ok')); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('admin_product_name')); ?></label>
                    <input type="text" name="nome" placeholder="<?php echo htmlspecialchars(__('admin_product_name_placeholder')); ?>" required>
                </div>

                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;">
                        <label><?php echo htmlspecialchars(__('admin_price')); ?> (€)</label>
                        <input type="number" step="0.01" name="preco" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label><?php echo htmlspecialchars(__('admin_category')); ?></label>
                        <select name="categoria">
                            <option value="quadros-caixas"><?php echo htmlspecialchars(__('filtro_quadros')); ?></option>
                            <option value="laser"><?php echo htmlspecialchars(__('filtro_laser')); ?></option>
                            <option value="extras"><?php echo htmlspecialchars(__('filtro_extras')); ?></option>
                            <option value="flores"><?php echo htmlspecialchars(__('filtro_flores')); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('admin_description')); ?></label>
                    <textarea name="descricao" rows="10" placeholder="<?php echo htmlspecialchars(__('admin_description_placeholder')); ?>"></textarea>
                </div>

                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('admin_image_upload')); ?></label>
                    <input type="file" name="imagem" accept="image/*" required>
                </div>

                <button type="submit" class="btn-auth"><?php echo htmlspecialchars(__('admin_publish')); ?></button>
                <a href="admin.php" style="display:block; text-align:center; margin-top:20px; color:#ffcc33; text-decoration:none; font-size:0.9rem;">← <?php echo htmlspecialchars(__('admin_back_panel')); ?></a>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
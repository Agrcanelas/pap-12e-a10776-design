<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

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
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target)) {
            $mensagem = "sucesso";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Produtol</title>
    
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
            <h2>✨ Adicionar Novo Produto</h2>
            
            <?php if($mensagem == "sucesso"): ?>
                <div class="alert success">✔ Produto guardado com sucesso!</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" name="nome" placeholder="Ex: Caixa Hamsa Dourada" required>
                </div>

                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;">
                        <label>Preço (€)</label>
                        <input type="number" step="0.01" name="preco" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Categoria</label>
                        <select name="categoria">
                            <option value="quadros-caixas">Quadros e Caixas</option>
                            <option value="laser">Produtos a Laser</option>
                            <option value="extras">Extras</option>
                            <option value="flores">Flores</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrição Detalhada</label>
                    <textarea name="descricao" rows="10" placeholder="Descreve a alma desta peça..."></textarea>
                </div>

                <div class="form-group">
                    <label>Imagem (Upload para images/produtos/)</label>
                    <input type="file" name="imagem" accept="image/*" required>
                </div>

                <button type="submit" class="btn-auth">Publicar Novo Produto</button>
                <a href="admin.php" style="display:block; text-align:center; margin-top:20px; color:#ffcc33; text-decoration:none; font-size:0.9rem;">← Voltar ao Painel</a>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
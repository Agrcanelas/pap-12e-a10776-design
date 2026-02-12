<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// Segurança: Só Admin entra
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php"); 
    exit;
}

$mensagem = "";

// 2. BUSCAR DADOS DO PRODUTO
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM produtos WHERE id = $id");
    $p = $res->fetch_assoc();
    
    if (!$p) {
        header("Location: admin.php");
        exit;
    }
} else {
    header("Location: admin.php");
    exit;
}

// 3. PROCESSAR ATUALIZAÇÃO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    
    // Se escolheu imagem nova
    if (!empty($_FILES['imagem']['name'])) {
        $imagem = $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], "images/produtos/" . $imagem);
    } else {
        $imagem = $p['imagem']; // Mantém a antiga
    }

    $stmt = $conn->prepare("UPDATE produtos SET nome=?, preco=?, descricao=?, imagem=?, categoria=? WHERE id=?");
    $stmt->bind_param("sdsssi", $nome, $preco, $descricao, $imagem, $categoria, $id);
    
    if ($stmt->execute()) {
        $mensagem = "sucesso";
        // Atualizar os dados locais para mostrar no formulário logo a seguir
        $p['nome'] = $nome;
        $p['preco'] = $preco;
        $p['descricao'] = $descricao;
        $p['categoria'] = $categoria;
        $p['imagem'] = $imagem;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Tesouro - Santuário Digital</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    
    <style>
        .auth-box { background: #1a1d15; color: white; border: 1px solid rgba(255,204,51,0.2); }
        .auth-box h2 { color: #ffcc33; font-family: 'Playfair Display', serif; font-style: italic; }
        .form-group label { color: #cccccc; }
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
        .current-img-preview { margin-top: 10px; border: 1px solid #333; padding: 5px; width: 100px; }
    </style>
</head>
<body class="dark-sanctuary">
    <div class="forest-glow"></div>

    <?php include 'header.php'; ?>

    <div class="auth-container">
        <div class="auth-box" style="max-width: 600px;">
            <h2>✏️ Editar: <?php echo htmlspecialchars($p['nome']); ?></h2>
            
            <?php if($mensagem == "sucesso"): ?>
                <div class="alert success">✔ Alterações guardadas com sucesso!</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nome do Produto</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($p['nome']); ?>" required>
                </div>

                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;">
                        <label>Preço (€)</label>
                        <input type="number" step="0.01" name="preco" value="<?php echo $p['preco']; ?>" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Categoria</label>
                        <select name="categoria">
                            <option value="quadros-caixas" <?php echo ($p['categoria'] == 'quadros-caixas') ? 'selected' : ''; ?>>Quadros e Caixas</option>
                            <option value="laser" <?php echo ($p['categoria'] == 'laser') ? 'selected' : ''; ?>>Produtos a Laser</option>
                            <option value="extras" <?php echo ($p['categoria'] == 'extras') ? 'selected' : ''; ?>>Extras</option>
                            <option value="flores" <?php echo ($p['categoria'] == 'flores') ? 'selected' : ''; ?>>Flores</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrição Detalhada</label>
                    <textarea name="descricao" rows="10" placeholder="Descreve a alma desta peça..."></textarea>
                </div>

                <div class="form-group">
                    <label>Imagem (Deixa vazio para manter a atual)</label>
                    <input type="file" name="imagem" accept="image/*">
                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                        <p style="font-size: 0.8rem; color: #888;">Atual:</p>
                        <img src="images/produtos/<?php echo $p['imagem']; ?>" class="current-img-preview">
                    </div>
                </div>

                <button type="submit" class="btn-auth">Guardar Alterações</button>
                <a href="admin.php" style="display:block; text-align:center; margin-top:20px; color:#ffcc33; text-decoration:none; font-size:0.9rem;">← Cancelar e Voltar</a>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'lang.php';

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

$SUPPORTED_LANGS = ['pt', 'en', 'fr', 'es', 'de'];
$trads = [];
$stmt_trads = $conn->prepare("SELECT lang, nome, descricao FROM produto_traducoes WHERE produto_id = ?");
$stmt_trads->bind_param("i", $id);
$stmt_trads->execute();
$res_trads = $stmt_trads->get_result();
while ($row = $res_trads->fetch_assoc()) {
    $trads[$row['lang']] = [
        'nome' => $row['nome'],
        'descricao' => $row['descricao'],
    ];
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

    // Guardar traduções (UPSERT)
    $stmt_upsert = $conn->prepare("
        INSERT INTO produto_traducoes (produto_id, lang, nome, descricao)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            nome = VALUES(nome),
            descricao = VALUES(descricao)
    ");

    foreach ($SUPPORTED_LANGS as $l) {
        $nome_l = trim((string)($_POST['nome_' . $l] ?? ''));
        $desc_l = trim((string)($_POST['descricao_' . $l] ?? ''));

        // Se estiver vazio, não grava (deixa o fallback PT atuar)
        if ($nome_l === '' && $desc_l === '') continue;

        // Garantir que nunca fica um lado vazio por acidente
        if ($nome_l === '') $nome_l = $nome;
        if ($desc_l === '') $desc_l = $descricao;

        $stmt_upsert->bind_param("isss", $id, $l, $nome_l, $desc_l);
        $stmt_upsert->execute();
        $trads[$l] = ['nome' => $nome_l, 'descricao' => $desc_l];
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars(__('admin_edit_title')); ?></title>
    
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
            <h2>✏️ <?php echo htmlspecialchars(__('admin_edit_prefix')); ?> <?php echo htmlspecialchars($p['nome']); ?></h2>
            
            <?php if($mensagem == "sucesso"): ?>
                <div class="alert success">✔ <?php echo htmlspecialchars(__('admin_saved_ok')); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('admin_product_name')); ?></label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($p['nome']); ?>" required>
                </div>

                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;">
                        <label><?php echo htmlspecialchars(__('admin_price')); ?> (€)</label>
                        <input type="number" step="0.01" name="preco" value="<?php echo $p['preco']; ?>" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label><?php echo htmlspecialchars(__('admin_category')); ?></label>
                        <select name="categoria">
                            <option value="quadros-caixas" <?php echo ($p['categoria'] == 'quadros-caixas') ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_quadros')); ?></option>
                            <option value="laser" <?php echo ($p['categoria'] == 'laser') ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_laser')); ?></option>
                            <option value="extras" <?php echo ($p['categoria'] == 'extras') ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_extras')); ?></option>
                            <option value="flores" <?php echo ($p['categoria'] == 'flores') ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_flores')); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('admin_description')); ?></label>
                    <textarea name="descricao" rows="10" placeholder="<?php echo htmlspecialchars(__('admin_description_placeholder')); ?>"><?php echo htmlspecialchars($p['descricao']); ?></textarea>
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <label style="display:block; margin-bottom: 10px;"><?php echo htmlspecialchars(__('admin_translations')); ?></label>

                    <?php foreach ($SUPPORTED_LANGS as $l): 
                        $nome_l = $trads[$l]['nome'] ?? '';
                        $desc_l = $trads[$l]['descricao'] ?? '';
                    ?>
                        <div style="border: 1px solid rgba(255,204,51,0.15); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                            <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 10px;">
                                <strong style="color:#ffcc33;"><?php echo strtoupper($l); ?></strong>
                                <span style="font-size: 0.85rem; opacity: 0.8;"><?php echo htmlspecialchars(__('admin_empty_uses_fallback')); ?></span>
                            </div>
                            <div class="form-group">
                                <label><?php echo htmlspecialchars(__('admin_product_name')); ?> (<?php echo strtoupper($l); ?>)</label>
                                <input type="text" name="nome_<?php echo $l; ?>" value="<?php echo htmlspecialchars($nome_l); ?>" placeholder="<?php echo htmlspecialchars(__('admin_translation_optional')); ?>">
                            </div>
                            <div class="form-group">
                                <label><?php echo htmlspecialchars(__('admin_description')); ?> (<?php echo strtoupper($l); ?>)</label>
                                <textarea name="descricao_<?php echo $l; ?>" rows="5" placeholder="<?php echo htmlspecialchars(__('admin_translation_optional')); ?>"><?php echo htmlspecialchars($desc_l); ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label><?php echo htmlspecialchars(__('admin_image_keep')); ?></label>
                    <input type="file" name="imagem" accept="image/*">
                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                        <p style="font-size: 0.8rem; color: #888;"><?php echo htmlspecialchars(__('admin_current')); ?>:</p>
                        <img src="images/produtos/<?php echo $p['imagem']; ?>" class="current-img-preview">
                    </div>
                </div>

                <button type="submit" class="btn-auth"><?php echo htmlspecialchars(__('admin_save')); ?></button>
                <a href="admin.php" style="display:block; text-align:center; margin-top:20px; color:#ffcc33; text-decoration:none; font-size:0.9rem;">← <?php echo htmlspecialchars(__('admin_cancel_back')); ?></a>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
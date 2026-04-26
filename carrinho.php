<?php require_once 'lang.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(__('carrinho_titulo')); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    <link rel="stylesheet" href="css/carrinho.css">
</head>
<body class="dark-sanctuary">
    <div class="forest-glow"></div>

    <?php include 'header.php'; ?>

    <section class="page-header cart-page-header">
        <div class="container">
            <h1><?php echo htmlspecialchars(__('teu_carrinho')); ?></h1>
            <p><?php echo htmlspecialchars(__('carrinho_subtitulo')); ?></p>
        </div>
    </section>

    <section class="cart-page">
        <div class="container">
            
            <div id="cart-page-empty" class="cart-page-empty" style="display: none;">
                <h2><?php echo htmlspecialchars(__('carrinho_vazio')); ?></h2>
                <a href="produtos.php" class="btn-explorar-produtos"><?php echo htmlspecialchars(__('explorar_produtos')); ?></a>
            </div>

            <div id="cart-page-content" class="cart-page-grid">
                
                <div id="cart-page-items-container"></div>
                
                <aside class="cart-summary">
                    <h3><?php echo htmlspecialchars(__('resumo')); ?></h3>
                    <p id="summary-items-count" class="summary-meta">0 produtos no carrinho</p>
                    <div class="summary-line">
                        <span><?php echo htmlspecialchars(__('itens')); ?></span>
                        <span id="summary-subtotal">0.00€</span>
                    </div>
                    <div class="summary-line">
                        <span><?php echo htmlspecialchars(__('portes')); ?></span>
                        <span id="summary-shipping">4.99€</span>
                    </div>
                    <div class="summary-total">
                        <span><?php echo htmlspecialchars(__('total')); ?></span>
                        <span id="summary-total">0.00€</span>
                    </div>
                    <div class="summary-shipping-progress">
                        <div class="summary-progress-track">
                            <div id="summary-progress-fill" class="summary-progress-fill" style="width: 0%;"></div>
                        </div>
                        <p id="summary-shipping-note" class="summary-shipping-note">Faltam 50.00€ para portes grátis.</p>
                    </div>
                    <button class="btn-finalizar" onclick="finalizarCompra()"><?php echo htmlspecialchars(__('finalizar_pedido')); ?></button>
                    <a href="produtos.php" class="btn-continuar-link">← <?php echo htmlspecialchars(__('continuar_comprar')); ?></a>
                </aside>
                
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/carrinho-page.js"></script>
</body>
</html>
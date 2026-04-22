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

    <section class="page-header" style="height: 40vh; display: flex; align-items: center; justify-content: center; text-align: center; background: none;">
        <div class="container">
            <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 4.5rem; color: #ffcc33; margin: 0;"><?php echo htmlspecialchars(__('teu_carrinho')); ?></h1>
            <p style="font-family: 'Outfit', sans-serif; letter-spacing: 8px; text-transform: uppercase; color: rgba(255,255,255,0.5); margin-top: 10px;"><?php echo htmlspecialchars(__('carrinho_subtitulo')); ?></p>
        </div>
    </section>

    <section class="cart-page">
        <div class="container">
            
            <div id="cart-page-empty" style="display: none; text-align: center; background: white; padding: 80px; box-shadow: 20px 20px 0px #ffcc33; color: #0a0c08;">
                <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-style: italic;"><?php echo htmlspecialchars(__('carrinho_vazio')); ?></h2>
                <a href="produtos.php" style="display: inline-block; margin-top: 30px; background: #0a0c08; color: white; padding: 15px 40px; text-decoration: none; font-family: 'Outfit'; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;"><?php echo htmlspecialchars(__('explorar_produtos')); ?></a>
            </div>

            <div id="cart-page-content" class="cart-page-grid">
                
                <div id="cart-page-items-container"></div>
                
                <aside class="cart-summary">
                    <h3><?php echo htmlspecialchars(__('resumo')); ?></h3>
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
                    <button class="btn-finalizar" onclick="finalizarCompra()"><?php echo htmlspecialchars(__('finalizar_pedido')); ?></button>
                    <a href="produtos.php" style="display: block; text-align: center; margin-top: 20px; color: #0a0c08; font-family: 'Outfit'; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">← <?php echo htmlspecialchars(__('continuar_comprar')); ?></a>
                </aside>
                
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/carrinho-page.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Santuário Digital</title>
    
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
            <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 4.5rem; color: #ffcc33; margin: 0;">O Teu Carrinho</h1>
            <p style="font-family: 'Outfit', sans-serif; letter-spacing: 8px; text-transform: uppercase; color: rgba(255,255,255,0.5); margin-top: 10px;">Peças selecionadas para o teu espaço</p>
        </div>
    </section>

    <section class="cart-page">
        <div class="container">
            
            <div id="cart-page-empty" style="display: none; text-align: center; background: white; padding: 80px; box-shadow: 20px 20px 0px #ffcc33; color: #0a0c08;">
                <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; font-style: italic;">O teu santuário está vazio</h2>
                <a href="produtos.php" style="display: inline-block; margin-top: 30px; background: #0a0c08; color: white; padding: 15px 40px; text-decoration: none; font-family: 'Outfit'; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">Explorar Produtos</a>
            </div>

            <div id="cart-page-content" class="cart-page-grid">
                
                <div id="cart-page-items-container"></div>
                
                <aside class="cart-summary">
                    <h3>Resumo</h3>
                    <div class="summary-line">
                        <span>Produtos</span>
                        <span id="summary-subtotal">0.00€</span>
                    </div>
                    <div class="summary-line">
                        <span>Portes</span>
                        <span id="summary-shipping">4.99€</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span id="summary-total">0.00€</span>
                    </div>
                    <button class="btn-finalizar" onclick="finalizarCompra()">Finalizar Pedido</button>
                    <a href="produtos.php" style="display: block; text-align: center; margin-top: 20px; color: #0a0c08; font-family: 'Outfit'; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">← Continuar a Comprar</a>
                </aside>
                
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/carrinho-page.js"></script>
</body>
</html>
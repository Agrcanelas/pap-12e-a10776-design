<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Artesanato em Madeira</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    <link rel="stylesheet" href="css/carrinho.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Cabeçalho da Página -->
    <section class="page-header">
        <div class="container">
            <h1>🛒 O Teu Carrinho</h1>
            <p>Revê os teus produtos antes de finalizar a compra</p>
        </div>
    </section>

    <!-- Carrinho Completo -->
    <section class="cart-page">
        <div class="container">
            <div class="cart-page-grid">
                
                <!-- Lista de Produtos -->
                <div class="cart-page-items">
                    <div id="cart-page-empty" class="cart-page-empty">
                        <div class="cart-empty-icon">🛒</div>
                        <h2>O teu carrinho está vazio</h2>
                        <p>Adiciona produtos incríveis ao teu carrinho!</p>
                        <a href="produtos.php" class="btn-primary">Ver Produtos</a>
                    </div>
                    
                    <div id="cart-page-content" style="display: none;">
                        <h2>Produtos no Carrinho</h2>
                        <div id="cart-page-items-list" class="cart-page-list">
                            <!-- Produtos inseridos dinamicamente -->
                        </div>
                    </div>
                </div>
                
                <!-- Resumo do Pedido -->
                <div id="cart-page-summary" class="cart-page-summary" style="display: none;">
                    <h3>Resumo do Pedido</h3>
                    
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">€0.00</span>
                    </div>
                    
                    <div class="summary-line">
                        <span>Envio</span>
                        <span id="summary-shipping">€4.99</span>
                    </div>
                    
                    <div class="summary-line shipping-note">
                        <small>🎉 Envio grátis a partir de €50</small>
                    </div>
                    
                    <hr>
                    
                    <div class="summary-total">
                        <span>Total</span>
                        <span id="summary-total">€0.00</span>
                    </div>
                    
                    <button class="btn-finalizar" onclick="finalizarCompra()">
                        Finalizar Compra
                    </button>
                    
                    <a href="produtos.php" class="btn-continuar-link">
                        ← Continuar a Comprar
                    </a>
                </div>
                
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/carrinho-page.js"></script>
</body>
</html>
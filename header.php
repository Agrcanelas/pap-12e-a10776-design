<?php
// Detectar a página atual
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<header>
    <nav class="navbar">
        <div class="logo">
            <h1>🌳 Artesanato Natural</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="<?php echo ($pagina_atual == 'index.php') ? 'active' : ''; ?>">Início</a></li>
            <li><a href="produtos.php" class="<?php echo ($pagina_atual == 'produtos.php') ? 'active' : ''; ?>">Produtos</a></li>
            <li><a href="contacto.php" class="<?php echo ($pagina_atual == 'contacto.php') ? 'active' : ''; ?>">Contacto</a></li>
            <li><a href="#" onclick="toggleCartDrawer(); return false;" class="cart-link <?php echo ($pagina_atual == 'carrinho.php') ? 'active' : ''; ?>">🛒 Carrinho (<span id="cart-count">0</span>)</a></li>
        </ul>
    </nav>
</header>

<!-- Overlay escuro -->
<div id="cart-overlay" class="cart-overlay" onclick="toggleCartDrawer()"></div>

<!-- Drawer do Carrinho -->
<div id="cart-drawer" class="cart-drawer">
    <!-- Cabeçalho -->
    <div class="cart-drawer-header">
        <h2>🛒 O Teu Carrinho</h2>
        <button class="cart-close-btn" onclick="toggleCartDrawer()">×</button>
    </div>
    
    <!-- Conteúdo -->
    <div class="cart-drawer-content">
        <!-- Carrinho Vazio -->
        <div id="cart-empty" class="cart-empty">
            <div class="cart-empty-icon">🛒</div>
            <p>O teu carrinho está vazio</p>
            <button class="btn-continuar-compras" onclick="toggleCartDrawer()">Continuar a Comprar</button>
        </div>
        
        <!-- Items do Carrinho -->
        <div id="cart-items-container" class="cart-items" style="display: none;">
            <!-- Os produtos serão inseridos aqui dinamicamente -->
        </div>
    </div>
    
    <!-- Rodapé -->
    <div class="cart-drawer-footer" style="display: none;">
        <!-- Barra de Progresso para Envio Grátis -->
        <div class="cart-progress">
            <p class="cart-progress-text">Faltam €20.00 para envio grátis</p>
            <div class="cart-progress-bar">
                <div class="cart-progress-fill" style="width: 0%"></div>
            </div>
        </div>
        
        <!-- Subtotal -->
        <div class="cart-subtotal">
            <span class="cart-subtotal-label">Subtotal:</span>
            <span class="cart-subtotal-value">€0.00</span>
        </div>
        
        <p class="cart-shipping-note">Portes e impostos calculados no checkout</p>
        
        <!-- Botões de Ação -->
        <div class="cart-actions">
            <button class="btn-view-cart" onclick="window.location.href='carrinho.php'">Ver Carrinho</button>
            <button class="btn-checkout" onclick="window.location.href='carrinho.php'">Checkout</button>
        </div>
    </div>
</div>
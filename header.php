<?php
// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar a página atual
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<header>
    <nav class="navbar">
    <div class="logo">
        <a href="index.php" style="text-decoration:none; color:inherit;">
            <h1>🌳 Artesanato Natural</h1>
        </a>
    </div>

    <ul class="nav-links main-nav">
        <li><a href="index.php" class="<?php echo ($pagina_atual == 'index.php') ? 'active' : ''; ?>">Início</a></li>
        <li><a href="produtos.php" class="<?php echo ($pagina_atual == 'produtos.php') ? 'active' : ''; ?>">Produtos</a></li>
    </ul>

    <ul class="nav-links user-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="user-greeting">Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
            <li><a href="logout.php" class="nav-btn-logout">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="<?php echo ($pagina_atual == 'login.php') ? 'active' : ''; ?>">Login</a></li>
        <?php endif; ?>

        <li>
            <a href="javascript:void(0);" onclick="toggleCartDrawer()" class="cart-link">
                🛒 Carrinho (<span id="cart-count">0</span>)
            </a>
        </li>
    </ul>
</nav>
</header>

<div id="cart-overlay" class="cart-overlay" onclick="toggleCartDrawer()"></div>

<div id="cart-drawer" class="cart-drawer">
    <div class="cart-drawer-header">
        <h2>🛒 O Teu Carrinho</h2>
        <button class="cart-close-btn" onclick="toggleCartDrawer()">×</button>
    </div>
    
    <div id="cart-empty" class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <p>O teu carrinho está vazio</p>
        <button class="btn-continuar-compras" onclick="toggleCartDrawer()">Continuar a Comprar</button>
    </div>
    
    <div id="cart-items-container" class="cart-items" style="display: none;"></div>
    
    <div class="cart-drawer-footer" style="display: none;">
        <div class="cart-subtotal">
            <span class="cart-subtotal-label">Subtotal:</span>
            <span class="cart-subtotal-value">0.00€</span>
        </div>
        
        <div class="cart-progress">
             <p class="cart-progress-text"></p>
             <div class="cart-progress-bar">
                 <div class="cart-progress-fill" style="width: 0%"></div>
             </div>
        </div>

        <p class="cart-shipping-note">Portes e impostos calculados no checkout</p>
        <div class="cart-actions">
            <a href="carrinho.php" class="btn-checkout">Ver Carrinho Completo</a>
        </div>
    </div>
</div>
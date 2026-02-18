<?php
// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar a página atual para marcar o link como "active"
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

    <li class="search-item">
        <form action="produtos.php" method="GET" class="search-form" autocomplete="off">
            <input type="text" id="live-search" name="q" placeholder="Procurar..." 
               value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit">🔍</button>
        
            <div id="search-results" class="search-dropdown"></div>
        </form>
    </li>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <li>
                <a href="admin.php" class="<?php echo ($pagina_atual == 'admin.php') ? 'active' : ''; ?>" 
                   style="color: #ffcc33; font-weight: bold; border: 1px dashed #ffcc33; padding: 5px 12px; border-radius: 4px; margin-left: 10px;">
                   ⚙️ Painel de Admin
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <ul class="nav-links user-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="user-greeting">Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
            <li><a href="logout.php" class="nav-btn-logout">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="<?php echo ($pagina_atual == 'login.php') ? 'active' : ''; ?>">Login</a></li>
        <?php endif; ?>
    
        <li>
            <a href="javascript:void(0);" onclick="toggleCartDrawer()" class="cart-trigger">
                🛒(<span id="cart-count">0</span>)
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
        
        <div class="cart-actions" style="margin-top: 20px;">
            <a href="carrinho.php" class="btn-view-cart" style="text-align: center; text-decoration: none; display: block; margin-bottom: 10px;">Ver Carrinho Completo</a>
        </div>
    </div>
</div>
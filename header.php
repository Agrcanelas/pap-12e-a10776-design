<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lang.php';

// Detectar a página atual para marcar o link como "active"
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<header>
    <nav class="navbar">
    <div class="logo">
        <a href="index.php" style="text-decoration:none; color:inherit;">
            <h1>🌳 <?php echo htmlspecialchars(__('site_nome')); ?></h1>
        </a>
    </div>

    <ul class="nav-links main-nav">
        <li><a href="index.php" class="<?php echo ($pagina_atual == 'index.php') ? 'active' : ''; ?>"><?php echo htmlspecialchars(__('inicio')); ?></a></li>
        <li><a href="produtos.php" class="<?php echo ($pagina_atual == 'produtos.php') ? 'active' : ''; ?>"><?php echo htmlspecialchars(__('produtos')); ?></a></li>
        
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <li>
                <a href="admin.php" class="<?php echo ($pagina_atual == 'admin.php') ? 'active' : ''; ?>" 
                   style="color: #ffcc33; font-weight: bold; border: 1px dashed #ffcc33; padding: 5px 12px; border-radius: 4px; margin-left: 10px;">
                   ⚙️ <?php echo htmlspecialchars(__('admin_panel')); ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <ul class="nav-links user-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="user-greeting"><?php echo htmlspecialchars(__('ola', ['name' => (string)$_SESSION['user_name']])); ?></span></li>
            <li><a href="logout.php" class="nav-btn-logout"><?php echo htmlspecialchars(__('logout')); ?></a></li>
        <?php else: ?>
            <li><a href="login.php" class="<?php echo ($pagina_atual == 'login.php') ? 'active' : ''; ?>"><?php echo htmlspecialchars(__('login')); ?></a></li>
        <?php endif; ?>

        <li class="lang-item">
            <button id="lang-btn" class="lang-btn">🌐 <?php echo strtoupper($lang); ?></button>
        <div id="lang-dropdown" class="lang-dropdown">
            <a href="<?php echo htmlspecialchars(lang_url('pt')); ?>">🇵🇹 Português</a>
            <a href="<?php echo htmlspecialchars(lang_url('en')); ?>">🇬🇧 English</a>
            <a href="<?php echo htmlspecialchars(lang_url('es')); ?>">🇪🇸 Español</a>
            <a href="<?php echo htmlspecialchars(lang_url('fr')); ?>">🇫🇷 Français</a>
            <a href="<?php echo htmlspecialchars(lang_url('de')); ?>">🇩🇪 Deutsch</a>
        </div>
    </li>
    
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
        <h2>🛒 <?php echo htmlspecialchars(__('teu_carrinho')); ?></h2>
        <button class="cart-close-btn" onclick="toggleCartDrawer()">×</button>
    </div>
    
    <div id="cart-empty" class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <p><?php echo htmlspecialchars(__('carrinho_vazio')); ?></p>
        <button class="btn-continuar-compras" onclick="toggleCartDrawer()"><?php echo htmlspecialchars(__('continuar_comprar')); ?></button>
    </div>
    
    <div id="cart-items-container" class="cart-items" style="display: none;"></div>
    
    <div class="cart-drawer-footer" style="display: none;">
        <div class="cart-progress">
            <div class="cart-progress-text"><?php echo htmlspecialchars(t('js_free_shipping_remaining', ['amount' => '50.00'])); ?></div>
            <div class="cart-progress-bar">
                <div class="cart-progress-fill" style="width: 0%;"></div>
            </div>
        </div>

        <div class="cart-subtotal">
            <span class="cart-subtotal-label"><?php echo htmlspecialchars(__('subtotal')); ?></span>
            <span class="cart-subtotal-value">0.00€</span>
        </div>
        
        <div class="cart-actions" style="margin-top: 20px;">
            <a href="carrinho.php" class="btn-view-cart" style="text-align: center; text-decoration: none; display: block; margin-bottom: 10px;"><?php echo htmlspecialchars(__('ver_carrinho_completo')); ?></a>
        </div>
    </div>
</div>

<button id="reviews-toggle-btn" class="reviews-toggle-btn" type="button" aria-label="Abrir reviews">
    ★ Reviews
</button>

<div id="reviews-overlay" class="reviews-overlay" hidden></div>
<aside id="reviews-panel" class="reviews-panel" aria-hidden="true">
    <div class="reviews-panel-header">
        <h3>Reviews dos clientes</h3>
        <button id="reviews-close-btn" class="reviews-close-btn" type="button" aria-label="Fechar reviews">×</button>
    </div>

    <div class="reviews-toolbar">
        <label for="reviews-sort">Ordenar por</label>
        <select id="reviews-sort">
            <option value="recent">Mais recentes</option>
            <option value="rating">Melhor avaliação</option>
        </select>
    </div>

    <div id="reviews-list" class="reviews-list"></div>
</aside>

<script>
window.I18N = window.I18N || {};
window.I18N.cartRemoved = <?php echo json_encode(t('js_cart_removed', ['name' => '{name}'])); ?>;
window.I18N.cartAdded = <?php echo json_encode(t('js_cart_added', ['name' => '{name}'])); ?>;
window.I18N.freeShippingCongrats = <?php echo json_encode(t('js_free_shipping_congrats')); ?>;
window.I18N.freeShippingRemaining = <?php echo json_encode(t('js_free_shipping_remaining', ['amount' => '{amount}'])); ?>;
window.I18N.confirmDelete = <?php echo json_encode(t('js_confirm_delete')); ?>;
window.I18N.freeLabel = <?php echo json_encode(t('js_free')); ?>;
window.I18N.cartEmptyAlert = <?php echo json_encode(t('js_cart_empty_alert')); ?>;
window.I18N.processing = <?php echo json_encode(t('js_processing')); ?>;
window.I18N.orderSuccess = <?php echo json_encode(t('js_order_success')); ?>;
window.I18N.orderErrorPrefix = <?php echo json_encode(t('js_order_error_prefix')); ?>;
window.I18N.serverError = <?php echo json_encode(t('js_server_error')); ?>;
window.I18N.reviewsLoadError = 'Não foi possível carregar as reviews.';
</script>
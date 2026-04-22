<?php require_once 'lang.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(__('site_nome')); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Secção Hero - Floresta em Ecrã Completo -->
    <section class="hero-fullscreen">
        <div class="forest-background">
            
            <div class="welcome-text">
                <h2><?php echo htmlspecialchars(__('bem_vindo')); ?></h2>
                <p><?php echo htmlspecialchars(__('escolha_caminho')); ?></p>
            </div>

            <!-- Caminho da Esquerda - Quadros e Caixas -->
            <a href="produtos.php?categoria=quadros-caixas" class="path-clickable path-left">
                <div class="path-pillar"></div>
            </a>

            <!-- Caminho da Direita - Produtos a Laser -->
            <a href="produtos.php?categoria=laser" class="path-clickable path-right">
                <div class="path-pillar"></div>
            </a>

            <!-- Placas no Centro -->
            <div class="signs-container">
                <div class="sign-left-pointer">
                    <div class="sign-arrow">
                        <span><?php echo htmlspecialchars(__('quadros_caixas')); ?></span>
                    </div>
                </div>
                <div class="sign-right-pointer">
                    <div class="sign-arrow">
                        <span><?php echo htmlspecialchars(__('produtos_laser')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="js/main.js"></script>
</body>
</html>
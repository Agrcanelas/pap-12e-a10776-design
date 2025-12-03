<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artesanato em Madeira - Produtos Personalizados</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Secção Hero - Floresta em Ecrã Completo -->
    <section class="hero-fullscreen">
        <div class="forest-background">
            
            <div class="welcome-text">
                <h2>Bem-vindo à Nossa Loja</h2>
                <p>Escolha o seu caminho</p>
            </div>

            <!-- Placas no Centro -->
            <div class="signs-container">
                <div class="sign-left-pointer">
                    <div class="sign-arrow">
                        <span>← Quadros e Caixas</span>
                    </div>
                </div>
                <div class="sign-right-pointer">
                    <div class="sign-arrow">
                        <span>Produtos a Laser →</span>
                    </div>
                </div>
            </div>

            <!-- Caminho da Esquerda - Quadros e Caixas -->
            <a href="produtos.php?categoria=quadros-caixas" class="path-clickable path-left">
                <div class="path-pillar"></div>
            </a>

            <!-- Caminho da Direita - Produtos a Laser -->
            <a href="produtos.php?categoria=laser" class="path-clickable path-right">
                <div class="path-pillar"></div>
            </a>
        </div>
    </section>
    
    <script src="js/main.js"></script>
</body>
</html>
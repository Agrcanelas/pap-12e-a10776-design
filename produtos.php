<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Artesanato em Madeira</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/produtos.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
</head>
<body>
    <!-- Cabeçalho -->
    <?php include 'header.php'; ?>

  


    <!-- Cabeçalho da Página -->
    <section class="page-header">
        <div class="container">
            <h1>Os Nossos Produtos</h1>
            <p>Peças únicas em madeira, feitas à mãos</p>
        </div>
    </section>

    <!-- Filtro de Categorias -->
    <section class="category-filter">
        <div class="container">
            <button class="filter-btn active" data-category="todos">Todos</button>
            <button class="filter-btn" data-category="quadros-caixas">Quadros e Caixas</button>
            <button class="filter-btn" data-category="laser">Produtos a Laser</button>
            <button class="filter-btn" data-category="extras">Extras</button>
        </div>
    </section>

    <!-- Lista de Produtos -->
    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                
                <!-- PRODUTOS A LASER -->
                
                <!-- Produto 1 - Ganesha -->
                <div class="product-card" data-category="laser">
                    <div class="product-image">
                        <img src="images/produtos/ganesha-madeira.jpg" alt="Ganesha em Madeira">
                        <span class="product-badge">Novo</span>
                        <span class="product-badge badge-personalizavel" style="top: 55px;">Personalizável</span>
                    </div>
                    <div class="product-info">
                        <span class="product-category">Produtos a Laser</span>
                        <h3>Ganesha em Madeira</h3>
                        <p class="product-description">Figura decorativa de Ganesha com detalhes intrincados cortados a laser</p>
                        <p class="price">8.90€</p>
                        <button class="btn-add-cart" onclick="addToCart('Ganesha em Madeira', 8.90)">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>

                <!-- Produto 2 - Mandala Yin Yang -->
                <div class="product-card" data-category="laser">
                    <div class="product-image">
                        <img src="images/produtos/mandala-yin-yang.jpg" alt="Mandala Yin Yang">
                        <span class="product-badge badge-personalizavel">Personalizável</span>
                    </div>
                    <div class="product-info">
                        <span class="product-category">Produtos a Laser</span>
                        <h3>Mandala Yin Yang</h3>
                        <p class="product-description">Mandala decorativa com símbolo Yin Yang em madeira natural</p>
                        <p class="price">9.90€</p>
                        <button class="btn-add-cart" onclick="addToCart('Mandala Yin Yang', 9.90)">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>

                <!-- Produto 3 - Globo Neve Natal -->
                <div class="product-card" data-category="laser">
                    <div class="product-image">
                        <img src="images/produtos/globo-neve-natal.jpg" alt="Globo de Neve Natal 2025">
                        <span class="product-badge badge-personalizavel">Personalizável</span>
                    </div>
                    <div class="product-info">
                        <span class="product-category">Produtos a Laser</span>
                        <h3>Globo de Neve Natal</h3>
                        <p class="product-description">Decoração natalícia personalizada com nome da família</p>
                        <p class="price">7.50€</p>
                        <button class="btn-add-cart" onclick="addToCart('Globo de Neve Natal', 7.50)">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>

                <!-- QUADROS E CAIXAS -->
                
                <!-- Produto 4 - Caixa Hamsa -->
                <div class="product-card" data-category="quadros-caixas">
                    <div class="product-image">
                        <img src="images/produtos/caixa-hamsa.jpg" alt="Caixa Decorativa Hamsa">
                        <span class="product-badge badge-personalizavel">Personalizável</span>
                    </div>
                    <div class="product-info">
                        <span class="product-category">Quadros e Caixas</span>
                        <h3>Caixa Decorativa Hamsa</h3>
                        <p class="product-description">Caixa em madeira com símbolo Hamsa gravado e suporte decorativo</p>
                        <p class="price">29.90€</p>
                        <button class="btn-add-cart" onclick="addToCart('Caixa Decorativa Hamsa', 29.90)">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>

                <!-- EXTRAS -->

                <!-- Produto 5 - Porta-chaves Puzzle -->
                <div class="product-card" data-category="extras">
                    <div class="product-image">
                        <img src="images/produtos/porta-chaves-puzzle.jpg" alt="Porta-chaves Puzzle">
                        <span class="product-badge badge-personalizavel">Personalizável</span>
                    </div>
                    <div class="product-info">
                        <span class="product-category">Extras</span>
                        <h3>Porta-chaves Puzzle</h3>
                        <p class="product-description">Conjunto de 4 porta-chaves em puzzle com nomes personalizados</p>
                        <p class="price">19.90€</p>
                        <button class="btn-add-cart" onclick="addToCart('Porta-chaves Puzzle', 19.90)">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
</body>



</html> 
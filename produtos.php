<?php
// Incluir conexão à base de dados
require_once 'db.php';

// Consulta para buscar todos os produtos
$sql = "SELECT * FROM produtos ORDER BY id ASC";
$result = $conn->query($sql);
?>
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
    <?php include 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1>Os Nossos Produtos</h1>
            <p>Peças únicas em madeira, feitas à mão</p>
        </div>
    </section>

    <section class="category-filter">
        <div class="container">
            <button class="filter-btn active" data-category="todos">Todos</button>
            <button class="filter-btn" data-category="quadros-caixas">Quadros e Caixas</button>
            <button class="filter-btn" data-category="laser">Produtos a Laser</button>
            <button class="filter-btn" data-category="extras">Extras</button>
        </div>
    </section>

    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                
                <?php
                if ($result->num_rows > 0) {
                    // Array para converter o código da categoria em texto bonito
                    $nomes_categorias = [
                        'laser' => 'Produtos a Laser',
                        'quadros-caixas' => 'Quadros e Caixas',
                        'extras' => 'Extras'
                    ];

                    // Loop através de cada produto na base de dados
                    while($row = $result->fetch_assoc()) {
                        $nome = $row["nome"];
                        $preco = $row["preco"];
                        $imagem = $row["imagem"];
                        $descricao = $row["descricao"];
                        $categoria_db = $row["categoria"];
                        
                        // Obter nome bonito da categoria (ou usar o original se não existir no array)
                        $categoria_texto = isset($nomes_categorias[$categoria_db]) ? $nomes_categorias[$categoria_db] : $categoria_db;

                        // Lógica para os Badges (Novo / Personalizável)
                        $badges_html = '';
                        $offset_style = ''; // Estilo para baixar o segundo badge se o primeiro existir
                        
                        // Badge "Novo"
                        if ($row["novo"]) {
                            $badges_html .= '<span class="product-badge">Novo</span>';
                            // Se tem o badge Novo, o próximo badge precisa de descer
                            $offset_style = 'style="top: 55px;"';
                        }
                        
                        // Badge "Personalizável"
                        if ($row["personalizavel"]) {
                            $badges_html .= '<span class="product-badge badge-personalizavel" ' . $offset_style . '>Personalizável</span>';
                        }
                ?>
                
                <div class="product-card" data-category="<?php echo $categoria_db; ?>">
                    <div class="product-image">
                        <img src="images/produtos/<?php echo $imagem; ?>" alt="<?php echo $nome; ?>">
                        <?php echo $badges_html; ?>
                    </div>
                    <div class="product-info">
                        <span class="product-category"><?php echo $categoria_texto; ?></span>
                        <h3><?php echo $nome; ?></h3>
                        <p class="product-description"><?php echo $descricao; ?></p>
                        <p class="price"><?php echo number_format($preco, 2); ?>€</p>
                        <button class="btn-add-cart" onclick="addToCart('<?php echo addslashes($nome); ?>', <?php echo $preco; ?>)">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
                <?php
                    } // Fim do while
                } else {
                    echo "<p>Ainda não há produtos cadastrados.</p>";
                }
                
                // Fechar conexão (boa prática, embora o PHP feche automaticamente no fim do script)
                $conn->close();
                ?>

            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html> 
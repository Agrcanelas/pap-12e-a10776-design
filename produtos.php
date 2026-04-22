<?php
require_once 'db.php';
require_once 'lang.php';
require_once 'i18n_produtos.php';

// 1. Capturar o termo de pesquisa
$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';

// 2. Preparar a SQL baseada na pesquisa
if (!empty($pesquisa)) {
    // Usamos o operador LIKE com % para encontrar correspondências parciais
    $termo = "%$pesquisa%";
    $stmt = $conn->prepare("
        SELECT
            p.*,
            tl.nome AS nome_i18n,
            tl.descricao AS descricao_i18n,
            tpt.nome AS nome_pt,
            tpt.descricao AS descricao_pt
        FROM produtos p
        LEFT JOIN produto_traducoes tl
            ON tl.produto_id = p.id AND tl.lang = ?
        LEFT JOIN produto_traducoes tpt
            ON tpt.produto_id = p.id AND tpt.lang = 'pt'
        WHERE (
            CONVERT(COALESCE(tl.nome, tpt.nome, p.nome) USING utf8mb4) COLLATE utf8mb4_general_ci
            LIKE
            CONVERT(? USING utf8mb4) COLLATE utf8mb4_general_ci
        )
        ORDER BY p.id ASC
    ");
    $stmt->bind_param("ss", $lang, $termo);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Se não houver pesquisa, mostra tudo como dantes
    $stmt = $conn->prepare("
        SELECT
            p.*,
            tl.nome AS nome_i18n,
            tl.descricao AS descricao_i18n,
            tpt.nome AS nome_pt,
            tpt.descricao AS descricao_pt
        FROM produtos p
        LEFT JOIN produto_traducoes tl
            ON tl.produto_id = p.id AND tl.lang = ?
        LEFT JOIN produto_traducoes tpt
            ON tpt.produto_id = p.id AND tpt.lang = 'pt'
        ORDER BY p.id ASC
    ");
    $stmt->bind_param("s", $lang);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(__('produtos_titulo')); ?></title>
    
   <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/produtos.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
</head>
<body>
    <div class="forest-glow"></div>
<div class="floating-embers"></div>
    <?php include 'header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1><?php echo htmlspecialchars(__('nossos_produtos')); ?></h1>
        </div>
    </section>

   <section class="category-filter">
    <div class="container">
        <button class="filter-btn active" data-category="todos"><?php echo htmlspecialchars(__('todos')); ?></button>
        <button class="filter-btn" data-category="quadros-caixas"><?php echo htmlspecialchars(__('filtro_quadros')); ?></button>
        <button class="filter-btn" data-category="laser"><?php echo htmlspecialchars(__('filtro_laser')); ?></button>
        <button class="filter-btn" data-category="extras"><?php echo htmlspecialchars(__('filtro_extras')); ?></button>
        <button class="filter-btn" data-category="flores"><?php echo htmlspecialchars(__('filtro_flores')); ?></button>
    </div>
</section>

    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                
                <?php
                if ($result->num_rows > 0) {
                    // Array para converter o código da categoria em texto bonito
                     $nomes_categorias = [
                        'laser' => __('filtro_laser'),
                        'quadros-caixas' => __('filtro_quadros'),
                        'imanes' => 'Ímanes', // <--- ADICIONAR ESTA LINHA
                        'extras' => __('filtro_extras'),
                        'flores' => __('filtro_flores'),
                    ];

                    // Loop através de cada produto na base de dados
                    while($row = $result->fetch_assoc()) {
                        $nome = produto_texto($row, 'nome');
                        $preco = $row["preco"];
                        $imagem = $row["imagem"];
                        $descricao = produto_texto($row, 'descricao');
                        $categoria_db = $row["categoria"];
                        
                        // Obter nome bonito da categoria (ou usar o original se não existir no array)
                        $categoria_texto = isset($nomes_categorias[$categoria_db]) ? $nomes_categorias[$categoria_db] : $categoria_db;

                        // Lógica para os Badges (Novo / Personalizável)
                        $badges_html = '';
                        $offset_style = ''; // Estilo para baixar o segundo badge se o primeiro existir
                        
                      
                        
                        // Badge "Personalizável"
                        if ($row["personalizavel"]) {
                            $badges_html .= '<span class="product-badge badge-personalizavel" ' . $offset_style . '>' . htmlspecialchars(__('personalizavel')) . '</span>';
                        }
                ?>
                            
                <div class="product-card" data-category="<?php echo $categoria_db; ?>">
                <a href="produtos-detalhes.php?id=<?php echo $row['id']; ?>" class="product-link">
                    <div class="product-image">
                        <img src="images/produtos/<?php echo $imagem; ?>" alt="<?php echo $nome; ?>">
                        <?php echo $badges_html; ?>
                    </div>
                </a>

                <div class="product-info">
                    <span class="product-category"><?php echo $categoria_texto; ?></span>
                    
                    <a href="produtos-detalhes.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                        <h3><?php echo $nome; ?></h3>
                    </a>
                    
                    <p class="product-description"><?php echo $descricao; ?></p>
                    <p class="price"><?php echo number_format($preco, 2); ?>€</p>
                    
                    <button class="btn-add-cart" onclick="addToCart('<?php echo addslashes($nome); ?>', <?php echo $preco; ?>, '<?php echo $imagem; ?>')">
                        <?php echo htmlspecialchars(__('add_carrinho')); ?>
                    </button>
                </div>
            </div>
                <?php
                    } // Fim do while
                } else {
                    echo "<p>" . htmlspecialchars(__('sem_produtos')) . "</p>";
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
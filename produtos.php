<?php
require_once 'db.php';
require_once 'lang.php';
require_once 'i18n_produtos.php';

// 1. Capturar filtros
$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';
$min_preco = isset($_GET['min']) && $_GET['min'] !== '' ? (float)$_GET['min'] : null;
$max_preco = isset($_GET['max']) && $_GET['max'] !== '' ? (float)$_GET['max'] : null;
$categoria = isset($_GET['categoria']) ? trim((string)$_GET['categoria']) : '';
$sort = isset($_GET['sort']) ? (string)$_GET['sort'] : 'recent';

$CATEGORIAS_SUPORTADAS = ['quadros-caixas', 'laser', 'extras', 'flores', 'imanes'];
if ($categoria !== '' && !in_array($categoria, $CATEGORIAS_SUPORTADAS, true)) {
    $categoria = '';
}

// 2. ORDER BY seguro
$orderBy = "p.id DESC";
switch ($sort) {
    case 'price_asc':
        $orderBy = "p.preco ASC";
        break;
    case 'price_desc':
        $orderBy = "p.preco DESC";
        break;
    case 'name_asc':
        $orderBy = "COALESCE(tl.nome, tpt.nome, p.nome) ASC";
        break;
    case 'name_desc':
        $orderBy = "COALESCE(tl.nome, tpt.nome, p.nome) DESC";
        break;
    case 'recent':
    default:
        $orderBy = "p.id DESC";
        break;
}

// 3. Montar SQL + binds dinamicamente
$sql = "
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
";

$where = [];
$types = "s";
$params = [$lang];

if ($pesquisa !== '') {
    $where[] = "(
        CONVERT(COALESCE(tl.nome, tpt.nome, p.nome) USING utf8mb4) COLLATE utf8mb4_general_ci
        LIKE
        CONVERT(? USING utf8mb4) COLLATE utf8mb4_general_ci
    )";
    $types .= "s";
    $params[] = "%" . $pesquisa . "%";
}

if ($categoria !== '') {
    $where[] = "p.categoria = ?";
    $types .= "s";
    $params[] = $categoria;
}

if ($min_preco !== null) {
    $where[] = "p.preco >= ?";
    $types .= "d";
    $params[] = $min_preco;
}

if ($max_preco !== null) {
    $where[] = "p.preco <= ?";
    $types .= "d";
    $params[] = $max_preco;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY $orderBy";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
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

    <section class="products-tools">
        <div class="container">
            <form method="GET" class="products-tools-form" autocomplete="off">
                <?php if (isset($_GET['lang'])): ?>
                    <input type="hidden" name="lang" value="<?php echo htmlspecialchars((string)$_GET['lang']); ?>">
                <?php endif; ?>
                <div class="tool-search">
                    <span class="tool-search-icon">⌕</span>
                    <input
                        type="text"
                        name="q"
                        value="<?php echo htmlspecialchars($pesquisa); ?>"
                        placeholder="<?php echo htmlspecialchars(__('filter_search')); ?>"
                    >
                </div>

                <div class="tool-group">
                    <label for="min-price"><?php echo htmlspecialchars(__('filter_min_price')); ?></label>
                    <input id="min-price" type="number" step="0.01" name="min" value="<?php echo $min_preco !== null ? htmlspecialchars((string)$min_preco) : ''; ?>" placeholder="0.00">
                </div>

                <div class="tool-group">
                    <label for="max-price"><?php echo htmlspecialchars(__('filter_max_price')); ?></label>
                    <input id="max-price" type="number" step="0.01" name="max" value="<?php echo $max_preco !== null ? htmlspecialchars((string)$max_preco) : ''; ?>" placeholder="99.99">
                </div>

                <div class="tool-group">
                    <label for="categoria"><?php echo htmlspecialchars(__('filter_category')); ?></label>
                    <select id="categoria" name="categoria">
                        <option value="" <?php echo $categoria === '' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('category_all')); ?></option>
                        <option value="quadros-caixas" <?php echo $categoria === 'quadros-caixas' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_quadros')); ?></option>
                        <option value="laser" <?php echo $categoria === 'laser' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_laser')); ?></option>
                        <option value="extras" <?php echo $categoria === 'extras' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_extras')); ?></option>
                        <option value="flores" <?php echo $categoria === 'flores' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('filtro_flores')); ?></option>
                        <option value="imanes" <?php echo $categoria === 'imanes' ? 'selected' : ''; ?>>Ímanes</option>
                    </select>
                </div>

                <div class="tool-group">
                    <label for="sort"><?php echo htmlspecialchars(__('filter_sort')); ?></label>
                    <select id="sort" name="sort">
                        <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('sort_recent')); ?></option>
                        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('sort_price_asc')); ?></option>
                        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('sort_price_desc')); ?></option>
                        <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('sort_name_asc')); ?></option>
                        <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>><?php echo htmlspecialchars(__('sort_name_desc')); ?></option>
                    </select>
                </div>

                <div class="tool-actions">
                    <button type="submit" class="tools-apply"><?php echo htmlspecialchars(__('filter_apply')); ?></button>
                    <a class="tools-reset" href="produtos.php<?php echo isset($_GET['lang']) ? ('?lang=' . urlencode((string)$_GET['lang'])) : ''; ?>"><?php echo htmlspecialchars(__('filter_reset')); ?></a>
                </div>
            </form>
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
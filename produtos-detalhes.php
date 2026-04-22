<?php
// No topo de produtos-detalhes.php
require_once 'db.php';
require_once 'lang.php';
require_once 'i18n_produtos.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Usar a variável $conn que está dentro do db.php
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
    WHERE p.id = ?
    LIMIT 1
");
$stmt->bind_param("si", $lang, $id);
$stmt->execute();
$resultado = $stmt->get_result();
$produto = $resultado ? $resultado->fetch_assoc() : null;

if (!$produto) { header("Location: produtos.php"); exit; }

$nome = produto_texto($produto, 'nome');
$descricao = produto_texto($produto, 'descricao');
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($nome); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    
    <link rel="stylesheet" href="css/produtos-detalhes.css">
</head>
<body class="dark-sanctuary">
    <div class="forest-glow"></div>
    <?php include 'header.php'; ?>

    <main class="details-container">
        <div class="container grid-details">
            <div class="product-hero-img">
                <img src="images/produtos/<?php echo $produto['imagem']; ?>" alt="<?php echo htmlspecialchars($nome); ?>">
            </div>

            <div class="product-hero-info">
                <p class="category-tag"></p>
                <h1><?php echo htmlspecialchars($nome); ?></h1>
                <div class="price-big"><?php echo number_format($produto['preco'], 2); ?>€</div>
                
                <div class="description">
                    <p><?php echo htmlspecialchars($descricao); ?></p>
                </div>

                <button class="btn-add-cart-huge" 
                        onclick="addToCart('<?php echo addslashes($nome); ?>', <?php echo $produto['preco']; ?>, '<?php echo $produto['imagem']; ?>')">
                    <?php echo htmlspecialchars(__('add_carrinho')); ?>
                </button>
            </div>
        </div>
    </main>

   <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
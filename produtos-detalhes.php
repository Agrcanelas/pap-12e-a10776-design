<?php
// No topo de produtos-detalhes.php
require_once 'db.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Usar a variável $conn que está dentro do db.php
$query = "SELECT * FROM produtos WHERE id = $id";
$resultado = $conn->query($query);
$produto = $resultado->fetch_assoc();

if (!$produto) { header("Location: produtos.php"); exit; }
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo $produto['nome']; ?> </title>
    
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
                <img src="images/produtos/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
            </div>

            <div class="product-hero-info">
                <p class="category-tag"></p>
                <h1><?php echo $produto['nome']; ?></h1>
                <div class="price-big"><?php echo number_format($produto['preco'], 2); ?>€</div>
                
                <div class="description">
                    <p><?php echo $produto['descricao']; ?></p>
                </div>

                <button class="btn-add-cart-huge" 
                        onclick="addToCart('<?php echo $produto['nome']; ?>', <?php echo $produto['preco']; ?>, '<?php echo $produto['imagem']; ?>')">
                    Adicionar ao Carrinho
                </button>
            </div>
        </div>
    </main>

   <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
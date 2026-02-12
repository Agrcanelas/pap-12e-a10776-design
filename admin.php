<?php
// 1. LIMPEZA TOTAL: Começa logo na linha 1 sem espaços
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// SEGURANÇA: Só Admin entra
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

// Lógica para Remover Produto
$mensagem = "";
if (isset($_GET['remover'])) {
    $id_remover = intval($_GET['remover']);
    // Primeiro, podíamos apagar a imagem da pasta, mas para já vamos focar na BD
    if ($conn->query("DELETE FROM produtos WHERE id = $id_remover")) {
        $mensagem = "removido";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Gestão de Produtos</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;600&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/carrinho-drawer.css">
    
    <style>
        /* Estilo Dark para a Tabela de Admin */
        .admin-container { padding: 60px 0; min-height: 80vh; }
        
        .admin-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px;
        }

        .admin-header h1 { 
            font-family: 'Playfair Display', serif; 
            font-style: italic; 
            color: #ffcc33; 
            font-size: 3rem;
        }

        .admin-table { 
            width: 100%; 
            border-collapse: collapse; 
            background: #1a1d15; 
            color: white;
            border: 1px solid rgba(255, 204, 51, 0.1);
            box-shadow: 15px 15px 0px rgba(0,0,0,0.5);
        }

        .admin-table th { 
            background: #0a0c08; 
            color: #ffcc33; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            padding: 20px;
            font-size: 1.0rem;
            border-bottom: 2px solid #ffcc33;
        }

        .admin-table td { 
            padding: 20px; 
            border-bottom: 1px solid #2a2d25;
            font-family: 'Outfit', sans-serif;
        }

        .admin-table tr:hover { background: #22261c; }

        .img-admin { 
            width: 150px; 
            height: 200px; 
            object-fit: cover; 
            border: 1px solid #ffcc33; 
        }

        .btn-new { 
            background: #ffcc33; 
            color: #0a0c08; 
            padding: 12px 25px; 
            text-decoration: none; 
            font-weight: bold; 
            text-transform: uppercase;
            transition: 0.3s;
        }

        .btn-new:hover { background: white; transform: translateY(-3px); }

        .action-links a { color: #ccc; text-decoration: none; margin-right: 15px; font-size: 1.2rem; }
        .action-links a:hover { color: #ffcc33; }
        .action-links .delete { color: #ff4444; }
        .action-links .delete:hover { color: white; background: #ff4444; padding: 2px 5px; }

        .alert-mini { background: #2d361e; color: #a3cf62; padding: 15px; margin-bottom: 20px; border-left: 5px solid #a3cf62; }
    </style>
</head>
<body class="dark-sanctuary">
    <div class="forest-glow"></div>

    <?php include 'header.php'; ?>

    <div class="container admin-container">
        
        <div class="admin-header">
            <h1>Gestão de Produtos</h1>
            <a href="adicionar-produto.php" class="btn-new">Novo Produto +</a>
        </div>

        <?php if($mensagem == "removido"): ?>
            <div class="alert-mini">✔ O produto foi removido (removido com sucesso).</div>
        <?php endif; ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $produtos = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
                while($p = $produtos->fetch_assoc()):
                ?>
                <tr>
                    <td><img src="images/produtos/<?php echo $p['imagem']; ?>" class="img-admin"></td>

                    <td style="font-weight: 500; font-size: 1.3rem;"><?php echo htmlspecialchars($p['nome']); ?></td>

                    <td style="font-size: 1.2rem;"><?php echo number_format($p['preco'], 2); ?>€</td>
                    
                    <td style="opacity: 0.7; font-size: 1.3rem;"><?php echo $p['categoria']; ?></td>

                    <td class="action-links">
                        <a href="editar-produto.php?id=<?php echo $p['id']; ?>">✏️ Editar</a>
                        <a href="admin.php?remover=<?php echo $p['id']; ?>" class="delete" onclick="return confirm('Tem a certeza que quer apagar esta peça?')">🗑️ Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
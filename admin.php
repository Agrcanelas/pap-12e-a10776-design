<?php
session_start();
require_once 'db.php';

// SEGURANÇA: Só entra quem estiver logado E for admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Verificar se é admin na BD
$user_id = $_SESSION['user_id'];
$res = $conn->query("SELECT is_admin FROM clientes WHERE id = $user_id");
$user = $res->fetch_assoc();

if (!$user || $user['is_admin'] != 1) {
    die("Acesso Negado. Esta área é apenas para guardiões do santuário.");
}

// Lógica para Remover Produto
if (isset($_GET['remover'])) {
    $id_remover = intval($_GET['remover']);
    $conn->query("DELETE FROM produtos WHERE id = $id_remover");
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Gestão - Santuário Digital</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; color: #333; }
        .admin-table th, .admin-table td { padding: 15px; border: 1px solid #ddd; text-align: left; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; padding: 40px 0; }
    </style>
</head>
<body class="dark-sanctuary">
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="admin-header">
            <h1>Gestão de Produtos</h1>
            <a href="adicionar-produto.php" class="btn-add-cart" style="position:static;">+ Novo Produto</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $produtos = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
                while($p = $produtos->fetch_assoc()):
                ?>
                <tr>
                    <td>#<?php echo $p['id']; ?></td>
                    <td><img src="images/produtos/<?php echo $p['imagem']; ?>" width="50"></td>
                    <td><?php echo $p['nome']; ?></td>
                    <td><?php echo number_format($p['preco'], 2); ?>€</td>
                    <td>
                        <a href="editar-produto.php?id=<?php echo $p['id']; ?>">Editar</a> | 
                        <a href="admin.php?remover=<?php echo $p['id']; ?>" class="btn-delete" onclick="return confirm('Tem a certeza?')">Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
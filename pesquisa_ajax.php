<?php
require_once 'db.php';

$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($pesquisa)) {
    $termo = "%$pesquisa%";
    $stmt = $conn->prepare("SELECT id, nome, imagem FROM produtos WHERE nome LIKE ? LIMIT 5");
    $stmt->bind_param("s", $termo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<a href='produtos-detalhes.php?id=" . $row['id'] . "' class='search-result-item'>";
            echo "<img src='images/produtos/" . $row['imagem'] . "' width='30'>";
            echo "<span>" . htmlspecialchars($row['nome']) . "</span>";
            echo "</a>";
        }
    } else {
        echo "<div class='no-results'>Nenhuma peça encontrada...</div>";
    }
}
?>
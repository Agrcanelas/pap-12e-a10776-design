<?php
require_once 'db.php';
require_once 'lang.php';
require_once 'i18n_produtos.php';

$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($pesquisa)) {
    $termo = "%$pesquisa%";
    $stmt = $conn->prepare("
        SELECT
            p.id,
            p.imagem,
            tl.nome AS nome_i18n,
            tpt.nome AS nome_pt,
            p.nome
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
        ORDER BY p.id DESC
        LIMIT 5
    ");
    $stmt->bind_param("ss", $lang, $termo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nome = produto_texto($row, 'nome');
            echo "<a href='produtos-detalhes.php?id=" . $row['id'] . "' class='search-result-item'>";
            echo "<img src='images/produtos/" . $row['imagem'] . "' width='30'>";
            echo "<span>" . htmlspecialchars($nome) . "</span>";
            echo "</a>";
        }
    } else {
        echo "<div class='no-results'>" . htmlspecialchars(__('pesquisa_sem_resultados')) . "</div>";
    }
}
?>
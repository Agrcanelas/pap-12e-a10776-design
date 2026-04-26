<?php
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

$sort = isset($_GET['sort']) ? (string)$_GET['sort'] : 'recent';
$orderBy = $sort === 'rating'
    ? 'rating DESC, created_at DESC'
    : 'created_at DESC, rating DESC';

function fallback_reviews(): array {
    return [
        ['customer_name' => 'Maddie C.', 'rating' => 5, 'comment' => 'Qualidade excelente e envio rápido. Recomendo!', 'product_name' => 'Vaso Decorativo Minimalista', 'created_at' => '2026-04-20 14:00:00'],
        ['customer_name' => 'Brayden M.', 'rating' => 4, 'comment' => 'Peça muito bonita e acabamento impecável.', 'product_name' => 'Caixa Listrada', 'created_at' => '2026-04-18 10:30:00'],
        ['customer_name' => 'Lewis R.', 'rating' => 5, 'comment' => 'Ainda mais bonito ao vivo, adorei.', 'product_name' => 'Mandala Yin Yang', 'created_at' => '2026-04-17 09:10:00'],
        ['customer_name' => 'Ari S.', 'rating' => 5, 'comment' => 'Ótimo presente e atendimento excelente.', 'product_name' => 'Árvore de Natal Intrincada', 'created_at' => '2026-04-15 19:20:00'],
    ];
}

try {
    $reviews = [];
    $tableCheck = $conn->query("SHOW TABLES LIKE 'reviews'");

    if ($tableCheck && $tableCheck->num_rows > 0) {
        $sql = "
            SELECT
                customer_name,
                rating,
                comment,
                product_name,
                created_at
            FROM reviews
            ORDER BY $orderBy
            LIMIT 60
        ";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
    }

    if (!$reviews) {
        $reviews = fallback_reviews();
        usort($reviews, function ($a, $b) use ($sort) {
            if ($sort === 'rating') {
                if ((int)$a['rating'] === (int)$b['rating']) {
                    return strcmp($b['created_at'], $a['created_at']);
                }
                return (int)$b['rating'] <=> (int)$a['rating'];
            }
            return strcmp($b['created_at'], $a['created_at']);
        });
    }

    echo json_encode(['success' => true, 'reviews' => $reviews], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao carregar reviews.'], JSON_UNESCAPED_UNICODE);
}

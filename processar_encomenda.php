<?php
// processar_encomenda.php
header('Content-Type: application/json');
require_once 'db.php';

// Iniciar sessão para saber se o utilizador está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Receber os dados brutos (JSON) enviados pelo Javascript
$json = file_get_contents('php://input');
$dados = json_decode($json, true);

// Verificar se há dados
if (!$dados || empty($dados['produtos'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Carrinho vazio ou dados inválidos.']);
    exit;
}

// 2. Calcular totais (Segurança: recalcular no servidor)
$total_compra = 0;
foreach ($dados['produtos'] as $item) {
    $total_compra += ($item['preco'] * $item['quantidade']);
}

// Adicionar portes se for menos de 50€ (Regra de negócio)
$portes = ($total_compra >= 50) ? 0 : 4.99;
$total_final = $total_compra + $portes;

// 3. Inserir a Encomenda (Cabeçalho)
// CORREÇÃO: Incluímos a coluna 'portes' que é obrigatória na tua BD
$sql_encomenda = "INSERT INTO encomendas (valor_total, portes, estado) VALUES (?, ?, 'Pendente')";
$stmt = $conn->prepare($sql_encomenda);

// "dd" significa que vamos passar dois números decimais (Double)
$stmt->bind_param("dd", $total_final, $portes);

if ($stmt->execute()) {
    // Recuperar o ID da encomenda que acabámos de criar
    $id_encomenda = $conn->insert_id;

    // 4. Inserir os Itens da Encomenda
    $sql_item = "INSERT INTO itens_encomenda (encomenda_id, produto_nome, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    foreach ($dados['produtos'] as $item) {
        $subtotal_item = $item['preco'] * $item['quantidade'];
        // "isidd" = Integer, String, Integer, Double, Double
        $stmt_item->bind_param("isidd", 
            $id_encomenda, 
            $item['nome'], 
            $item['quantidade'], 
            $item['preco'], 
            $subtotal_item
        );
        $stmt_item->execute();
    }

    echo json_encode([
        'sucesso' => true, 
        'mensagem' => 'Encomenda registada com sucesso! ID: ' . $id_encomenda
    ]);

} else {
    // Se chegar aqui, houve um erro no banco de dados
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'Erro ao gravar na base de dados: ' . $conn->error
    ]);
}

$conn->close();
?>
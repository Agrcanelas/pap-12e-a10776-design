<?php
// processar_encomenda.php
session_start(); // Iniciar sessão para aceder ao ID do cliente
header('Content-Type: application/json');
require_once 'db.php';

// Habilitar reporte de erros para debugging (apenas durante desenvolvimento)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// 1. Verificar se o utilizador está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sessão expirou. Por favor faça login novamente.']);
    exit;
}

$cliente_id = $_SESSION['user_id'];

// 2. Receber os dados do JSON
$json = file_get_contents('php://input');
$dados = json_decode($json, true);

if (!$dados || empty($dados['produtos'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Carrinho vazio ou dados inválidos.']);
    exit;
}

// 3. Recalcular totais (Segurança)
$total_compra = 0;
foreach ($dados['produtos'] as $item) {
    $total_compra += ($item['preco'] * $item['quantidade']);
}

// Calcular portes (Regra: Grátis >= 50€)
$portes = ($total_compra >= 50) ? 0.00 : 4.99;
$total_final = $total_compra + $portes;

// 4. Inserir a Encomenda
// ATENÇÃO: A ordem dos campos tem de bater certo com os valores
$sql_encomenda = "INSERT INTO encomendas (cliente_id, valor_total, portes, estado) VALUES (?, ?, ?, 'Pendente')";
$stmt = $conn->prepare($sql_encomenda);

if (!$stmt) {
    // Se falhar a preparação (erro de SQL), mostra qual é
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro SQL Prepare: ' . $conn->error]);
    exit;
}

// "idd" significa: Integer (id), Double (total), Double (portes)
$stmt->bind_param("idd", $cliente_id, $total_final, $portes);

if ($stmt->execute()) {
    $id_encomenda = $conn->insert_id;

    // 5. Inserir os Itens
    $sql_item = "INSERT INTO itens_encomenda (encomenda_id, produto_nome, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    foreach ($dados['produtos'] as $item) {
        $subtotal_item = $item['preco'] * $item['quantidade'];
        // "isidd": int, string, int, double, double
        $stmt_item->bind_param("isidd", $id_encomenda, $item['nome'], $item['quantidade'], $item['preco'], $subtotal_item);
        $stmt_item->execute();
    }

    echo json_encode(['sucesso' => true, 'mensagem' => 'Encomenda registada com sucesso! ID: ' . $id_encomenda]);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao executar venda: ' . $stmt->error]);
}

$conn->close();
?>
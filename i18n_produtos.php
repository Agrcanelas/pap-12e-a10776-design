<?php
declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'lang.php';

/**
 * Vai buscar o melhor texto disponível para o produto:
 * 1) tradução no idioma atual
 * 2) fallback PT
 * 3) campo original em `produtos` (compatibilidade)
 */
function produto_texto(array $row, string $field): string {
    // Esperado: nome/descricao
    if ($field === 'nome') {
        return (string)($row['nome_i18n'] ?? $row['nome_pt'] ?? $row['nome'] ?? '');
    }
    if ($field === 'descricao') {
        return (string)($row['descricao_i18n'] ?? $row['descricao_pt'] ?? $row['descricao'] ?? '');
    }
    return '';
}


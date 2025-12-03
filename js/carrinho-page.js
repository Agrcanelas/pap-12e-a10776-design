// Mapa de imagens dos produtos
const imagensProdutos = {
    'Ganesha em Madeira': 'ganesha-madeira.jpg',
    'Mandala Yin Yang': 'mandala-yin-yang.jpg',
    'Globo de Neve Natal': 'globo-neve-natal.jpg',
    'Caixa Decorativa Hamsa': 'caixa-hamsa.jpg',
    'Porta-chaves Puzzle': 'porta-chaves-puzzle.jpg'
};

// Função para obter imagem do produto
function obterImagemProduto(nomeProduto) {
    return imagensProdutos[nomeProduto] || 'caixa-hamsa.jpg';
}

// Renderizar página completa do carrinho
function renderizarCarrinhoCompleto() {
    const carrinhoEmpty = document.getElementById('cart-page-empty');
    const carrinhoContent = document.getElementById('cart-page-content');
    const carrinhoSummary = document.getElementById('cart-page-summary');
    const carrinhoList = document.getElementById('cart-page-items-list');
    
    if (!carrinhoEmpty || !carrinhoContent) return;
    
    if (carrinho.length === 0) {
        carrinhoEmpty.style.display = 'block';
        carrinhoContent.style.display = 'none';
        carrinhoSummary.style.display = 'none';
    } else {
        carrinhoEmpty.style.display = 'none';
        carrinhoContent.style.display = 'block';
        carrinhoSummary.style.display = 'block';
        
        // Renderizar items
        carrinhoList.innerHTML = carrinho.map((item, index) => {
            const imagemPath = item.imagem ? `images/produtos/${item.imagem}` : `images/produtos/${obterImagemProduto(item.nome)}`;
            
            return `
            <div class="cart-page-item">
                <div class="cart-page-item-image">
                    <img src="${imagemPath}" alt="${item.nome}">
                </div>
                <div class="cart-page-item-info">
                    <h3 class="cart-page-item-name">${item.nome}</h3>
                    <p class="cart-page-item-price">${item.preco.toFixed(2)}€ cada</p>
                    <div class="cart-page-item-quantity">
                        <button class="qty-btn-page" onclick="alterarQuantidade(${index}, -1)">-</button>
                        <span class="qty-number-page">${item.quantidade}</span>
                        <button class="qty-btn-page" onclick="alterarQuantidade(${index}, 1)">+</button>
                    </div>
                </div>
                <div class="cart-page-item-actions">
                    <p class="cart-page-item-total">${(item.preco * item.quantidade).toFixed(2)}€</p>
                    <button class="btn-remove-page" onclick="removerDoCarrinho(${index})">
                        Remover
                    </button>
                </div>
            </div>
            `;
        }).join('');
        
        // Atualizar resumo
        atualizarResumo();
    }
}

// Atualizar resumo do pedido
function atualizarResumo() {
    const subtotal = carrinho.reduce((total, item) => total + (item.preco * item.quantidade), 0);
    const freeShippingThreshold = 50;
    const shippingCost = subtotal >= freeShippingThreshold ? 0 : 4.99;
    const total = subtotal + shippingCost;
    
    // Atualizar valores
    document.getElementById('summary-subtotal').textContent = `€${subtotal.toFixed(2)}`;
    
    const shippingElement = document.getElementById('summary-shipping');
    if (shippingCost === 0) {
        shippingElement.textContent = 'Grátis';
        shippingElement.style.color = '#6b8e23';
        shippingElement.style.fontWeight = 'bold';
    } else {
        shippingElement.textContent = `€${shippingCost.toFixed(2)}`;
        shippingElement.style.color = '';
        shippingElement.style.fontWeight = '';
    }
    
    document.getElementById('summary-total').textContent = `€${total.toFixed(2)}`;
}

// Finalizar compra
function finalizarCompra() {
    if (carrinho.length === 0) {
        mostrarNotificacao('O carrinho está vazio!');
        return;
    }
    
    // Aqui podes adicionar lógica de checkout real
    alert('Função de checkout a ser implementada!');
    
    // Por agora, apenas mostra uma mensagem
    mostrarNotificacao('A processar o teu pedido...');
}

// Carregar carrinho quando a página está pronta
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se estamos na página do carrinho
    if (document.getElementById('cart-page-content')) {
        renderizarCarrinhoCompleto();
    }
});
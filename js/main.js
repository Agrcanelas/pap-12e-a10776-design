// Carrinho de Compras
let carrinho = [];

// Atualizar contador do carrinho
function atualizarContadorCarrinho() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = carrinho.length;
    }
}

// Adicionar produto ao carrinho
function addToCart(nomeProduto, preco) {
    const produto = {
        nome: nomeProduto,
        preco: preco,
        quantidade: 1
    };
    
    // Verificar se produto já existe no carrinho
    const produtoExistente = carrinho.find(item => item.nome === nomeProduto);
    
    if (produtoExistente) {
        produtoExistente.quantidade++;
    } else {
        carrinho.push(produto);
    }
    
    // Guardar no localStorage
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    
    // Atualizar contador
    atualizarContadorCarrinho();
    
    // Mostrar feedback visual
    mostrarNotificacao(`${nomeProduto} adicionado ao carrinho!`);
}

// Mostrar notificação
function mostrarNotificacao(mensagem) {
    // Remover notificação anterior se existir
    const notificacaoExistente = document.querySelector('.notificacao');
    if (notificacaoExistente) {
        notificacaoExistente.remove();
    }
    
    // Criar nova notificação
    const notificacao = document.createElement('div');
    notificacao.className = 'notificacao';
    notificacao.textContent = mensagem;
    notificacao.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background-color: #6b8e23;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notificacao);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notificacao.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notificacao.remove(), 300);
    }, 3000);
}

// Adicionar CSS das animações
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Filtro de produtos por categoria
document.addEventListener('DOMContentLoaded', function() {
    // Carregar carrinho do localStorage
    const carrinhoSalvo = localStorage.getItem('carrinho');
    if (carrinhoSalvo) {
        carrinho = JSON.parse(carrinhoSalvo);
        atualizarContadorCarrinho();
    }
    
    // Verificar se estamos na página de produtos
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    if (filterButtons.length > 0) {
        // Verificar se há categoria na URL
        const urlParams = new URLSearchParams(window.location.search);
        const categoriaUrl = urlParams.get('categoria');
        
        if (categoriaUrl) {
            // Ativar filtro baseado na URL
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            if (categoriaUrl === 'caixas') {
                document.querySelector('[data-category="quadros-caixas"]')?.classList.add('active');
                filtrarProdutos('quadros-caixas');
            } else if (categoriaUrl === 'quadros') {
                document.querySelector('[data-category="quadros-caixas"]')?.classList.add('active');
                filtrarProdutos('quadros-caixas');
            } else if (categoriaUrl === 'laser') {
                document.querySelector('[data-category="laser"]')?.classList.add('active');
                filtrarProdutos('laser');
            }
        }
        
        // Event listeners para os botões de filtro
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remover classe active de todos os botões
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Adicionar classe active ao botão clicado
                this.classList.add('active');
                
                // Obter categoria
                const categoria = this.getAttribute('data-category');
                
                // Filtrar produtos
                filtrarProdutos(categoria);
            });
        });
    }
});

// Função para filtrar produtos
function filtrarProdutos(categoria) {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        if (categoria === 'todos') {
            card.classList.remove('hidden');
        } else {
            const cardCategoria = card.getAttribute('data-category');
            if (cardCategoria === categoria) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        }
    });
}
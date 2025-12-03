// Carrinho de Compras
let carrinho = [];

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

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada!');
    
    // Carregar carrinho do localStorage
    const carrinhoSalvo = localStorage.getItem('carrinho');
    if (carrinhoSalvo) {
        carrinho = JSON.parse(carrinhoSalvo);
        
        // Corrigir produtos antigos sem imagem
        carrinho = carrinho.map(item => {
            if (!item.imagem) {
                item.imagem = obterImagemProduto(item.nome);
            }
            return item;
        });
        
        // Salvar carrinho corrigido
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        
        console.log('Carrinho carregado e corrigido:', carrinho);
    }
    
    atualizarContadorCarrinho();
    
    // Configurar filtros de produtos (se existirem)
    configurarFiltros();
});

// Atualizar contador do carrinho
function atualizarContadorCarrinho() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        const totalItems = carrinho.reduce((total, item) => total + item.quantidade, 0);
        cartCount.textContent = totalItems;
        console.log('Contador atualizado:', totalItems);
    }
}

// Abrir/Fechar Drawer do Carrinho
function toggleCartDrawer() {
    console.log('Toggle drawer chamado');
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    
    if (drawer && overlay) {
        const isActive = drawer.classList.contains('active');
        
        if (isActive) {
            drawer.classList.remove('active');
            overlay.classList.remove('active');
        } else {
            drawer.classList.add('active');
            overlay.classList.add('active');
            renderizarCarrinho();
        }
    } else {
        console.error('Drawer ou overlay não encontrado!');
    }
}

// Renderizar carrinho no drawer
function renderizarCarrinho() {
    console.log('Renderizando carrinho:', carrinho);
    
    const carrinhoContent = document.getElementById('cart-items-container');
    const carrinhoEmpty = document.getElementById('cart-empty');
    const carrinhoFooter = document.querySelector('.cart-drawer-footer');
    
    if (!carrinhoContent || !carrinhoEmpty) {
        console.error('Elementos do carrinho não encontrados');
        return;
    }
    
    if (carrinho.length === 0) {
        carrinhoEmpty.style.display = 'block';
        carrinhoContent.style.display = 'none';
        if (carrinhoFooter) carrinhoFooter.style.display = 'none';
    } else {
        carrinhoEmpty.style.display = 'none';
        carrinhoContent.style.display = 'block';
        if (carrinhoFooter) carrinhoFooter.style.display = 'block';
        
        // Renderizar items
        carrinhoContent.innerHTML = carrinho.map((item, index) => {
            const imagemPath = item.imagem ? `images/produtos/${item.imagem}` : `images/produtos/${obterImagemProduto(item.nome)}`;
            console.log('Renderizando item:', item.nome, 'com imagem:', imagemPath);
            
            return `
            <div class="cart-item">
                <div class="cart-item-image">
                    <img src="${imagemPath}" alt="${item.nome}" onerror="console.error('Erro ao carregar imagem:', this.src)">
                </div>
                <div class="cart-item-details">
                    <h4 class="cart-item-name">${item.nome}</h4>
                    <p class="cart-item-price">${item.preco.toFixed(2)}€</p>
                    <div class="cart-item-quantity">
                        <button class="qty-btn" onclick="alterarQuantidade(${index}, -1)">-</button>
                        <span class="qty-number">${item.quantidade}</span>
                        <button class="qty-btn" onclick="alterarQuantidade(${index}, 1)">+</button>
                    </div>
                </div>
                <button class="cart-item-remove" onclick="removerDoCarrinho(${index})" title="Remover">
                    ×
                </button>
            </div>
            `;
        }).join('');
        
        // Atualizar subtotal
        atualizarSubtotal();
    }
}

// Alterar quantidade de produto
function alterarQuantidade(index, mudanca) {
    if (carrinho[index]) {
        carrinho[index].quantidade += mudanca;
        
        if (carrinho[index].quantidade <= 0) {
            carrinho.splice(index, 1);
        }
        
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        atualizarContadorCarrinho();
        renderizarCarrinho();
    }
}

// Remover produto do carrinho
function removerDoCarrinho(index) {
    const nomeProduto = carrinho[index].nome;
    carrinho.splice(index, 1);
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarContadorCarrinho();
    renderizarCarrinho();
    mostrarNotificacao(`${nomeProduto} removido do carrinho`);
}

// Atualizar subtotal e progresso
function atualizarSubtotal() {
    const subtotal = carrinho.reduce((total, item) => total + (item.preco * item.quantidade), 0);
    const subtotalElement = document.querySelector('.cart-subtotal-value');
    
    if (subtotalElement) {
        subtotalElement.textContent = `${subtotal.toFixed(2)}€`;
    }
    
    // Atualizar barra de progresso (envio grátis a partir de 50€)
    const freeShippingThreshold = 50;
    const progressBar = document.querySelector('.cart-progress-fill');
    const progressText = document.querySelector('.cart-progress-text');
    
    if (progressBar && progressText) {
        const percentage = Math.min((subtotal / freeShippingThreshold) * 100, 100);
        progressBar.style.width = `${percentage}%`;
        
        if (subtotal >= freeShippingThreshold) {
            progressText.textContent = '🎉 Parabéns! Tens envio grátis!';
        } else {
            const restante = (freeShippingThreshold - subtotal).toFixed(2);
            progressText.textContent = `Faltam ${restante}€ para envio grátis`;
        }
    }
}

// Adicionar produto ao carrinho
function addToCart(nomeProduto, preco) {
    console.log('Adicionando ao carrinho:', nomeProduto, preco);
    
    const produto = {
        nome: nomeProduto,
        preco: preco,
        quantidade: 1,
        imagem: obterImagemProduto(nomeProduto)
    };
    
    console.log('Produto com imagem:', produto);
    
    // Verificar se produto já existe no carrinho
    const produtoExistente = carrinho.find(item => item.nome === nomeProduto);
    
    if (produtoExistente) {
        produtoExistente.quantidade++;
    } else {
        carrinho.push(produto);
    }
    
    // Guardar no localStorage
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    console.log('Carrinho após adicionar:', carrinho);
    
    // Atualizar contador
    atualizarContadorCarrinho();
    
    // Mostrar feedback visual
    mostrarNotificacao(`${nomeProduto} adicionado ao carrinho!`);
    
    // Abrir drawer automaticamente após 300ms
    setTimeout(() => {
        toggleCartDrawer();
    }, 300);
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
        z-index: 1001;
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
function configurarFiltros() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    if (filterButtons.length > 0) {
        console.log('Configurando filtros');
        
        // Verificar se há categoria na URL
        const urlParams = new URLSearchParams(window.location.search);
        const categoriaUrl = urlParams.get('categoria');
        
        if (categoriaUrl) {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            if (categoriaUrl === 'caixas' || categoriaUrl === 'quadros') {
                const btnQuadrosCaixas = document.querySelector('[data-category="quadros-caixas"]');
                if (btnQuadrosCaixas) {
                    btnQuadrosCaixas.classList.add('active');
                    filtrarProdutos('quadros-caixas');
                }
            } else if (categoriaUrl === 'laser') {
                const btnLaser = document.querySelector('[data-category="laser"]');
                if (btnLaser) {
                    btnLaser.classList.add('active');
                    filtrarProdutos('laser');
                }
            }
        }
        
        // Event listeners para os botões de filtro
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const categoria = this.getAttribute('data-category');
                filtrarProdutos(categoria);
            });
        });
    }
}

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
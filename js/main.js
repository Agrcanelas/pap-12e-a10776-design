// js/main.js - CÓDIGO COMPLETO E ATUALIZADO

// Carrinho de Compras
let carrinho = [];

// Mapa de imagens dos produtos (Backup)
const imagensProdutos = {
   //produtos a laser
    'Ganesha em Madeira': 'ganesha-madeira.jpg',
    'Mandala Yin Yang': 'mandala-yin-yang.jpg',
    'Globo de Neve Natal': 'globo-neve-natal.jpg',
    'Árvore de Natal Minimalista': 'arvore.jpg',
    'Árvore de Natal Intrincada': 'arvore-natal.jpg',
    'Mão Íman Decorativa': 'mao-iman.jpg',
    'Vaso com Flores Artesanais': 'vaso-flores.jpg',
    
   //caixas e quadros
    'Caixa Decorativa Hamsa': 'caixa-hamsa.jpg',
    'Caixa Branca': 'caixa-foto.jpg',
    'Caixa Listrada': 'caixa-listrada.png',
    'Caixa Preta': 'caixa-preta.png',
    'Caixa Hexagonal': 'caixa-hexagonal.png',
    'Caixa de Parede Decorativa': 'caixa-parede.jpg',
    'Caixa Castanha Premium': 'caixa-cast.jpg',

    //extras
    'Porta-chaves Puzzle': 'porta-chaves-puzzle.jpg',
    'Enfeite Árvore com Bola': 'arvore-com-bola.jpg',
    'Caixa Multiusos Organizadora': 'Caixa_Multi.jpg',
    'Organizador Post-it Wood': 'copo-postit.jpg',
    'Copo Organizador de Material': 'copo-material.jpg',
    'Mini Cómoda com Gavetas': 'caixa-gavetas.jpg',
    'Casinha de Natal Decorativa': 'casa-natal-P.jpg',
    'Quadro Decorativo Branco': 'Qdl-branco.jpg',
    'Casinha de Natal Média': 'casa-natal-M.jpg',
    'Caixa Multiusos Marmoreada': 'casa-multi-marmore.jpg',
    'Vaso Decorativo Minimalista': 'vaso1.jpg',

   //flores 
    'Flor do Amanhecer': 'flor-amanhecer.png',
    'Girassol Silvestre': 'girassol-silvestre.png',
    'Lirio em Relevo': 'lirio-relevo.png',
    'Margarida Rustica': 'margarida-rustica.png',
    'Ramo de Sakura': 'ramo-sakura.png',
    'Conjunto Flores Prata/Vermelho': 'flores-prata-vermelho.jpg',
    'Conjunto Flores Preto/Vermelho': 'flores-preto-vermelho.jpg',

};

// Função para obter imagem do produto
function obterImagemProduto(nomeProduto) {
    return imagensProdutos[nomeProduto] || 'caixa-hamsa.jpg';
}

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página carregada!');
    const langBtn = document.getElementById('lang-btn');
    const langDropdown = document.getElementById('lang-dropdown');
    
    if (langBtn && langDropdown) {
        langBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Impede que o clique feche o menu imediatamente
            langDropdown.classList.toggle('active');
        });
    }

    document.addEventListener('click', function(e) {
        if (langDropdown && !langBtn.contains(e.target)) {
            langDropdown.classList.remove('active');
        }
        if (resultsDiv && !searchInput.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });

    const searchInput = document.getElementById('live-search');
    const resultsDiv = document.getElementById('search-results');

if (searchInput) {
        searchInput.addEventListener('input', function() {
            let query = this.value;

            if (query.length > 1) { // Só pesquisa após 2 letras
                fetch('pesquisa_ajax.php?q=' + query)
                    .then(response => response.text())
                    .then(data => {
                        resultsDiv.innerHTML = data;
                        resultsDiv.style.display = 'block';
                    });
            } else {
                resultsDiv.style.display = 'none';
            }
        });

        // Fechar a lista se clicar fora
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });
    }



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
    
    // Configurar filtros de produtos (se existirem na página)
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
                <button class="cart-item-remove" onclick="removerDoCarrinho(${index})" title="Remover">×</button>
            </div>`;
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
    const tpl = (window.I18N && window.I18N.cartRemoved) ? window.I18N.cartRemoved : '{name} removido do carrinho';
    mostrarNotificacao(tpl.replace('{name}', nomeProduto));
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
            progressText.textContent = (window.I18N && window.I18N.freeShippingCongrats) ? window.I18N.freeShippingCongrats : '🎉 Parabéns! Tens envio grátis!';
        } else {
            const restante = (freeShippingThreshold - subtotal).toFixed(2);
            const tpl = (window.I18N && window.I18N.freeShippingRemaining) ? window.I18N.freeShippingRemaining : 'Faltam {amount}€ para envio grátis';
            progressText.textContent = tpl.replace('{amount}', restante);
        }
    }
}

// Adicionar produto ao carrinho
function addToCart(nomeProduto, preco, imagemProduto) {
    console.log('Adicionando:', nomeProduto);
    
    const produto = {
        nome: nomeProduto,
        preco: preco,
        quantidade: 1,
        imagem: imagemProduto // Usa a imagem real da BD
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
    
    // Atualizar contador e mostrar feedback
    atualizarContadorCarrinho();
    const tpl = (window.I18N && window.I18N.cartAdded) ? window.I18N.cartAdded : '{name} adicionado ao carrinho!';
    mostrarNotificacao(tpl.replace('{name}', nomeProduto));
    
    // Abrir drawer automaticamente após 300ms
    setTimeout(() => {
        toggleCartDrawer();
    }, 300);
}

// Mostrar notificação
function mostrarNotificacao(mensagem) {
    const notificacaoExistente = document.querySelector('.notificacao');
    if (notificacaoExistente) {
        notificacaoExistente.remove();
    }
    
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
    setTimeout(() => {
        notificacao.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notificacao.remove(), 300);
    }, 3000);
}

// CSS das animações
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
`;
document.head.appendChild(style);

// ==========================================
// FILTRO DE PRODUTOS (COM CATEGORIA ÍMANES)
// ==========================================
function configurarFiltros() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    if (filterButtons.length > 0) {
        console.log('Configurando filtros');
        
        // Verificar se há categoria na URL
        const urlParams = new URLSearchParams(window.location.search);
        const categoriaUrl = urlParams.get('categoria');
        
        console.log('Categoria da URL:', categoriaUrl);
        
        if (categoriaUrl) {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Lógica para ativar o botão correto
            if (categoriaUrl === 'quadros-caixas') {
                const btn = document.querySelector('[data-category="quadros-caixas"]');
                if (btn) { btn.classList.add('active'); filtrarProdutos('quadros-caixas'); }
                
            } else if (categoriaUrl === 'laser') {
                const btn = document.querySelector('[data-category="laser"]');
                if (btn) { btn.classList.add('active'); filtrarProdutos('laser'); }
                
            } else if (categoriaUrl === 'extras') {
                const btn = document.querySelector('[data-category="extras"]');
                if (btn) { btn.classList.add('active'); filtrarProdutos('extras'); }
            
            } 
            else if (categoriaUrl === 'flores') {
                const btn = document.querySelector('[data-category="flores"]');
                if (btn) { 
                    btn.classList.add('active'); 
                    filtrarProdutos('flores'); 
                }
        }
        }
        
        // Event listeners para cliques nos botões
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

// Função para esconder/mostrar produtos
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
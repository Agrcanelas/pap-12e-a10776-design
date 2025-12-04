// js/carrinho-page.js

// Variável específica para esta página
let meuCarrinho = [];

// Mapa de imagens de backup
const imagensBackup = {
    'Ganesha em Madeira': 'ganesha-madeira.jpg',
    'Mandala Yin Yang': 'mandala-yin-yang.jpg',
    'Globo de Neve Natal': 'globo-neve-natal.jpg',
    'Caixa Decorativa Hamsa': 'caixa-hamsa.jpg',
    'Porta-chaves Puzzle': 'porta-chaves-puzzle.jpg'
};

// Quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    console.log("Iniciando página do carrinho...");
    
    // 1. Ler os dados frescos do LocalStorage
    const dadosSalvos = localStorage.getItem('carrinho');
    
    if (dadosSalvos) {
        meuCarrinho = JSON.parse(dadosSalvos);
        console.log("Produtos encontrados:", meuCarrinho.length);
    } else {
        console.log("Nenhum produto no localStorage.");
    }

    // 2. Renderizar a página
    renderizarPagina();
});

function renderizarPagina() {
    const divVazio = document.getElementById('cart-page-empty');
    const divConteudo = document.getElementById('cart-page-content');
    const divResumo = document.getElementById('cart-page-summary');
    const listaItems = document.getElementById('cart-page-items-list');

    // Se os elementos não existirem na página, para tudo
    if (!divVazio || !divConteudo) return;

    if (meuCarrinho.length === 0) {
        // MOSTRAR MENSAGEM DE VAZIO
        divVazio.style.display = 'block';
        divConteudo.style.display = 'none';
        if (divResumo) divResumo.style.display = 'none';
    } else {
        // MOSTRAR PRODUTOS
        divVazio.style.display = 'none';
        divConteudo.style.display = 'block';
        if (divResumo) divResumo.style.display = 'block';

        // Criar o HTML de cada item
        listaItems.innerHTML = meuCarrinho.map((item, index) => {
            // Tenta usar a imagem salva, ou procura no backup, ou usa padrão
            let img = item.imagem || imagensBackup[item.nome] || 'caixa-hamsa.jpg';
            
            return `
            <div class="cart-page-item">
                <div class="cart-page-item-image">
                    <img src="images/produtos/${img}" alt="${item.nome}" onerror="this.src='images/produtos/caixa-hamsa.jpg'">
                </div>
                <div class="cart-page-item-info">
                    <h3 class="cart-page-item-name">${item.nome}</h3>
                    <p class="cart-page-item-price">${parseFloat(item.preco).toFixed(2)}€</p>
                    <div class="cart-page-item-quantity">
                        <button class="qty-btn-page" onclick="mudarQtd(${index}, -1)">-</button>
                        <span class="qty-number-page">${item.quantidade}</span>
                        <button class="qty-btn-page" onclick="mudarQtd(${index}, 1)">+</button>
                    </div>
                </div>
                <div class="cart-page-item-actions">
                    <p class="cart-page-item-total">${(item.preco * item.quantidade).toFixed(2)}€</p>
                    <button class="btn-remove-page" onclick="removerItem(${index})">Remover</button>
                </div>
            </div>
            `;
        }).join('');

        atualizarTotais();
    }
}

// Atualizar preço total
function atualizarTotais() {
    const subtotal = meuCarrinho.reduce((total, item) => total + (item.preco * item.quantidade), 0);
    const envio = subtotal >= 50 ? 0 : 4.99;
    const total = subtotal + envio;

    const elSubtotal = document.getElementById('summary-subtotal');
    const elEnvio = document.getElementById('summary-shipping');
    const elTotal = document.getElementById('summary-total');

    if (elSubtotal) elSubtotal.textContent = `${subtotal.toFixed(2)}€`;
    if (elTotal) elTotal.textContent = `${total.toFixed(2)}€`;
    
    if (elEnvio) {
        if (envio === 0) {
            elEnvio.textContent = "Grátis";
            elEnvio.style.color = "green";
        } else {
            elEnvio.textContent = "4.99€";
            elEnvio.style.color = "inherit";
        }
    }
}

// Funções de Ação (Globais)
window.mudarQtd = function(index, delta) {
    if (meuCarrinho[index]) {
        meuCarrinho[index].quantidade += delta;
        if (meuCarrinho[index].quantidade <= 0) {
            meuCarrinho.splice(index, 1);
        }
        salvarEAtualizar();
    }
};

window.removerItem = function(index) {
    meuCarrinho.splice(index, 1);
    salvarEAtualizar();
};

function salvarEAtualizar() {
    // Salva no navegador
    localStorage.setItem('carrinho', JSON.stringify(meuCarrinho));
    // Recarrega a página para atualizar tudo (header e lista)
    location.reload(); 
}

window.finalizarCompra = function() {
    if (meuCarrinho.length === 0) {
        alert("Carrinho vazio!");
    } else {
        alert("A processar compra...");
    }
};
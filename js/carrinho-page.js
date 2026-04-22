// js/carrinho-page.js

// Variável global para armazenar os itens e ser usada no checkout
let meuCarrinho = [];

function renderizarCarrinhoPagina() {
    const container = document.getElementById('cart-page-items-container');
    const displayVazio = document.getElementById('cart-page-empty');
    const displayConteudo = document.getElementById('cart-page-content');
    
    // Carregar dados do LocalStorage
    meuCarrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    if (meuCarrinho.length === 0) {
        if(displayVazio) displayVazio.style.display = 'block';
        if(displayConteudo) displayConteudo.style.display = 'none';
        return;
    }

    if(displayVazio) displayVazio.style.display = 'none';
    if(displayConteudo) displayConteudo.style.display = 'grid';

    // Gerar o HTML dos cartões com o novo visual
    container.innerHTML = meuCarrinho.map((item, index) => `
        <div class="cart-item-card">
            <div class="cart-item-img">
                <img src="images/produtos/${item.imagem}" alt="${item.nome}">
            </div>
            <div class="cart-item-info">
                <h3>${item.nome}</h3>
                <div class="item-price-tag">${parseFloat(item.preco).toFixed(2)}€</div>
                <p style="font-family: 'Outfit'; color: #666; margin-top: 5px;">Quantidade: ${item.quantidade}</p>
            </div>
            <button class="btn-remove" onclick="removerItem(${index})">×</button>
        </div>
    `).join('');

    atualizarResumo();
}

function atualizarResumo() {
    const subtotal = meuCarrinho.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
    const envio = subtotal >= 50 ? 0 : 4.99;
    const total = subtotal + envio;

    const subEl = document.getElementById('summary-subtotal');
    const shipEl = document.getElementById('summary-shipping');
    const totalEl = document.getElementById('summary-total');

    if(subEl) subEl.textContent = subtotal.toFixed(2) + '€';
    if(shipEl) shipEl.textContent = envio === 0
        ? ((window.I18N && window.I18N.freeLabel) ? window.I18N.freeLabel : 'GRÁTIS')
        : envio.toFixed(2) + '€';
    if(totalEl) totalEl.textContent = total.toFixed(2) + '€';
}

function removerItem(index) {
    meuCarrinho.splice(index, 1);
    localStorage.setItem('carrinho', JSON.stringify(meuCarrinho));
    renderizarCarrinhoPagina();
    // Atualiza o contador no menu superior (header)
    if(typeof atualizarContadorCarrinho === 'function') atualizarContadorCarrinho();
}

// ESTA É A FUNÇÃO QUE ESTAVA A FALTAR:
function finalizarCompra() {
    if (meuCarrinho.length === 0) {
        alert((window.I18N && window.I18N.cartEmptyAlert) ? window.I18N.cartEmptyAlert : "O teu carrinho está vazio!");
        return;
    }

    const btn = document.querySelector('.btn-finalizar');
    const originalText = btn.textContent;
    
    // Feedback visual de carregamento
    btn.disabled = true;
    btn.textContent = (window.I18N && window.I18N.processing) ? window.I18N.processing : "A PROCESSAR...";

    // Enviar para o PHP processar a encomenda na base de dados
    fetch('processar_encomenda.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ produtos: meuCarrinho })
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            alert((window.I18N && window.I18N.orderSuccess) ? window.I18N.orderSuccess : "🎉 Encomenda realizada com sucesso!");
            localStorage.removeItem('carrinho'); // Limpa o carrinho após sucesso
            window.location.href = 'index.php';   // Redireciona para a home
        } else {
            const prefix = (window.I18N && window.I18N.orderErrorPrefix) ? window.I18N.orderErrorPrefix : "Erro: ";
            alert(prefix + data.mensagem);
            btn.disabled = false;
            btn.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert((window.I18N && window.I18N.serverError) ? window.I18N.serverError : "Ocorreu um erro ao ligar ao servidor.");
        btn.disabled = false;
        btn.textContent = originalText;
    });
}

// Iniciar a página
document.addEventListener('DOMContentLoaded', renderizarCarrinhoPagina);
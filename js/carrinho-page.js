// js/carrinho-page.js - CÓDIGO COMPLETO E FINAL

// Variável específica para esta página
let meuCarrinho = [];

// Mapa de imagens de backup (caso falhem)
const imagensBackup = {
    'Ganesha em Madeira': 'ganesha-madeira.jpg',
    'Mandala Yin Yang': 'mandala-yin-yang.jpg',
    'Globo de Neve Natal': 'globo-neve-natal.jpg',
    'Caixa Decorativa Hamsa': 'caixa-hamsa.jpg',
    'Porta-chaves Puzzle': 'porta-chaves-puzzle.jpg'
};

// 1. Quando a página carrega: Ler LocalStorage e Renderizar
document.addEventListener('DOMContentLoaded', function() {
    console.log("Iniciando página do carrinho...");
    
    const dadosSalvos = localStorage.getItem('carrinho');
    
    if (dadosSalvos) {
        meuCarrinho = JSON.parse(dadosSalvos);
        console.log("Produtos carregados:", meuCarrinho.length);
    } else {
        console.log("LocalStorage vazio ou inexistente.");
        meuCarrinho = [];
    }

    renderizarPagina();
});

// 2. Função principal de desenho da página
function renderizarPagina() {
    const divVazio = document.getElementById('cart-page-empty');
    const divConteudo = document.getElementById('cart-page-content');
    const divResumo = document.getElementById('cart-page-summary');
    const listaItems = document.getElementById('cart-page-items-list');

    // Segurança: se não estivermos na página do carrinho, parar.
    if (!divVazio || !divConteudo) return;

    if (meuCarrinho.length === 0) {
        // Estado Vazio
        divVazio.style.display = 'block';
        divConteudo.style.display = 'none';
        if (divResumo) divResumo.style.display = 'none';
    } else {
        // Estado com Produtos
        divVazio.style.display = 'none';
        divConteudo.style.display = 'block';
        if (divResumo) divResumo.style.display = 'block';

        // Gerar HTML dos produtos
        listaItems.innerHTML = meuCarrinho.map((item, index) => {
            // Tenta obter imagem do item, ou do backup, ou a padrão
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

// 3. Função para atualizar os valores monetários
function atualizarTotais() {
    const subtotal = meuCarrinho.reduce((total, item) => total + (item.preco * item.quantidade), 0);
    const envio = subtotal >= 50 ? 0 : 4.99;
    const total = subtotal + envio;

    // Atualizar HTML
    const elSubtotal = document.getElementById('summary-subtotal');
    const elEnvio = document.getElementById('summary-shipping');
    const elTotal = document.getElementById('summary-total');

    if (elSubtotal) elSubtotal.textContent = `${subtotal.toFixed(2)}€`;
    if (elTotal) elTotal.textContent = `${total.toFixed(2)}€`;
    
    if (elEnvio) {
        if (envio === 0) {
            elEnvio.textContent = "Grátis";
            elEnvio.style.color = "#6b8e23"; // Verde
            elEnvio.style.fontWeight = "bold";
        } else {
            elEnvio.textContent = "4.99€";
            elEnvio.style.color = "inherit";
            elEnvio.style.fontWeight = "normal";
        }
    }
}

// 4. Funções Globais (acessíveis pelo onclick do HTML)

// Alterar Quantidade
window.mudarQtd = function(index, delta) {
    if (meuCarrinho[index]) {
        meuCarrinho[index].quantidade += delta;
        
        // Se a quantidade for 0 ou menos, remove o item
        if (meuCarrinho[index].quantidade <= 0) {
            meuCarrinho.splice(index, 1);
        }
        
        // Salvar e Recarregar
        localStorage.setItem('carrinho', JSON.stringify(meuCarrinho));
        // Recarregar a página atualiza tudo (incluindo header e totais)
        location.reload(); 
    }
};

// Remover Item
window.removerItem = function(index) {
    meuCarrinho.splice(index, 1);
    localStorage.setItem('carrinho', JSON.stringify(meuCarrinho));
    location.reload();
};

// 5. Função FINALIZAR COMPRA (Conecta à Base de Dados)
window.finalizarCompra = function() {
    if (meuCarrinho.length === 0) {
        alert("O carrinho está vazio!");
        return;
    }

    const botao = document.querySelector('.btn-finalizar');
    const textoOriginal = botao ? botao.textContent : "Finalizar Compra";
    
    // Feedback visual
    if(botao) {
        botao.textContent = "A processar...";
        botao.disabled = true;
    }

    // Preparar dados
    const dadosPedido = {
        produtos: meuCarrinho
    };

    console.log("Enviando pedido:", dadosPedido);

    // Enviar para o PHP
    fetch('processar_encomenda.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosPedido)
    })
    .then(response => {
        // Verifica se a resposta é JSON válido
        if (!response.ok) {
            throw new Error('Erro na rede ou servidor: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log("Resposta do servidor:", data);
        
        if (data.sucesso) {
            alert("🎉 Compra realizada com sucesso!\nID da Encomenda: " + (data.mensagem.split('ID: ')[1] || 'Registado'));
            
            // Limpar Carrinho
            localStorage.removeItem('carrinho');
            meuCarrinho = [];
            
            // Redirecionar
            window.location.href = 'index.php';
        } else {
            alert("Erro ao gravar: " + data.mensagem);
            if(botao) {
                botao.textContent = textoOriginal;
                botao.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Erro Fetch:', error);
        alert("Erro técnico. Verifica a consola (F12) para detalhes.");
        if(botao) {
            botao.textContent = textoOriginal;
            botao.disabled = false;
        }
    });
};
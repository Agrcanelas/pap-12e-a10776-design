-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Abr-2026 às 22:52
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_madeira`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_registo` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `password`, `data_registo`, `is_admin`) VALUES
(1, 'teste', 'aaaaa@gmail.com', '$2y$10$gKSxPDbKG4Kx4NsGb5tifuYeL8rxbW6tSLdSr1NVrCG/9aGlotCaK', '2026-01-20 14:46:51', 1),
(2, 'teste3', 'aaaab@gmail.com', '$2y$10$yUZuMW2aK7HWiLxhvbZR/.Lc2WhTYMzAq/HTr.hHjf5PKHlPbZLya', '2026-01-21 09:32:30', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
--

CREATE TABLE `encomendas` (
  `id` int(11) NOT NULL,
  `data_encomenda` timestamp NOT NULL DEFAULT current_timestamp(),
  `valor_total` decimal(10,2) NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendente' COMMENT 'Pendente, Em Processamento, Enviada, Entregue',
  `portes` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `encomendas`
--

INSERT INTO `encomendas` (`id`, `data_encomenda`, `valor_total`, `estado`, `portes`) VALUES
(1, '2025-12-15 17:36:40', 22.99, 'Pendente', 0.00),
(2, '2026-01-21 09:30:36', 46.38, 'Pendente', 4.99),
(3, '2026-01-21 09:41:18', 27.49, 'Pendente', 4.99),
(4, '2026-01-21 11:02:40', 90.00, 'Pendente', 0.00),
(5, '2026-01-26 17:15:40', 23.49, 'Pendente', 4.99),
(6, '2026-01-26 17:15:59', 19.99, 'Pendente', 4.99),
(7, '2026-01-27 18:52:50', 19.99, 'Pendente', 4.99),
(8, '2026-02-25 10:27:19', 54.90, 'Pendente', 0.00),
(9, '2026-04-22 20:20:46', 19.99, 'Pendente', 4.99);

-- --------------------------------------------------------

--
-- Estrutura da tabela `itens_encomenda`
--

CREATE TABLE `itens_encomenda` (
  `id` int(11) NOT NULL,
  `encomenda_id` int(11) NOT NULL,
  `produto_nome` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `itens_encomenda`
--

INSERT INTO `itens_encomenda` (`id`, `encomenda_id`, `produto_nome`, `quantidade`, `preco_unitario`, `subtotal`) VALUES
(1, 1, 'Porta-chaves Puzzle', 4, 4.50, 18.00),
(2, 2, 'Caixa Decorativa Hamsa', 1, 12.99, 12.99),
(3, 2, 'Mandala Yin Yang', 1, 15.00, 15.00),
(4, 2, 'Porta-chaves Puzzle', 1, 4.50, 4.50),
(5, 2, 'Ganesha em Madeira', 1, 8.90, 8.90),
(6, 3, 'Globo de Neve Natal', 1, 22.50, 22.50),
(7, 4, 'Globo de Neve Natal', 4, 22.50, 90.00),
(8, 5, 'Caixa Listrada', 1, 18.50, 18.50),
(9, 6, 'Caixa Branca', 1, 15.00, 15.00),
(10, 7, 'Conjunto Flores Prata/Vermelho', 1, 15.00, 15.00),
(11, 8, 'Coração Decorativo em Madeira', 2, 18.00, 36.00),
(12, 8, 'Ganesha', 1, 10.00, 10.00),
(13, 8, 'Ganesha em Madeira', 1, 8.90, 8.90),
(14, 9, 'Mandala Yin Yang', 1, 15.00, 15.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `personalizavel` tinyint(1) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `imagem`, `categoria`, `personalizavel`, `descricao`) VALUES
(1, 'Ganesha em Madeira', 8.90, 'ganesha-madeira.jpg', 'laser', 0, 'Estátua detalhada feita em madeira.'),
(2, 'Mandala Yin Yang', 15.00, 'mandala-yin-yang.jpg', 'laser', 0, 'Mandala decorativa cortada a laser.'),
(3, 'Globo de Neve Natal', 22.50, 'globo-neve-natal.jpg', 'laser', 1, 'Globo de neve festivo personalizado.'),
(4, 'Caixa Decorativa Hamsa', 12.99, 'caixa-hamsa.jpg', 'quadros-caixas', 1, 'Caixa útil e decorativa.'),
(5, 'Porta-chaves Puzzle', 4.50, 'porta-chaves-puzzle.jpg', 'extras', 1, 'Conjunto de porta-chaves que se encaixam.'),
(6, 'Caixa Branca', 15.00, 'caixa-foto.jpg', 'quadros-caixas', 0, 'Elegante caixa em madeira com acabamento branco acetinado.'),
(7, 'Caixa Listrada', 18.50, 'caixa-listrada.png', 'quadros-caixas', 0, 'Caixa artesanal com detalhes listrados e textura natural.'),
(8, 'Caixa Preta', 16.00, 'caixa-preta.png', 'quadros-caixas', 0, 'Design moderno com acabamento em preto fosco, ideal para decoração.'),
(9, 'Caixa Hexagonal', 22.00, 'caixa-hexagonal.png', 'quadros-caixas', 0, 'Caixa com formato geométrico único, perfeita para joias.'),
(10, 'Flor do Amanhecer', 12.50, 'arvore-flores-azul.png', 'flores', 0, 'Flor artesanal em madeira clara, ideal para centros de mesa.'),
(11, 'Girassol Silvestre', 15.00, 'arvore-comcaixa.png', 'flores', 0, 'Peça vibrante com detalhes em relevo e acabamento natural.'),
(12, 'Lirio em Relevo', 18.90, 'arvore-flores-preto.png', 'flores', 0, 'Quadro floral esculpido com efeito de profundidade 3D.'),
(13, 'Margarida Rustica', 11.00, 'arvore-flores-vermelho.png', 'flores', 0, 'Decoração de parede em madeira recuperada com design floral.'),
(14, 'Ramo de Sakura', 22.50, 'flores-azul-branco.png', 'flores', 0, 'Painel delicado inspirado nas flores de cerejeira orientais.'),
(15, 'Árvore de Natal Minimalista', 12.50, 'arvore.jpg', 'laser', 0, 'Árvore decorativa cortada a laser em madeira natural, perfeita para mesas.'),
(16, 'Enfeite Árvore com Bola', 7.99, 'arvore-com-bola.jpg', 'extras', 0, 'Decoração de Natal personalizada com detalhe de bola suspensa.'),
(17, 'Árvore de Natal Intrincada', 14.90, 'arvore-natal.jpg', 'laser', 0, 'Design detalhado feito a laser com padrões geométricos natalícios.'),
(18, 'Caixa Multiusos Organizadora', 18.90, 'Caixa_Multi.jpg', 'extras', 0, 'Caixa versátil em madeira natural, ideal para organizar acessórios ou escritório.'),
(19, 'Caixa de Parede Decorativa', 24.50, 'caixa-parede.jpg', 'quadros-caixas', 0, 'Suporte de parede elegante para exposição de pequenos objetos ou plantas.'),
(20, 'Organizador Post-it Wood', 9.50, 'copo-postit.png', 'extras', 0, 'Prático suporte de secretária com compartimento para post-its e canetas.'),
(21, 'Conjunto Flores Prata/Vermelho', 15.00, 'flores-prata-vermelho.png', 'flores', 0, 'Arranjo decorativo em madeira com acabamento metalizado prata e apontamentos vermelhos.'),
(22, 'Conjunto Flores Preto/Vermelho', 15.00, 'flores-preto-vermelho.png', 'flores', 0, 'Design moderno de flores em madeira com contraste elegante entre preto e vermelho.'),
(23, 'Copo Organizador de Material', 10.90, 'copo-material.png', 'extras', 0, 'Copo em madeira natural ideal para pincéis, lápis ou ferramentas de artesanato.'),
(24, 'Mão Íman Decorativa', 5.50, 'mao_iman.jpg', 'laser', 0, 'Íman de frigorífico cortado a laser com design detalhado de uma mão artesanal.'),
(25, 'Caixa Castanha Premium', 22.00, 'caixa-cast.png', 'quadros-caixas', 0, 'Caixa em madeira de tom castanho profundo, ideal para presentes de luxo ou arrumação.'),
(26, 'Mini Cómoda com Gavetas', 29.90, 'caixa-gavetas.png', 'extras', 0, 'Organizador funcional com gavetas, perfeito para joias ou material de escritório.'),
(27, 'Casinha de Natal Decorativa', 12.50, 'casa-natal-P.png', 'extras', 0, 'Miniatura de casa natalícia em madeira, ideal para criar ambientes festivos.'),
(28, 'Quadro Decorativo Branco', 18.00, 'QdI-aberto.png', 'extras', 0, 'Quadro com acabamento minimalista em branco para uma decoração moderna.'),
(29, 'Casinha de Natal Média', 15.50, 'casa-natal-M.png', 'extras', 0, 'Decoração natalícia em madeira de tamanho médio, ideal para centros de mesa.'),
(30, 'Caixa Multiusos Marmoreada', 22.90, 'caixa-multi-marmore.png', 'extras', 0, 'Caixa organizadora com design elegante e acabamento que simula mármore.'),
(31, 'Vaso Decorativo Minimalista', 14.00, 'vaso1.png', 'extras', 0, 'Vaso em madeira natural para flores secas ou decoração de interiores.'),
(33, 'Coração Decorativo em Madeira', 8.50, 'coraçao-madeira.png', 'laser', 0, 'Coração delicado cortado a laser com acabamento rústico, ideal para presentes ou decoração.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_traducoes`
--

CREATE TABLE `produto_traducoes` (
  `produto_id` int(11) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `produto_traducoes`
--

INSERT INTO `produto_traducoes` (`produto_id`, `lang`, `nome`, `descricao`, `updated_at`) VALUES
(1, 'pt', 'Ganesha em Madeira', 'Estátua detalhada feita em madeira.', '2026-04-22 20:38:09'),
(2, 'pt', 'Mandala Yin Yang', 'Mandala decorativa cortada a laser.', '2026-04-22 20:38:09'),
(3, 'pt', 'Globo de Neve Natal', 'Globo de neve festivo personalizado.', '2026-04-22 20:38:09'),
(4, 'pt', 'Caixa Decorativa Hamsa', 'Caixa útil e decorativa.', '2026-04-22 20:38:09'),
(5, 'pt', 'Porta-chaves Puzzle', 'Conjunto de porta-chaves que se encaixam.', '2026-04-22 20:38:09'),
(6, 'pt', 'Caixa Branca', 'Elegante caixa em madeira com acabamento branco acetinado.', '2026-04-22 20:38:09'),
(7, 'pt', 'Caixa Listrada', 'Caixa artesanal com detalhes listrados e textura natural.', '2026-04-22 20:38:09'),
(8, 'pt', 'Caixa Preta', 'Design moderno com acabamento em preto fosco, ideal para decoração.', '2026-04-22 20:38:09'),
(9, 'pt', 'Caixa Hexagonal', 'Caixa com formato geométrico único, perfeita para joias.', '2026-04-22 20:38:09'),
(10, 'pt', 'Flor do Amanhecer', 'Flor artesanal em madeira clara, ideal para centros de mesa.', '2026-04-22 20:38:09'),
(11, 'pt', 'Girassol Silvestre', 'Peça vibrante com detalhes em relevo e acabamento natural.', '2026-04-22 20:38:09'),
(12, 'pt', 'Lirio em Relevo', 'Quadro floral esculpido com efeito de profundidade 3D.', '2026-04-22 20:38:09'),
(13, 'pt', 'Margarida Rustica', 'Decoração de parede em madeira recuperada com design floral.', '2026-04-22 20:38:09'),
(14, 'pt', 'Ramo de Sakura', 'Painel delicado inspirado nas flores de cerejeira orientais.', '2026-04-22 20:38:09'),
(15, 'pt', 'Árvore de Natal Minimalista', 'Árvore decorativa cortada a laser em madeira natural, perfeita para mesas.', '2026-04-22 20:38:09'),
(16, 'pt', 'Enfeite Árvore com Bola', 'Decoração de Natal personalizada com detalhe de bola suspensa.', '2026-04-22 20:38:09'),
(17, 'pt', 'Árvore de Natal Intrincada', 'Design detalhado feito a laser com padrões geométricos natalícios.', '2026-04-22 20:38:09'),
(18, 'pt', 'Caixa Multiusos Organizadora', 'Caixa versátil em madeira natural, ideal para organizar acessórios ou escritório.', '2026-04-22 20:38:09'),
(19, 'pt', 'Caixa de Parede Decorativa', 'Suporte de parede elegante para exposição de pequenos objetos ou plantas.', '2026-04-22 20:38:09'),
(20, 'pt', 'Organizador Post-it Wood', 'Prático suporte de secretária com compartimento para post-its e canetas.', '2026-04-22 20:38:09'),
(21, 'pt', 'Conjunto Flores Prata/Vermelho', 'Arranjo decorativo em madeira com acabamento metalizado prata e apontamentos vermelhos.', '2026-04-22 20:38:09'),
(22, 'pt', 'Conjunto Flores Preto/Vermelho', 'Design moderno de flores em madeira com contraste elegante entre preto e vermelho.', '2026-04-22 20:38:09'),
(23, 'pt', 'Copo Organizador de Material', 'Copo em madeira natural ideal para pincéis, lápis ou ferramentas de artesanato.', '2026-04-22 20:38:09'),
(24, 'pt', 'Mão Íman Decorativa', 'Íman de frigorífico cortado a laser com design detalhado de uma mão artesanal.', '2026-04-22 20:38:09'),
(25, 'pt', 'Caixa Castanha Premium', 'Caixa em madeira de tom castanho profundo, ideal para presentes de luxo ou arrumação.', '2026-04-22 20:38:09'),
(26, 'pt', 'Mini Cómoda com Gavetas', 'Organizador funcional com gavetas, perfeito para joias ou material de escritório.', '2026-04-22 20:38:09'),
(27, 'pt', 'Casinha de Natal Decorativa', 'Miniatura de casa natalícia em madeira, ideal para criar ambientes festivos.', '2026-04-22 20:38:09'),
(28, 'pt', 'Quadro Decorativo Branco', 'Quadro com acabamento minimalista em branco para uma decoração moderna.', '2026-04-22 20:38:09'),
(29, 'pt', 'Casinha de Natal Média', 'Decoração natalícia em madeira de tamanho médio, ideal para centros de mesa.', '2026-04-22 20:38:09'),
(30, 'pt', 'Caixa Multiusos Marmoreada', 'Caixa organizadora com design elegante e acabamento que simula mármore.', '2026-04-22 20:38:09'),
(31, 'pt', 'Vaso Decorativo Minimalista', 'Vaso em madeira natural para flores secas ou decoração de interiores.', '2026-04-22 20:38:09'),
(33, 'pt', 'Coração Decorativo em Madeira', 'Coração delicado cortado a laser com acabamento rústico, ideal para presentes ou decoração.', '2026-04-22 20:38:09');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `itens_encomenda`
--
ALTER TABLE `itens_encomenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `encomenda_id` (`encomenda_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produto_traducoes`
--
ALTER TABLE `produto_traducoes`
  ADD PRIMARY KEY (`produto_id`,`lang`),
  ADD KEY `idx_produto_traducoes_lang` (`lang`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `itens_encomenda`
--
ALTER TABLE `itens_encomenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `itens_encomenda`
--
ALTER TABLE `itens_encomenda`
  ADD CONSTRAINT `itens_encomenda_ibfk_1` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `produto_traducoes`
--
ALTER TABLE `produto_traducoes`
  ADD CONSTRAINT `fk_produto_traducoes_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

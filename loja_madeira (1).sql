-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Jan-2026 às 18:44
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
  `data_registo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `password`, `data_registo`) VALUES
(1, 'teste', 'aaaaa@gmail.com', '$2y$10$gKSxPDbKG4Kx4NsGb5tifuYeL8rxbW6tSLdSr1NVrCG/9aGlotCaK', '2026-01-20 14:46:51'),
(2, 'teste3', 'aaaab@gmail.com', '$2y$10$yUZuMW2aK7HWiLxhvbZR/.Lc2WhTYMzAq/HTr.hHjf5PKHlPbZLya', '2026-01-21 09:32:30');

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
(6, '2026-01-26 17:15:59', 19.99, 'Pendente', 4.99);

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
(9, 6, 'Caixa Branca', 1, 15.00, 15.00);

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
(14, 'Ramo de Sakura', 22.50, 'flores-azul-branco.png', 'flores', 0, 'Painel delicado inspirado nas flores de cerejeira orientais.');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `itens_encomenda`
--
ALTER TABLE `itens_encomenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `itens_encomenda`
--
ALTER TABLE `itens_encomenda`
  ADD CONSTRAINT `itens_encomenda_ibfk_1` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

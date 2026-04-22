-- Criar tabela de traduções de produtos
-- Modelo: 1 produto pode ter 0..N traduções (pt/en/fr/es/de)

CREATE TABLE IF NOT EXISTS produto_traducoes (
  produto_id INT NOT NULL,
  lang VARCHAR(5) NOT NULL,
  nome VARCHAR(255) NOT NULL,
  descricao TEXT NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (produto_id, lang),
  CONSTRAINT fk_produto_traducoes_produto
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_produto_traducoes_lang ON produto_traducoes (lang);


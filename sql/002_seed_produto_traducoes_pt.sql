-- Seed inicial: copiar o texto atual de `produtos` para PT
-- Nota: usa INSERT IGNORE para não duplicar se já existir

INSERT IGNORE INTO produto_traducoes (produto_id, lang, nome, descricao)
SELECT id, 'pt', nome, descricao
FROM produtos;


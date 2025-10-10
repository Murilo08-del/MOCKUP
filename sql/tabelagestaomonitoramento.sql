-- Tabela de Inspeções
CREATE TABLE IF NOT EXISTS inspecoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    status ENUM('Concluída', 'Pendente', 'Urgente') NOT NULL DEFAULT 'Pendente',
    data_inspecao DATE NOT NULL,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Alertas
CREATE TABLE IF NOT EXISTS alertas_manutencao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    prioridade ENUM('Alta', 'Média', 'Baixa') NOT NULL DEFAULT 'Média',
    data_alerta DATE NOT NULL,
    resolvido BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados de exemplo
INSERT INTO inspecoes (nome, status, data_inspecao, observacoes) VALUES
('Motor Principal', 'Concluída', '2025-04-10', 'Inspeção completa, tudo funcionando corretamente'),
('Sistema Hidráulico', 'Pendente', '2025-04-10', 'Aguardando peças de reposição'),
('Sistema Elétrico', 'Urgente', '2025-04-10', 'Detectado problema crítico no sistema');

INSERT INTO alertas_manutencao (titulo, descricao, prioridade, data_alerta) VALUES
('Manutenção urgente no Sistema Elétrico', 'Sistema apresentando falhas intermitentes', 'Alta', CURDATE());
-- Tabela de Ferrovias (Rotas principais)
CREATE TABLE IF NOT EXISTS ferrovias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativa BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Linhas (Linhas de cada ferrovia)
CREATE TABLE IF NOT EXISTS linhas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ferrovia_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(20),
    horario_inicio TIME,
    horario_fim TIME,
    intervalo_minutos INT,
    ativa BOOLEAN DEFAULT TRUE,
    cor VARCHAR(20),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ferrovia_id) REFERENCES ferrovias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Estações
CREATE TABLE IF NOT EXISTS estacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    linha_id INT NOT NULL,
    nome_estacao VARCHAR(150) NOT NULL,
    ordem INT NOT NULL,
    tempo_parada_minutos INT DEFAULT 2,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (linha_id) REFERENCES linhas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Horários
CREATE TABLE IF NOT EXISTS horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    linha_id INT NOT NULL,
    horario TIME NOT NULL,
    tipo ENUM('partida', 'chegada') DEFAULT 'partida',
    dias_semana SET('seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom') DEFAULT 'seg,ter,qua,qui,sex',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (linha_id) REFERENCES linhas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados de exemplo
INSERT INTO ferrovias (nome, descricao, ativa) VALUES
('Ferrovia Central', 'Principal via férrea da cidade', TRUE),
('Ferrovia Norte', 'Conecta a região norte ao centro', TRUE),
('Ferrovia Sul', 'Atende os bairros da zona sul', TRUE),
('Ferrovia Leste', 'Liga o centro à zona leste', TRUE),
('Ferrovia Oeste', 'Rota para a região oeste', TRUE);

-- Inserir linhas para cada ferrovia
INSERT INTO linhas (ferrovia_id, nome, codigo, horario_inicio, horario_fim, intervalo_minutos, cor, ativa) VALUES
-- Ferrovia Central
(1, 'Linha A - Expressa Central', 'CA', '05:00:00', '23:00:00', 15, '#FF0000', TRUE),
(1, 'Linha B - Local Central', 'CB', '06:00:00', '22:00:00', 20, '#0000FF', TRUE),
(1, 'Linha C - Noturna Central', 'CC', '22:00:00', '02:00:00', 30, '#800080', TRUE),
(1, 'Linha D - Rápida Central', 'CD', '05:30:00', '22:30:00', 18, '#FFA500', TRUE),

-- Ferrovia Norte
(2, 'Linha A - Expressa Norte', 'NA', '05:30:00', '23:30:00', 18, '#00FF00', TRUE),
(2, 'Linha B - Local Norte', 'NB', '06:00:00', '22:00:00', 25, '#FFA500', TRUE),

-- Ferrovia Sul
(3, 'Linha A - Expressa Sul', 'SA', '05:00:00', '23:00:00', 12, '#FFFF00', TRUE),
(3, 'Linha B - Local Sul', 'SB', '06:30:00', '21:30:00', 20, '#00FFFF', TRUE),

-- Ferrovia Leste
(4, 'Linha A - Expressa Leste', 'LA', '05:15:00', '23:15:00', 15, '#FF00FF', TRUE),
(4, 'Linha B - Local Leste', 'LB', '06:00:00', '22:00:00', 22, '#808080', TRUE),

-- Ferrovia Oeste
(5, 'Linha A - Expressa Oeste', 'OA', '05:00:00', '23:00:00', 16, '#008000', TRUE),
(5, 'Linha B - Local Oeste', 'OB', '06:00:00', '22:00:00', 20, '#000080', TRUE);

-- Inserir estações de exemplo (Linha A - Ferrovia Central)
INSERT INTO estacoes (linha_id, nome_estacao, ordem, tempo_parada_minutos) VALUES
(1, 'Estação Terminal Central', 1, 3),
(1, 'Estação Centro Comercial', 2, 2),
(1, 'Estação Jardins', 3, 2),
(1, 'Estação Universitária', 4, 2),
(1, 'Estação Industrial', 5, 3);

-- Inserir horários de exemplo (Linha A - Ferrovia Central)
INSERT INTO horarios (linha_id, horario, tipo, dias_semana) VALUES
(1, '05:00:00', 'partida', 'seg,ter,qua,qui,sex'),
(1, '06:30:00', 'partida', 'seg,ter,qua,qui,sex'),
(1, '10:58:00', 'partida', 'seg,ter,qua,qui,sex,sab,dom'),
(1, '12:00:00', 'partida', 'seg,ter,qua,qui,sex,sab,dom'),
(1, '18:23:00', 'partida', 'seg,ter,qua,qui,sex'),
(1, '22:00:00', 'partida', 'seg,ter,qua,qui,sex,sab,dom');
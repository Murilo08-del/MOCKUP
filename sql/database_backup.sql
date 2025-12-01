CREATE DATABASE IF NOT EXISTS Ferrovia 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE Ferrovia;

-- Tabela: usuarios — dados de login e perfil
CREATE TABLE IF NOT EXISTS usuarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL UNIQUE,
	senha VARCHAR(255) NOT NULL,
	tipo_usuario ENUM('admin', 'funcionario', 'comum') DEFAULT 'comum',
	foto_perfil VARCHAR(255),
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	ultimo_acesso TIMESTAMP NULL,
	status ENUM('ativo', 'inativo', 'bloqueado') DEFAULT 'ativo',
	INDEX idx_email (email),
	INDEX idx_tipo (tipo_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: estacoes — informações das estações
CREATE TABLE IF NOT EXISTS estacoes (
	id INT AUTO_INCREMENT PRIMARY KEY,
	codigo VARCHAR(20) NOT NULL UNIQUE,
	nome VARCHAR(100) NOT NULL,
	cidade VARCHAR(100) NOT NULL,
	estado CHAR(2) NOT NULL,
	endereco VARCHAR(255),
	latitude DECIMAL(10, 8),
	longitude DECIMAL(11, 8),
	capacidade INT DEFAULT 0,
	num_plataformas INT DEFAULT 0,
	acessibilidade BOOLEAN DEFAULT FALSE,
	telefone VARCHAR(20),
	email VARCHAR(100),
	status ENUM('ativa', 'inativa', 'manutencao') DEFAULT 'ativa',
	observacoes TEXT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	INDEX idx_codigo (codigo),
	INDEX idx_status (status),
	INDEX idx_cidade (cidade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: trens — cadastro da frota
CREATE TABLE IF NOT EXISTS trens (
	id INT AUTO_INCREMENT PRIMARY KEY,
	codigo VARCHAR(20) NOT NULL UNIQUE,
	nome VARCHAR(100) NOT NULL,
	tipo ENUM('expresso', 'regional', 'metropolitano', 'luxo', 'carga') NOT NULL,
	modelo VARCHAR(100),
	fabricante VARCHAR(100),
	ano_fabricacao YEAR,
	capacidade_passageiros INT,
	capacidade_carga DECIMAL(10, 2),
	velocidade_maxima DECIMAL(5, 2),
	consumo_medio DECIMAL(8, 2),
	ultima_manutencao DATE,
	proxima_manutencao DATE,
	km_rodados DECIMAL(12, 2) DEFAULT 0,
	status ENUM('operando', 'manutencao', 'inativo', 'em_viagem') DEFAULT 'inativo',
	observacoes TEXT,
	data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	INDEX idx_codigo (codigo),
	INDEX idx_status (status),
	INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: sensores — dispositivos IoT (trens/estações)
CREATE TABLE IF NOT EXISTS sensores (
	id INT AUTO_INCREMENT PRIMARY KEY,
	codigo VARCHAR(20) NOT NULL UNIQUE,
	nome VARCHAR(100) NOT NULL,
	tipo ENUM('temperatura', 'umidade', 'luminosidade', 'presenca', 'velocidade', 'pressao', 'acelerometro', 'gps') NOT NULL,
	modelo VARCHAR(50),
	localizacao VARCHAR(255) NOT NULL,
	topico_mqtt VARCHAR(100),
	unidade_medida VARCHAR(20),
	valor_minimo DECIMAL(10, 2),
	valor_maximo DECIMAL(10, 2),
	ultima_leitura DECIMAL(10, 2),
	data_ultima_leitura TIMESTAMP NULL,
	status ENUM('online', 'offline', 'manutencao', 'erro') DEFAULT 'offline',
	trem_id INT,
	estacao_id INT,
	descricao TEXT,
	data_instalacao DATE,
	data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (trem_id) REFERENCES trens(id) ON DELETE SET NULL,
	FOREIGN KEY (estacao_id) REFERENCES estacoes(id) ON DELETE SET NULL,
	INDEX idx_codigo (codigo),
	INDEX idx_status (status),
	INDEX idx_tipo (tipo),
	INDEX idx_trem (trem_id),
	INDEX idx_estacao (estacao_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: rotas — trechos entre estações
CREATE TABLE IF NOT EXISTS rotas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	codigo VARCHAR(20) NOT NULL UNIQUE,
	nome VARCHAR(100) NOT NULL,
	estacao_origem_id INT NOT NULL,
	estacao_destino_id INT NOT NULL,
	distancia DECIMAL(8, 2) NOT NULL,
	duracao_estimada INT NOT NULL COMMENT 'Em minutos',
	linha VARCHAR(50),
	coordenadas_gps TEXT COMMENT 'JSON com array de lat/lng',
	status ENUM('ativa', 'inativa', 'manutencao') DEFAULT 'ativa',
	observacoes TEXT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (estacao_origem_id) REFERENCES estacoes(id),
	FOREIGN KEY (estacao_destino_id) REFERENCES estacoes(id),
	INDEX idx_origem (estacao_origem_id),
	INDEX idx_destino (estacao_destino_id),
	INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: itinerarios — viagens (sequência de rotas)
CREATE TABLE IF NOT EXISTS itinerarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	codigo VARCHAR(20) NOT NULL UNIQUE,
	nome VARCHAR(100) NOT NULL,
	trem_id INT NOT NULL,
	distancia_total DECIMAL(10, 2) NOT NULL,
	duracao_total INT NOT NULL COMMENT 'Em minutos',
	status ENUM('ativo', 'inativo', 'planejado') DEFAULT 'planejado',
	descricao TEXT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (trem_id) REFERENCES trens(id),
	INDEX idx_trem (trem_id),
	INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: itinerarios_rotas — associação itinerários ↔ rotas
CREATE TABLE IF NOT EXISTS itinerarios_rotas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	itinerario_id INT NOT NULL,
	rota_id INT NOT NULL,
	ordem INT NOT NULL COMMENT 'Ordem da rota no itinerário',
	FOREIGN KEY (itinerario_id) REFERENCES itinerarios(id) ON DELETE CASCADE,
	FOREIGN KEY (rota_id) REFERENCES rotas(id) ON DELETE CASCADE,
	UNIQUE KEY unique_itinerario_rota (itinerario_id, rota_id),
	INDEX idx_itinerario (itinerario_id),
	INDEX idx_rota (rota_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: manutencoes — histórico de manutenção
CREATE TABLE IF NOT EXISTS manutencoes (
	id INT AUTO_INCREMENT PRIMARY KEY,
	trem_id INT NOT NULL,
	tipo ENUM('preventiva', 'corretiva', 'emergencial', 'revisao') NOT NULL,
	data_agendada DATE NOT NULL,
	data_realizada DATE,
	hora_inicio TIME,
	hora_fim TIME,
	descricao TEXT NOT NULL,
	tecnico_responsavel VARCHAR(100),
	custo DECIMAL(10, 2),
	km_trem INT COMMENT 'KM do trem no momento da manutenção',
	pecas_trocadas TEXT,
	observacoes TEXT,
	status ENUM('agendada', 'em_andamento', 'concluida', 'cancelada') DEFAULT 'agendada',
	usuario_id INT,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (trem_id) REFERENCES trens(id) ON DELETE CASCADE,
	FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
	INDEX idx_trem (trem_id),
	INDEX idx_data_agendada (data_agendada),
	INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: alertas — eventos gerados pelos sensores
CREATE TABLE IF NOT EXISTS alertas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	sensor_id INT NOT NULL,
	tipo ENUM('critico', 'aviso', 'info') NOT NULL,
	titulo VARCHAR(200) NOT NULL,
	descricao TEXT NOT NULL,
	valor_leitura DECIMAL(10, 2),
	data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	status ENUM('pendente', 'visualizado', 'resolvido') DEFAULT 'pendente',
	resolvido_por INT,
	data_resolucao TIMESTAMP NULL,
	observacao_resolucao TEXT,
	FOREIGN KEY (sensor_id) REFERENCES sensores(id) ON DELETE CASCADE,
	FOREIGN KEY (resolvido_por) REFERENCES usuarios(id) ON DELETE SET NULL,
	INDEX idx_sensor (sensor_id),
	INDEX idx_tipo (tipo),
	INDEX idx_status (status),
	INDEX idx_data (data_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: notificacoes — avisos/alertas para usuários
CREATE TABLE IF NOT EXISTS notificacoes (
	id INT AUTO_INCREMENT PRIMARY KEY,
	usuario_id INT,
	titulo VARCHAR(200) NOT NULL,
	mensagem TEXT NOT NULL,
	tipo ENUM('info', 'alerta', 'sucesso', 'erro') DEFAULT 'info',
	link VARCHAR(255),
	lida BOOLEAN DEFAULT FALSE,
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	data_leitura TIMESTAMP NULL,
	FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
	INDEX idx_usuario (usuario_id),
	INDEX idx_lida (lida),
	INDEX idx_data (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: chamados_manutencao — chamados de manutenção
CREATE TABLE IF NOT EXISTS chamados_manutencao (
	id INT AUTO_INCREMENT PRIMARY KEY,
	protocolo VARCHAR(20) NOT NULL UNIQUE,
	usuario_id INT NOT NULL,
	trem_id INT,
	estacao_id INT,
	tipo ENUM('trem', 'estacao', 'rota', 'sensor', 'outro') NOT NULL,
	prioridade ENUM('baixa', 'media', 'alta', 'urgente') DEFAULT 'media',
	titulo VARCHAR(200) NOT NULL,
	descricao TEXT NOT NULL,
	localizacao VARCHAR(255),
	fotos TEXT COMMENT 'JSON array com URLs das fotos',
	status ENUM('aberto', 'em_analise', 'em_andamento', 'resolvido', 'cancelado') DEFAULT 'aberto',
	tecnico_responsavel_id INT,
	data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	data_atribuicao TIMESTAMP NULL,
	data_resolucao TIMESTAMP NULL,
	solucao TEXT,
	avaliacao INT CHECK (avaliacao BETWEEN 1 AND 5),
	comentario_avaliacao TEXT,
	FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
	FOREIGN KEY (trem_id) REFERENCES trens(id) ON DELETE SET NULL,
	FOREIGN KEY (estacao_id) REFERENCES estacoes(id) ON DELETE SET NULL,
	FOREIGN KEY (tecnico_responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
	INDEX idx_protocolo (protocolo),
	INDEX idx_usuario (usuario_id),
	INDEX idx_status (status),
	INDEX idx_tipo (tipo),
	INDEX idx_data (data_abertura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: leituras_sensores — histórico de leituras
CREATE TABLE IF NOT EXISTS leituras_sensores (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
	sensor_id INT NOT NULL,
	valor DECIMAL(10, 2) NOT NULL,
	unidade VARCHAR(20),
	data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (sensor_id) REFERENCES sensores(id) ON DELETE CASCADE,
	INDEX idx_sensor_data (sensor_id, data_hora),
	INDEX idx_data (data_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserção de dados de exemplo

-- Estações exemplo
INSERT INTO estacoes (codigo, nome, cidade, estado, endereco, latitude, longitude, capacidade, num_plataformas, acessibilidade, status) VALUES
('EST-001', 'Estação Central', 'São Paulo', 'SP', 'Praça da Sé, Centro', -23.5505, -46.6333, 5000, 12, TRUE, 'ativa'),
('EST-002', 'Estação Norte', 'São Paulo', 'SP', 'Av. Santos Dumont, 1500', -23.5200, -46.6300, 3000, 8, TRUE, 'ativa'),
('EST-003', 'Estação Jundiaí', 'Jundiaí', 'SP', 'Centro, Jundiaí', -23.1900, -46.8900, 2000, 6, TRUE, 'ativa'),
('EST-004', 'Estação Campinas', 'Campinas', 'SP', 'Centro, Campinas', -22.9099, -47.0626, 2500, 6, TRUE, 'ativa'),
('EST-005', 'Estação Santos', 'Santos', 'SP', 'Av. Conselheiro Nébias, 200', -23.9608, -46.3334, 1800, 4, TRUE, 'ativa');

-- Trens exemplo
INSERT INTO trens (codigo, nome, tipo, modelo, capacidade_passageiros, velocidade_maxima, km_rodados, ultima_manutencao, status) VALUES
('TRM-007', 'Expresso Central', 'expresso', 'EMU-500', 450, 120.00, 125340.00, '2024-11-20', 'operando'),
('TRM-003', 'Regional Sul', 'regional', 'DMU-300', 350, 100.00, 98720.00, '2024-11-15', 'operando'),
('TRM-005', 'Metro Norte', 'metropolitano', 'METRO-400', 600, 80.00, 156890.00, '2024-11-10', 'operando'),
('TRM-012', 'Luxo Internacional', 'luxo', 'LUXE-200', 200, 150.00, 87450.00, '2024-11-22', 'manutencao');

-- Adicionar a coluna data_ultima_leitura na tabela sensores
ALTER TABLE sensores 
ADD COLUMN data_ultima_leitura DATETIME DEFAULT NULL AFTER ultima_leitura;

-- Atualizar com a data atual para os registros existentes que têm leitura
UPDATE sensores 
SET data_ultima_leitura = NOW() 
WHERE ultima_leitura IS NOT NULL;


-- Atualizar com a data atual para os registros existentes que têm leitura
UPDATE sensores 
SET data_ultima_leitura = NOW() 
WHERE ultima_leitura IS NOT NULL;

-- Atualizar com a data atual para os registros existentes que têm leitura
UPDATE sensores 
SET data_ultima_leitura = NOW() 
WHERE ultima_leitura IS NOT NULL;

-- Observação: rever se os UPDATEs acima são necessários (há repetições).

-- ==================== TABELA SENSORES ====================
-- Adicionar todas as colunas necessárias
ALTER TABLE sensores 
ADD COLUMN IF NOT EXISTS codigo VARCHAR(50) UNIQUE,
ADD COLUMN IF NOT EXISTS nome VARCHAR(255),
ADD COLUMN IF NOT EXISTS tipo VARCHAR(50),
ADD COLUMN IF NOT EXISTS modelo VARCHAR(100),
ADD COLUMN IF NOT EXISTS localizacao VARCHAR(255),
ADD COLUMN IF NOT EXISTS topico_mqtt VARCHAR(255),
ADD COLUMN IF NOT EXISTS unidade_medida VARCHAR(20),
ADD COLUMN IF NOT EXISTS valor_minimo DECIMAL(10,2),
ADD COLUMN IF NOT EXISTS valor_maximo DECIMAL(10,2),
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'offline',
ADD COLUMN IF NOT EXISTS ultima_leitura DECIMAL(10,2),
ADD COLUMN IF NOT EXISTS data_ultima_leitura DATETIME,
ADD COLUMN IF NOT EXISTS trem_id INT,
ADD COLUMN IF NOT EXISTS estacao_id INT,
ADD COLUMN IF NOT EXISTS descricao TEXT,
ADD COLUMN IF NOT EXISTS data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP;

-- ==================== TABELA TRENS ====================
ALTER TABLE trens
ADD COLUMN IF NOT EXISTS codigo VARCHAR(50) UNIQUE,
ADD COLUMN IF NOT EXISTS nome VARCHAR(255),
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'parado';

-- ==================== TABELA ESTACOES ====================
ALTER TABLE estacoes
ADD COLUMN IF NOT EXISTS codigo VARCHAR(50) UNIQUE,
ADD COLUMN IF NOT EXISTS nome VARCHAR(255),
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'ativa';

-- ==================== TABELA ROTAS ====================
ALTER TABLE rotas
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'ativa';

-- ==================== TABELA ALERTAS ====================
ALTER TABLE alertas
ADD COLUMN IF NOT EXISTS titulo VARCHAR(255),
ADD COLUMN IF NOT EXISTS descricao TEXT,
ADD COLUMN IF NOT EXISTS tipo VARCHAR(50),
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'pendente',
ADD COLUMN IF NOT EXISTS sensor_id INT,
ADD COLUMN IF NOT EXISTS data_hora DATETIME DEFAULT CURRENT_TIMESTAMP;

-- ==================== TABELA MANUTENCOES ====================
ALTER TABLE manutencoes
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'agendada';

-- ==================== GERAR CÓDIGOS AUTOMÁTICOS ====================
-- Para trens sem código
UPDATE trens 
SET codigo = CONCAT('TRN-', LPAD(id, 3, '0')) 
WHERE codigo IS NULL OR codigo = '';

-- Para estações sem código
UPDATE estacoes 
SET codigo = CONCAT('EST-', LPAD(id, 3, '0')) 
WHERE codigo IS NULL OR codigo = '';

-- Para sensores sem código
UPDATE sensores 
SET codigo = CONCAT('SENS-', LPAD(id, 3, '0')) 
WHERE codigo IS NULL OR codigo = '';

-- Atualizar data_ultima_leitura onde há leituras
UPDATE sensores 
SET data_ultima_leitura = NOW() 
WHERE ultima_leitura IS NOT NULL AND data_ultima_leitura IS NULL;


-- ==================== TABELA ITINERARIOS ====================
-- Adicionar todas as colunas necessárias
ALTER TABLE itinerarios
ADD COLUMN IF NOT EXISTS nome VARCHAR(255),
ADD COLUMN IF NOT EXISTS descricao TEXT,
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'planejado',
ADD COLUMN IF NOT EXISTS trem_id INT,
ADD COLUMN IF NOT EXISTS distancia_total DECIMAL(10,2),
ADD COLUMN IF NOT EXISTS duracao_total INT,
ADD COLUMN IF NOT EXISTS numero_rotas INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Adicionar foreign key para trem_id
ALTER TABLE itinerarios
ADD CONSTRAINT IF NOT EXISTS fk_itinerario_trem 
FOREIGN KEY (trem_id) REFERENCES trens(id) ON DELETE CASCADE;

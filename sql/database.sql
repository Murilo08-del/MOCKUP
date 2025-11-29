-- ==================================================
-- SISTEMA FERROVIÁRIO MINITRILHOS
-- Script de Criação do Banco de Dados MySQL
-- ==================================================

CREATE DATABASE IF NOT EXISTS Ferrovia 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE Ferrovia;

-- ==================================================
-- TABELA: usuarios
-- Armazena dados de login e informações dos usuários
-- ==================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'funcionario', 'comum') DEFAULT 'comum',
    telefone VARCHAR(20),
    foto_perfil VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL,
    status ENUM('ativo', 'inativo', 'bloqueado') DEFAULT 'ativo',
    INDEX idx_email (email),
    INDEX idx_tipo (tipo_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==================================================
-- TABELA: estacoes
-- Dados das estações ferroviárias
-- ==================================================
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

-- ==================================================
-- TABELA: trens
-- Cadastro da frota de trens
-- ==================================================
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

-- ==================================================
-- TABELA: sensores
-- Dispositivos IoT instalados nos trens e estações
-- ==================================================
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

-- ==================================================
-- TABELA: rotas
-- Rotas individuais entre estações
-- ==================================================
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

-- ==================================================
-- TABELA: itinerarios
-- Viagens compostas por múltiplas rotas
-- ==================================================
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

-- ==================================================
-- TABELA: itinerarios_rotas
-- Relacionamento entre itinerários e suas rotas
-- ==================================================
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

-- ==================================================
-- TABELA: manutencoes
-- Histórico de manutenções dos trens
-- ==================================================
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

-- ==================================================
-- TABELA: alertas
-- Alertas gerados pelos sensores
-- ==================================================
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

-- ==================================================
-- TABELA: notificacoes
-- Notificações gerais do sistema
-- ==================================================
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

-- ==================================================
-- TABELA: chamados_manutencao
-- Chamados abertos por usuários
-- ==================================================
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

-- ==================================================
-- TABELA: leituras_sensores
-- Histórico de leituras dos sensores
-- ==================================================
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

-- ==================================================
-- INSERÇÃO DE DADOS INICIAIS
-- ==================================================

-- Usuário administrador padrão (senha: password)
INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES
('Administrador', 'admin@minitrilhos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('João Silva', 'joao@minitrilhos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'funcionario'),
('Maria Santos', 'maria@usuario.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'comum');

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

-- ==================================================
-- FIM DO SCRIPT
-- ==================================================
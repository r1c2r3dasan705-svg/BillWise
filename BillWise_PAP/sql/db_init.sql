-- Script SQL para criar a base de dados BillWise (em Português)
-- Execute em MySQL (phpMyAdmin ou mysql CLI)

CREATE DATABASE IF NOT EXISTS billwise_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE billwise_db;

-- tabela de utilizadores
CREATE TABLE IF NOT EXISTS utilizadores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabela de orçamentos
CREATE TABLE IF NOT EXISTS orcamentos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT UNSIGNED NOT NULL,
  nome VARCHAR(255) NOT NULL,
  limite DECIMAL(12,2) NOT NULL DEFAULT 0,
  gasto DECIMAL(12,2) NOT NULL DEFAULT 0,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabela de despesas
CREATE TABLE IF NOT EXISTS despesas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT UNSIGNED NOT NULL,
  valor DECIMAL(12,2) NOT NULL,
  categoria VARCHAR(255) NOT NULL,
  data DATE NOT NULL,
  descricao VARCHAR(255) DEFAULT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabela de feedback/reclamações
CREATE TABLE IF NOT EXISTS feedback (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT UNSIGNED NULL,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  tipo ENUM('sugestao', 'reclamacao', 'elogio', 'bug') DEFAULT 'sugestao',
  assunto VARCHAR(255) NOT NULL,
  mensagem TEXT NOT NULL,
  status ENUM('pendente', 'em_analise', 'resolvido') DEFAULT 'pendente',
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


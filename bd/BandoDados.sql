CREATE DATABASE IF NOT EXISTS PlatPILeitura;
USE PlatPILeitura;

CREATE TABLE IF NOT EXISTS usuario (
    codUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(250) NOT NULL,
    email VARCHAR(250) UNIQUE NOT NULL,
    senha VARCHAR(50) NOT NULL
)DEFAULT CHARSET=utf8;

INSERT INTO usuario (nome, email, senha) VALUES ("admin", "admin@platleitura.com","adm25-Leitura3");


DROP DATABASE Lectus;
CREATE DATABASE IF NOT EXISTS Lectus;
USE Lectus;

CREATE TABLE IF NOT EXISTS Lectus.usuario(
    codUsuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(250) UNIQUE NOT NULL,
    senha VARCHAR(60) NOT NULL,
    nome VARCHAR(250) NOT NULL,
    dtNascimento DATE NOT NULL,
    adm BOOLEAN NOT NULL /* 0=usuario; 1=admin */
)DEFAULT CHARSET=utf8;

INSERT INTO usuario (email, senha, nome, dtNascimento, adm) VALUES
         ("usuarioAdmin@platleitura.com","adm25-Leitura3", "Administrador", "2025-03-20", true),
         ("usuarioTeste@platleitura.com", "UsuarioTestePI3", "Usuário Teste", "2025-03-20", false);

CREATE TABLE IF NOT EXISTS Lectus.autor(
	codAutor INT AUTO_INCREMENT PRIMARY KEY,
    autor VARCHAR(250) NOT NULL
)DEFAULT CHARSET=utf8;

INSERT INTO autor (autor) VALUES
	("Machado de Assis"),
    ("Clarice Lispector");

CREATE TABLE IF NOT EXISTS Lectus.categoria(
	codCategoria INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(100) NOT NULL
)DEFAULT CHARSET=utf8;

INSERT INTO categoria (categoria) VALUES
	("Antologia de crônicas"),
    ("Romance");

CREATE TABLE IF NOT EXISTS Lectus.editora(
	codEditora INT AUTO_INCREMENT PRIMARY KEY,
    editora VARCHAR(100) NOT NULL,
    site LONGTEXT NOT NULL
)DEFAULT CHARSET=utf8;

INSERT INTO editora (editora, site) VALUES
	("Editora Saraiva","https://www.editorasaraiva.com.br/"),
    ("Rocco", "https://rocco.com.br/"),
    ("Penguin-Companhia das Letras", "https://www.companhiadasletras.com.br/");
    
CREATE TABLE IF NOT EXISTS Lectus.faixaEtaria(
	codFaixaEtaria INT AUTO_INCREMENT PRIMARY KEY,
    faixaEtaria VARCHAR(100) NOT NULL,
    abreviacao VARCHAR(3) NOT NULL
)DEFAULT CHARSET=utf8;

INSERT INTO faixaEtaria (faixaEtaria, abreviacao) VALUES
	("Livre","L"),
    ("10 anos", "+10"),
    ("15 anos", "+15");
    
CREATE TABLE IF NOT EXISTS Lectus.livro(
	codLivro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(250) NOT NULL,
    ISBN INT NOT NULL,
    anoPublicacao INT NOT NULL,
    fk_faixaEtaria INT,
        FOREIGN KEY (fk_faixaEtaria) REFERENCES faixaEtaria(codFaixaEtaria)
        ON UPDATE CASCADE,
		/* ON DELETE CASCADE */

	fk_editora INT,
        FOREIGN KEY (fk_editora) REFERENCES editora(codEditora)
        ON UPDATE CASCADE    
)DEFAULT CHARSET=utf8;

INSERT INTO livro (titulo, ISBN, anoPublicacao, fk_faixaEtaria, fk_editora) VALUES
	("Memórias Póstumas de Brás Cubas", 9788534923224, 1811, 3, 3),
    ("Aprendendo a Viver", 978-8532511553, 2007, 3, 2);
    
CREATE TABLE IF NOT EXISTS Lectus.livro_has_autor(   
    fk_livro INT,
        FOREIGN KEY (fk_livro) REFERENCES livro(codLivro)
        ON UPDATE CASCADE
		ON DELETE CASCADE,

	fk_autor INT,
        FOREIGN KEY (fk_autor) REFERENCES autor(codAutor)
        ON UPDATE CASCADE    
        ON DELETE CASCADE
)DEFAULT CHARSET=utf8;

INSERT INTO livro_has_autor (fk_livro, fk_autor) VALUES
	(1, 1),
    (2, 2);

CREATE TABLE IF NOT EXISTS Lectus.livro_has_categoria(   
    fk_livro INT,
        FOREIGN KEY (fk_livro) REFERENCES livro(codLivro)
        ON UPDATE CASCADE
		ON DELETE CASCADE,

	fk_categoria INT,
        FOREIGN KEY (fk_categoria) REFERENCES categoria(codCategoria)
        ON UPDATE CASCADE    
        ON DELETE CASCADE
)DEFAULT CHARSET=utf8;

INSERT INTO livro_has_categoria (fk_livro, fk_categoria) VALUES
	(1, 2),
    (2, 1);

CREATE TABLE IF NOT EXISTS Lectus.edicao(
	codEdicao INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    autorImagem VARCHAR(100) NOT NULL,
    URL LONGTEXT NOT NULL,
    
    fk_livro INT,
        FOREIGN KEY (fk_livro) REFERENCES livro(codLivro)
        ON UPDATE CASCADE
		ON DELETE CASCADE 
)DEFAULT CHARSET=utf8;

INSERT INTO edicao (nome, autorImagem, URL, fk_livro) VALUES
    ("Classica", "Amazon", "https://m.media-amazon.com/images/I/91GAAzBixYL._UF1000,1000_QL80_.jpg", 1),
	("Classica", "Amazon", "https://m.media-amazon.com/images/I/618sqam55uL._UF1000,1000_QL80_.jpg", 2);

CREATE TABLE IF NOT EXISTS Lectus.resenha(
	codResenha INT AUTO_INCREMENT PRIMARY KEY,
    dataCriacao DATE NOT NULL,
    dataModificacao DATE NOT NULL,
    resenha LONGTEXT NOT NULL,
    
    fk_livro INT,
        FOREIGN KEY (fk_livro) REFERENCES livro(codLivro)
        ON UPDATE CASCADE
)DEFAULT CHARSET=utf8;

INSERT INTO resenha (dataCriacao, dataModificacao, resenha, fk_livro) VALUES
    ('2025-08-30', '2025-08-30', "TextoResenha TextoResenha TextoResenha", 1),
	('2025-08-30', '2025-08-30', "TextoResenha TextoResenha TextoResenha", 2);
    
CREATE TABLE IF NOT EXISTS Lectus.leitura(
	codLeitura INT AUTO_INCREMENT PRIMARY KEY,
    situacao INT NOT NULL, /*0-FINALIZADO  1-LENDO  2-QUEROLER*/
    dataInicio DATE NOT NULL,
    numPagina INT NOT NULL,
    paginaAtual INT NOT NULL,
    
    fk_livro INT,
        FOREIGN KEY (fk_livro) REFERENCES livro(codLivro)
        ON UPDATE CASCADE,
	
     fk_resenha INT,
        FOREIGN KEY (fk_resenha) REFERENCES resenha(codResenha)
        ON UPDATE CASCADE,
        
	fk_usuario INT,
        FOREIGN KEY (fk_usuario) REFERENCES usuario(codUsuario)
        ON UPDATE CASCADE
)DEFAULT CHARSET=utf8;

INSERT INTO leitura (situacao, dataInicio, numPagina, paginaAtual, fk_livro, fk_resenha, fk_usuario) VALUES
    (1, "2025-08-30", 368, 1, 1, 1, 2),
	(2, "2025-08-30", 144, 1, 2, 2, 2);
    
CREATE TABLE IF NOT EXISTS Lectus.comentario(
	codComentario INT AUTO_INCREMENT PRIMARY KEY,
    comentario LONGTEXT NOT NULL,
    
    fk_livro INT,
        FOREIGN KEY (fk_livro) REFERENCES livro(codLivro)
        ON UPDATE CASCADE,
        
	fk_usuario INT,
        FOREIGN KEY (fk_usuario) REFERENCES usuario(codUsuario)
        ON UPDATE CASCADE
)DEFAULT CHARSET=utf8;

INSERT INTO comentario (comentario, fk_livro, fk_usuario) VALUES
    ("Comentario Comentario Comentario", 1, 2),
	("Comentario Comentario Comentario", 2, 2);
    
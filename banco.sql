CREATE DATABASE IF NOT EXISTS biblioteca CHARACTER SET utf8mb4;
USE biblioteca;

CREATE TABLE aluno (
    matricula       VARCHAR(20)  PRIMARY KEY,
    nome            VARCHAR(100) NOT NULL,
    sexo            CHAR(1)      NOT NULL,
    data_nascimento DATE         NOT NULL
);

CREATE TABLE livro (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nome    VARCHAR(150) NOT NULL,
    editora VARCHAR(100) NOT NULL,
    edicao  INT          NOT NULL,
    autor   VARCHAR(100) NOT NULL,
    estoque INT          NOT NULL DEFAULT 0
);

CREATE TABLE emprestimo (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    livro_id        INT         NOT NULL,
    aluno_matricula VARCHAR(20) NOT NULL,
    data_emprestimo DATE        NOT NULL,
    data_devolucao  DATE        NOT NULL,
    FOREIGN KEY (livro_id)        REFERENCES livro(id),
    FOREIGN KEY (aluno_matricula) REFERENCES aluno(matricula)
);

-- Dados que estavam nos arquivos data/*.txt (para não perder o que você já cadastrou)
INSERT INTO aluno (matricula, nome, sexo, data_nascimento) VALUES
    ('258003', 'Lucas Willian Ferreira da Silva', 'M', '2008-06-11');

INSERT INTO livro (id, nome, editora, edicao, autor, estoque) VALUES
    (1, 'Ao Farol', 'Autêntica', 1, 'Virginia Woolf', 3895),
    (2, 'Sobre a arte de capturar sombras.', 'Sui', 1, 'heinsahamner', 11);

INSERT INTO emprestimo (id, livro_id, aluno_matricula, data_emprestimo, data_devolucao) VALUES
    (1, 1, '258003', '2026-05-27', '2026-06-05'),
    (2, 1, '258003', '2026-05-18', '2026-05-25');

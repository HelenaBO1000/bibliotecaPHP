<?php

class Livro {
    public $id;
    public $nome;
    public $editora;
    public $edicao;
    public $autor;
    public $estoque;

    public function __construct($id, $nome, $editora, $edicao, $autor, $estoque) {
        $this->id = $id;
        $this->nome = $nome;
        $this->editora = $editora;
        $this->edicao = $edicao;
        $this->autor = $autor;
        $this->estoque = $estoque;
    }

    // Grava o livro e guarda no objeto o id que o banco gerou (AUTO_INCREMENT).
    public function cadastrar($conn) {
        $stmt = $conn->prepare("INSERT INTO livro (nome, editora, edicao, autor, estoque) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssisi', $this->nome, $this->editora, $this->edicao, $this->autor, $this->estoque);
        $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
    }

    public static function listarTodos($conn) {
        $retorno = [];
        $resultado = $conn->query("SELECT id, nome, editora, edicao, autor, estoque FROM livro ORDER BY id");
        while ($linha = $resultado->fetch_assoc()) {
            $retorno[] = new Livro($linha['id'], $linha['nome'], $linha['editora'], $linha['edicao'], $linha['autor'], $linha['estoque']);
        }
        return $retorno;
    }

    // Retorna um Livro ou null se não existir.
    public static function findById($conn, $id) {
        $stmt = $conn->prepare("SELECT id, nome, editora, edicao, autor, estoque FROM livro WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($linha === null) {
            return null;
        }
        return new Livro($linha['id'], $linha['nome'], $linha['editora'], $linha['edicao'], $linha['autor'], $linha['estoque']);
    }

    public function alterar($conn) {
        $stmt = $conn->prepare("UPDATE livro SET nome = ?, editora = ?, edicao = ?, autor = ?, estoque = ? WHERE id = ?");
        $stmt->bind_param('ssisii', $this->nome, $this->editora, $this->edicao, $this->autor, $this->estoque, $this->id);
        $stmt->execute();
        $stmt->close();
    }

    public function remover($conn) {
        $stmt = $conn->prepare("DELETE FROM livro WHERE id = ?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $stmt->close();
    }

    // Livro com empréstimo não pode ser removido (chave estrangeira).
    public function possuiEmprestimos($conn) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM emprestimo WHERE livro_id = ?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) $linha['total'] > 0;
    }
}

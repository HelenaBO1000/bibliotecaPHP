<?php

class Aluno {
    public $nome;
    public $matricula;
    public $sexo;
    public $dataNasc;

    public function __construct($nome, $matricula, $sexo, $dataNasc) {
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->sexo = $sexo;
        $this->dataNasc = $dataNasc;
    }

    public function cadastrar($conn) {
        $stmt = $conn->prepare("INSERT INTO aluno (nome, matricula, sexo, data_nascimento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $this->nome, $this->matricula, $this->sexo, $this->dataNasc);
        $stmt->execute();
        $stmt->close();
    }

    // static: é chamado como Aluno::listarTodos($conn), sem precisar de um objeto.
    public static function listarTodos($conn) {
        $retorno = [];
        $resultado = $conn->query("SELECT nome, matricula, sexo, data_nascimento FROM aluno ORDER BY nome");
        while ($linha = $resultado->fetch_assoc()) {
            $retorno[] = new Aluno($linha['nome'], $linha['matricula'], $linha['sexo'], $linha['data_nascimento']);
        }
        return $retorno;
    }

    // Retorna um Aluno ou null se não existir.
    public static function findByMatricula($conn, $matricula) {
        $stmt = $conn->prepare("SELECT nome, matricula, sexo, data_nascimento FROM aluno WHERE matricula = ?");
        $stmt->bind_param('s', $matricula);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($linha === null) {
            return null;
        }
        return new Aluno($linha['nome'], $linha['matricula'], $linha['sexo'], $linha['data_nascimento']);
    }

    // A matrícula é a chave: ela identifica o aluno e não é alterada.
    public function alterar($conn) {
        $stmt = $conn->prepare("UPDATE aluno SET nome = ?, sexo = ?, data_nascimento = ? WHERE matricula = ?");
        $stmt->bind_param('ssss', $this->nome, $this->sexo, $this->dataNasc, $this->matricula);
        $stmt->execute();
        $stmt->close();
    }

    public function remover($conn) {
        $stmt = $conn->prepare("DELETE FROM aluno WHERE matricula = ?");
        $stmt->bind_param('s', $this->matricula);
        $stmt->execute();
        $stmt->close();
    }

    // Aluno com empréstimo não pode ser removido (chave estrangeira).
    public function possuiEmprestimos($conn) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM emprestimo WHERE aluno_matricula = ?");
        $stmt->bind_param('s', $this->matricula);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) $linha['total'] > 0;
    }
}

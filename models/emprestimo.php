<?php

class Emprestimo {
    public $id;
    public $livro;            // id do livro
    public $aluno;            // matrícula do aluno
    public $dataEmprestimo;
    public $dataDevolucao;

    // Só vêm preenchidos nas buscas (listarTodos / findById), que fazem JOIN
    // com livro e aluno para trazer os nomes.
    public $livroNome;
    public $alunoNome;

    public function __construct($id, $livro, $aluno, $dataEmprestimo, $dataDevolucao) {
        $this->id = $id;
        $this->livro = $livro;
        $this->aluno = $aluno;
        $this->dataEmprestimo = $dataEmprestimo;
        $this->dataDevolucao = $dataDevolucao;
    }

    // SELECT usado por listarTodos e findById (evita repetir o JOIN).
    private static $selectBase =
        "SELECT e.id, e.livro_id, e.aluno_matricula, e.data_emprestimo, e.data_devolucao,
                l.nome AS livro_nome, a.nome AS aluno_nome
         FROM emprestimo e
         INNER JOIN livro l ON l.id = e.livro_id
         INNER JOIN aluno a ON a.matricula = e.aluno_matricula";

    private static function montar($linha) {
        $emp = new Emprestimo($linha['id'], $linha['livro_id'], $linha['aluno_matricula'],
                              $linha['data_emprestimo'], $linha['data_devolucao']);
        $emp->livroNome = $linha['livro_nome'];
        $emp->alunoNome = $linha['aluno_nome'];
        return $emp;
    }

    public function cadastrar($conn) {
        $stmt = $conn->prepare("INSERT INTO emprestimo (livro_id, aluno_matricula, data_emprestimo, data_devolucao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $this->livro, $this->aluno, $this->dataEmprestimo, $this->dataDevolucao);
        $stmt->execute();
        $this->id = $conn->insert_id;
        $stmt->close();
    }

    public static function listarTodos($conn) {
        $retorno = [];
        $resultado = $conn->query(self::$selectBase . " ORDER BY e.id");
        while ($linha = $resultado->fetch_assoc()) {
            $retorno[] = self::montar($linha);
        }
        return $retorno;
    }

    // Retorna um Emprestimo ou null se não existir.
    public static function findById($conn, $id) {
        $stmt = $conn->prepare(self::$selectBase . " WHERE e.id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($linha === null) {
            return null;
        }
        return self::montar($linha);
    }

    public function alterar($conn) {
        $stmt = $conn->prepare("UPDATE emprestimo SET livro_id = ?, aluno_matricula = ?, data_emprestimo = ?, data_devolucao = ? WHERE id = ?");
        $stmt->bind_param('isssi', $this->livro, $this->aluno, $this->dataEmprestimo, $this->dataDevolucao, $this->id);
        $stmt->execute();
        $stmt->close();
    }

    public function remover($conn) {
        $stmt = $conn->prepare("DELETE FROM emprestimo WHERE id = ?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $stmt->close();
    }
}

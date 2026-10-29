<?php
require_once '../includes/auth.php';
require_once '../includes/conexao.php';
require_once '../models/aluno.php';

$matricula = isset($_GET['matricula']) ? trim($_GET['matricula']) : '';

$aluno = ($matricula !== '') ? Aluno::findByMatricula($conn, $matricula) : null;

if ($aluno === null) {
    header('Location: listar.php');
    exit;
}

// Aluno com empréstimo registrado não pode ser removido.
if ($aluno->possuiEmprestimos($conn)) {
    header('Location: listar.php?msg=emprestimos');
    exit;
}

$aluno->remover($conn);
header('Location: listar.php?msg=removido');
exit;

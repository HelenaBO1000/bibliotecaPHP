<?php
require_once '../includes/auth.php';
require_once '../includes/conexao.php';
require_once '../models/livro.php';

$id = (int) ($_GET['id'] ?? 0);

$livro = ($id > 0) ? Livro::findById($conn, $id) : null;

if ($livro === null) {
    header('Location: listar.php');
    exit;
}

// Livro com empréstimo registrado não pode ser removido.
if ($livro->possuiEmprestimos($conn)) {
    header('Location: listar.php?msg=emprestimos');
    exit;
}

$livro->remover($conn);
header('Location: listar.php?msg=removido');
exit;

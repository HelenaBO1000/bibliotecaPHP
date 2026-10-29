<?php
require_once '../includes/auth.php';
require_once '../includes/conexao.php';
require_once '../models/emprestimo.php';

$id = (int) ($_GET['id'] ?? 0);

$emprestimo = ($id > 0) ? Emprestimo::findById($conn, $id) : null;

if ($emprestimo !== null) {
    $emprestimo->remover($conn);
    header('Location: listar.php?msg=removido');
    exit;
}

header('Location: listar.php');
exit;

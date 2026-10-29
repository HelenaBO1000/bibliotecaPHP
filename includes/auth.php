<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    // Caminho absoluto: funciona tanto de /biblioteca/index.php quanto de /biblioteca/alunos/listar.php
    header('Location: /biblioteca/login.php');
    exit();
}

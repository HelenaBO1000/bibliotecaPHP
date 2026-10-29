<?php
// Faz o mysqli lançar exceção quando algo dá errado (conexão, SQL...).
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // host, usuário, senha, nome do banco
    // (no XAMPP o usuário "root" vem sem senha; se o seu tiver senha, coloque aqui)
    $conn = new mysqli('localhost', 'root', '', 'biblioteca');
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Erro ao conectar no banco de dados: ' . htmlspecialchars($e->getMessage()));
}

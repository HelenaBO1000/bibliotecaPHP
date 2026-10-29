<?php

$pagina_atual = $_SERVER['SCRIPT_NAME'];
echo '
<div class="app-shell">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <span class="brand-icon">📚</span>
      <h1>Biblioteca</h1>
      <p>Sistema de Gestão</p>
    </div>

    <nav class="sidebar-nav">
      <p class="nav-section-label">Geral</p>
      <a href="/biblioteca/index.php" class="nav-item ' . (strpos($pagina_atual, 'index.php') !== false ? 'active' : '') . '">
        <span class="nav-icon">🏠</span> Painel
      </a>

      <p class="nav-section-label">Cadastros</p>
      <a href="/biblioteca/livros/listar.php" class="nav-item ' . (strpos($pagina_atual, '/livros/') !== false ? 'active' : '') . '">
        <span class="nav-icon">📖</span> Livros
      </a>
      <a href="/biblioteca/alunos/listar.php" class="nav-item ' . (strpos($pagina_atual, '/alunos/') !== false ? 'active' : '') . '">
        <span class="nav-icon">🎓</span> Alunos
      </a>
      <a href="/biblioteca/emprestimos/listar.php" class="nav-item ' . (strpos($pagina_atual, '/emprestimos/') !== false ? 'active' : '') . '">
        <span class="nav-icon">📋</span> Empréstimos
      </a>
    </nav>

    <div class="sidebar-user">
';

echo mb_strtoupper(mb_substr($_SESSION['usuario'] ?? 'AD', 0, 2));
echo '<div class="user-info">';
echo htmlspecialchars($_SESSION['usuario'] ?? 'Admin');
echo '
      <div class="user-role">Bibliotecário</div>
    </div>
    <a href="/biblioteca/includes/logout.php" class="btn-logout" title="Sair">⏻</a>
  </div>
</aside>
';
?>
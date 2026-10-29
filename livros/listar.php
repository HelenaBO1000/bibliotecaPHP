<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livros — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/livro.php';
  require_once '../includes/navbar.php';

  $livros = Livro::listarTodos($conn);
  $msg    = $_GET['msg'] ?? '';
  ?>

  <!-- ════ MAIN ════ -->
  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">📖 Livros</div>
        <div class="topbar-sub">Gerenciamento do acervo</div>
      </div>
      <div class="topbar-actions">
        <a href="cadastrar.php" class="btn btn-primary">＋ Novo livro</a>
      </div>
    </div>

    <div class="content">

      <?php if ($msg === 'cadastrado'): ?>
        <div class="alert alert-success">✓ Livro cadastrado com sucesso!</div>
      <?php elseif ($msg === 'alterado'): ?>
        <div class="alert alert-success">✓ Livro atualizado com sucesso!</div>
      <?php elseif ($msg === 'removido'): ?>
        <div class="alert alert-success">✓ Livro removido com sucesso!</div>
      <?php elseif ($msg === 'emprestimos'): ?>
        <div class="alert alert-error">⚠ Não é possível remover: este livro possui empréstimos registrados.</div>
      <?php endif; ?>

      <div class="table-header">
        <h2>Acervo: <?= count($livros) ?> livros</h2>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Autor</th>
              <th>Editora</th>
              <th>Edição</th>
              <th>Estoque</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>

              <?php foreach ($livros as $livro): ?>
              <tr>
                <td class="td-mono"><?= htmlspecialchars($livro->id) ?></td>
                <td class="td-main"><?= htmlspecialchars($livro->nome) ?></td>
                <td><?= htmlspecialchars($livro->autor) ?></td>
                <td><?= htmlspecialchars($livro->editora) ?></td>
                <td style="text-align:center"><?= htmlspecialchars($livro->edicao) ?></td>
                <td>
                  <span class="badge badge-stock"><?= htmlspecialchars($livro->estoque) ?> un.</span>
                </td>
                <td class="td-actions">
                  <a href="alterar.php?id=<?= urlencode($livro->id) ?>" class="btn btn-sm btn-edit">Editar</a>
                  <a href="remover.php?id=<?= urlencode($livro->id) ?>"
                     onclick="return confirm('Remover este livro?')"
                     class="btn btn-sm btn-danger">Remover</a>
                </td>
              </tr>
              <?php endforeach; ?>

          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>

</body>
</html>

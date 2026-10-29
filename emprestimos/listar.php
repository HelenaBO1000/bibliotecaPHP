<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Empréstimos — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/emprestimo.php';
  require_once '../includes/navbar.php';

  // O JOIN feito no model já traz o nome do livro e do aluno em cada empréstimo.
  $emprestimos = Emprestimo::listarTodos($conn);
  $msg         = $_GET['msg'] ?? '';
  ?>

  <!-- ════ MAIN ════ -->
  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">📋 Empréstimos</div>
        <div class="topbar-sub">Controle de empréstimos e devoluções</div>
      </div>
      <div class="topbar-actions">
        <a href="cadastrar.php" class="btn btn-primary">＋ Novo empréstimo</a>
      </div>
    </div>

    <div class="content">

      <?php if ($msg === 'cadastrado'): ?>
        <div class="alert alert-success">✓ Empréstimo registrado com sucesso!</div>
      <?php elseif ($msg === 'alterado'): ?>
        <div class="alert alert-success">✓ Empréstimo atualizado com sucesso!</div>
      <?php elseif ($msg === 'removido'): ?>
        <div class="alert alert-success">✓ Empréstimo removido com sucesso!</div>
      <?php endif; ?>

      <div class="table-header">
        <h2>Empréstimos: <?= count($emprestimos) ?></h2>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Livro</th>
              <th>Aluno</th>
              <th>Matrícula</th>
              <th>Empréstimo</th>
              <th>Devolução</th>
              <th>Situação</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>

              <?php foreach ($emprestimos as $emp): ?>
              <?php $atrasado = $emp->dataDevolucao < date('Y-m-d'); ?>
              <tr>
                <td class="td-mono"><?= htmlspecialchars($emp->id) ?></td>
                <td class="td-main"><?= htmlspecialchars($emp->livroNome) ?></td>
                <td><?= htmlspecialchars($emp->alunoNome) ?></td>
                <td class="td-mono"><?= htmlspecialchars($emp->aluno) ?></td>
                <td><?= date('d/m/Y', strtotime($emp->dataEmprestimo)) ?></td>
                <td><?= date('d/m/Y', strtotime($emp->dataDevolucao)) ?></td>
                <td>
                  <span class="badge <?= $atrasado ? 'badge-late' : 'badge-ok' ?>">
                    <?= $atrasado ? '⚠ Atrasado' : '✓ No prazo' ?>
                  </span>
                </td>
                <td class="td-actions">
                  <a href="alterar.php?id=<?= urlencode($emp->id) ?>" class="btn btn-sm btn-edit">Editar</a>
                  <a href="remover.php?id=<?= urlencode($emp->id) ?>"
                     onclick="return confirm('Remover este empréstimo?')"
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alunos — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/aluno.php';
  require_once '../includes/navbar.php';

  $alunos = Aluno::listarTodos($conn);
  $msg    = $_GET['msg'] ?? '';
  ?>

  <!-- ════ MAIN ════ -->
  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">🎓 Alunos</div>
        <div class="topbar-sub">Gerenciamento de alunos cadastrados</div>
      </div>
      <div class="topbar-actions">
        <a href="cadastrar.php" class="btn btn-primary">＋ Novo aluno</a>
      </div>
    </div>

    <div class="content">

      <?php if ($msg === 'cadastrado'): ?>
        <div class="alert alert-success">✓ Aluno cadastrado com sucesso!</div>
      <?php elseif ($msg === 'alterado'): ?>
        <div class="alert alert-success">✓ Aluno atualizado com sucesso!</div>
      <?php elseif ($msg === 'removido'): ?>
        <div class="alert alert-success">✓ Aluno removido com sucesso!</div>
      <?php elseif ($msg === 'emprestimos'): ?>
        <div class="alert alert-error">⚠ Não é possível remover: este aluno possui empréstimos registrados.</div>
      <?php endif; ?>

      <div class="table-header">
        <h2>Alunos</h2>
        <span class="table-count"><?= count($alunos) ?> aluno(s)</span>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>Matrícula</th>
              <th>Sexo</th>
              <th>Nascimento</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>

              <?php foreach ($alunos as $aluno): ?>
              <tr>
                <td class="td-main"><?= htmlspecialchars($aluno->nome) ?></td>
                <td class="td-mono"><?= htmlspecialchars($aluno->matricula) ?></td>
                <td>
                  <?php if ($aluno->sexo === 'M'): ?>
                    <span class="badge badge-masc">♂ Masc.</span>
                  <?php else: ?>
                    <span class="badge badge-fem">♀ Fem.</span>
                  <?php endif; ?>
                </td>
                <td><?= date('d/m/Y', strtotime($aluno->dataNasc)) ?></td>
                <td class="td-actions">
                  <a href="alterar.php?matricula=<?= urlencode($aluno->matricula) ?>" class="btn btn-sm btn-edit">Editar</a>
                  <a href="remover.php?matricula=<?= urlencode($aluno->matricula) ?>"
                     onclick="return confirm('Remover este aluno?')"
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

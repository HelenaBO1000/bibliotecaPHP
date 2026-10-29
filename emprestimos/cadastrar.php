<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Novo Empréstimo — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/livro.php';
  require_once '../models/aluno.php';
  require_once '../models/emprestimo.php';
  require_once '../includes/navbar.php';

  $livros = Livro::listarTodos($conn);
  $alunos = Aluno::listarTodos($conn);
  $erro   = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idLivro        = trim($_POST['idLivro']);
      $matricula      = trim($_POST['matricula']);
      $dataEmprestimo = trim($_POST['dataEmprestimo']);
      $dataDevolucao  = trim($_POST['dataDevolucao']);

      if (!$idLivro || !$matricula || !$dataEmprestimo || !$dataDevolucao) {
          $erro = 'Preencha todos os campos obrigatórios.';
      } elseif ($dataDevolucao <= $dataEmprestimo) {
          $erro = 'A data de devolução deve ser posterior à data do empréstimo.';
      } elseif (Livro::findById($conn, (int) $idLivro) === null) {
          $erro = 'Livro não encontrado.';
      } elseif (Aluno::findByMatricula($conn, $matricula) === null) {
          $erro = 'Aluno não encontrado.';
      } else {
          $emprestimo = new Emprestimo(null, (int) $idLivro, $matricula, $dataEmprestimo, $dataDevolucao);
          $emprestimo->cadastrar($conn);
          header('Location: listar.php?msg=cadastrado');
          exit;
      }
  }
  ?>

  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">➕ Novo Empréstimo</div>
        <div class="topbar-sub">Registrar saída de livro</div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

      <?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form action="cadastrar.php" method="POST">
        <div class="form-card">

          <div class="form-row">
            <div class="form-group">
              <label for="idLivro">Livro *</label>
              <select id="idLivro" name="idLivro" required>
                <option value="">Selecione um livro</option>
                <?php foreach ($livros as $livro): ?>
                  <option value="<?= htmlspecialchars($livro->id) ?>"
                    <?= (($_POST['idLivro'] ?? '') == $livro->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$livro->id} – {$livro->nome} (Estoque: {$livro->estoque})") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="matricula">Aluno *</label>
              <select id="matricula" name="matricula" required>
                <option value="">Selecione um aluno</option>
                <?php foreach ($alunos as $aluno): ?>
                  <option value="<?= htmlspecialchars($aluno->matricula) ?>"
                    <?= ((string) ($_POST['matricula'] ?? '') === (string) $aluno->matricula) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$aluno->nome} (Mat: {$aluno->matricula})") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="dataEmprestimo">Data do empréstimo *</label>
              <input type="date" id="dataEmprestimo" name="dataEmprestimo" required
              value="<?= htmlspecialchars($_POST['dataEmprestimo'] ?? date('Y-m-d')) ?>">
            </div>
            <div class="form-group">
              <label for="dataDevolucao">Data de devolução *</label>
              <input type="date" id="dataDevolucao" name="dataDevolucao" required
              value="<?= htmlspecialchars($_POST['dataDevolucao'] ?? '') ?>">
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Registrar empréstimo</button>
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div>
      </form>

    </div>
  </main>
</div>

</body>
</html>

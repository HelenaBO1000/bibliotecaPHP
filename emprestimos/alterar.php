<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Empréstimo — Biblioteca</title>
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

  $id         = (int) ($_GET['id'] ?? 0);
  $emprestimo = Emprestimo::findById($conn, $id);

  if ($emprestimo === null) {
      header('Location: listar.php'); exit;
  }

  $livros = Livro::listarTodos($conn);
  $alunos = Aluno::listarTodos($conn);
  $erro   = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idLivro        = trim($_POST['idLivro']);
      $matricula      = trim($_POST['matricula']);
      $dataEmprestimo = trim($_POST['dataEmprestimo']);
      $dataDevolucao  = trim($_POST['dataDevolucao']);

      if (!$idLivro || !$matricula || !$dataEmprestimo || !$dataDevolucao) {
          $erro = 'Preencha todos os campos.';
      } elseif ($dataDevolucao <= $dataEmprestimo) {
          $erro = 'A data de devolução deve ser posterior à data do empréstimo.';
      } elseif (Livro::findById($conn, (int) $idLivro) === null) {
          $erro = 'Livro não encontrado.';
      } elseif (Aluno::findByMatricula($conn, $matricula) === null) {
          $erro = 'Aluno não encontrado.';
      } else {
          $emprestimo->livro          = (int) $idLivro;
          $emprestimo->aluno          = $matricula;
          $emprestimo->dataEmprestimo = $dataEmprestimo;
          $emprestimo->dataDevolucao  = $dataDevolucao;
          $emprestimo->alterar($conn);
          header('Location: listar.php?msg=alterado'); exit;
      }
  }

  // Se o formulário foi enviado com erro, mantém o que a pessoa escolheu;
  // senão, mostra os dados que vieram do banco.
  $dados = [
      'idLivro'        => $_POST['idLivro']        ?? $emprestimo->livro,
      'matricula'      => $_POST['matricula']      ?? $emprestimo->aluno,
      'dataEmprestimo' => $_POST['dataEmprestimo'] ?? $emprestimo->dataEmprestimo,
      'dataDevolucao'  => $_POST['dataDevolucao']  ?? $emprestimo->dataDevolucao,
  ];
  ?>

  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">✏️ Editar Empréstimo</div>
        <div class="topbar-sub">Registro nº <?= htmlspecialchars($emprestimo->id) ?></div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

      <?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form action="alterar.php?id=<?= urlencode($emprestimo->id) ?>" method="POST">
        <div class="form-card">

          <div class="form-row">
            <div class="form-group">
              <label for="idLivro">Livro *</label>
              <select id="idLivro" name="idLivro" required>
                <option value="">Selecione um livro</option>
                <?php foreach ($livros as $livro): ?>
                  <option value="<?= htmlspecialchars($livro->id) ?>"
                    <?= ($dados['idLivro'] == $livro->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$livro->id} – {$livro->nome}") ?>
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
                    <?= ((string) $dados['matricula'] === (string) $aluno->matricula) ? 'selected' : '' ?>>
                    <?= htmlspecialchars("{$aluno->nome} ({$aluno->matricula})") ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="dataEmprestimo">Data do empréstimo *</label>
              <input type="date" id="dataEmprestimo" name="dataEmprestimo" required
                value="<?= htmlspecialchars($dados['dataEmprestimo']) ?>">
            </div>
            <div class="form-group">
              <label for="dataDevolucao">Data de devolução *</label>
              <input type="date" id="dataDevolucao" name="dataDevolucao" required
                value="<?= htmlspecialchars($dados['dataDevolucao']) ?>">
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div>
      </form>

    </div>
  </main>
</div>

</body>
</html>

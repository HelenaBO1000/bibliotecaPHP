<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Aluno — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/aluno.php';
  require_once '../includes/navbar.php';

  $matricula = $_GET['matricula'] ?? '';

  $aluno = Aluno::findByMatricula($conn, $matricula);
  if ($aluno === null) { header('Location: listar.php'); exit; }

  $erro = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome     = trim($_POST['nome']);
      $sexo     = $_POST['sexo'] ?? '';
      $dataNasc = trim($_POST['dataNascimento']);

      if (!$nome || !$sexo || !$dataNasc) {
          $erro = 'Preencha todos os campos obrigatórios.';
      } else {
          $aluno->nome     = $nome;
          $aluno->sexo     = $sexo;
          $aluno->dataNasc = $dataNasc;
          $aluno->alterar($conn);
          header('Location: listar.php?msg=alterado');
          exit;
      }
  }

  // Se o formulário foi enviado com erro, mantém o que a pessoa digitou;
  // senão, mostra os dados que vieram do banco.
  $dados = [
      'nome'           => $_POST['nome']           ?? $aluno->nome,
      'sexo'           => $_POST['sexo']           ?? $aluno->sexo,
      'dataNascimento' => $_POST['dataNascimento'] ?? $aluno->dataNasc,
  ];
  ?>

  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">✏️ Editar Aluno</div>
        <div class="topbar-sub">Matrícula: <strong><?= htmlspecialchars($matricula) ?></strong></div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

      <?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form action="alterar.php?matricula=<?= urlencode($matricula) ?>" method="POST">
        <div class="form-card">

          <!-- Matrícula só exibe, não edita -->
          <div class="form-row cols-1" style="margin-bottom:1.25rem">
            <div class="form-group">
              <label>Matrícula (não editável)</label>
              <input type="text" value="<?= htmlspecialchars($matricula) ?>" disabled
                     style="opacity:.5;cursor:not-allowed">
            </div>
          </div>

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="nome">Nome completo *</label>
              <input type="text" id="nome" name="nome"
               value="<?= htmlspecialchars($dados['nome']) ?>"
               required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
            <label for="sexo">Sexo *</label>
            <select id="sexo" name="sexo" required>
              <option value="">Selecione</option>
              <option value="M" <?= ($dados['sexo'] === 'M') ? 'selected' : '' ?>>Masculino</option>
              <option value="F" <?= ($dados['sexo'] === 'F') ? 'selected' : '' ?>>Feminino</option>
            </select>
            </div>
            <div class="form-group">
              <label for="dataNascimento">Data de nascimento *</label>
              <input type="date" id="dataNascimento" name="dataNascimento"
               value="<?= htmlspecialchars($dados['dataNascimento']) ?>"
               required>
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

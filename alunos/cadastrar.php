<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Aluno — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/aluno.php';
  require_once '../includes/navbar.php';
  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome      = trim($_POST['nome']);
      $matricula = trim($_POST['matricula']);
      $sexo      = $_POST['sexo'] ?? '';
      $dataNasc  = trim($_POST['dataNascimento']);

      if (!$nome || !$matricula || !$sexo || !$dataNasc) {
          $erro = 'Preencha todos os campos obrigatórios.';
      } elseif (Aluno::findByMatricula($conn, $matricula) !== null) {
          $erro = 'Matrícula já cadastrada.';
      } else {
          $aluno = new Aluno($nome, $matricula, $sexo, $dataNasc);
          $aluno->cadastrar($conn);
          header('Location: listar.php?msg=cadastrado');
          exit;
      }
  }
  ?>

  <!-- ════ MAIN ════ -->
  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">➕ Cadastrar Aluno</div>
        <div class="topbar-sub">Adicionar novo aluno ao sistema</div>
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
            <div class="form-group full">
              <label for="nome">Nome completo *</label>
              <input type="text" id="nome" name="nome"
                     placeholder="Ex: Ana Beatriz Souza"
                     value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                     required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="matricula">Matrícula *</label>
              <input type="text" id="matricula" name="matricula"
                     placeholder="Ex: 2024009"
                     value="<?= htmlspecialchars($_POST['matricula'] ?? '') ?>"
                     required>
            </div>
            <div class="form-group">
            <label for="sexo">Sexo *</label>
            <select id="sexo" name="sexo" required>
              <option value="">Selecione</option>
              <option value="M" <?= (($_POST['sexo'] ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
              <option value="F" <?= (($_POST['sexo'] ?? '') === 'F') ? 'selected' : '' ?>>Feminino</option>
            </select>
            </div>
          </div>

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="dataNascimento">Data de nascimento *</label>
              <input type="date" id="dataNascimento" name="dataNascimento"
              value="<?= htmlspecialchars($_POST['dataNascimento'] ?? '') ?>"
              required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cadastrar aluno</button>
            <a href="listar.php" class="btn btn-ghost">Cancelar</a>
          </div>

        </div>
      </form>

    </div>
  </main>
</div>

</body>
</html>

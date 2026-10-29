<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Livro — Biblioteca</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <?php
  require_once '../includes/auth.php';
  require_once '../includes/conexao.php';
  require_once '../models/livro.php';
  require_once '../includes/navbar.php';

  $id    = (int) ($_GET['id'] ?? 0);
  $livro = Livro::findById($conn, $id);
  if ($livro === null) { header('Location: listar.php'); exit; }

  $erro = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nome    = trim($_POST['nome']);
      $editora = trim($_POST['editora']);
      $edicao  = trim($_POST['edicao']);
      $autor   = trim($_POST['autor']);
      $estoque = trim($_POST['estoque']);

      if ($nome === '' || $editora === '' || $edicao === '' || $autor === '' || $estoque === '') {
          $erro = 'Preencha todos os campos obrigatórios.';
      } elseif (!ctype_digit($edicao) || (int) $edicao < 1) {
          $erro = 'A edição deve ser um número inteiro maior que zero.';
      } elseif (!ctype_digit($estoque)) {
          $erro = 'O estoque deve ser um número inteiro (zero ou mais).';
      } else {
          $livro->nome    = $nome;
          $livro->editora = $editora;
          $livro->edicao  = (int) $edicao;
          $livro->autor   = $autor;
          $livro->estoque = (int) $estoque;
          $livro->alterar($conn);
          header('Location: listar.php?msg=alterado');
          exit;
      }
  }

  // Se o formulário foi enviado com erro, mantém o que a pessoa digitou;
  // senão, mostra os dados que vieram do banco.
  $dados = [
      'nome'    => $_POST['nome']    ?? $livro->nome,
      'editora' => $_POST['editora'] ?? $livro->editora,
      'edicao'  => $_POST['edicao']  ?? $livro->edicao,
      'autor'   => $_POST['autor']   ?? $livro->autor,
      'estoque' => $_POST['estoque'] ?? $livro->estoque,
  ];
  ?>

  <main class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">✏️ Editar Livro <span style="font-size:.9rem;color:var(--ink3)">#<?= htmlspecialchars($livro->id) ?></span></div>
        <div class="topbar-sub">Alterar dados do título</div>
      </div>
      <div class="topbar-actions">
        <a href="listar.php" class="btn btn-ghost">← Voltar</a>
      </div>
    </div>

    <div class="content">

      <?php if ($erro): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form action="alterar.php?id=<?= urlencode($livro->id) ?>" method="POST">
        <div class="form-card">

          <div class="form-row cols-1">
            <div class="form-group">
              <label for="nome">Título do livro *</label>
              <input type="text" id="nome" name="nome"
                value="<?= htmlspecialchars($dados['nome']) ?>"
                required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="autor">Autor *</label>
              <input type="text" id="autor" name="autor"
                value="<?= htmlspecialchars($dados['autor']) ?>"
                required>
            </div>
            <div class="form-group">
              <label for="editora">Editora *</label>
              <input type="text" id="editora" name="editora"
               value="<?= htmlspecialchars($dados['editora']) ?>"
               required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="edicao">Edição *</label>
              <input type="number" id="edicao" name="edicao" min="1"
                value="<?= htmlspecialchars($dados['edicao']) ?>"
                required>
            </div>
            <div class="form-group">
              <label for="estoque">Quantidade em estoque *</label>
              <input type="number" id="estoque" name="estoque" min="0"
               value="<?= htmlspecialchars($dados['estoque']) ?>"
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

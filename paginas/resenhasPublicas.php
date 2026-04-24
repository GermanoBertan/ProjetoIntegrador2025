<?php
// resenhas_publicas.php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

// obter codLivro via GET
$codLivro = isset($_GET['codLivro']) ? intval($_GET['codLivro']) : 0;
if ($codLivro <= 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Livro inválido.</div></div>";
    exit;
}

// mensagem via sessão (se houver)
if (!empty($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Busca título do livro (opcional, para cabeçalho)
$stmtBook = $conexao->prepare("SELECT titulo FROM livro WHERE codLivro = ? LIMIT 1");
$stmtBook->bind_param("i", $codLivro);
$stmtBook->execute();
$resBook = $stmtBook->get_result();
$book = $resBook->fetch_assoc() ?: null;
$stmtBook->close();

// Busca resenhas públicas vinculadas ao livro
// Observação: garantimos que r.resenha não esteja vazia
$sql = "
    SELECT r.codResenha, r.resenha, r.dataCriacao, r.dataModificacao,
           u.nome AS autorNome, u.codUsuario AS autorId, le.nota
    FROM resenha AS r
    INNER JOIN leitura AS le ON le.fk_resenha = r.codResenha
    INNER JOIN usuario AS u ON u.codUsuario = le.fk_usuario
    WHERE le.fk_livro = ? AND r.privado = 0 AND TRIM(r.resenha) <> ''
    ORDER BY COALESCE(r.dataModificacao, r.dataCriacao) DESC
";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $codLivro);
$stmt->execute();
$res = $stmt->get_result();
$resenhas = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Resenhas públicas - <?= htmlspecialchars($book['titulo'] ?? 'Livro') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .star { color: orange; font-size: 1.1rem; }
    .resenha-card { white-space: pre-wrap; }
  </style>
</head>
<body class="bg-light">

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Resenhas Públicas <?= $book ? '— '.htmlspecialchars($book['titulo']) : '' ?></h3>
    <div>
      <a href="../index.php?menu=livro&codLivro=<?= $codLivro ?>" class="btn btn-outline-secondary btn-sm">← Voltar ao livro</a>
      <a href="../index.php?menu=inicio" class="btn btn-outline-primary btn-sm ms-2">Início</a>
    </div>
  </div>

  <?php if (empty($resenhas)): ?>
    <div class="alert alert-info">Ainda não há resenhas públicas para este livro.</div>
  <?php else: ?>

    <div class="row g-3">
      <?php foreach ($resenhas as $r): ?>
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <strong><?= htmlspecialchars($r['autorNome'] ?? 'Usuário') ?></strong>
                  <div class="text-muted small">
                    <?= date('d/m/Y H:i', strtotime($r['dataModificacao'] ?? $r['dataCriacao'])) ?>
                  </div>
                </div>

                <div class="text-end">
                  <?php
                    $nota = isset($r['nota']) ? intval($r['nota']) : 0;
                    if ($nota > 0):
                  ?>
                    <!-- mostra estrelas -->
                    <div aria-label="Avaliação: <?= $nota ?> de 5">
                      <?php for ($i=1;$i<=5;$i++): ?>
                        <span class="star"><?= $i <= $nota ? '★' : '☆' ?></span>
                      <?php endfor; ?>
                    </div>
                  <?php else: ?>
                    <div class="text-muted small">Sem avaliação</div>
                  <?php endif; ?>
                </div>
              </div>

              <hr>

              <div class="resenha-card">
                <?= nl2br(htmlspecialchars($r['resenha'])) ?>
              </div>

              <div class="mt-3 d-flex justify-content-end">
                <!-- mostrar data de modificação se existir -->
                <?php if (!empty($r['dataModificacao'])): ?>
                  <small class="text-muted align-self-center">Atualizado em <?= date('d/m/Y H:i', strtotime($r['dataModificacao'])) ?></small>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

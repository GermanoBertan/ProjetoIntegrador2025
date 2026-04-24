<?php
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

$codLivro = isset($_GET['codLivro']) ? intval($_GET['codLivro']) : 0;
if ($codLivro <= 0) {
    echo "Livro inválido.";
    exit;
}

// Busca dados do livro (sinopse/descricao vem de li.descricao)
$stmt = $conexao->prepare("
    SELECT li.codLivro, li.titulo, li.descricao, ed.URL, li.nPagina, li.anoPublicacao, edi.editora, li.ISBN, fe.faixaEtaria, fe.abreviacao
    FROM livro AS li
    LEFT JOIN edicao AS ed ON ed.fk_livro = li.codLivro
    LEFT JOIN editora AS edi ON edi.codEditora = li.fk_editora
    INNER JOIN faixaetaria AS fe ON li.fk_faixaEtaria = fe.codFaixaEtaria
    WHERE li.codLivro = ?
    LIMIT 1
");
$stmt->bind_param("i", $codLivro);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo "Livro não encontrado.";
    exit;
}
$livro = $res->fetch_assoc();
$stmt->close();

// Busca comentários públicos do livro (sempre públicos)
$stmtC = $conexao->prepare("
    SELECT c.codComentario, c.comentario, c.data, u.nome AS autorNome
    FROM comentario AS c
    LEFT JOIN usuario AS u ON u.codUsuario = c.fk_usuario
    WHERE c.fk_livro = ?
    ORDER BY c.data DESC
");
$stmtC->bind_param("i", $codLivro);
$stmtC->execute();
$rsC = $stmtC->get_result();
$comentarios = $rsC->fetch_all(MYSQLI_ASSOC);
$stmtC->close();

// Verifica se usuário já tem leitura para este livro (para mostrar botão apropriado)
// (se estiver logado)
$temLeitura = false;
if (isset($_SESSION['codUsuario'])) {
    $codUsuario = intval($_SESSION['codUsuario']);
    $stmtL = $conexao->prepare("
        SELECT codLeitura, situacao
        FROM leitura
        WHERE fk_livro = ? AND fk_usuario = ?
        LIMIT 1
    ");
    $stmtL->bind_param("ii", $codLivro, $codUsuario);
    $stmtL->execute();
    $rsL = $stmtL->get_result();
    if ($rsL->num_rows > 0) {
        $leituraRow = $rsL->fetch_assoc();
        $temLeitura = true;
    }
    $stmtL->close();

    $stmt2 = $conexao->prepare("
                SELECT li.codlivro, au.autor
                FROM livro AS li
                INNER JOIN livro_has_autor AS lha ON lha.fk_livro = li.codLivro 
                INNER JOIN autor AS au ON au.codAutor = lha.fk_autor 
                WHERE li.codLivro = ?
                ORDER BY au.autor ASC;
            ");
            $stmt2->bind_param("i", $livro['codLivro']);
            $stmt2->execute();
            $rs2 = $stmt2->get_result();
            $autores = [];
            while ($row2 = $rs2->fetch_assoc()) {
                $autores[] = $row2['autor'];
            }
            $stringAutores = implode(", ", $autores);
        
            $stmt3 = $conexao->prepare("
                SELECT li.codlivro, ca.categoria
                FROM livro AS li
                INNER JOIN livro_has_categoria AS lhc ON lhc.fk_livro = li.codLivro 
                INNER JOIN categoria AS ca ON ca.codCategoria = lhc.fk_categoria 
                WHERE li.codLivro = ?
                ORDER BY ca.categoria ASC;
            ");
            $stmt3->bind_param("i", $livro['codLivro']);
            $stmt3->execute();
            $rs3 = $stmt3->get_result();
            $categoria = [];
            while ($row3 = $rs3->fetch_assoc()) {
                $categoria[] = $row3['categoria'];
            }
            $stringCategoria = implode(", ", $categoria);
}

if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>

<div class="container my-4 shadow-lg p-4 bg-white rounded">

  <div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <h2 class="mb-0 letraMaiuscula "><?=htmlspecialchars($livro['titulo'])?></h2>

      <div>
        <!-- Link para ver resenhas públicas -->
        <a href="paginas/resenhasPublicas.php?codLivro=<?=$livro['codLivro']?>" class="btn btn-outline-primary btn-sm me-2" title="Ver resenhas públicas de outros usuários">
          Ver Resenhas Públicas
        </a>

        <!-- Botão de ação (Quero Ler / Iniciar Leitura) -->
          <?php if (isset($_SESSION['codUsuario'])): ?>
              <?php if ($temLeitura): ?>
                  <!-- Se já houver leitura registrada -->
                  <?php if (isset($_SESSION['codUsuario'])): ?>
              <?php if ($temLeitura): ?>
                  <!-- Botão para iniciar leitura -->
                  <button type="button" 
                          class="btn btn-success btn-sm" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalPaginas"
                          title="Iniciar leitura deste livro">
                      Iniciar Leitura
                  </button>
              <?php else: ?>
                  <!-- Botão para adicionar a quero ler -->
                  <form action="paginas/codigosPHP/adicionarQueroLer.php?codLivro=<?=$livro['codLivro']?>" method="POST" class="d-inline">
                      <button type="submit" class="btn btn-warning btn-sm" title="Adicionar à sua lista de leituras futuras">Adicionar a Quero Ler</button>
                  </form>
              <?php endif; ?>
          <?php else: ?>
              <a href="index.php?menu=login" class="btn btn-warning btn-sm">Fazer login para adicionar</a>
          <?php endif; ?>

 <!--       <form action="paginas/codigosPHP/adicionarQueroLer.php?codLivro=<?=$livro['codLivro']?>" method="POST" class="d-inline">
            <button type="submit" class="btn btn-success btn-sm" title="Continuar sua leitura deste livro">
                📖 Iniciar Leitura
            </button>
          </form>  
-->
    <?php else: ?>
        <!-- Se ainda não houver leitura -->
        <form action="paginas/codigosPHP/adicionarQueroLer.php?codLivro=<?=$livro['codLivro']?>" method="POST" class="d-inline">
            <button type="submit" class="btn btn-warning btn-sm" title="Adicionar à sua lista de leitura futura">
                ➕ Adicionar a Quero Ler
            </button>
        </form>
    <?php endif; ?>
<?php else: ?>
    <!-- Usuário não logado -->
    <a href="index.php?menu=login" class="btn btn-warning btn-sm" title="Faça login para adicionar à sua lista">
        ➕ Adicionar a Quero Ler
    </a>
<?php endif; ?>

      </div>
    </div>
  </div>

  <hr>

  <div class="row g-4">
    <div class="col-12 col-md-4 text-center">
      <img src="<?=htmlspecialchars($livro['URL'] ?: '/PlataformaPi/imagens/default-capa.jpg')?>" alt="Capa" class="img-fluid rounded shadow w-100 mb-3">
      
        <p><b>Autor(es):</b> <?=$stringAutores?></p>
        <p><b>Editora:</b> <?=$livro['editora']?></p>
        <p><b>Ano:</b> <?=$livro['anoPublicacao']?></p>
        <p><b>Categoria(as):</b> <?=$stringCategoria?></p>
        <p><b>Faixa Etária:</b> <?=$livro['faixaEtaria']?> (<?=$livro['abreviacao']?>) </p>
        <p><b>Código ISBN:</b> <?=$livro['ISBN']?> </p>
        <p><b>Total de Páginas:</b> <?=$livro['nPagina']?></p>
    </div>

    <div class="col-12 col-md-8">
      <h5>Sinopse</h5>
      <p><?= nl2br(htmlspecialchars($livro['descricao'] ?? 'Sem sinopse disponível.')) ?></p>

      <hr>

      <h5>Comentários</h5>
        <form action="paginas/codigosPHP/salvarComentario.php?codLivro=<?=$livro['codLivro']?>" method="POST" class="mb-3">
          <div class="mb-2">
            <textarea name="textoComentario" class="form-control" rows="3" placeholder="Escreva um comentário público sobre o livro..." required></textarea>
          </div>
          <button type="submit" class="btn btn-sm corAzul corBranca">Enviar comentário</button>
        </form>
        

      <!-- Lista de comentários -->
      <?php if (count($comentarios) === 0): ?>
        <p class="text-muted">Ainda não há comentários.</p>
      <?php else: ?>
        <ul class="list-group">
          <?php foreach ($comentarios as $c): ?>
            <li class="list-group-item">
              <div class="small text-muted"><?=date('d/m/Y H:i', strtotime($c['data']))?> — <b><?=htmlspecialchars($c['autorNome'] ?? 'Anônimo')?></b></div>
              <div><?=nl2br(htmlspecialchars($c['comentario']))?></div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

    </div>
  </div>
</div>



<!-- Modal para informar o número de páginas -->
<div class="modal fade" id="modalPaginas" tabindex="-1" aria-labelledby="modalPaginasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPaginasLabel">Informe o número de páginas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <p class="text-muted mb-3">
          As edições dos livros podem variar. Informe o total de páginas do seu exemplar para iniciar a leitura corretamente.
        </p>

        <form id="formPaginas" action="paginas/codigosPHP/iniciarLeitura.php" method="POST">
          <input type="hidden" name="codLivro" value="<?=$livro['codLivro']?>">
          
          <div class="mb-3">
            <label for="numPaginas" class="form-label">Total de páginas:</label>
            <input type="number" name="numPaginas" id="numPaginas" class="form-control" min="1" required placeholder="Ex: 278">
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formPaginas" class="btn btn-primary">Salvar e Iniciar</button>
      </div>
    </div>
  </div>
</div>

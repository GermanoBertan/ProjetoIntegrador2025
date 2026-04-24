<?php
// explorar.php - bloco PHP simplificado para buscar / filtrar livros
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

// buscar categorias e faixas para os selects
$categorias = $conexao->query("SELECT codCategoria, categoria FROM categoria ORDER BY categoria ASC")->fetch_all(MYSQLI_ASSOC);
$faixas = $conexao->query("SELECT codFaixaEtaria, faixaEtaria, abreviacao FROM faixaetaria ORDER BY codFaixaEtaria ASC")->fetch_all(MYSQLI_ASSOC);

// parâmetros de busca (GET)
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter_categoria = isset($_GET['categoria']) && intval($_GET['categoria']) > 0 ? intval($_GET['categoria']) : null;
$filter_faixa = isset($_GET['faixa']) && intval($_GET['faixa']) > 0 ? intval($_GET['faixa']) : null;

$hasFilters = ($q !== '' || $filter_categoria !== null || $filter_faixa !== null);

/* ===========================
   Seções horizontais (sem filtro)
   =========================== */
$horizontal_sections = [];
if (!$hasFilters) {
    $sql1 = "SELECT li.codLivro, li.titulo,
                    (SELECT ed2.URL FROM edicao ed2 WHERE ed2.fk_livro = li.codLivro ORDER BY ed2.codEdicao ASC LIMIT 1) AS URL
             FROM livro li
             ORDER BY li.codLivro DESC
             LIMIT 10";
    $horizontal_sections[] = ['titulo' => 'Destaques', 'items' => $conexao->query($sql1)->fetch_all(MYSQLI_ASSOC)];

    $sql2 = "SELECT li.codLivro, li.titulo,
                    (SELECT ed2.URL FROM edicao ed2 WHERE ed2.fk_livro = li.codLivro ORDER BY ed2.codEdicao ASC LIMIT 1) AS URL
             FROM livro li
             ORDER BY li.anoPublicacao DESC
             LIMIT 10";
    $horizontal_sections[] = ['titulo' => 'Mais recentes', 'items' => $conexao->query($sql2)->fetch_all(MYSQLI_ASSOC)];

    $sql3 = "SELECT li.codLivro, li.titulo,
                    (SELECT ed2.URL FROM edicao ed2 WHERE ed2.fk_livro = li.codLivro ORDER BY ed2.codEdicao ASC LIMIT 1) AS URL
             FROM livro li
             ORDER BY RAND()
             LIMIT 10";
    $horizontal_sections[] = ['titulo' => 'Recomendados para você', 'items' => $conexao->query($sql3)->fetch_all(MYSQLI_ASSOC)];
}

/* ===========================
   Busca / filtros (simples e segura)
   - usa GROUP_CONCAT(DISTINCT ...) para autores
   - usa EXISTS para filtrar por autor (evita duplicação)
   =========================== */

// Monta WHERE dinamicamente e params simples
$where = [];
$params = [];
$types = ""; // tipos para bind

// filtro por categoria (usando EXISTS para não multiplicar linhas)
if ($filter_categoria !== null) {
    $where[] = "EXISTS (SELECT 1 FROM livro_has_categoria lhc WHERE lhc.fk_livro = li.codLivro AND lhc.fk_categoria = ?)";
    $params[] = $filter_categoria;
    $types .= "i";
}

// filtro por faixa
if ($filter_faixa !== null) {
    $where[] = "li.fk_faixaEtaria = ?";
    $params[] = $filter_faixa;
    $types .= "i";
}

// filtro por termo q (título OU autor) - usa LIKE para título e EXISTS para autor
if ($q !== '') {
    $where[] = "(li.titulo LIKE ? OR EXISTS (
                    SELECT 1 FROM livro_has_autor lha2
                    INNER JOIN autor au2 ON au2.codAutor = lha2.fk_autor
                    WHERE lha2.fk_livro = li.codLivro AND au2.autor LIKE ?
                ))";
    $q_like = '%' . $q . '%';
    $params[] = $q_like; // para li.titulo
    $params[] = $q_like; // para au2.autor
    $types .= "ss";
}

// Monta SQL base (agrega autores)
$sql = "
    SELECT
        li.codLivro,
        li.titulo,
        (SELECT ed2.URL FROM edicao ed2 WHERE ed2.fk_livro = li.codLivro ORDER BY ed2.codEdicao ASC LIMIT 1) AS URL,
        COALESCE(GROUP_CONCAT(DISTINCT au.autor SEPARATOR ', '), '') AS autores
    FROM livro li
    LEFT JOIN livro_has_autor lha ON lha.fk_livro = li.codLivro
    LEFT JOIN autor au ON au.codAutor = lha.fk_autor
";

// adiciona where se necessário
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " GROUP BY li.codLivro ORDER BY li.titulo ASC";

/* executa preparado (ou sem bind se não há params) */
$results = [];
if (count($params) === 0) {
    // sem parâmetros: query simples
    $res = $conexao->query($sql);
    if ($res) $results = $res->fetch_all(MYSQLI_ASSOC);
} else {
    $stmt = $conexao->prepare($sql);
    if ($stmt === false) {
        // erro no prepare — devolve vazio para não quebrar a tela
        $results = [];
    } else {
        // bind dinâmico simples
        $bind_names = [];
        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        // call_user_func_array precisa de references
        call_user_func_array([$stmt, 'bind_param'], $bind_names);

        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) $results = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// agora $horizontal_sections (sem filtro) e $results (com filtro) estão prontos para usar no HTML abaixo.
?>
  <style>
    /* =========================
       Design aplicado
       - 3 linhas horizontais com scroll (sem filtros)
       - Grid responsivo quando filtrado
       - Capas ocupando todo espaço sem achatar (object-fit: cover)
       ========================= */

    :root{
      --card-width: 12rem;
      --card-cover-height: 18rem;
      --grid-gap: 1rem;
      --accent: #36489e;
    }

    .horizontal-card {
    transition: border 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border: 2px solid transparent; /* base sem desaparecer layout */
}

.horizontal-card:hover {
    border: 2px solid #888;   /* cor da borda ao passar mouse */
    cursor: pointer;
    box-shadow: 0 0 8px rgba(0,0,0,0.18);
}


    body { background: #f5f6fb; }

    .container-explore { margin-top: 1.25rem; margin-bottom: 2.5rem; }

    /* Header + filtros */
    .explorer-header { background: #ffffffff; padding: 1rem; border-radius: 8px; box-shadow: 0 6px 18px rgba(0,0,0,0.04); }

    /* horizontal sections */
    .horizontal-section { margin-top: 1rem; margin-bottom: 1.25rem; }
    .horizontal-title { margin-bottom: .5rem; font-weight:600; color:var(--accent); }
    .horizontal-cards {
      display: grid;
      grid-auto-flow: column;
      grid-auto-columns: var(--card-width);
      gap: var(--grid-gap);
      overflow-x: auto;
      padding: .25rem 0 .5rem 0;
      scroll-snap-type: x proximity;
    }
    .horizontal-card {
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      scroll-snap-align: start;
      box-shadow: 0 6px 12px rgba(0,0,0,0.04);
      display:flex;
      flex-direction:column;
      height: calc(var(--card-cover-height) + 72px); /* cover + body */
    }
    .horizontal-card a { color: inherit; text-decoration:none; display:block; height:100%; }

    .horizontal-cover {
      width:100%;
      height: var(--card-cover-height);
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(180deg, #ffffff 0%, #f3f4f8 100%);
    }
    .horizontal-cover img {
      width:100%;
      height:100%;
      object-fit: cover; /* preenche todo o espaço, recortando se necessário, mantendo proporção */
      display:block;
    }
    .horizontal-body { padding: .6rem; text-align:center; }
    .horizontal-title-book { font-weight:700; color:var(--accent); line-height:1.1; }

    /* Grid results */
    .results-grid { margin-top:1rem; display:grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap:1rem; }
    .grid-item { background:#fff; border-radius:8px; padding: .45rem; text-align:center; box-shadow: 0 6px 12px rgba(0,0,0,0.04); cursor:pointer; }
    .grid-cover { width:100%; height:220px; object-fit: cover; border-radius:6px; display:block; }
    .grid-title { margin-top:.5rem; font-weight:700; color:var(--accent); font-size:.92rem; }

    /* Mobile adjustments: horizontal cards smaller and more compact */
    @media (max-width: 768px) {
      :root { --card-width: 9.5rem; --card-cover-height: 12rem; }
      .horizontal-cards { grid-auto-columns: var(--card-width); gap: .6rem; }
      .horizontal-card { height: calc(var(--card-cover-height) + 64px); }
      .results-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* Busca/filtros row */
    .filters-row { display:flex; gap:.5rem; align-items:center; }
    .nomeLivroSmall { font-size:.85rem; font-weight:700; color:var(--accent); text-align:center; }

    /* hide scrollbar (but still scrollable) - cosmetic */
    .horizontal-cards::-webkit-scrollbar { height: 8px; }
    .horizontal-cards::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 6px; }
    /* GRID UNIFICADO — mesmo layout dos horizontais */
.results-grid-unified {
    margin-top: 1rem;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(var(--card-width), 1fr));
    gap: var(--grid-gap);
}

/* fazer o card ocupar toda a largura da coluna */
.results-grid-unified .horizontal-card {
    width: 100%;
    cursor: pointer;
}

  </style>
</head>
<body>

<div class="container container-explore">

  <div class="explorer-header">
    <div class="row g-2 align-items-center">
      <div class="col-md-6">
        <form method="GET" action="index.php" class="d-flex">
          <input type="hidden" name="menu" value="explorar">
          <input type="text" name="q" value="<?=htmlspecialchars($q)?>" class="form-control me-2" placeholder="Buscar por título ou autor...">
          <button class="btn btn-primary">Buscar</button>
        </form>
      </div>
      

      <div class="col-md-6">
        <form id="filtersForm" method="GET" action="index.php" class="d-flex justify-content-md-end gap-2">
          <input type="hidden" name="menu" value="explorar">
          <input type="hidden" name="q" value="<?=htmlspecialchars($q)?>">
          <select name="categoria" class="form-select form-select-sm w-auto">
            <option value="">Categoria</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?=$c['codCategoria']?>" <?= $filter_categoria == $c['codCategoria'] ? 'selected' : '' ?>>
                <?=htmlspecialchars($c['categoria'])?>
              </option>
            <?php endforeach; ?>
          </select>

          <select name="faixa" class="form-select form-select-sm w-auto">
            <option value="">Faixa etária</option>
            <?php foreach ($faixas as $f): ?>
              <option value="<?=$f['codFaixaEtaria']?>" <?= $filter_faixa == $f['codFaixaEtaria'] ? 'selected' : '' ?>>
                <?=htmlspecialchars($f['faixaEtaria'])?> (<?=$f['abreviacao']?>)
              </option>
            <?php endforeach; ?>
          </select>

          <button class="btn btn-outline-secondary btn-sm" type="submit">Filtrar</button>
        </form>
      </div>
    </div>

  <!-- Sem filtros: mostra 3 linhas horizontais -->
  <?php if (!$hasFilters): ?>
    <?php foreach ($horizontal_sections as $section): ?>
      <div class="horizontal-section">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="horizontal-title"><?=htmlspecialchars($section['titulo'])?></h5>
        </div>
        <div class="horizontal-cards">
          <?php foreach ($section['items'] as $it): ?>
            <div class="horizontal-card">
              <a href="index.php?menu=livro&codLivro=<?=$it['codLivro']?>">
                <div class="horizontal-cover">
                  <img src="<?=htmlspecialchars($it['URL'])?>" alt="<?=htmlspecialchars($it['titulo'])?>">
                </div>
                <div class="horizontal-body">
                  <div class="nomeLivro horizontal-title-book"><?=htmlspecialchars($it['titulo'])?></div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php else: ?>
    <!-- Com filtros/busca: mostrar resultados com o MESMO DESIGN dos cards horizontais -->
    <div class="mt-3 mb-2">
        <p class="mb-0">
            <?=count($results)?> resultado(s)
            <?php if ($q !== ''): ?> para <strong><?=htmlspecialchars($q)?></strong><?php endif; ?>
        </p>
    </div>

    <?php if (count($results) === 0): ?>
        <div class="alert alert-info">Nenhum livro encontrado com esses critérios.</div>

    <?php else: ?>

        <div class="results-grid-unified">
            <?php foreach ($results as $r): ?>
                <div class="horizontal-card" onclick="location.href='index.php?menu=livro&codLivro=<?=$r['codLivro']?>'">
                    <div class="horizontal-cover">
                        <img src="<?=htmlspecialchars($r['URL'])?>" alt="<?=htmlspecialchars($r['titulo'])?>">
                    </div>
                    <div class="horizontal-body">
                        <div class="nomeLivro horizontal-title-book "><?=htmlspecialchars($r['titulo'])?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

<?php endif; ?>

</div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

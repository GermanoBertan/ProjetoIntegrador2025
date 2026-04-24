<?php
include("./classes/Leitura.php");

    $codLeitura = $_GET['codLeitura'];
    $codUsuario = $_SESSION['codUsuario'];

    $stmt = $conexao->prepare("
        SELECT * FROM leitura AS le
            INNER JOIN livro AS li ON li.codLivro = le.fk_livro
            INNER JOIN edicao AS ed ON ed.fk_livro = li.codLivro
            INNER JOIN resenha AS re ON le.fk_resenha = re.codResenha
            INNER JOIN faixaetaria AS fe ON li.fk_faixaEtaria = fe.codFaixaEtaria
            INNER JOIN editora AS edi ON edi.codEditora = li.fk_editora
            WHERE codLeitura=? AND fk_usuario=? AND situacao=0
            LIMIT 1;
    ");
    $stmt->bind_param("ii", $codLeitura, $codUsuario);
    $stmt->execute();
    $rs = $stmt->get_result();

    if ($rs->num_rows > 0) {
        
        $dados = $rs->fetch_assoc();
        $leitura = new Leitura($dados['codLeitura']);
        $progressPercent = round($leitura->porcentagem());

        $stmt2 = $conexao->prepare("
                SELECT li.codlivro, au.autor
                FROM livro AS li
                INNER JOIN livro_has_autor AS lha ON lha.fk_livro = li.codLivro 
                INNER JOIN autor AS au ON au.codAutor = lha.fk_autor 
                WHERE li.codLivro = ?
                ORDER BY au.autor ASC;
            ");
            $stmt2->bind_param("i", $dados['codLivro']);
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
            $stmt3->bind_param("i", $dados['codLivro']);
            $stmt3->execute();
            $rs3 = $stmt3->get_result();
            $categoria = [];
            while ($row3 = $rs3->fetch_assoc()) {
                $categoria[] = $row3['categoria'];
            }
            $stringCategoria = implode(", ", $categoria);

?>  

 <style>
    /* Estilo para estrelas */
    .rating {
      direction: rtl;
      unicode-bidi: bidi-override;
      display: inline-flex;
    }
    .rating input {
      display: none;
    }
    .rating label {
      font-size: 2rem;
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
      color: orange;
    }
</style>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>

<div class="container my-4 shadow-lg p-4 bg-white rounded">

    <div class="d-flex justify-content-between align-items-center mb-2">
      <h3 class="mb-0">✅ DETALHES DA LEITURA FINALIZADA</h3>
    </div>
    <hr class="mt-2" style="border: 2px solid #000000ff; opacity: 0.89;">


  <div class="row g-4 align-items-start">

  

    <!-- CAPA DO LIVRO -->
    <div class="col-12 col-md-4 text-center">

      <img src="<?=$dados['URL']?>" alt="Capa do livro" class="img-fluid rounded shadow w-100 mb-4">

      <!-- Título -->
      <p class="infoLivroLeitura"><?=$dados['titulo']?></p>

      <!-- Informações técnicas -->
      <div class="recuoLeituraInfoLivro" >
        <p><b>Autor(es):</b> <?=$stringAutores?></p>
        <p><b>Editora:</b> <?=$dados['editora']?></p>
        <p><b>Ano:</b> <?=$dados['anoPublicacao']?></p>
        <p><b>Categoria(as):</b> <?=$stringCategoria?></p>
        <p><b>Faixa Etária:</b> <?=$dados['faixaEtaria']?> (<?=$dados['abreviacao']?>) </p>
        <p><b>Código ISBN:</b> <?=$dados['ISBN']?> </p>
        <p><b>Total de Páginas:</b> <?=$dados['numPagina']?></p>

      </div>

      <hr>

    </div>

    <!-- INFORMAÇÕES DO LIVRO -->
    <div class="col-12 col-md-8">

      <!-- STATUS DA LEITURA -->
      <div class="recuoLeituraInfoLivro mb-4" style="text-align: -webkit-center;" >
        <p class="infoLivroLeitura text-start">Progresso da Leitura</p>

        <div class="ms-sm-3">
                    <p class="fonteCinza mb-0">
                        Iniciada em: 
                        <?= isset($dados['dataInicioLei']) && !empty($dados['dataInicioLei']) 
                            ? date('d/m/Y', strtotime($dados['dataInicioLei'])) 
                            : '-' ?>
                        | Finalizada em: 
                        <?= isset($dados['dataModificacaoLei']) && !empty($dados['dataModificacaoLei']) 
                            ? date('d/m/Y', strtotime($dados['dataModificacaoLei'])) 
                            : '-' ?>
                    </p>
                </div>

        <div class="progress mb-2" style="height: 15px; width: 100%; max-width: 100%;">
            <div class="progress-bar corAzul" role="progressbar" style="width:100%;" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100">100% - Finalizado</div>
        </div>
        <p class="nPagina mt-1">Total de páginas lidas: <?=$dados['paginaAtual']?> </p>

      </div>

      <hr>

      

      <!-- RESENHA -->
      <div class="recuoLeituraInfoLivro">
      <div class="d-flex justify-content-between align-items-center mb-1">
          <p class="infoLivroLeitura mb-0">Resenha</p>

          <form action="paginas/codigosPHP/atualizarPrivacidadeResenha.php?codLeitura=<?= $dados['codLeitura'] ?>" method="POST" class="d-flex align-items-center">
              <input type="hidden" name="codLeitura" value="<?= $dados['codLeitura'] ?>">
              <input type="hidden" name="privada" value="<?= $dados['privado'] ? 0 : 1 ?>"> <!-- alterna o valor -->

              <button type="submit" class="btn btn-sm <?= $dados['privado'] ? 'btn-outline-secondary' : 'btn-outline-success' ?>">
                  <?= $dados['privado'] ? '🔒 Privada' : '🌐 Pública' ?>
              </button>
          </form>
      </div>

        <p class="fonteCinza mb-0">Escreva ou edite sua resenha abaixo:</p>

        <form action="paginas/codigosPHP/salvarResenha.php?codLeitura=<?= $dados['codLeitura'] ?>" method="POST">
          <div class="mb-3">
            <textarea class="form-control" name="textoResenha" rows="12" placeholder="Digite sua resenha aqui..." style="border:1px solid #36489e;" required><?= htmlspecialchars($dados['resenha']) ?></textarea>
          </div>
          <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between">
                <button type="submit" class="btn corAzul corBranca mb-2 mb-sm-0">Salvar Resenha</button>
                <div class="ms-sm-3">
                    <p class="fonteCinza mb-0">
                        Criada em: 
                        <?= isset($dados['dataCriacao']) && !empty($dados['dataCriacao']) 
                            ? date('d/m/Y', strtotime($dados['dataCriacao'])) 
                            : '-' ?>
                    </p>
                    <p class="fonteCinza mb-0">
                        Modificada em: 
                        <?= isset($dados['dataModificacao']) && !empty($dados['dataModificacao']) 
                            ? date('d/m/Y', strtotime($dados['dataModificacao'])) 
                            : '-' ?>
                    </p>
                </div>

          </div>
          
        </form>

      </div>

    <hr>

    <!-- AVALIAÇÃO -->
<?php
    // Dentro do mesmo bloco onde você já tem $dados
    $nota = $dados['nota']; // campo da tabela leitura (por exemplo)

    // Verifica se o usuário já avaliou
    $avaliado = !is_null($nota) && $nota > 0;
?>

<div class="recuoLeituraInfoLivro mb-4">
    <p class="infoLivroLeitura mb-1">Avaliação do Livro</p>

    <?php if ($avaliado): ?>
        <p class="fonteCinza mb-0">Você avaliou este livro com nota <b><?= $nota ?></b> estrela<?= $nota > 1 ? 's' : '' ?>.</p>
        <div class="rating mt-0 mb-3">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="estrela" id="estrela<?= $i ?>" value="<?= $i ?>" 
                       <?= ($nota == $i ? 'checked' : '') ?> disabled>
                <label for="estrela<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>
        <button class="btn btn-sm btn-secondary px-3" disabled>Avaliação Salva</button>

    <?php else: ?>
        <p class="fonteCinza mb-0 p-0">Escolha uma nota de 1 a 5 estrelas (somente uma vez):</p>

        <form action="paginas/codigosPHP/salvarNota.php?codLeitura=<?$codLeitura?>" method="POST">
            <input type="hidden" name="codLeitura" value="<?= $dados['codLeitura'] ?>">
            <div class="rating mt-0 mb-0">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="estrela" id="estrela<?= $i ?>" value="<?= $i ?>">
                    <label for="estrela<?= $i ?>">★</label>
                <?php endfor; ?>
            </div>
            <button type="submit" class="btn btn-sm corAzul corBranca px-3">Salvar Avaliação</button>
        </form>
    <?php endif; ?>
</div>

<hr>

    <div class="recuoLeituraInfoLivro mb-4" >
        <p class="infoLivroLeitura text-start  mb-0">Operações da Leitura</p>
        <p class="fonteCinza  mt-1 mb-1 m-2">Excluir a leitura. Todas as informaçãoes de leitura e resenha serão apagadas. </p>  

        <!-- Excluir leitura finalizada -->
        <form action="paginas/codigosPHP/excluirLeituraFinalizada.php" method="POST" class="me-2">
            <input type="hidden" name="codLeitura" value="<?= $dados['codLeitura'] ?>">
            <button type="submit" name="moverQueroLer" title="Excluir os dados de sua leitura" class="btn btn-sm btn-outline-danger mt-1 mb-1 m-2">
              Excluir Leitura
            </button>
        </form>
        
    </div>

      

    </div>

  </div>
</div>


<?php
} else {
    echo 'Essa leitura não pertence a você ou ainda não foi finalizada! <br> <a href="index.php?menu=inicio">Voltar a página inicial.</a>';
}
?>
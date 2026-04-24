<!-- TELA LENDO -->
 
<div class="shadow-lg p-3 mb-4 bg-white rounded">
    <h3 class="mb-0">LENDO</h3>
    <hr class="mt-0" style="border: 2px solid #000000ff; opacity: 0.89;">
    
    <div class="container">
        <ul class="cards">

<?php 
include("./classes/Leitura.php");

    $codUsua = $_SESSION['codUsuario'];
    $sql = "SELECT le.codLeitura, ed.URL, li.titulo, le.numPagina 
            FROM leitura AS le 
            INNER JOIN livro AS li ON li.codLivro = le.fk_livro 
            INNER JOIN edicao AS ed ON ed.fk_livro = le.fk_livro 
            WHERE le.situacao='1' 
            AND le.fk_usuario='$codUsua' 
            ORDER BY le.dataInicioLei DESC";
    $rs = mysqli_query($conexao, $sql);

    if ($rs->num_rows > 0) {
        while ($dados = mysqli_fetch_assoc($rs)) {
            $leitura = new Leitura($dados['codLeitura']);
            $progressPercent = round($leitura->porcentagem());
?>

    <li class="card mb-2">
        <img class="card-img-top imgCard" src="<?=$dados['URL']?>" alt="Imagem de capa do livro">
        <div class="rounded-bottom progress barraProgresso">
            <div class="progress-bar corAzul" role="progressbar" style="width: <?=$progressPercent?>%;" aria-valuenow="<?=$progressPercent?>" aria-valuemin="0" aria-valuemax="100"><?=$progressPercent?>%</div>
        </div>
        <div class="card-body">
            <p class="nomeLivro text-center m-0">  <?=$dados['titulo']?> </p>
            <p class="nPagina text-center m-0">Página <?=$leitura->getPaginaAtual()?> de <?=$dados['numPagina']?></p>
            <a href="index.php?menu=leitura&codLeitura=<?=$dados['codLeitura']?>" class="btn w-100 p-0 corAzul corBranca">Atualizar</a>
        </div>
    </li>

<?php
}   
} else {
    echo "0 resultados encontrados";
}
?>


        </ul>
    </div>
</div>

<!-- TELA QUERO LER -->
<div class="shadow-lg p-3 mb-3 bg-white rounded">
    <h3 class="mb-0">QUERO LER</h3>
    <hr class="mt-0" style="border: 2px solid #000000ff; opacity: 0.89;">
    
    <div class="container">
        <ul class="cards">
           
<?php 

    $codUsua = $_SESSION['codUsuario'];
    $sql = "SELECT le.codLeitura, ed.URL, li.titulo, li.codLivro
            FROM leitura AS le 
            INNER JOIN livro AS li ON li.codLivro = le.fk_livro 
            INNER JOIN edicao AS ed ON ed.fk_livro = le.fk_livro 
            WHERE le.situacao='2' 
            AND le.fk_usuario='$codUsua' 
            ORDER BY le.dataInicioLei DESC";
    $rs = mysqli_query($conexao, $sql);
    if ($rs->num_rows > 0) {
        while ($dados = mysqli_fetch_assoc($rs)) {
?>

    <a class="text-decoration-none" href="index.php?menu=livro&codLivro=<?=$dados['codLivro']?>">
        <li class="card mb-2">
            <img class="card-img-top imgCard" src="<?=$dados['URL']?>" alt="Imagem de capa do livro">
            <div class="card-body">
                <p class="nomeLivro text-center m-0">  <?=$dados['titulo']?> </p>
            </div>
        </li>
    </a>
    

<?php
}   
} else {
    echo "0 resultados encontrados";
}
?>

        </ul>
    </div>
      
</div>

<!-- TELA LIDO -->
<div class="shadow-lg p-3 mb-3 bg-white rounded">
    <h3 class="mb-0">LIDO</h3>
    <hr class="mt-0" style="border: 2px solid #000000ff; opacity: 0.89;">
    
    <div class="container">
        <ul class="cards">
            
<?php 
    $codUsua = $_SESSION['codUsuario'];
    $stmt = $conexao->prepare("
        SELECT le.codLeitura, ed.URL, li.titulo, le.numPagina, li.codLivro
        FROM leitura AS le 
        INNER JOIN livro AS li ON li.codLivro = le.fk_livro 
        INNER JOIN edicao AS ed ON ed.fk_livro = le.fk_livro 
        WHERE le.situacao='0' AND le.fk_usuario=? 
        ORDER BY le.dataInicioLei DESC
    ");
    $stmt->bind_param("i", $codUsua);
    $stmt->execute();
    $rs = $stmt->get_result();

    if ($rs->num_rows > 0) {
        while ($dados = $rs->fetch_assoc()) {
            $leitura3 = new Leitura($dados['codLeitura']);

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
?>

    <li class="card mb-2">
        <img class="card-img-top imgCard" src="<?=$dados['URL']?>" alt="Imagem de capa do livro">
        <div class="rounded-bottom progress barraProgresso">
            <div class="progress-bar corAzul" role="progressbar" style="width:100%;" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100">100% - Finalizado</div>
        </div>
        <div class="card-body">
            <p class="nomeLivro text-center m-0">  <?=$dados['titulo']?> </p>
            <p class="nPagina text-center m-0"> <?=$stringAutores?> </p> <!-- autor -->
            <a href="index.php?menu=lido&codLeitura=<?=$dados['codLeitura']?>" class="btn w-100 p-0 corAzul corBranca">Visualizar</a>
        </div>
    </li>

<?php
    }   
} else {
    echo "0 resultados encontrados";
}

?>

        </ul>
    </div>
      
</div>

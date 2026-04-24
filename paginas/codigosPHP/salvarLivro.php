<?php
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$isbn = intval($_POST['isbn'] ?? 0);
$nPagina = intval($_POST['nPagina'] ?? 0);
$anoPublicacao = intval($_POST['anoPublicacao'] ?? 0);
$fk_editora = intval($_POST['fk_editora'] ?? 0);
$fk_faixaEtaria = intval($_POST['fk_faixaEtaria'] ?? 0);
$autores = $_POST['autores'] ?? [];
$categorias = $_POST['categorias'] ?? [];

$edicao_nome = trim($_POST['edicao_nome'] ?? '');
$autorImagem = trim($_POST['autorImagem'] ?? '');
$url_capa = trim($_POST['url_capa'] ?? '');
$uploadPath = null;

if ($titulo === '' || $descricao === '' || $isbn <= 0) {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Preencha todos os campos obrigatórios.</div>";
    header("Location: ../../index.php?menu=cadastrar_livro");
    exit;
}

// 📁 Upload da imagem (se enviada)
if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $nomeArquivo = uniqid() . "_" . basename($_FILES['capa']['name']);
    $destino = $_SERVER['DOCUMENT_ROOT']."/PlataformaPi/img/".$nomeArquivo;
    if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
        $uploadPath = "img/".$nomeArquivo;
    }
}

// 📘 Insere o livro
$stmt = $conexao->prepare("INSERT INTO livro (titulo, descricao, ISBN, nPagina, anoPublicacao, fk_faixaEtaria, fk_editora) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiiiii", $titulo, $descricao, $isbn, $nPagina, $anoPublicacao, $fk_faixaEtaria, $fk_editora);
$stmt->execute();
$codLivro = $conexao->insert_id;
$stmt->close();

// 🔗 Relaciona autores e categorias
$stmtA = $conexao->prepare("INSERT INTO livro_has_autor (fk_livro, fk_autor) VALUES (?, ?)");
foreach ($autores as $a) {
    $stmtA->bind_param("ii", $codLivro, $a);
    $stmtA->execute();
}
$stmtA->close();

$stmtC = $conexao->prepare("INSERT INTO livro_has_categoria (fk_livro, fk_categoria) VALUES (?, ?)");
foreach ($categorias as $c) {
    $stmtC->bind_param("ii", $codLivro, $c);
    $stmtC->execute();
}
$stmtC->close();

// 💿 Insere edição (caso informada)
if ($edicao_nome !== '' || $url_capa !== '' || $uploadPath !== '') {
    $urlFinal = $uploadPath ?: $url_capa;
    $stmtE = $conexao->prepare("INSERT INTO edicao (nome, autorImagem, URL, fk_livro) VALUES (?, ?, ?, ?)");
    $stmtE->bind_param("sssi", $edicao_nome, $autorImagem, $urlFinal, $codLivro);
    $stmtE->execute();
    $stmtE->close();
}

$_SESSION['msg'] = "<div class='alert alert-success'>📚 Livro cadastrado com sucesso!</div>";
header("Location: ../../index.php?menu=livro&codLivro={$codLivro}");
exit;

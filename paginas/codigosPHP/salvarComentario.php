<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

$codLivro = isset($_GET['codLivro']) ? intval($_GET['codLivro']) : 0;
$codUsuario = intval($_SESSION['codUsuario']);
$texto = isset($_POST['textoComentario']) ? trim($_POST['textoComentario']) : '';

if ($codLivro <= 0 || $texto === '') {
    $_SESSION['msg'] = "<div class='alert alert-warning'>Comente algo para enviar.</div>";
    header("Location: ../../index.php?menu=livro&codLivro={$codLivro}");
    exit;
}

// Insere comentário (sempre público)
$stmt = $conexao->prepare("INSERT INTO comentario (fk_livro, fk_usuario, comentario, data) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $codLivro, $codUsuario, $texto);
$ok = $stmt->execute();
$stmt->close();

$_SESSION['msg'] = $ok
    ? "<div class='alert alert-success'>Comentário publicado.</div>"
    : "<div class='alert alert-danger'>Erro ao publicar comentário.</div>";

header("Location: ../../index.php?menu=livro&codLivro={$codLivro}");
exit;

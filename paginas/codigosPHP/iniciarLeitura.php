<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if (!isset($_SESSION['codUsuario'])) {
    header("Location: ../../index.php?menu=login");
    exit;
}

$codUsuario = intval($_SESSION['codUsuario']);
$codLivro = intval($_POST['codLivro'] ?? 0);
$numPaginas = intval($_POST['numPaginas'] ?? 0);

if ($codLivro <= 0 || $numPaginas <= 0) {
    $_SESSION['msg'] = "<div class='alert alert-warning'>Informe um número de páginas válido.</div>";
    header("Location: ../../index.php?menu=livro&codLivro={$codLivro}");
    exit;
}

// Verifica se já existe leitura para esse livro e usuário
$stmt = $conexao->prepare("SELECT codLeitura FROM leitura WHERE fk_livro = ? AND fk_usuario = ? LIMIT 1");
$stmt->bind_param("ii", $codLivro, $codUsuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $codLeitura = intval($row['codLeitura']);

    $stmt2 = $conexao->prepare("
        UPDATE leitura
        SET numPagina = ?, situacao = 1, paginaAtual = 1,
            dataInicioLei = NOW(), dataModificacaoLei = NOW()
        WHERE codLeitura = ? AND fk_usuario = ?
    ");
    $stmt2->bind_param("iii", $numPaginas, $codLeitura, $codUsuario);
    $ok = $stmt2->execute();
    $stmt2->close();
} else {
    // Cria nova resenha e leitura
    $privada = 1;
    $empty = '1';
    $stmtR = $conexao->prepare("INSERT INTO resenha (resenha, privado, dataCriacao) VALUES (?, ?, NOW())");
    $stmtR->bind_param("si", $empty, $privada);
    $stmtR->execute();
    $fkResenha = $conexao->insert_id;
    $stmtR->close();

    $stmtI = $conexao->prepare("
        INSERT INTO leitura (fk_livro, fk_usuario, fk_resenha, situacao, paginaAtual, numPagina, dataInicioLei, dataModificacaoLei)
        VALUES (?, ?, ?, 1, 1, ?, NOW(), NOW())
    ");
    $stmtI->bind_param("iiiii", $codLivro, $codUsuario, $fkResenha, $numPaginas);
    $ok = $stmtI->execute();
    $stmtI->close();
}

$_SESSION['msg'] = $ok
    ? "<div class='alert alert-success'>Leitura iniciada com sucesso!</div>"
    : "<div class='alert alert-danger'>Erro ao iniciar leitura.</div>";

header("Location: ../../index.php?menu=leitura&codLeitura={$codLeitura}");
exit;
?>

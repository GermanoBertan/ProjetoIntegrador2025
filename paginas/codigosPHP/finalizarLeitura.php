<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if (!isset($_SESSION['codUsuario'])) {
    header("Location: ../../index.php?menu=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codLeitura'])) {
    $codLeitura = intval($_POST['codLeitura']);
    $codUsuario = intval($_SESSION['codUsuario']);

    // 1) Verifica se a leitura pertence ao usuário e busca página e resenha
    $stmt = $conexao->prepare("
        SELECT le.paginaAtual, le.numPagina, re.resenha
        FROM leitura AS le
        INNER JOIN resenha AS re ON re.codResenha = le.fk_resenha
        WHERE le.codLeitura = ? AND le.fk_usuario = ? AND le.situacao = 1
        LIMIT 1
    ");
    $stmt->bind_param("ii", $codLeitura, $codUsuario);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Leitura não encontrada ou não pertence a você.</div>";
        header("Location: ../../index.php?menu=inicio");
        exit;
    }

    $row = $res->fetch_assoc();
    $paginaAtual = intval($row['paginaAtual'] ?? 0);
    $numPagina   = intval($row['numPagina'] ?? 0);
    $textoResenha = trim($row['resenha'] ?? '');

    // 2) Verifica se a resenha foi preenchida
    if (empty($textoResenha)) {
        $_SESSION['msg'] = "<div class='alert alert-warning'>⚠️ Para finalizar a leitura, escreva sua resenha primeiro.</div>";
        header("Location: ../../index.php?menu=leitura&codLeitura={$codLeitura}");
        exit;
    }

    // 3) Verifica se a leitura está completa
    if ($paginaAtual < $numPagina) {
        $_SESSION['msg'] = "<div class='alert alert-warning'>⚠️ Não é possível finalizar: página atual ({$paginaAtual}) é menor que o total de páginas ({$numPagina}).</div>";
        header("Location: ../../index.php?menu=leitura&codLeitura={$codLeitura}");
        exit;
    }

    // 4) Atualiza a situação para 'finalizado' (0)
    $stmt2 = $conexao->prepare("
        UPDATE leitura
        SET situacao = 0, dataModificacaoLei = NOW()
        WHERE codLeitura = ? AND fk_usuario = ?
    ");
    $stmt2->bind_param("ii", $codLeitura, $codUsuario);

    if ($stmt2->execute()) {
        $_SESSION['msg'] = "<div class='alert alert-success'>✅ Leitura finalizada com sucesso!</div>";
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>❌ Erro ao finalizar leitura.</div>";
    }

    $stmt2->close();
    $stmt->close();

    header("Location: ../../index.php?menu=inicio");
    exit;
}
?>

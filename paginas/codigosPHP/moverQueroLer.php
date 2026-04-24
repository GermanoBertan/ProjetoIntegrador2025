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

    // 1) Verifica que leitura pertence ao usuário
    $stmt = $conexao->prepare("
        SELECT fk_resenha
        FROM leitura
        WHERE codLeitura = ? AND fk_usuario = ?
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
    $fkResenha = isset($row['fk_resenha']) ? intval($row['fk_resenha']) : null;
    $stmt->close();

    // 2) Atualiza leitura: situacao = 2, limpa paginaAtual, datas, nota
    $stmt2 = $conexao->prepare("
        UPDATE leitura
        SET situacao = 2,
            paginaAtual = NULL,
            dataInicioLei = NULL,
            dataModificacaoLei = NULL,
            nota = NULL
        WHERE codLeitura = ? AND fk_usuario = ?
    ");
    $stmt2->bind_param("ii", $codLeitura, $codUsuario);
    $ok1 = $stmt2->execute();
    $stmt2->close();

    // 3) Limpa resenha (se existir fk_resenha)
    $ok2 = true;
    if ($fkResenha) {
        $stmt3 = $conexao->prepare("
            UPDATE resenha
            SET resenha = NULL,
                privado = 1,
                dataModificacao = NULL,
                dataCriacao = NULL
            WHERE codResenha = ? 
        ");
        $stmt3->bind_param("i", $fkResenha);
        $ok2 = $stmt3->execute();
        $stmt3->close();
    }

    if ($ok1 && $ok2) {
        $_SESSION['msg'] = "<div class='alert alert-success'>Leitura movida para 'Quero Ler' e dados removidos.</div>";
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao mover leitura — tente novamente.</div>";
    }

    header("Location: ../../index.php?menu=inicio");
    exit;
}
?>

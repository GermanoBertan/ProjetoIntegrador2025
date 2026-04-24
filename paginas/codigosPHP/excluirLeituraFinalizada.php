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

    // 1) Verifica se a leitura pertence ao usuário e obtém a resenha
    $stmt = $conexao->prepare("
        SELECT fk_resenha 
        FROM leitura 
        WHERE codLeitura = ? AND fk_usuario = ? AND situacao = 0
        LIMIT 1
    ");
    $stmt->bind_param("ii", $codLeitura, $codUsuario);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        $_SESSION['msg'] = "<div class='alert alert-danger mt-3'>❌ Leitura não encontrada ou não pertence a você.</div>";
        header("Location: ../../index.php?menu=inicio");
        exit;
    }

    $dados = $res->fetch_assoc();
    $codResenha = intval($dados['fk_resenha']);
    $stmt->close();

    // 2) Primeiro exclui a LEITURA (filha)
    $stmt2 = $conexao->prepare("DELETE FROM leitura WHERE codLeitura = ? AND fk_usuario = ?");
    $stmt2->bind_param("ii", $codLeitura, $codUsuario);
    $okLeitura = $stmt2->execute();
    $stmt2->close();

    // 3) Depois exclui a RESENHA associada (pai)
    if ($okLeitura && $codResenha > 0) {
        $stmt3 = $conexao->prepare("DELETE FROM resenha WHERE codResenha = ?");
        $stmt3->bind_param("i", $codResenha);
        $stmt3->execute();
        $stmt3->close();
    }

    header("Location: ../../index.php?menu=inicio");
    exit;

} else {
    header("Location: ../../index.php?menu=inicio");
    exit;
}
?>

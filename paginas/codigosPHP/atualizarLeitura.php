<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");


if (isset($_POST['novaPagina'])) {
    $codLeitura = $_GET['codLeitura'];
    $codUsuario = $_SESSION['codUsuario'];
    $novaPagina = intval($_POST['novaPagina']);

    if ($novaPagina >= 1) {
        $stmt = $conexao->prepare("
            UPDATE leitura 
            SET paginaAtual = ?, dataModificacaoLei = NOW()
            WHERE codLeitura = ? AND fk_usuario = ?
        ");
        $stmt->bind_param("iii", $novaPagina, $codLeitura, $codUsuario);
        if ($stmt->execute()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>✅ Página atual salva com sucesso!</div>";
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>❌ Erro ao salvar a página atual da leitura.</div>";
        }
        $stmt->close();
    }

    
    header("Location: ../../index.php?menu=leitura&codLeitura=$codLeitura");
    exit;
}
?>

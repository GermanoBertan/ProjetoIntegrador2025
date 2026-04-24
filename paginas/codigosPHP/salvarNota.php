<?php
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");
session_start();

$codLeitura = $_POST['codLeitura'];
$nota = $_POST['estrela'];
$codUsuario = $_SESSION['codUsuario'];

$stmt = $conexao->prepare("
    UPDATE leitura 
    SET nota = ? 
    WHERE codLeitura = ? AND fk_usuario = ? AND nota IS NULL
");
$stmt->bind_param("iii", $nota, $codLeitura, $codUsuario);
if ($stmt->execute()) {
    $_SESSION['msg'] = "<div class='alert alert-success'>✅ Nota salva com sucesso!</div>";
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>❌ Erro ao salvar a nota da leitura.</div>";
}

$stmt = $conexao->prepare(" 
    SELECT situacao 
    FROM leitura 
    WHERE codLeitura = ? AND fk_usuario = ?
    LIMIT 1; ");
$stmt->bind_param("ii", $codLeitura, $codUsuario);
$stmt->execute();
$rs = $stmt->get_result();
if ($rs->num_rows > 0) {
    $dados = $rs->fetch_assoc();
    if ($dados['situacao'] === 0) {
        header("Location: ../../index.php?menu=lido&codLeitura=$codLeitura");
    }else{
        header("Location: ../../index.php?menu=leitura&codLeitura=$codLeitura");
    }
}
exit;

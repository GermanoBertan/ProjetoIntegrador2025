<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if (isset($_POST['codLeitura'], $_POST['privada'])) {
    $codLeitura = intval($_POST['codLeitura']);
    $privada = intval($_POST['privada']);
    $codUsuario = $_SESSION['codUsuario'];

    // Atualiza a resenha vinculada à leitura
    $stmt = $conexao->prepare("
        UPDATE resenha 
        SET privado = ?, dataModificacao = NOW()
        WHERE codResenha = (
            SELECT fk_resenha 
            FROM leitura 
            WHERE codLeitura = ? AND fk_usuario = ?
        )
    ");
    $stmt->bind_param("iii", $privada, $codLeitura, $codUsuario);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "<div class='alert alert-success'>✅ Privacidade de resenha atualizada com sucesso!</div>";

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
    } else {
        echo "<div class='alert alert-danger mt-3'>❌ Erro ao atualizar privacidade da resenha.</div>";
    }
    $stmt->close();
} else {
    echo "Parâmetros inválidos.";
}
?>

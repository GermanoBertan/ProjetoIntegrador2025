<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if (isset($_GET['codLeitura'], $_POST['textoResenha'])) {
    $codLeitura = intval($_GET['codLeitura']);
    $codUsuario = $_SESSION['codUsuario'];
    $textoResenha = trim($_POST['textoResenha']);

    if (!empty($textoResenha)) {
        // Atualiza o texto da resenha vinculada à leitura
        $stmt = $conexao->prepare("
            UPDATE resenha 
            SET resenha = ?, dataModificacao = NOW()
            WHERE codResenha = (
                SELECT fk_resenha 
                FROM leitura 
                WHERE codLeitura = ? AND fk_usuario = ?
            )
        ");
        $stmt->bind_param("sii", $textoResenha, $codLeitura, $codUsuario);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>✅ Resenha salva com sucesso!</div>";
            
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
            echo "<div class='alert alert-danger mt-3'>❌ Erro ao salvar a resenha.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning mt-3'>⚠️ O texto da resenha não pode estar vazio.</div>";
    }
} else {
    echo "<div class='alert alert-danger mt-3'>❌ Dados inválidos para salvar a resenha.</div>";
}
?>

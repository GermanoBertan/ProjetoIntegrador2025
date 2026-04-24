<?php
// paginas/codigosPHP/adicionarQueroLer.php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if (!isset($_SESSION['codUsuario'])) {
    header("Location: ../../index.php?menu=login");
    exit;
}

$codLivro = isset($_GET['codLivro']) ? intval($_GET['codLivro']) : 0;
$codUsuario = intval($_SESSION['codUsuario']);
if ($codLivro <= 0) {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Livro inválido.</div>";
    header("Location: ../../index.php");
    exit;
}

// Verifica se já existe leitura para este usuário e livro
// ADICIONA O LIVRO A LENDO.
$stmt = $conexao->prepare("SELECT codLeitura, situacao, fk_resenha FROM leitura WHERE fk_livro = ? AND fk_usuario = ? LIMIT 1");
$stmt->bind_param("ii", $codLivro, $codUsuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // já existe: apenas atualiza situacao para 1 e limpa dados de leitura
    $row = $res->fetch_assoc();
    $codLeitura = intval($row['codLeitura']);

    $stmt2 = $conexao->prepare("
        UPDATE leitura
        SET situacao = 1,
            paginaAtual = 1,
            dataInicioLei = NOW(),
            dataModificacaoLei = NOW(),
            nota = NULL
        WHERE codLeitura = ? AND fk_usuario = ?
    ");
    $stmt2->bind_param("ii", $codLeitura, $codUsuario);
    $ok = $stmt2->execute();
    $stmt2->close();

    $_SESSION['msg'] = $ok
        ? "<div class='alert alert-success'>Livro movido para 'Lendo'.</div>"
        : "<div class='alert alert-danger'>Erro ao mover para 'Quero Ler'.</div>";

    header("Location: ../../index.php?menu=livro&codLivro={$codLivro}");
    exit;
} else {
    // não existe: cria uma resenha vazia primeiro (caso sua FK exija)
    $privada = 1;
    $empty = '';
    $stmtR = $conexao->prepare("INSERT INTO resenha (resenha, privado, dataCriacao) VALUES (?, ?, NOW())");
    $stmtR->bind_param("si", $empty, $privada);
    $stmtR->execute();
    $fkResenha = $conexao->insert_id;
    $stmtR->close();

    // Insere nova leitura com situacao = 2
    $stmtI = $conexao->prepare("
        INSERT INTO leitura (fk_livro, fk_usuario, fk_resenha, situacao, paginaAtual, dataInicioLei, dataModificacaoLei, nota)
        VALUES (?, ?, ?, 2, 1, NULL, NULL, NULL)
    ");
    $stmtI->bind_param("iii", $codLivro, $codUsuario, $fkResenha);
    $ok2 = $stmtI->execute();
    $stmtI->close();

    $_SESSION['msg'] = $ok2
        ? "<div class='alert alert-success'>Livro adicionado a 'Quero Ler'.</div>"
        : "<div class='alert alert-danger'>Erro ao adicionar a leitura.</div>";

    header("Location: ../../index.php?menu=livro&codLivro={$codLivro}");
    exit;
}

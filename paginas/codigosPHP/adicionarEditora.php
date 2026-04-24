<?php
session_start();
header('Content-Type: application/json');
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if (!isset($_SESSION['codUsuario'])) {
    echo json_encode(['success'=>false,'msg'=>'Você precisa estar logado.']); exit;
}

$nome = trim($_POST['nome'] ?? '');
if ($nome === '') {
    echo json_encode(['success'=>false,'msg'=>'Nome vazio.']); exit;
}

// evita duplicidade
$stmt = $conexao->prepare("SELECT codEditora, editora FROM editora WHERE LOWER(editora) = LOWER(?) LIMIT 1");
$stmt->bind_param("s", $nome);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode(['success'=>true,'id'=>intval($row['codEditora']),'nome'=>$row['editora'],'msg'=>'Editora já existente.']);
    exit;
}
$stmt->close();

// insere
$stmt2 = $conexao->prepare("INSERT INTO editora (editora) VALUES (?)");
$stmt2->bind_param("s", $nome);
$ok = $stmt2->execute();
if ($ok) {
    $id = $conexao->insert_id;
    echo json_encode(['success'=>true,'id'=>$id,'nome'=>$nome]);
} else {
    echo json_encode(['success'=>false,'msg'=>'Erro ao inserir editora.']);
}
$stmt2->close();

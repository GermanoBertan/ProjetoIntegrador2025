<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

// Verifica login
if (!isset($_SESSION['codUsuario'])) {
    header("Location: ../../index.php");
    exit;
}

$codUsuario = $_SESSION['codUsuario'];

// Dados POST
$nome      = trim($_POST['nome'] ?? '');
$dtNasc    = trim($_POST['dtNascimento'] ?? '');
$senhaAnt  = trim($_POST['senha_antiga'] ?? '');
$novaSenha = trim($_POST['senha_nova'] ?? '');
$confSenha = trim($_POST['senha_confirma'] ?? '');

// ===============================
// VALIDAÇÃO CAMPOS BÁSICOS
// ===============================

if (strlen($nome) < 2) {
    $_SESSION['msg'] = "Nome muito curto.";
    header("Location: ../../index.php?menu=usuario");
    exit;
}

if (empty($dtNasc)) {
    $_SESSION['msg'] = "Informe uma data de nascimento válida.";
    header("Location: ../../index.php?menu=usuario");
    exit;
}

// Valida idade mínima (10 anos)
$data = new DateTime($dtNasc);
$hoje = new DateTime();
$idade = $hoje->diff($data)->y;

if ($idade < 10) {
    $_SESSION['msg'] = "É necessário ter ao menos 10 anos.";
    header("Location: ../../index.php?menu=usuario");
    exit;
}

// ===============================
// INICIAR ATUALIZAÇÃO — SEM SENHA
// ===============================

$atualizarSenha = false;
$senhaHash = "";

// ===============================
// VERIFICAR SE O USUÁRIO QUER TROCAR SENHA
// ===============================
if (!empty($senhaAnt) || !empty($novaSenha) || !empty($confSenha)) {

    // Senha atual obrigatória
    if (empty($senhaAnt)) {
        $_SESSION['msg'] = "Informe sua senha atual para alterar a senha.";
        header("Location: ../../index.php?menu=usuario");
        exit;
    }

    // Validar força da senha (MESMO PADRÃO DA CRIAÇÃO DE CONTA)
    $regexSenha = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

    if (!preg_match($regexSenha, $novaSenha)) {
        $_SESSION['msg'] = "A nova senha precisa ter ao menos 8 caracteres, 1 letra maiúscula, 1 letra minúscula e 1 número.";
        header("Location: ../../index.php?menu=usuario");
        exit;
    }

    // Verificar confirmação
    if ($novaSenha !== $confSenha) {
        $_SESSION['msg'] = "A confirmação da nova senha não coincide.";
        header("Location: ../../index.php?menu=usuario");
        exit;
    }

    // Buscar senha atual no BD
    $stmt = $conexao->prepare("SELECT senha FROM usuario WHERE codUsuario = ?");
    $stmt->bind_param("i", $codUsuario);
    $stmt->execute();
    $senhaBD = $stmt->get_result()->fetch_assoc()['senha'];
    $stmt->close();

    // Validar senha antiga
    if (!password_verify($senhaAnt, $senhaBD)) {
        $_SESSION['msg'] = "Senha atual incorreta.";
        header("Location: ../../index.php?menu=usuario");
        exit;
    }

    // Tudo OK → aplicar nova senha
    $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $atualizarSenha = true;
}

// ===============================
// ATUALIZAÇÃO FINAL NO BANCO
// ===============================

if ($atualizarSenha) {
    $sql = $conexao->prepare("
        UPDATE usuario 
        SET nome = ?, dtNascimento = ?, senha = ?
        WHERE codUsuario = ?
    ");
    $sql->bind_param("sssi", $nome, $dtNasc, $senhaHash, $codUsuario);

} else {
    $sql = $conexao->prepare("
        UPDATE usuario 
        SET nome = ?, dtNascimento = ?
        WHERE codUsuario = ?
    ");
    $sql->bind_param("ssi", $nome, $dtNasc, $codUsuario);
}

if ($sql->execute()) {
    $_SESSION['msg'] = "Perfil atualizado com sucesso!";

    // Atualiza o nome na sessão
    $_SESSION['nome'] = $nome;

    // Recarrega toda a index para atualizar cabeçalho e sessão
    header("Location: ../../index.php?menu=usuario");
    exit;

} else {
    $_SESSION['msg'] = "Erro ao atualizar: " . $conexao->error;
    header("Location: ../../index.php?menu=usuario");
    exit;
}
?>

<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php"); 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/classes/Usuario.php");

/*
============================================================
  TRATAMENTO E VALIDAÇÃO DOS CAMPOS RECEBIDOS
============================================================
*/

// Campos vindos do formulário
$nome        = trim($_POST['nome']        ?? '');
$email       = trim($_POST['email']       ?? '');
$dtNasc      = trim($_POST['dtNascimento']?? '');
$senha       = trim($_POST['senha']       ?? '');
$confirma    = trim($_POST['conf_senha']  ?? '');

// Verificação: todos os campos preenchidos
if (empty($nome) || empty($email) || empty($dtNasc) || empty($senha) || empty($confirma)) {
    $_SESSION['msgErro'] = "Preencha todos os campos.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

// Verifica nome mínimo
if (strlen($nome) < 2) {
    $_SESSION['msgErro'] = "Nome muito curto.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

// Verifica email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msgErro'] = "Email inválido.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

// Valida idade mínima (10 anos)
$data = new DateTime($dtNasc);
$hoje = new DateTime();
$idade = $hoje->diff($data)->y;

if ($idade < 10) {
    $_SESSION['msgErro'] = "É necessário ter ao menos 10 anos.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

// Valida a força da senha
$regexSenha = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
if (!preg_match($regexSenha, $senha)) {
    $_SESSION['msgErro'] = "Senha fraca. Use letras maiúsculas, minúsculas e números.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

// Verifica confirmação
if ($senha !== $confirma) {
    $_SESSION['msgErro'] = "As senhas não coincidem.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

/*
============================================================
  INSERÇÃO NO BANCO DE DADOS
============================================================
*/

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Verificar se email já existe
$sqlCheck = $conexao->prepare("SELECT email FROM usuario WHERE email = ?");
$sqlCheck->bind_param("s", $email);
$sqlCheck->execute();
$result = $sqlCheck->get_result();

if ($result->num_rows > 0) {
    $_SESSION['msgErro'] = "Este e-mail já está cadastrado.";
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

// Inserir novo usuário
$sql = $conexao->prepare("
    INSERT INTO usuario (email, senha, nome, dtNascimento, adm) 
    VALUES (?, ?, ?, ?, 0)
");
$sql->bind_param("ssss", $email, $senhaHash, $nome, $dtNasc);

if ($sql->execute()) {
    $_SESSION['msgSucesso'] = "Conta criada com sucesso! Faça login.";
    header("Location: /PlataformaPi/login.php");
    exit;
} else {
    $_SESSION['msgErro'] = "Erro ao salvar: " . $con->error;
    header("Location: /PlataformaPi/criarConta.php");
    exit;
}

?>

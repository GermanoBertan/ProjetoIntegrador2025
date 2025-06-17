<?php
include("./bd/conexao.php");
session_start();

// CODIGOS PARA NÃO PRECISAR FAZER O LOGIN TODA VEZ
$_SESSION["usuarioEmail"] = 'teste@plat.com';
$_SESSION["usuarioSenha"] = 'admin123';
$_SESSION["usuarioNome"] = 'nome';
$_SESSION["usuarioTipo"] = 'tipo';


if (isset($_SESSION["usuarioEmail"]) && isset($_SESSION["usuarioSenha"])) {
    $usuarioEmail = $_SESSION["usuarioEmail"];
    $usuarioSenha = $_SESSION["usuarioSenha"];
    $usuarioNome = $_SESSION["usuarioNome"];
    $usuarioTipo = $_SESSION["usuarioTipo"];

    $sql = "SELECT * FROM usuario WHERE Email = '{$usuarioEmail}' and Senha = '{$usuarioSenha}'";
    $rs = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_assoc($rs);
    $linha = mysqli_num_rows($rs);

    if ($linha == 0) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }

} else {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Plataforma</title>
</head>

<body class=" ">

    <header>
        <div class="mb-3">
            <nav class="navbar navbar-expand-md navbar-dark corPrincipal">

                <a class="navbar-brand" href="index.php">
                    <span class="material-symbols-outlined"><img src="./imagens/logo.png" height="80px"
                            alt="Logo"></span></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapsibleNavbar">
                    <span class="bi bi-list"></span>
                </button>

                <div class="collapse navbar-collapse text-center" id="collapsibleNavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?menu=inicio">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?menu=usuario">Explorar</a>
                        </li>
                    </ul>

                    <div class="justify-content-end">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a href="index.php?menu=usuario" class="nav-link">
                                    <i class="bi bi-person corAzulEscuro"> <?= $usuarioNome ?> </i>
                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="sair.php" class="nav-link text-danger">Sair <i
                                        class="bi bi-box-arrow-right text-danger"></i> </a>
                            </li>
                    </div>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">

<?php
    $menu = ( isset($_GET['menu'])) ? $_GET['menu'] : 'inicio';

    switch ($menu) {
        case 'inicio':
            include("./paginas/livros/1.php");
            break;
        case 'usuario' :
             include("./paginas/usuario/1.php");
             break;

        default:
            include("./paginas/inicio/inicio.php");
            break;
    }

?>

    </div>
    
    </main>

    <footer class="container-fluid bg-dark">
        <div class="text-center">
            <p class="text-white">
                PLATAFORMA LEITURA - V 1.0
            </p>
        </div>
    </footer>

    <script src="./js/jquery.js"></script>
    <script src="./js/jquery.form.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj"
        crossorigin="anonymous"></script>
    <script src="./js/validation.js"></script>
    <script src="./js/novoItem.js"></script>
</body>

</html>
<?php
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

session_start();
if (isset($_SESSION["codUsuario"]) && isset($_SESSION["nome"])) {

} else {
    session_unset();
    session_destroy();
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
    <link href='https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>LECTUS - Organize suas leituras</title>
</head>

<body class="">

    <header>
        <div class="mb-3">
            <nav class="navbar navbar-expand-md navbar-dark corAzul">

                <a class="navbar-brand" href="index.php" style="margin-left: 1rem;">
                    <span class="material-symbols-outlined "><img src="./img/logoCinza.png" class="rounded" height="80px" alt="Logo"></span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="bi bi-list"></span>
                </button>

               <div class="collapse navbar-collapse text-center" id="collapsibleNavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?menu=inicio">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?menu=explorar">Explorar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?menu=cad">Cadastrar Livro</a>
                        </li>   
                    </ul>

                    <div class="justify-content-end ">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a href="index.php?menu=usuario&codUsuario=<?=$_SESSION["codUsuario"]?>" class="nav-link">
                                    <i class="bi bi-person"> <?= $_SESSION["nome"]?> </i>     
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="sair.php" class="nav-link text-danger">Sair <i class="bi bi-box-arrow-right text-danger"></i> </a>
                            </li>
                        </ul>
                    </div>
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
            include("./paginas/inicial.php");
            break;
        case 'leitura':
            include("./paginas/leitura.php");
            break;
        case 'usuario' :
             include("./paginas/perfil.php");
             break;
        case 'lido' :
            include("./paginas/lido.php");
            break;
        case 'livro' :
            include("./paginas/livro.php");
            break;
        case 'cad' :
            include("./paginas/cadastrarLivro.php");
            break;
        case 'explorar' :
            include("./paginas/explorar.php");
            break;
        default:
            include("./paginas/inicial.php");
            break;
    }

?>

    </div>
    
    </main>

    <footer class="container-fluid bg-dark">
        <div class="text-center">
            <p class="text-white">
                PLATAFORMA LECTUS   _   2025   _   PI3
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
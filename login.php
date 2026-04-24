<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");
include("./classes/Usuario.php");

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST["usuarioEmail"];
    $senha = $_POST["usuarioSenha"];

    $usuario = new Usuario($conexao, $email, $senha);
    //$usuario->imprimir();

    if ( $usuario->getCodUsuario() != "") {
        $usuario->iniciarSessao();
    }else{
        $erro = " <h6 class='text-danger fw-bold'> Usuário e/ou senha incorreto! </h6>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href='https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap' rel='stylesheet'>
    <link href="./css/style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="img/logo.png">
    <title>Login - LECTUS</title>
</head>

<body class="corAzul">
<div class="container-fluid">

    <ul class="nav mb-3 justify-content-end">
        <li class="nav-item">
            <a href="criarConta.php" class="btn btn-warning mt-4 m-3 float-end">CRIAR CONTA</a>
        </li>
    </ul>

    <div class="row justify-content-around text-center align-items-center">
        <div class="col-10 col-xl-6 text-center align-items-center">
            <img src="./img/logoCinza.png" class="rounded w-50 mb-4 mb-sm-0" >
        </div>

        <div class="col-10 col-xl-6">
            <div class="row justify-content-center align-items-center ">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 p-3 shadow-lg rounded vw-75 corCinza">
                    <div class="row text-black justify-content-center mb-3">
                        <h4>Acesse a LECTUS</h4>
                    </div>

                    <div class="row text-black justify-content-center">
                        <?php
                            echo $erro; 
                        ?>  
                    </div>

                    <form class="needs-validation mb-3 text-black" action="login.php" method="post" novalidate>
                        <div class="form-group mb-2 text-start">
                            <label class="form-label mb-1 " for="usuarioEmail">E-mail</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input class="form-control" type="email" name="usuarioEmail" id="usuarioEmail" placeholder="nome@dominio.com" required>
                                <div class="invalid-feedback">
                                    Informe seu E-mail de acesso.
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 text-start">
                            <label class="form-label mb-1" for="usuarioSenha">Senha</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input class="form-control" type="password" name="usuarioSenha" id="usuarioSenha" placeholder="******" required>
                                <div class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                        </div>

                        <button class="btn corAzul text-white w-100" id="btnEntrar"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>
                    </form>

                    <div class="text-center">
                        <a href="esqueceuSenha.php" class="badge text-dark mb-0">Esqueceu a senha? </a>
                        <!-- <a href="#.php" class="badge badge-danger mb-0">Crie sua conta!</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    

</div>  
</body>

</html>
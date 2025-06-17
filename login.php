<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="./css/style.css" rel="stylesheet">
    <title>Login - Plataforma</title>
</head>

<body class="corPrincipal">
<div class="container-fluid">

    <ul class="nav mb-3 justify-content-end">
        <li class="nav-item">
            <a href="#" class="btn btn-warning mt-4 m-3 float-end">CRIAR CONTA</a>
        </li>
    </ul>

    <div class="row justify-content-around align-items-center">
        <div class="col-10 col-xl-4 mb-4 align-items-center">
            <h1 class="text-center text-danger">LOGO DA PLATAFORMA</h1>
        </div>

        <div class="col-10 col-xl-8 mt-4">
            <div class="row justify-content-center align-items-center ">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 p-4 shadow-lg rounded">
                    <div class="row text-white text-center justify-content-center mb-4">
                        <h4>Acesse à plataforma</h4>
                    </div>

                    <form class="needs-validation mb-3 text-white" action="login.php" method="post" novalidate>
                        <div class="form-group mb-2">
                            <label class="form-label mb-1" for="usuarioEmail">E-mail</label>
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

                        <div class="form-group mb-3">
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

                        <button class="btn btn-success w-100"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>
                    </form>

                    <div class="text-center">
                        <a href="#.php" class="badge badge-ligth mb-0">Esqueceu a senha? </a>
                        <!-- <a href="#.php" class="badge badge-danger mb-0">Crie sua conta!</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    

</div>  
</body>

</html>
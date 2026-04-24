<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="img/logo.png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/criarConta.css">

    <title>Crie sua conta!</title>
</head>

<body class="bg-light">

    <!-- Topo com nome da plataforma -->
    <header class="text-center py-4 mb-3 azul text-white shadow-sm">
        <h1 class="m-0 fw-bold" style="letter-spacing: 1px;">Lectus</h1>
    </header>

    <div class="container">

        <?php
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['msgErro'])) {
            echo "<div class='alert alert-danger text-center'>".$_SESSION['msgErro']."</div>";
            unset($_SESSION['msgErro']);
        }
        ?>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <div class="card shadow p-4">

                    <h2 class="text-center mb-4">Crie sua conta e comece a ler!</h2>

                    <form id="formulario" action="paginas/codigosPHP/salvarUsuario.php" method="POST" novalidate>

                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-semibold">Nome completo:</label>
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Seu nome aqui">
                            <small id="erroNome" class="erro">Digite um nome válido (mín. 2 caracteres).</small>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="nome@dominio.com">
                            <small id="erroEmail" class="erro">Digite um e-mail válido.</small>
                        </div>

                        <!-- Data nascimento -->
                        <div class="mb-3">
                            <label for="dtNascimento" class="form-label fw-semibold">Data de nascimento:</label>
                            <input type="date" name="dtNascimento" id="dtNascimento" class="form-control">
                            <small id="erroNascimento" class="erro">Data inválida (mínimo 10 anos).</small>
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label for="senha" class="form-label fw-semibold">Senha:</label>
                            <input type="password" name="senha" id="senha" class="form-control" placeholder="Mínimo 8 caracteres">
                            <small id="erroSenha" class="erro">
                                A senha deve ter ao menos 8 caracteres, incluindo letras maiúsculas, minúsculas e números.
                            </small>
                        </div>

                        <!-- Confirmar -->
                        <div class="mb-3">
                            <label for="conf_senha" class="form-label fw-semibold">Confirmar senha:</label>
                            <input type="password" name="conf_senha" id="conf_senha" class="form-control" placeholder="Repita a senha">
                            <small id="erroConf" class="erro">As senhas devem ser iguais.</small>
                        </div>

                        <!-- Botão -->
                        <div class="d-grid mt-4">
                            <button type="submit" id="btnCriar" class="btn btn-primary btn-lg fw-bold">
                                Criar Conta
                            </button>
                        </div>

                    </form>

                    <p class="text-center mt-3 text-secondary">
                        Já tem conta? <a href="index.php?menu=login" class="text-primary fw-semibold">Fazer login</a>
                    </p>

                </div>

            </div>
        </div>

    </div>

    <!-- Script JS original -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // (Seu script completo, mantido exatamente igual)
        const form = document.getElementById("formulario");

        form.addEventListener("submit", function (e) {
            let valido = true;

            const nome = document.getElementById("nome");
            if (nome.value.trim().length < 2) {
                nome.classList.add("erro");
                document.getElementById("erroNome").style.display = "block";
                valido = false;
            } else {
                nome.classList.remove("erro");
                document.getElementById("erroNome").style.display = "none";
            }

            const email = document.getElementById("email");
            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regexEmail.test(email.value)) {
                email.classList.add("erro");
                document.getElementById("erroEmail").style.display = "block";
                valido = false;
            } else {
                email.classList.remove("erro");
                document.getElementById("erroEmail").style.display = "none";
            }

            const dt = document.getElementById("dtNascimento");
            const data = new Date(dt.value);
            const hoje = new Date();
            const idade = hoje.getFullYear() - data.getFullYear();

            if (!dt.value || idade < 10) {
                dt.classList.add("erro");
                document.getElementById("erroNascimento").style.display = "block";
                valido = false;
            } else {
                dt.classList.remove("erro");
                document.getElementById("erroNascimento").style.display = "none";
            }

            const senha = document.getElementById("senha");
            const forte = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            if (!forte.test(senha.value)) {
                senha.classList.add("erro");
                document.getElementById("erroSenha").style.display = "block";
                valido = false;
            } else {
                senha.classList.remove("erro");
                document.getElementById("erroSenha").style.display = "none";
            }

            const conf = document.getElementById("conf_senha");
            if (conf.value !== senha.value || conf.value === "") {
                conf.classList.add("erro");
                document.getElementById("erroConf").style.display = "block";
                valido = false;
            } else {
                conf.classList.remove("erro");
                document.getElementById("erroConf").style.display = "none";
            }

            if (!valido) e.preventDefault();
        });
    </script>

</body>
</html>

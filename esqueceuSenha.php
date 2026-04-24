<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $para = "germanobertan8@gmail.com";
    $assunto = "Teste de envio de e-mail PHP";
    $mensagem = "Olá! Este é um e-mail enviado via função mail() do PHP.";
    $cabecalhos = "From: germanobertan8@gmail.com\r\n" .
                "Reply-To: germanobertan8@gmail.com\r\n" .
                "X-Mailer: PHP/" . phpversion();

    if (mail($para, $assunto, $mensagem, $cabecalhos)) {
        echo "E-mail enviado com sucesso!";
    } else {
        echo "Falha no envio do e-mail.";
    }

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/esqueceuSenha.css">
        <link rel="icon" type="image/png" href="img/logo.png">

    <title>Esquecer a senha</title>
</head>

<body>
    <div id="container">
        <h1>Esqueceu a Senha?</h1><br>
        <form id="formulario" action="esqueceuSenha.php" method="post">
            <label id="pEmail" for="Email"><p>Email:</p></label>
            <input type="email" name="Email" id="Email" placeholder="nome@dominio.com">

            <br>
            <p>O código de recuperação será enviado no seu email!</p><br>
            <button type="submit">Enviar código</button>
        </form>
        <br><a href="login.php">Voltar para login</a>
    </div>
</body>

</html>
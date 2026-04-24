<?php
require_once("config.php");

$conexao = mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCO) 
or die("Erro na conexão com o servidor! Erro: " . mysqli_connect_error());

if (mysqli_connect_errno()){
echo "Falha ao conectar no Banco de Dados MySQL: " . mysqli_connect_error();
}

mysqli_query($conexao, "SET NAMES 'utf8'") or die("Erro na SQL" . mysqli_error($conexao));
mysqli_query($conexao, 'SET character_set_connection=utf8') or die("Erro na SQL" . mysqli_error($conexao));
mysqli_query($conexao, 'SET character_set_client=utf8') or die("Erro na SQL" . mysqli_error($conexao));
mysqli_query($conexao, 'SET character_set_results=utf8') or die("Erro na SQL" . mysqli_error($conexao));



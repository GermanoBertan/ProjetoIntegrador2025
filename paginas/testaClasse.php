<?php
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/classes/Autor.php");
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/classes/Categoria.php");
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/classes/FaixaEtaria.php"); 

    $autor = new Autor(2);
    $autor->imprimir();

    $categoria = new Categoria(2);
    $categoria->imprimir();

    $faixaetaria = new FaixaEtaria(2);
    $faixaetaria->imprimir();
    
?>
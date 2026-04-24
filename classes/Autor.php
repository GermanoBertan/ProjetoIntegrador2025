<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class Autor {
    private $codAutor;
    private $autor;

    public function __construct($codAutor) {
    global $conexao;

        $sql = "SELECT * FROM autor WHERE codAutor = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $codAutor); // "i" = integer
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $this->codAutor = $dados['codAutor'];
            $this->autor = $dados['autor'];
        }
        $stmt->close();
    
    }

    public function imprimir(){
        if ( isset($this->codAutor) ) {
            echo $this->codAutor." ".$this->autor."<br>";
        }else {
            echo 'Autor não encontrado!';
        }
    }
}

?>

<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class Categoria {
    private $codCategoria;
    private $categoria;

    public function __construct($codCategoria) {
    global $conexao;

        $sql = "SELECT * FROM categoria WHERE codCategoria = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $codCategoria); // "i" = integer
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $this->codCategoria = $dados['codCategoria'];
            $this->categoria = $dados['categoria'];
        }
        $stmt->close();
    
    }

    public function imprimir(){
        if ( isset($this->codCategoria) ) {
            echo $this->codCategoria." ".$this->categoria."<br>";
        }else {
            echo 'Categoria não encontrado!';
        }
    }
}

?>

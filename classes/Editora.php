<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class Autor {
    private $codEditora;
    private $nome;
    private $cidade;
    private $estado;
    private $site;

    public function __construct($codEditora) {
    global $conexao;
    
    $sql = "SELECT * FROM editora WHERE codEditora = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $codEditora); // "i" = integer
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $this->codEditora = $dados['codEditora'];
            $this->nome = $dados['editora'];
            $this->cidade = $dados['cidade'];
            $this->cidade = $dados['estado'];
            $this->site = $dados['site'];
        }
        $stmt->close();
    
    }

    public function imprimir(){
        if ( isset($this->codEditora) ) {
            echo $this->codEditora." ".$this->nome."<br>";
        }else {
            echo 'Editora não encontrada!';
        }
    }
}

?>

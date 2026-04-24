<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class FaixaEtaria {
    private $codFaixaEtaria;
    private $nome;
    private $abreviacao;
    private $idadeLimite;

    public function __construct($codFE) {
    global $conexao;

        $sql = "SELECT * FROM faixaetaria WHERE codFaixaEtaria = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $codFE); // "i" = integer
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $this->codFaixaEtaria = $dados['codFaixaEtaria'];
            $this->nome = $dados['faixaEtaria'];
            $this->abreviacao = $dados['abreviacao'];
        }
        $stmt->close();
    
    }

    public function imprimir(){
        if ( isset($this->codFaixaEtaria) ) {
            echo $this->codFaixaEtaria." ".$this->nome."<br>";
        }else {
            echo 'Faixa etária não encontrada!';
        }
    }
}

?>

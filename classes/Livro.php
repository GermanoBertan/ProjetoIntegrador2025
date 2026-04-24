<?php 
//session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class Livro {
    private $codLivro;
    private $titulo;
    private $ISBN;
    private $anoPublicacao;
    private $fk_faixaEtaria;
    private $fk_editora;

    public function __construct($cLivro) {
    global $conexao;

        $stmt = $conexao->prepare("SELECT * FROM Livro 
                                   WHERE codLivro = ? 
                                   LIMIT 1");
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $conexao->error);
        }

        $cLivro = (int)$cLivro;
        $stmt->bind_param("i", $cLivro);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($dados = $result->fetch_assoc()) {
            $this->codLivro   = $dados['codLivro'];
            $this->titulo     = $dados['titulo'];
            $this->ISBN       = $dados['ISBN'];
            $this->anoPublicacao   = $dados['anoPublicacao'];
            $this->fk_faixaEtaria  = $dados['fk_faixaEtaria'];
            $this->fk_editora      = $dados['fk_editora'];
        } 
        $stmt->close();
    }

    
}
?>

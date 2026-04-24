<?php 
//session_start();
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class Leitura {
    private $codLeitura;
    private $situacao;
    private $dataInicio;
    private $numPagina;
    private $paginaAtual;
    private $fk_livro;
    private $fk_resenha;
    private $fk_usuario;

    public function __construct($cLeitura) {
    global $conexao;

        // if (is_array($cLeitura)) {
        //     if (isset($cLeitura['codLeitura'])) {
        //         $cLeitura = $cLeitura['codLeitura'];
        //     } else {
        //         throw new Exception("Chave 'codLeitura' não encontrada no array passado para o construtor.");
        //     }
        // }

        $stmt = $conexao->prepare("SELECT * FROM leitura 
                                    WHERE situacao = 1 AND 
                                    fk_usuario = ? AND 
                                    codLeitura = ? 
                                    LIMIT 1");
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta: " . $conexao->error);
        }

        $codUsuario = (int)$_SESSION['codUsuario'];
        $codLeit = (int)$cLeitura;

        $stmt->bind_param("ii", $codUsuario, $codLeit);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($dados = $result->fetch_assoc()) {
            $this->codLeitura   = $dados['codLeitura'];
            $this->situacao     = $dados['situacao'];
            $this->dataInicio   = $dados['dataInicioLei'];
            $this->paginaAtual  = $dados['paginaAtual'];
            $this->numPagina    = $dados['numPagina'];
            $this->fk_livro     = $dados['fk_livro'];
            $this->fk_resenha   = $dados['fk_resenha'];
            $this->fk_usuario   = $dados['fk_usuario'];
        } 
        $stmt->close();
    }

    public function porcentagem() {
        $total = (double) $this->numPagina;
        $atual = (double) $this->paginaAtual;
        if ($total <= 0) return 0; 
        return ($atual * 100) / $total;
    }

    public function getCodLeitura() {
        return $this->codLeitura;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function getNumPagina() {
        return $this->numPagina;
    }

    public function getPaginaAtual() {
        return $this->paginaAtual;
    }

    public function getFkLivro() {
        return $this->fk_livro;
    }

    public function getFkResenha() {
        return $this->fk_resenha;
    }

    public function getFkUsuario() {
        return $this->fk_usuario;
    }


}
?>

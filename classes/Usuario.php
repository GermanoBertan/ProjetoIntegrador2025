<?php 
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

class Usuario {
    private $codUsuario;
    private $email;
    private $senha;
    private $nome;
    private $dtNascimento;
    public $adm;

    public function __construct($conexao, $em, $sen) {

        $sql = "SELECT * FROM usuario WHERE email = ? LIMIT 1";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $em);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();

            // Verifica a senha com hash
            if (password_verify($sen, $dados['senha'])) {
                $this->codUsuario   = $dados['codUsuario'];
                $this->email        = $dados['email'];
                $this->senha        = $dados['senha'];
                $this->nome         = $dados['nome'];
                $this->dtNascimento = $dados['dtNascimento'];
                $this->adm          = $dados['adm'];
            }
        }
        $stmt->close();
    

    }

    public function iniciarSessao(){
        session_start();
        $_SESSION["codUsuario"] = $this->codUsuario;
        $_SESSION["nome"] = $this->nome;
        $_SESSION["adm"] = $this->adm;

        header('Location: index.php');
    }

    public function getCodUsuario(){
        return $this->codUsuario;
    }

    public function imprimir(){
        echo $this->codUsuario." ".$this->email." ".$this->senha." ".$this->nome." ".$this->dtNascimento." ".$this->adm."<br>";
    }

}

?>

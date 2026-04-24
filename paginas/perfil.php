<?php
include($_SERVER['DOCUMENT_ROOT'] . "/PlataformaPi/bd/conexao.php");

// Verifica login
if (!isset($_SESSION['codUsuario'])) {
    header("Location: index.php?menu=login");
    exit;
}

$codUsuario = $_SESSION['codUsuario'];

// Buscar dados do usuário
$stmt = $conexao->prepare("SELECT nome, email, dtNascimento FROM usuario WHERE codUsuario = ?");
$stmt->bind_param("i", $codUsuario);
$stmt->execute();
$dados = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<style>
body {
    background:#f5f6fb;
}

.profile-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

/* Cor do botão */
.btn-lectus {
    background:#36489e;
    color:white;
}
.btn-lectus:hover {
    background:#2c3c85;
    color:white;
}
</style>

<div class="container py-4">
    <h3 class="text-center mb-4">Meu Perfil</h3>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-12">

            <div class="profile-card">

                <?php 
                if(isset($_SESSION['msg'])){
                    echo "<div class='alert alert-info text-center'>".$_SESSION['msg']."</div>";
                    unset($_SESSION['msg']);
                }
                ?>

                <form action="paginas/codigosPHP/salvarPerfil.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome</label>
                        <input type="text" name="nome" class="form-control" required 
                               value="<?= htmlspecialchars($dados['nome']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">E-mail (não pode ser alterado)</label>
                        <input type="email" class="form-control" disabled
                               value="<?= htmlspecialchars($dados['email']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Data de nascimento</label>
                        <input type="date" name="dtNascimento" class="form-control" required
                               value="<?= $dados['dtNascimento'] ?>">
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-2">Alterar senha</h6>
                    <p class="text-muted" style="font-size:0.9rem;">
                        ⚠ Esses campos são opcionais.<br>
                        Preencha <b>somente</b> se deseja alterar sua senha.<br>
                        Caso contrário, deixe <b>todos em branco</b>.
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Senha atual</label>
                        <input type="password" name="senha_antiga" class="form-control" placeholder="Digite sua senha atual">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nova senha</label>
                        <input type="password" name="senha_nova" class="form-control"
                               placeholder="Nova senha (mínimo 8 caracteres)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar nova senha</label>
                        <input type="password" name="senha_confirma" class="form-control"
                               placeholder="Repita a nova senha">
                    </div>

                    <button class="btn btn-lectus w-100 mt-3">Salvar alterações</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

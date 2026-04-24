<script>
document.addEventListener('DOMContentLoaded', function() {

  // helper: mostra alerta simples dentro do modal
  function showAlert(containerId, msg, type='danger') {
    const html = `<div class="alert alert-${type} alert-sm">${msg}</div>`;
    document.getElementById(containerId).innerHTML = html;
  }

  // ADICIONAR AUTOR
  document.getElementById('formAddAutor').addEventListener('submit', async function(e){
    e.preventDefault();
    const nome = document.getElementById('nomeAutor').value.trim();
    if (!nome) return showAlert('alertAutor','Informe o nome do autor.','warning');

    const fd = new FormData();
    fd.append('nome', nome);

    const res = await fetch('paginas/codigosPHP/adicionarAutor.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      // adiciona ao select e seleciona
      const sel = document.getElementById('autoresSelect');
      const opt = document.createElement('option');
      opt.value = json.id;
      opt.text = json.nome;
      opt.selected = true;
      sel.appendChild(opt);
      // fecha modal
      var modal = bootstrap.Modal.getInstance(document.getElementById('modalAddAutor'));
      modal.hide();
      document.getElementById('nomeAutor').value = '';
    } else {
      showAlert('alertAutor', json.msg || 'Erro ao salvar autor.');
    }
  });

  // ADICIONAR CATEGORIA
  document.getElementById('formAddCategoria').addEventListener('submit', async function(e){
    e.preventDefault();
    const nome = document.getElementById('nomeCategoria').value.trim();
    if (!nome) return showAlert('alertCategoria','Informe o nome da categoria.','warning');

    const fd = new FormData();
    fd.append('nome', nome);

    const res = await fetch('paginas/codigosPHP/adicionarCategoria.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      const sel = document.getElementById('categoriasSelect');
      const opt = document.createElement('option');
      opt.value = json.id;
      opt.text = json.nome;
      opt.selected = true;
      sel.appendChild(opt);
      var modal = bootstrap.Modal.getInstance(document.getElementById('modalAddCategoria'));
      modal.hide();
      document.getElementById('nomeCategoria').value = '';
    } else {
      showAlert('alertCategoria', json.msg || 'Erro ao salvar categoria.');
    }
  });

  // ADICIONAR EDITORA
  document.getElementById('formAddEditora').addEventListener('submit', async function(e){
    e.preventDefault();
    const nome = document.getElementById('nomeEditora').value.trim();
    if (!nome) return showAlert('alertEditora','Informe o nome da editora.','warning');

    const fd = new FormData();
    fd.append('nome', nome);

    const res = await fetch('paginas/codigosPHP/adicionarEditora.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      const sel = document.getElementById('editorasSelect');
      const opt = document.createElement('option');
      opt.value = json.id;
      opt.text = json.nome;
      opt.selected = true;
      sel.appendChild(opt);
      var modal = bootstrap.Modal.getInstance(document.getElementById('modalAddEditora'));
      modal.hide();
      document.getElementById('nomeEditora').value = '';
    } else {
      showAlert('alertEditora', json.msg || 'Erro ao salvar editora.');
    }
  });

});
</script>

<?php
include($_SERVER['DOCUMENT_ROOT']."/PlataformaPi/bd/conexao.php");

// Busca listas de autores, categorias, editoras e faixas etárias
$autores = $conexao->query("SELECT codAutor, autor FROM autor ORDER BY autor ASC")->fetch_all(MYSQLI_ASSOC);
$categorias = $conexao->query("SELECT codCategoria, categoria FROM categoria ORDER BY categoria ASC")->fetch_all(MYSQLI_ASSOC);
$editoras = $conexao->query("SELECT codEditora, editora FROM editora ORDER BY editora ASC")->fetch_all(MYSQLI_ASSOC);
$faixas = $conexao->query("SELECT codFaixaEtaria, faixaEtaria, abreviacao FROM faixaetaria ORDER BY faixaEtaria ASC")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container my-5 shadow-lg p-4 bg-white rounded">
  <h2 class="mb-4 text-center">Cadastrar Novo Livro</h2>

  <form action="paginas/codigosPHP/salvarLivro.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Título do Livro:</label>
      <input type="text" name="titulo" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Descrição / Sinopse:</label>
      <textarea name="descricao" class="form-control" rows="4" required></textarea>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">ISBN:</label>
        <input type="number" name="isbn" class="form-control" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Número de Páginas:</label>
        <input type="number" name="nPagina" min="1" class="form-control" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Ano de Publicação:</label>
        <input type="number" name="anoPublicacao" class="form-control" required>
      </div>
    </div>

    <div class="row">
      <!-- Editoras select + botão adicionar -->
       <div class="col-md-6 mb-3">
        <label class="form-label">Editora:</label>
        <div class="input-group">
            <select id="editorasSelect" name="fk_editora" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($editoras as $e): ?>
                <option value="<?=$e['codEditora']?>"><?=htmlspecialchars($e['editora'])?></option>
            <?php endforeach; ?>
            </select>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalAddEditora">+</button>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Faixa Etária:</label>
        <select name="fk_faixaEtaria" class="form-select" required>
          <option value="">Selecione...</option>
          <?php foreach ($faixas as $f): ?>
            <option value="<?=$f['codFaixaEtaria']?>"><?=$f['faixaEtaria']?> (<?=$f['abreviacao']?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- Autores select + botão adicionar -->
    <div class="mb-3">
    <label class="form-label">Autores:</label>
    <div class="input-group">
        <select id="autoresSelect" name="autores[]" class="form-select" multiple required>
        <?php foreach ($autores as $a): ?>
            <option value="<?=$a['codAutor']?>"><?=htmlspecialchars($a['autor'])?></option>
        <?php endforeach; ?>
        </select>
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalAddAutor">+</button>
    </div>
    <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar vários autores.</small>
    </div>

    <!-- Categorias select + botão adicionar -->
    <div class="mb-3">
    <label class="form-label">Categorias:</label>
    <div class="input-group">
        <select id="categoriasSelect" name="categorias[]" class="form-select" multiple required>
        <?php foreach ($categorias as $c): ?>
            <option value="<?=$c['codCategoria']?>"><?=htmlspecialchars($c['categoria'])?></option>
        <?php endforeach; ?>
        </select>
<!--    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalAddCategoria">+</button> -->
    </div>
    <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar várias categorias.</small>
    </div>

    <hr>
    <h5 class="mt-4">Informações da Edição</h5>

    <div class="mb-3">
      <label class="form-label">Nome da Edição:</label>
      <input type="text" name="edicao_nome" class="form-control" placeholder="Ex: Edição Clássica">
    </div>

    <div class="mb-3">
      <label class="form-label">Autor da Imagem:</label>
      <input type="text" name="autorImagem" class="form-control" placeholder="Ex: Amazon, Lectus...">
    </div>

    <div class="mb-3">
      <label class="form-label">Capa do Livro:</label>
      <input type="file" name="capa" class="form-control mb-2" accept="image/*">
      <small class="text-muted">Ou insira o link (URL) abaixo:</small>
      <input type="url" name="url_capa" class="form-control" placeholder="https://...">
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-3">Salvar Livro</button>
  </form>
</div>


<!-- MODAL: Adicionar Categoria -->
<div class="modal fade" id="modalAddCategoria" tabindex="-1" aria-labelledby="modalAddCategoriaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAddCategoria" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddCategoriaLabel">Adicionar Categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div id="alertCategoria"></div>
        <div class="mb-3">
          <label for="nomeCategoria" class="form-label">Nome da Categoria:</label>
          <input type="text" id="nomeCategoria" name="nome" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Adicionar</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Adicionar Autor -->
<div class="modal fade" id="modalAddAutor" tabindex="-1" aria-labelledby="modalAddAutorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAddAutor" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddAutorLabel">Adicionar Autor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div id="alertAutor"></div>
        <div class="mb-3">
          <label for="nomeAutor" class="form-label">Nome do Autor:</label>
          <input type="text" id="nomeAutor" name="nome" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Adicionar</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Adicionar Editora -->
<div class="modal fade" id="modalAddEditora" tabindex="-1" aria-labelledby="modalAddEditoraLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAddEditora" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddEditoraLabel">Adicionar Editora</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div id="alertEditora"></div>
        <div class="mb-3">
          <label for="nomeEditora" class="form-label">Nome da Editora:</label>
          <input type="text" id="nomeEditora" name="nome" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Adicionar</button>
      </div>
    </form>
  </div>
</div>

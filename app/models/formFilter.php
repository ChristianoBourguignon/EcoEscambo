<?php $categorias = (new \app\controllers\ProductsController)::getCategorias(); ?>
<form id="filter" method="GET" class="mb-5">
    <div class="row align-items-end">
        <div class="col-md-6 mb-3">
            <label for="busca" class="form-label">Busca</label>
            <input type="text" class="form-control" id="busca" name="busca" placeholder="Digite o que você procura...">
        </div>
        <div class="col-md-4 mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="" selected>Selecione uma opção</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['nome'] ?>"><?= $cat['nome'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 mb-3 d-grid">
            <button type="submit" id="filtrar" class="btn btn-primary">
                <i class="bi bi-filter"></i> Filtrar
            </button>
        </div>
    </div>
</form>

<?php
namespace app\controllers;
dbController::getConnection();
$categorias = ProductsController::getCategorias();
?>
<!-- Modal de Cadastro de Produto -->
<div class="modal fade" id="alterarProdutoModal" tabindex="-1" aria-labelledby="alterarProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="alterarProdutoModalLabel">Alterar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="<?= BASE ?>/alterarProduto" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" readonly id="id" name="id" required/>

                        <label for="nome" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="nome" name="nome" required/>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input class="form-control" id="descricao" name="descricao" required/>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categorias para o produto: </label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['nome'] ?>"><?= $cat['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem do Produto</label>
                        <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Alterar</button>
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('alterarProdutoModal');
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const nome = button.getAttribute('data-nome');
                const descricao = button.getAttribute('data-descricao');
                const categoria = button.getAttribute('data-categoria');

                modal.querySelector('#id').value = id;
                modal.querySelector('#nome').value = nome;
                modal.querySelector('#descricao').value = descricao;
                modal.querySelector('#categoria').value = categoria;
            });
        });
    </script>

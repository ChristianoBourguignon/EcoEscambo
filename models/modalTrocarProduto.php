<!-- Modal de Troca de Produto -->
<div class="modal fade" id="modalTrocarProduto" tabindex="-1" aria-labelledby="modalTrocarProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTrocarProdutoLabel">Solicitar Troca de Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="backend/TrocarProduto.php" method="POST">
                <div class="modal-body">
                    <div class="row text-center">
                        <!-- Produto Desejado -->
                        <div class="col-md-6 border-end">
                            <h6 class="mb-3">Produto Desejado</h6>
                            <div class="p-2">
                                <img src="<?= htmlspecialchars($produto['img']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="img-fluid rounded shadow-sm mb-2">
                                <p><strong><?= htmlspecialchars($produto['nome']) ?></strong></p>
                                <p><?= htmlspecialchars($produto['descricao']) ?></p>
                            </div>
                        </div>

                        <!-- Seu Produto -->
                        <div class="col-md-6">
                            <h6 class="mb-3 d-flex justify-content-center align-items-center">
                                Seu Produto
                            </h6>
                            <div class="p-2" id="boxProdutoSelecionado">
                                <p>Nenhum produto selecionado.</p>
                                <button type="button"
                                        class="btn btn-primary btn-sm w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalSelecionarProduto">
                                    Selecionar
                                </button>

                            </div>
                        </div>
                        <!-- Inputs escondidos -->
                        <input type="hidden" name="produto_desejado_id" id="produtoDesejadoId" value="<?= $produto['id'] ?>">
                        <input type="hidden" name="meuProdutoId" id="meuProdutoId">
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirmar Troca</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

if (isset($_SESSION['usuario_id'])) {
    $idUser = $_SESSION['usuario_id'];
    include_once 'models/modalSelecionarProduto.php';
}



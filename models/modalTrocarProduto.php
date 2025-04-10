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
                                <img src="" id="imgProd" class="img-fluid rounded shadow-sm mb-2 img-prod">
                                <p><strong id="nomeProd"></strong></p>
                                <p id="descricaoProd"></p>
                            </div>
                        </div>

                        <!-- Seu Produto -->
                        <div class="col-md-6 d-flex justify-content-center align-items-center flex-column">
                            <?php if (!isset($_SESSION['usuario_id'])) { ?>
                            <h6 class="mb-3 d-flex justify-content-center align-items-center">
                                Necessário criar uma conta para visualizar seus produtos
                            </h6>
                            <div class="p-2 " id="boxProdutoSelecionado">
                                <a class="login-button" href="#perfilModal" data-bs-toggle="modal" data-bs-target="#perfilModal">Logar/Criar sua conta</a>
                            </div>
                            <?php } else { ?>
                            <h6 class="mb-3 d-flex justify-content-center align-items-center">
                                Seu Produto
                            </h6>
                            <div class="p-2 " id="boxProdutoSelecionado">
                                <div id="produtoSelecionadoInfo">
                                    <p>Nenhum produto selecionado.</p>
                                    <button type="button"
                                            class="btn btn-primary btn-sm w-100"
                                            id="btnAbrirSelecionarProduto">
                                        Selecionar
                                    </button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- Inputs escondidos -->
                        <input type="hidden" name="produtoDesejadoId" id="produtoDesejadoId">
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
<script>
        const modal = document.getElementById('modalTrocarProduto');
        if (modal) {
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');
            const descricao = button.getAttribute('data-descricao');
            const srcimg = button.getAttribute('data-imagem');

            // Preencher campos ocultos
            modal.querySelector('#produtoDesejadoId').value = id;

            // Preencher dados visuais
            modal.querySelector('#nomeProd').textContent = nome;
            modal.querySelector('#descricaoProd').textContent = descricao;
            modal.querySelector('#imgProd').src = srcimg;
            modal.querySelector('#imgProd').title = nome;
        });
    }
        document.getElementById('btnAbrirSelecionarProduto').addEventListener('click', function () {
            const modalSelecionar = new bootstrap.Modal(document.getElementById('modalSelecionarProduto'), {
                backdrop: false // não adiciona nova camada escura por trás
            });
            modalSelecionar.show();
        });
</script>
<?php

if (isset($_SESSION['usuario_id'])) {
    $idUser = $_SESSION['usuario_id'];
    include_once 'models/modalSelecionarProduto.php';
}




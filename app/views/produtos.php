<?php

namespace app\controllers;

$this->layout("master", [
    'title' => "Produtos",
    'description' => "Aqui você encontrará todos os produtos disponível para realizar uma troca, tendo total liberdade de escolha."
]);
session_start();
$idUser = $_SESSION['usuario_id'] ?? NULL;

$produtos = (new ProductsController)->buscarProdutos($idUser);
$totalPaginas = ProductsController::contarProduts($idUser);
include_once("app/static/js/filter.php");
?>
<?php $this->start('body'); ?>

<main class="container my-5">
    <?php include_once "app/models/formFilter.php" ?>

    <section class="produtos-categoria">
        <h3>Todos os Itens</h3>
        <div class="produtos-lista">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="product" style="position: relative; width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 10px; transition: all 0.3s; text-align: center; overflow: visible; z-index: 1; background-color: #fff;">
                        <img src="<?= htmlspecialchars($produto['img']) ?>"  class="img-prod" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <p class="condition font-monospace"><?= htmlspecialchars($produto['fk_categoria']) ?></p>
                        <p class="product-name" style="font-weight: bold; margin-top: 10px;"><?= htmlspecialchars($produto['nome']) ?></p>
                        <p class="condition"><?= htmlspecialchars($produto['descricao']) ?></p>
                            <a href="#modalTrocarProduto"
                               class="btn-trocar"
                               data-bs-toggle="modal"
                               data-bs-target="#modalTrocarProduto"
                               data-id="<?= $produto['id'] ?>"
                               data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                               data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
                               data-imagem="<?= htmlspecialchars($produto['img']) ?>"
                               data-categoria =<?= htmlspecialchars($produto['fk_categoria']) ?>
                            >
                                Trocar
                            </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum produto cadastrado ainda.</p>
            <?php endif; ?>
        </div>
    </section>
    <div class="d-flex justify-content-center my-4">
        <button class="btn btn-primary" id="moreProducts">Mostrar mais</button>
    </div>
</main>
<script>
    let totalPaginas = <?php echo $totalPaginas ?>;
    const limit = <?= (new ProductsController)->getLimit(); ?>;
    let offset = 10;
    $("#moreProducts").off("click").on("click", function () {
        $.ajax({
            url: "/EcoEscambo/buscarProdutos",
            method: "GET",
            data: { offset: offset },
            dataType: 'json',
            success: function (produtos) {
                console.log(produtos);
                if (produtos.length === 0) {
                    alert("Não há mais produtos.");
                    return;
                }
                produtos.forEach(function(produto) {
                    const produtoHtml = `
                    <div class="product" style="position: relative; width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 10px; transition: all 0.3s; text-align: center; overflow: visible; z-index: 1; background-color: #fff;">
                        <img src="${produto.img}" class="img-prod" alt="${produto.nome}">
                        <p class="condition font-monospace">${produto.fk_categoria}</p>
                        <p class="product-name" style="font-weight: bold; margin-top: 10px;">${produto.nome}</p>
                        <p class="condition">${produto.descricao}</p>
                        <a href="#modalTrocarProduto"
                           class="btn-trocar"
                           data-bs-toggle="modal"
                           data-bs-target="#modalTrocarProduto"
                           data-id="${produto.id}"
                           data-nome="${produto.nome}"
                           data-descricao="${produto.descricao}"
                           data-imagem="${produto.img}"
                           data-categoria="${produto.fk_categoria}">
                           Trocar
                        </a>
                    </div>`;
                    $(".produtos-lista").append(produtoHtml);
                });
                offset += limit;
                if (offset >= totalPaginas){
                    $("#moreProducts").prop("disabled", true).text("Todos os produtos carregados");
                }
            },
            error: function () {
                console.log("Erro ao carregar produtos.");
            }
        });
    });

</script>

<?php
require_once("app/models/modalPerfil.php");
require_once("app/models/modalCadastrarProdutos.php");
require_once("app/models/modalTrocarProduto.php");
?>
<?php $this->stop(); ?>



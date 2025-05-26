<?php

namespace app\controllers;

$this->layout("master", [
    'title' => "Produtos",
    'description' => "Aqui você encontrará todos os produtos disponível para realizar uma troca, tendo total liberdade de escolha."
]);
session_start();
$idUser = $_SESSION['usuario_id'] ?? NULL;

$produtos = (new ProductsController)->buscarProdutos($idUser);
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
<!--        <div class="d-flex justify-content-center my-4">-->
<!--            <button class="btn btn-primary">Mostrar mais --><?php //echo $totalPaginas ?><!--</button>-->
<!--        </div>-->
    </section>
</main>

<?php
require_once("app/models/modalPerfil.php");
require_once("app/models/modalCadastrarProdutos.php");
require_once("app/models/modalTrocarProduto.php");
?>
<?php $this->stop(); ?>



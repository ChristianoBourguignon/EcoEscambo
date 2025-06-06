<?php

namespace app\controllers;
use app\controllers\dbController;

$this->layout("master", [
    'title' => "Meu Inventário",
    'description' => "Aqui você encontrará todos os produtos cadastrados por você"
]);

$idUser = $_SESSION['usuario_id'];
if (!isset($idUser)){
    $_SESSION['modal'] = [
        'msg' => "Você precisa está logado!",
        'statuscode' => 401
    ];
    header('Location:'. BASE);
    exit;
}

$produtos = (new userController)->meusProdutos($idUser);

?>
<?php $this->start('body');?>

<div class="container mt-5 py-5">
    <h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
    <?php include_once "app/models/formFilter.php" ?>
    <h2 class="mt-5">Seus Produtos</h2>
    <div class="produtos-lista" style="display: flex; flex-wrap: wrap; gap: 20px; position: relative; overflow: visible;">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="product" style="position: relative; width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 10px; transition: all 0.3s; text-align: center; overflow: visible; z-index: 1; background-color: #fff;">
                    <img src="<?= htmlspecialchars($produto['img']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <p class="condition"><?= htmlspecialchars($produto['fk_categoria']) ?></p>
                    <p class="product-name font-weight-bold" style="font-weight: bold; margin-top: 10px;"><?= htmlspecialchars($produto['nome']) ?></p>
                    <p class="condition"><?= htmlspecialchars($produto['descricao']) ?></p>

                    <div class="dropdown dropdown-btn" style="position: absolute; top: 10px; left: 10px;">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            :
                        </button>
                        <ul class="dropdown-menu" style="position: absolute;">
                            <li>
                                <a class="dropdown-item" href="<?= BASE ?>/trocas">Consultar Solicitações</a>
                            </li>
                            <a href="#alterarProdutoModal"
                               class="dropdown-item btn-editar-produto"
                               data-bs-toggle="modal"
                               data-bs-target="#alterarProdutoModal"
                               data-id="<?= $produto['id'] ?>"
                               data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                               data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
                               data-categoria="<?= $produto['fk_categoria'] ?>">
                                Alterar
                            </a>
                            <li>
                                <form action="<?= BASE ?>/excluirProduto" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')" style="margin: 0;">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <button type="submit" class="dropdown-item text-danger" style="background: none; border: none;">Excluir</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto cadastrado ainda.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once("app/static/js/filter.php"); ?>
<?php $this->stop(); ?>

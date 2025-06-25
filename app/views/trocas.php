<?php
use app\controllers\userController;
use League\Plates;

/** @var Plates\Template\Template $this */

$this->layout("master", [
    'title' => "Minhas Trocas",
    'description' => "Aqui você poderá consultar todas as suas trocas pendentes e as que foram feitas por você!"
]);
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
/** @var int|null $idUser */
$idUser = $_SESSION['usuario_id'] ?? NULL;
if($idUser === NULL){
    $_SESSION['modal'] = [
    'msg' =>'Erro ao obter dados do usuario',
    'statuscode' => 404
    ];
    return;};
$userController = (new userController());
$solicitacao = $userController->getTrocasSolicitadas($idUser);
$pendente = $userController->getTrocasPendentes($idUser);
$trocados = $userController->getHistoricoTrocas($idUser);
$nomeUsuario = userController::getNome($idUser);
?>

<?php $this->start('body'); ?>

<div class="container mt-5 py-5">
    <h1>Olá, <?= htmlspecialchars($nomeUsuario) ?>!</h1>

    <h2 class="mt-5">Ofertas no seu produto</h2>

    <?php if ($solicitacao != NULL):
        foreach ($solicitacao as $sol): ?>
                <form action="<?= BASE ?>/realizarTroca" method="POST" enctype="multipart/form-data">
                    <div class="container my-4">
                        <div class="row justify-content-center align-items-center g-4">
                            <!-- Produto Oferecido -->
                            <div class="col-md-3 text-center">
                                <div class="card">
                                    <div class="card-header bg-transparent">Produto oferecido</div>
                                    <img src="<?= htmlspecialchars($sol['imgProdutoDesejado']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($sol['nomeProdutoDesejado']) ?>">
                                    <div class="card-body">
                                        <p class="condition font-monospace"><?= htmlspecialchars($sol['categoriaProdutoDesejado']) ?></p>
                                        <h5 class="card-title"><?= htmlspecialchars($sol['nomeProdutoDesejado']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($sol['descricaoProdutoDesejado']) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ícone de troca -->
                            <?php include("app/models/trocaicon.php"); ?>

                            <!-- Produto do Usuário -->
                            <div class="col-md-3 text-center">
                                <div class="card">
                                    <div class="card-header bg-transparent">Seu produto</div>
                                    <img src="<?= htmlspecialchars($sol['imgProdutoUser']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($sol['nomeProdutoUser']) ?>">
                                    <div class="card-body">
                                        <p class="condition font-monospace"><?= htmlspecialchars($sol['categoriaProdutoUser']) ?></p>
                                        <h5 class="card-title"><?= htmlspecialchars($sol['nomeProdutoUser']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($sol['descricaoProdutoUser']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões e dados escondidos -->
                        <div class="row mt-3">
                            <div class="col text-center">
                                <input type="hidden" name="prodOferecido" value="<?= $sol['idProdDesejado'] ?>">
                                <input type="hidden" name="meuProd" value="<?= $sol['idProdUser'] ?>">
                                <button type="submit" name="confirmar" value="Confirmar" class="btn btn-success me-2">Confirmar</button>
                                <button type="submit" name="rejeitar" value="Rejeitar" class="btn btn-danger">Rejeitar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma solicitação de troca encontrada.</p>
    <?php endif; ?>

    <h2 class="mt-5">Suas trocas pendentes</h2>

    <?php if ($pendente != NULL): ?>
        <?php foreach ($pendente as $pen): ?>
                <div class="container my-4">
                    <div class="row justify-content-center align-items-center g-4">
                        <!-- Produto Oferecido -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Produto Desejado</div>
                                <img src="<?= htmlspecialchars($pen['imgProdutoDesejado']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($pen['nomeProdutoDesejado']) ?>">
                                <div class="card-body">
                                    <p class="condition font-monospace"><?= htmlspecialchars($pen['categoriaProdutoDesejado']) ?></p>
                                    <h5 class="card-title"><?= htmlspecialchars($pen['nomeProdutoDesejado']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($pen['descricaoProdutoDesejado']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Ícone de troca -->
                        <?php include("app/models/trocaicon.php"); ?>

                        <!-- Produto do Usuário -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Seu produto</div>
                                <img src="<?= htmlspecialchars($pen['imgProdutoUser']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($pen['imgProdutoUser']) ?>">
                                <div class="card-body">
                                    <p class="condition font-monospace"><?= htmlspecialchars($pen['categoriaProdutoUser']) ?></p>
                                    <h5 class="card-title"><?= htmlspecialchars($pen['nomeProdutoUser']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($pen['descricaoProdutoUser']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <a class="btn btn-success me-2">AGUARDANDO RETORNO</a>
                        </div>
                    </div>
                </div>
                <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma solicitação de troca encontrada.</p>
    <?php endif; ?>

    <h2 class="mt-5">Trocas realizadas e Canceladas</h2>
    <?php if ($trocados != NULL): ?>
        <?php foreach ($trocados as $ht): ?>
                <div class="container my-4">
                    <div class="row justify-content-center align-items-center g-4">
                        <!-- Produto Oferecido -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Seu Produto</div>
                                <img src="<?= htmlspecialchars($ht['imgProdutoUser']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($ht['nomeProdutoUser']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($ht['nomeProdutoUser']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($ht['descricaoProdutoUser']) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php include("app/models/trocaicon.php"); ?>

                        <!-- Produto do Usuário -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Produto Oferecido</div>
                                <img src="<?= htmlspecialchars($ht['imgProdutoDesejado']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($ht['nomeProdutoDesejado']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($ht['nomeProdutoDesejado']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($ht['descricaoProdutoDesejado']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões e dados escondidos -->
                    <div class="row mt-3">
                        <div class="col text-center">
                            <?php if(($ht['status'] == 1)): ?>
                            <button type="submit" value="aceito" class="btn btn-success me-2">Troca Confirmada</button>
                            <?php else: ?>
                            <button type="submit" value="rejeitado" class="btn btn-danger">Troca Rejeitada/Cancelada</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <hr>
<?php
        endforeach;
    else:
?>
    <p>Nenhum registro de troca encontrada.</p>
    <?php  endif;  ?>
</div>

<?php $this->stop(); ?>

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
$idUser = $_SESSION['usuario_id'] ?? NULL;
/** @var array<int,String,mixed> $resultados */
$solicitacao = (new userController)->getTrocasSolicitadas($idUser);
$pendente = (new userController)->getTrocasPendentes($idUser);
$trocados = (new userController)->getHistoricoTrocas($idUser);
$nomeUsuario = userController::getNome($idUser);
/** @var array{id: int, img: string, nome: string, descricao: string, fk_categoria: string} $solicitacao */
/** @var array{id: int, img: string, nome: string, descricao: string, fk_categoria: string} $pendente */
/** @var array{id: int, img: string, nome: string, descricao: string, fk_categoria: string} $trocados */
?>

<?php $this->start('body'); ?>

<div class="container mt-5 py-5">
    <h1>Olá, <?= htmlspecialchars($nomeUsuario) ?>!</h1>

    <h2 class="mt-5">Ofertas no seu produto</h2>

    <?php if ($solicitacao != NULL): ?>
        <?php for ($i = 0; $i < count($solicitacao); $i ++): ?>
            <?php if (isset($solicitacao[$i])): // Verifica se existe um par de trocas ?>
                <form action="<?= BASE ?>/realizarTroca" method="POST" enctype="multipart/form-data">
                    <div class="container my-4">
                        <div class="row justify-content-center align-items-center g-4">
                            <!-- Produto Oferecido -->
                            <div class="col-md-3 text-center">
                                <div class="card">
                                    <div class="card-header bg-transparent">Produto oferecido</div>
                                    <img src="<?= htmlspecialchars($solicitacao[$i]['imgProdutoDesejado']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['nomeProdutoDesejado']) ?>">
                                    <div class="card-body">
                                        <p class="condition font-monospace"><?= htmlspecialchars($pendente[$i]['categoriaProdutoDesejado']) ?></p>
                                        <h5 class="card-title"><?= htmlspecialchars($solicitacao[$i]['nomeProdutoDesejado']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($solicitacao[$i]['descricaoProdutoDesejado']) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ícone de troca -->
                            <?php include("app/models/trocaicon.php"); ?>

                            <!-- Produto do Usuário -->
                            <div class="col-md-3 text-center">
                                <div class="card">
                                    <div class="card-header bg-transparent">Seu produto</div>
                                    <img src="<?= htmlspecialchars($solicitacao[$i]['imgProdutoUser']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i + 1]['nome']) ?>">
                                    <div class="card-body">
                                        <p class="condition font-monospace"><?= htmlspecialchars($pendente[$i]['categoriaProdutoUser']) ?></p>
                                        <h5 class="card-title"><?= htmlspecialchars($solicitacao[$i]['nomeProdutoUser']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($solicitacao[$i]['descricaoProdutoUser']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões e dados escondidos -->
                        <div class="row mt-3">
                            <div class="col text-center">
                                <input type="hidden" name="prodOferecido" value="<?= $solicitacao[$i]['idProdDesejado'] ?>">
                                <input type="hidden" name="meuProd" value="<?= $solicitacao[$i]['idProdUser'] ?>">
                                <button type="submit" name="confirmar" value="Confirmar" class="btn btn-success me-2">Confirmar</button>
                                <button type="submit" name="rejeitar" value="Rejeitar" class="btn btn-danger">Rejeitar</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
            <?php endif; ?>
        <?php endfor; ?>
    <?php else: ?>
        <p>Nenhuma solicitação de troca encontrada.</p>
    <?php endif; ?>

    <h2 class="mt-5">Suas trocas pendentes</h2>

    <?php if ($pendente != NULL): ?>
        <?php for ($i = 0; $i < count($pendente); $i++): ?>
            <?php if (isset($pendente[$i])): // Verifica se existe um par de trocas ?>
                <div class="container my-4">
                    <div class="row justify-content-center align-items-center g-4">
                        <!-- Produto Oferecido -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Produto Desejado</div>
                                <img src="<?= htmlspecialchars($pendente[$i]['imgProdutoDesejado']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['nomeProdutoDesejado']) ?>">
                                <div class="card-body">
                                    <p class="condition font-monospace"><?= htmlspecialchars($pendente[$i]['categoriaProdutoDesejado']) ?></p>
                                    <h5 class="card-title"><?= htmlspecialchars($pendente[$i]['nomeProdutoDesejado']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($pendente[$i]['descricaoProdutoDesejado']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Ícone de troca -->
                        <?php include("app/models/trocaicon.php"); ?>

                        <!-- Produto do Usuário -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Seu produto</div>
                                <img src="<?= htmlspecialchars($pendente[$i]['imgProdutoUser']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['imgProdutoUser']) ?>">
                                <div class="card-body">
                                    <p class="condition font-monospace"><?= htmlspecialchars($pendente[$i]['categoriaProdutoUser']) ?></p>
                                    <h5 class="card-title"><?= htmlspecialchars($pendente[$i]['nomeProdutoUser']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($pendente[$i]['descricaoProdutoUser']) ?></p>
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
            <?php endif; ?>
        <?php endfor; ?>
    <?php else: ?>
        <p>Nenhuma solicitação de troca encontrada.</p>
    <?php endif; ?>

    <h2 class="mt-5">Trocas realizadas e Canceladas</h2>
    <?php if ($trocados != NULL): ?>
        <?php for ($i = 0; $i < count($trocados); $i += 2): ?>
            <?php if (isset($trocados[$i + 1])): ?>
                <div class="container my-4">
                    <div class="row justify-content-center align-items-center g-4">
                        <!-- Produto Oferecido -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Seu Produto</div>
                                <img src="<?= htmlspecialchars($trocados[$i]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['nome']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($trocados[$i]['nome']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($trocados[$i]['descricao']) ?></p>
                                </div>
                            </div>
                        </div>

                        <?php include("app/models/trocaicon.php"); ?>

                        <!-- Produto do Usuário -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Produto Oferecido</div>
                                <img src="<?= htmlspecialchars($trocados[$i + 1]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i + 1]['nome']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($trocados[$i + 1]['nome']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($trocados[$i + 1]['descricao']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões e dados escondidos -->
                    <div class="row mt-3">
                        <div class="col text-center">
                            <?php if(($trocados[$i]['Status'] == 1)): ?>
                            <button type="submit" value="aceito" class="btn btn-success me-2">Troca Confirmada</button>
                            <?php else: ?>
                            <button type="submit" value="rejeitado" class="btn btn-danger">Troca Rejeitada/Cancelada</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <hr>
<?php
            endif;
        endfor;
    else:
?>
    <p>Nenhum registro de troca encontrada.</p>
    <?php  endif;  ?>
</div>

<?php $this->stop(); ?>

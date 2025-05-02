<?php
session_start();
require_once 'backend/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$pdo = Db::getConnection();
$idUser = $_SESSION['usuario_id'];
$nomeUsuario = $_SESSION['usuario_nome'];

try {
    // Consulta todos os produtos em propostas de troca feitas ao usuário logado
    $stmt = $pdo->prepare("
       SELECT * 
        FROM troca t 
        JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
        WHERE t.idUserDesejado = :idUser 
        AND t.status = 0;
    ");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $solicitacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
       SELECT * 
        FROM troca t 
        JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
        WHERE t.idUser = :idUser 
        AND t.status = 0;
    ");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $pendente = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
       SELECT * 
        FROM troca t 
        JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
        WHERE t.idUser = :idUser 
        AND (t.status = 1 or t.status = -1);
    ");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $trocados = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}

require_once("models/header.php");
?>

<div class="container mt-5 py-5">
    <h1>Olá, <?= htmlspecialchars($nomeUsuario) ?>!</h1>

    <h2 class="mt-5">Ofertas no seu produto</h2>

    <?php if (count($solicitacao) > 0): ?>
        <?php for ($i = 0; $i < count($solicitacao); $i += 2): ?>
            <?php if (isset($solicitacao[$i + 1])): // Verifica se existe um par de trocas ?>
                <form action="backend/operacaoTroca.php" method="POST" enctype="multipart/form-data">
                    <div class="container my-4">
                        <div class="row justify-content-center align-items-center g-4">
                            <!-- Produto Oferecido -->
                            <div class="col-md-3 text-center">
                                <div class="card">
                                    <div class="card-header bg-transparent">Seu Produto</div>
                                    <img src="<?= htmlspecialchars($solicitacao[$i]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['nome']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($solicitacao[$i]['nome']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($solicitacao[$i]['descricao']) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ícone de troca -->
                            <?php include("models/trocaicon.php"); ?>

                            <!-- Produto do Usuário -->
                            <div class="col-md-3 text-center">
                                <div class="card">
                                    <div class="card-header bg-transparent">Produto Oferecido</div>
                                    <img src="<?= htmlspecialchars($solicitacao[$i + 1]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i + 1]['nome']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($solicitacao[$i + 1]['nome']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($solicitacao[$i + 1]['descricao']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões e dados escondidos -->
                        <div class="row mt-3">
                            <div class="col text-center">
                                <input type="hidden" name="prodOferecido" value="<?= $solicitacao[$i]['id'] ?>">
                                <input type="hidden" name="meuProd" value="<?= $solicitacao[$i+1]['id'] ?>">
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

    <?php if (count($pendente) > 0): ?>
        <?php for ($i = 0; $i < count($pendente); $i += 2): ?>
            <?php if (isset($pendente[$i + 1])): // Verifica se existe um par de trocas ?>
                <div class="container my-4">
                    <div class="row justify-content-center align-items-center g-4">
                        <!-- Produto Oferecido -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Produto Desejado</div>
                                <img src="<?= htmlspecialchars($pendente[$i]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['nome']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($pendente[$i]['nome']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($pendente[$i]['descricao']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Ícone de troca -->
                        <?php include("models/trocaicon.php"); ?>

                        <!-- Produto do Usuário -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Seu produto</div>
                                <img src="<?= htmlspecialchars($pendente[$i + 1]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i + 1]['nome']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($pendente[$i + 1]['nome']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($pendente[$i + 1]['descricao']) ?></p>
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

    <?php if (count($trocados) > 0): ?>
        <h2 class="mt-5">Trocas realizadas e Canceladas</h2>
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

                        <?php include("models/trocaicon.php"); ?>

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
    endif;
?>
</div>

<?php
require_once("models/footer.php");
?>

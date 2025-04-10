<?php
session_start();
require_once 'backend/db.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}
$pdo = Db::getConnection();
$idUser = $_SESSION['usuario_id'];
$nomeUsuario = $_SESSION['usuario_nome'];

try {

    //EXEMPLO DE CONSULTA DE TODOS OS PRODUTOS DA TROCA DO USUARIO
        //SELECT *
        //FROM troca t
        //JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser)
        //WHERE t.idUserDesejado = 3;

    $stmt = $pdo->prepare("SELECT * FROM troca t JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) WHERE t.idUserDesejado = :idUser;");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $solicitacao = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}

require_once("models/header.php");
?>

<div class="container mt-5 py-5">
    <h1>Olá, <?= htmlspecialchars($nomeUsuario) ?>!</h1>

    <h2 class="mt-5">Suas Trocas</h2>
        <?php if (count($solicitacao) > 0): ?>
            <?php for ($i = 0; $i<count($solicitacao);$i+=2) {?>
    <form action="backend/operacaoTroca.php" method="POST" enctype="multipart/form-data">
                <div class="container my-4">
                    <div class="row justify-content-center align-items-center g-4">

                        <!-- Produto 1 -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Produto Oferecido</div>
                                <img src="<?= htmlspecialchars($solicitacao[$i]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i]['nome']) ?>" ">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($solicitacao[$i]['nome']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($solicitacao[$i]['descricao']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- SVG no meio -->
                        <div class="col-md-1 d-flex justify-content-center align-items-center">
                            <svg viewBox="0 0 24 24" width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 7.5L8 7.5M21 7.5L16.6667 3M21 7.5L16.6667 12" stroke="#60db2f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M4 16.5L17 16.5M4 16.5L8.33333 21M4 16.5L8.33333 12" stroke="#96079b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>

                        <!-- Produto 2 -->
                        <div class="col-md-3 text-center">
                            <div class="card">
                                <div class="card-header bg-transparent">Seu Produto</div>
                                <img src="<?= htmlspecialchars($solicitacao[$i+1]['img']) ?>" class="card-img img-prod" alt="<?= htmlspecialchars($solicitacao[$i+1]['nome']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($solicitacao[$i+1]['nome']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($solicitacao[$i+1]['descricao']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Botões abaixo -->
                    <div class="row mt-3">
                        <div class="col text-center">
                                <input type="hidden" id="prodOferecido" value="<?= $solicitacao[$i]['id'] ?>">
                                <input type="hidden" id="meuProd" value="<?= $solicitacao[$i+1]['id'] ?>">
                                <button type="submit" name="confirmar" value="Confirmar" class="btn btn-success me-2">Confirmar</button>
                                <button type="submit" name="rejeitar" value="Rejeitar" class="btn btn-danger">Rejeitar</button>

                        </div>
                    </div>
                </div>
    </form>
                <hr>

            <?php }; ?>
        <?php else: ?>
            <p>Nenhum produto ofertado ainda.</p>
        <?php endif; ?>
    </div>
<!--</div>-->

<?php
require_once("models/footer.php");
?>

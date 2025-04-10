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
    $stmt = $pdo->prepare("SELECT * FROM troca WHERE idUser = :idUser and Status = 0");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $trocas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE idUser = :idUser");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM troca t JOIN produtos p ON t.idUserDesejado = p.idUser WHERE t.idProdUser IN ();");
    
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}

require_once("models/header.php");
?>

<div class="container mt-5 py-5">
    <h1>Olá, <?= htmlspecialchars($nomeUsuario) ?>!</h1>

    <h2 class="mt-5">Suas Trocas</h2>
    <div class="produtos-lista" style="display: flex; flex-wrap: wrap; gap: 20px; position: relative; overflow: visible;">
        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="product" style="position: relative; width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 10px; transition: all 0.3s; text-align: center; overflow: visible; z-index: 1; background-color: #fff;">
                    <img src="<?= htmlspecialchars($produto['img']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px;">
                    <p class="product-name" style="font-weight: bold; margin-top: 10px;"><?= htmlspecialchars($produto['nome']) ?></p>
                    <p class="condition"><?= htmlspecialchars($produto['descricao']) ?></p>

                    <div class="dropdown dropdown-btn" style="position: absolute; top: 10px; left: 10px;">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            :
                        </button>
                        <ul class="dropdown-menu" style="position: absolute;">
                            <li>
                                <a class="dropdown-item" href="consultarSolicitacoes.php?id=<?= $produto['id'] ?>">Confirmar</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="consultarSolicitacoes.php?id=<?= $produto['id'] ?>">Rejeitar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto ofertado ainda.</p>
        <?php endif; ?>
    </div>
</div>

<?php
require_once("models/footer.php");
?>

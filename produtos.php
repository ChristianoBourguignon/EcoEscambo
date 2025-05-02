<?php
require_once 'backend/db.php';
include_once("models/header.php");
$pdo = Db::getConnection();
$idUser = $_SESSION['usuario_id'] ?? NULL;


try {
    if(!$idUser){
        $stmt = $pdo->prepare("SELECT * FROM produtos ");
    } else {
        $stmt = $pdo->prepare("
        SELECT *
        FROM produtos p
        WHERE p.idUser != $idUser
          AND EXISTS (
            SELECT 1 
            FROM troca t 
            WHERE (t.idProdDesejado = p.id OR t.idProdUser = p.id)
              AND t.Status IN (0, -1)
          )
          AND NOT EXISTS (
            SELECT 1 
            FROM troca t2 
            WHERE (t2.idProdDesejado = p.id OR t2.idProdUser = p.id)
              AND t2.Status = 1
        );
        ");
    }
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}

?>

<main class="container my-5">
    <!-- Trocas Populares -->
    <section class="produtos-categoria">
        <h3>Todos os Itens</h3>
        <div class="produtos-lista">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="product" style="position: relative; width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 10px; transition: all 0.3s; text-align: center; overflow: visible; z-index: 1; background-color: #fff;">
                        <img src="<?= htmlspecialchars($produto['img']) ?>"  class="img-prod" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <p class="product-name" style="font-weight: bold; margin-top: 10px;"><?= htmlspecialchars($produto['nome']) ?></p>
                        <p class="condition"><?= htmlspecialchars($produto['descricao']) ?>
                            <a href="#modalTrocarProduto"
                               class="btn-trocar"
                               data-bs-toggle="modal"
                               data-bs-target="#modalTrocarProduto"
                               data-id="<?= $produto['id'] ?>"
                               data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                               data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
                               data-imagem="<?= htmlspecialchars($produto['img']) ?>">
                                Trocar
                            </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum produto cadastrado ainda.</p>
            <?php endif; ?>
        </div>
        </div>
        </div>
    </section>
</main>

<?php
include_once("perfil.php");
include_once("models/produtoCadastrado.php");
include_once("models/cadastrarProdutos.php");
include_once("models/modalTrocarProduto.php");
include_once("models/footer.php");
?>

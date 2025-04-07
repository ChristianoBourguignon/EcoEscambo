<?php
require_once 'backend/db.php';
$pdo = Db::getConnection();

try {
    $stmt = $pdo->prepare("SELECT * FROM produtos ");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}
include("models/header.php");
?>

<main class="container my-5">
    <!-- Trocas Populares -->
    <section class="produtos-categoria">
        <h3>Todos os Itens</h3>
        <div class="produtos-lista">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="product" style="position: relative; width: 200px; padding: 10px; border: 1px solid #ddd; border-radius: 10px; transition: all 0.3s; text-align: center; overflow: visible; z-index: 1; background-color: #fff;">
                        <img src="<?= htmlspecialchars($produto['img']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px;">
                        <p class="product-name" style="font-weight: bold; margin-top: 10px;"><?= htmlspecialchars($produto['nome']) ?></p>
                        <p class="condition"><?= htmlspecialchars($produto['descricao']) ?>
                        <a href="#modalTrocarProduto"
                           class="btn-trocar"
                           data-bs-toggle="modal"
                           data-bs-target="#modalTrocarProduto"
                           data-id="<?= $produto['id'] ?>"
                           data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                           data-descricao="<?= htmlspecialchars($produto['descricao']) ?>">
                            Alterar
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
include("models/modalTrocarProduto.php");
include("models/footer.php");
?>

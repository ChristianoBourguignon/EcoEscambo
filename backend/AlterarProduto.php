<?php
require_once ("db.php");
$pdo = Db::getConnection();
$idProd = isset($_POST['id']) ? $_POST['id'] : null;
try {
//    SELECT * FROM `produtos` where id = 6;
    //$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :idProd");
    $stmt = $pdo->prepare("SELECT * FROM `produtos` where id = :idProd");
    $stmt->bindParam(':idProd', $idProd);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    ?>
<!-- Modal de Cadastro de Produto -->
<div class="modal fade" id="alterarProdutoModal" tabindex="-1" aria-labelledby="alterarProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="alterarProdutoModalLabel">Alterar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="alterarProduto.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" value="<?= htmlspecialchars($produto['descricao']) ?>" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem do Produto</label>
                        <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Alterar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}



<?php
require_once 'backend/db.php';

if (isset($_SESSION['usuario_id'])) {
    $pdo = Db::getConnection();
    $idUser = $_SESSION['usuario_id'];
}
try {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE idUser = :idUser");
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}
?>

<!-- Modal para Selecionar Produto -->
<div class="modal fade" id="modalSelecionarProduto" tabindex="-1" aria-labelledby="modalSelecionarProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Selecione um dos seus produtos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="listaProdutosUsuario">
                    <?php if (count($produtos) > 0): ?>
                        <?php foreach ($produtos as $produto): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 selecionar-produto cursor-pointer"
                                     data-id="<?= $produto['id'] ?>"
                                     data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                                     data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
                                     data-img="<?= $produto['img'] ?>">
                                    <img src="<?= $produto['img'] ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h6>
                                        <p class="card-text"><?= htmlspecialchars($produto['descricao']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">Nenhum produto encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnConfirmarProduto" class="btn btn-primary" disabled>Confirmar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .selecionado { border: 2px solid #007bff; box-shadow: 0 0 5px rgba(0, 123, 255, 0.7); }
</style>

<script>
    let produtoSelecionado = null;

    document.querySelectorAll('.selecionar-produto').forEach(card => {
        card.addEventListener('click', function () {
            // Remove classe 'selecionado' dos outros
            document.querySelectorAll('.selecionar-produto').forEach(c => c.classList.remove('selecionado'));

            // Adiciona ao clicado
            this.classList.add('selecionado');
            produtoSelecionado = {
                id: this.getAttribute('data-id'),
                nome: this.getAttribute('data-nome'),
                descricao: this.getAttribute('data-descricao'),
                img: this.getAttribute('data-img')
            };

            // Ativa botão
            document.getElementById('btnConfirmarProduto').disabled = false;
        });
    });

    document.getElementById('btnConfirmarProduto').addEventListener('click', function () {
        if (produtoSelecionado) {
            // Preencher dados no modal anterior
            document.getElementById('boxProdutoSelecionado').innerHTML = `
                <img src="${produtoSelecionado.img}" alt="${produtoSelecionado.nome}" class="img-fluid rounded shadow-sm mb-2">
                <p><strong>${produtoSelecionado.nome}</strong></p>
                <p>${produtoSelecionado.descricao}</p>
            `;
            document.getElementById('meuProdutoId').value = produtoSelecionado.id;

            // Fechar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalSelecionarProduto'));
            modal.hide();
        }
    });
</script>

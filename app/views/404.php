<?php
$this->layout("master", [
    'title' => "Meu Inventário",
    'description' => "Aqui você encontrará todos os produtos cadastrados por você"
]);

$idUser = $_SESSION['usuario_id'] ?? NULL;
?>
<?php $this->start('body');?>

<div class="text-center">
    <h2 class="display-1 text-danger fw-bold">404</h2>
    <div class="border-top border-3 border-danger w-25 mx-auto my-3"></div>
    <p class="lead text-secondary mb-4">Página não encontrada, ou você não tem acesso.</p>
    <a href="<?= BASE ?>" class="btn btn-outline-primary">Voltar para página Inicial</a>
</div>

<?php if ($idUser != NULL){ ?>
    <script>
    window.addEventListener('load', function () {
        const modalPerfil = document.querySelector('#perfilModal');
        if (modalPerfil) {
            const modal = new bootstrap.Modal(modalPerfil);
            modal.show();
        }
    });
    </script>
<?php } ?>

<?php $this->stop() ?>

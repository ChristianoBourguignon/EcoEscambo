
<?php
include_once("perfil.php");

if ($nomeUsuario) {
    include_once("models/cadastrarProdutos.php");
    include_once("models/produtoCadastrado.php");

    if (isset($_SESSION['sucesso'])){ ?>
    <script>
        const sucessoModal = new bootstrap.Modal(document.getElementById('modalSucesso'));
        sucessoModal.show();
        <?php
        unset($_SESSION['sucesso']);
        ?>
    </script>
    <?php } else {;?>
    <script>
        const modalErro = new bootstrap.Modal(document.getElementById('modalErro'));
        modalErro.show();
    </script>
<?php
        include_once("models/modalAlterarProduto.php");
    }
}
?>
</body>
</html>
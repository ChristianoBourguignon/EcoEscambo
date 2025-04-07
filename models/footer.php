
<?php
include ("perfil.php");

if ($nomeUsuario) {
    include_once("models/cadastrarProdutos.php");
    include_once("models/produtoCadastrado.php");
    if (isset($sucesso) && $sucesso){ ?>
    <script>
        const sucessoModal = new bootstrap.Modal(document.getElementById('modalSucesso'));
        sucessoModal.show();
    </script>
    <?php } else {;?>
    <script>
        const modalErro = new bootstrap.Modal(document.getElementById('modalErro'));
        modalErro.show();
    </script>
<?php
    }
}
?>
</body>
</html>
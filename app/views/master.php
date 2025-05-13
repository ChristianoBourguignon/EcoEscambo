<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nomeUsuario = $_SESSION['usuario_nome'] ?? null;
$idUser = $_SESSION['usuario_id'];
var_dump($nomeUsuario);
var_dump($_SESSION['usuario_id']);
var_dump($idUser);

?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?=$this->e($title)?>">
        <link rel="icon" type="image/x-icon" href="img/logo.png">
        <title>EcoEscambo<?=' - ' . $this->e($title)?></title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Montserrat -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">

        <!-- CSS global -->
        <link rel="stylesheet" href="css/globals.css">

        <?php
        if ($_SERVER['REQUEST_URI'] == (BASE . "/sobre")){
            ?>
            <!-- CSS sobre -->
            <link rel="stylesheet" href="css/sobre.css">
        <?php }  ?>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>

    <!-- Navbar -->
<?php
include_once("models/navbarRouter.php");

?>

<?= $this->section('body') ?>

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

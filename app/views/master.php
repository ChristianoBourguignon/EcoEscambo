<?php

use app\controllers\MessagesController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nomeUsuario = $_SESSION['usuario_nome'] ?? null;
$idUser = $_SESSION['usuario_id'] ?? null;

if (isset($_SESSION['modal'])) {
    $modal = (new MessagesController());
    $modal->mensagemCadastroProduto($_SESSION['modal']['msg'], $_SESSION['modal']['statuscode']);
    unset($_SESSION['modal']);
}
?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?=$this->e($description)?>">
        <link rel="icon" type="image/x-icon" href="<?= BASE ?>/icon">
        <title>EcoEscambo<?=' - ' . $this->e($title)?></title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Montserrat -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">

        <!-- CSS global -->
        <link rel="stylesheet" href="app/static/css/globals.css">

        <?php
        if ($_SERVER['REQUEST_URI'] == (BASE . "/sobre")){
            ?>
            <!-- CSS sobre -->
            <link rel="stylesheet" href="app/static/css/sobre.css">
        <?php }  ?>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body <?php if($_SERVER['REQUEST_URI'] == BASE.'/404'){ ?> class="bg-light d-flex align-items-center justify-content-center vh-100" <?php }?>>
    <?php require_once("app/models/scriptMostrarModal.php"); ?>

    <!-- Navbar -->
    <?php
    include_once("app/models/navbarRouter.php");

    ?>

    <?= $this->section('body') ?>

    <?php
        include_once("app/models/modalPerfil.php");
        if ($nomeUsuario) {
        include_once("app/models/modalCadastrarProdutos.php");
        include_once("app/models/modalAlterarProduto.php");
        }
    ?>
    </body>
</html>



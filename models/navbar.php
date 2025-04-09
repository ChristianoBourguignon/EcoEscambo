<?php
//if (session_status() === PHP_SESSION_NONE) {
//    session_start();
//}
//
//$nomeUsuario = $_SESSION['usuario_nome'] ?? null;
?>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand me-auto" href="index.php">
            <span class="Eco">Eco</span><span class="Escambo">Escambo</span>
        </a>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">
                    <span class="Eco">Eco</span><span class="Escambo">Escambo</span>
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="produtos.php">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="sobre.php">Sobre nós</a>
                    </li>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link mx-lg-2" href="dashboard.php">Inventário</a>-->
<!--                    </li>-->
                </ul>
            </div>
        </div>

        <?php if ($nomeUsuario): ?>
            <!-- Se estiver logado, mostra o nome e botão de logout -->
            <div class="dropdown">
            <a class="btn btn-outline-light dropdown-toggle login-button" href="#" role="button" id="dropdownPerfil" data-bs-toggle="dropdown" aria-expanded="false">
                Olá, <?= htmlspecialchars($nomeUsuario) ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownPerfil">
                <li>
                    <a class="dropdown-item" href="dashboard.php">
                        Meu Inventário
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#cadastroProdutosModal" data-bs-toggle="modal" data-bs-target="#cadastroProdutosModal">
                        Cadastrar Produto
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="backend/logout.php">Sair</a>
                </li>
            </ul>
        </div>
        <?php else: ?>
            <!-- Se não estiver logado, mostra botão de login -->
            <a class="login-button" href="#perfilModal" data-bs-toggle="modal" data-bs-target="#perfilModal">Perfil</a>
        <?php endif; ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Script para fechar o menu mobile ao clicar em links -->
<script>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasNavbar'));
            if (offcanvas) {
                offcanvas.hide();
            }
        });
    });
</script>

<!-- Fonte -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">

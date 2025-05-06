<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand me-auto" href="<?= BASE ?>">
            <span class="Eco">Eco</span><span class="Escambo">Escambo</span>
        </a>

        <!-- Botão hamburguer (só aparece no mobile) -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Itens centralizados no desktop -->
        <div class="collapse navbar-collapse justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="<?= BASE ?>">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE ?>/produtos">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE ?>/sobre">Sobre nós</a></li>
            </ul>
        </div>

        <!-- Perfil no canto direito (desktop) -->
        <?php if ($nomeUsuario): ?>
            <div class="dropdown d-none d-lg-block">
                <a class="btn btn-outline-light dropdown-toggle login-button" href="#" id="perfilDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Olá, <?= htmlspecialchars($nomeUsuario) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="perfilDropdown">
                    <li><a class="dropdown-item" href="dashboard.php">Meu Inventário</a></li>
                    <li><a class="dropdown-item" href="trocas.php">Minhas Trocas</a></li>
                    <li><a class="dropdown-item" href="#cadastroProdutosModal" data-bs-toggle="modal" data-bs-target="#cadastroProdutosModal">Cadastrar Produto</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="backend/logout.php">Sair</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a class="btn btn-outline-light d-none d-lg-block login-button" href="#perfilModal" data-bs-toggle="modal" data-bs-target="#perfilModal">Entrar / Cadastrar</a>
        <?php endif; ?>

        <!-- Offcanvas (menu mobile) -->
        <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="offcanvasNavbar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">
                    <span class="Eco">Eco</span><span class="Escambo">Escambo</span>
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php if ($nomeUsuario): ?>
                    <div class="fw-bold text-center mb-3">
                        Olá, <?= htmlspecialchars($nomeUsuario) ?>
                    </div>
                    <div class="list-group">
                        <div class="list-group-item disabled">Páginas</div>
                        <a class="list-group-item list-group-item-action" href="index.php">Início</a>
                        <a class="list-group-item list-group-item-action" href="produtos.php">Produtos</a>
                        <a class="list-group-item list-group-item-action" href="sobre.php">Sobre nós</a>

                        <div class="list-group-item disabled mt-3">Minha Conta</div>
                        <a class="list-group-item list-group-item-action" href="dashboard.php">Meu Inventário</a>
                        <a class="list-group-item list-group-item-action" href="trocas.php">Minhas Trocas</a>
                        <a class="list-group-item list-group-item-action" href="#cadastroProdutosModal" data-bs-toggle="modal" data-bs-target="#cadastroProdutosModal">Cadastrar Produto</a>

                        <a class="list-group-item list-group-item-action text-danger mt-3" href="backend/logout.php">Sair</a>
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <div class="list-group-item disabled ">Páginas</div>
                        <a class="list-group-item list-group-item-action" href="index.php">Início</a>
                        <a class="list-group-item list-group-item-action" href="produtos.php">Produtos</a>
                        <a class="list-group-item list-group-item-action" href="sobre.php">Sobre nós</a>
                        <a class="btn btn-outline-dark w-100 mt-3" href="#perfilModal" data-bs-toggle="modal" data-bs-target="#perfilModal">Entrar / Cadastrar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
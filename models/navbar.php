<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand me-auto" href="../index.php">
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
                        <a class="nav-link mx-lg-2" href="../index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="../pages/produtos">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="#">Sobre nós</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="#">inventário</a>
                    </li>
                </ul>
            </div>
        </div>
        <a href="perfil.html" class="login-button">Perfil</a>
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
            offcanvas.hide();
        });
    });

</script>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
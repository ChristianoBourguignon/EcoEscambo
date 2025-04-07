<?php
include("models/header.php");
include("models/navbar.php");
?>

    <!-- Navbar -->
    <div id="navbar-container"></div>

    <main class="container my-5">
        <!-- Trocas Populares -->
        <section class="produtos-categoria">
            <h3>Trocas Populares</h3>
            <div class="produtos-lista">
                <div class="product">
                    <img src="img/0005_01.webp" alt="">
                    <p class="product-name">Óculos de Sol</p>
                    <p class="condition">Estado: Bom</p>
                    <a href="produtodesc.html?produto=Óculos de Sol" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/398536-800-800.webp" alt="">
                    <p class="product-name">Geladeira Consul</p>
                    <p class="condition">Estado: Médio</p>
                    <a href="produtodesc.html?produto=Geladeira Consul" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/39d5a9649a.webp" alt="">
                    <p class="product-name">Camiseta Ecológica</p>
                    <p class="condition">Estado: Ruim</p>
                    <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
                </div>
            </div>
            <a href="trocas_populares.php" class="ver-mais">Ver mais →</a>
        </section>

        <!-- Adicionados Recentemente -->
        <section class="produtos-categoria">
            <h3>Adicionados Recentemente</h3>
            <div class="produtos-lista">
                <div class="product">
                    <img src="img/a640f7e87b601033d58fc246e302167f.jpg" alt="">
                    <p class="product-name">Kit de Livros</p>
                    <p class="condition">Estado: Excelente</p>
                    <a href="produtodesc.html?produto=Kit de Livros" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/CGY-0283-028_zoom1.webp" alt="">
                    <p class="product-name">Bicicleta Esportiva</p>
                    <p class="condition">Estado: Médio</p>
                    <a href="produtodesc.html?produto=Bicicleta Esportiva" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/tenis_lacoste_39sma0057br_042_02-6399ef658b4f6.jpg" alt="">
                    <p class="product-name">Tênis Lacoste</p>
                    <p class="condition">Estado: Médio</p>
                    <a href="produtodesc.html?produto=Tênis Lacoste" class="btn-trocar">Trocar</a>
                </div>
            </div>
            <a href="adicionados_recentemente.php" class="ver-mais">Ver mais →</a>
        </section>

        <!-- Todos os Itens -->
        <section class="produtos-categoria">
            <h3>Todos os Itens</h3>
            <div class="produtos-lista">
                <div class="product">
                    <img src="img/0005_01.webp" alt="">
                    <p class="product-name">Óculos de Sol</p>
                    <p class="condition">Estado: Bom</p>
                    <a href="produtodesc.html?produto=Óculos de Sol" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/398536-800-800.webp" alt="">
                    <p class="product-name">Geladeira Consul</p>
                    <p class="condition">Estado: Médio</p>
                    <a href="produtodesc.html?produto=Geladeira Consul" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/39d5a9649a.webp" alt="">
                    <p class="product-name">Camiseta Ecológica</p>
                    <p class="condition">Estado: Ruim</p>
                    <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/a640f7e87b601033d58fc246e302167f.jpg" alt="">
                    <p class="product-name">Kit de Livros</p>
                    <p class="condition">Estado: Excelente</p>
                    <a href="produtodesc.html?produto=Kit de Livros" class="btn-trocar">Trocar</a>
                </div>
                <div class="product">
                    <img src="img/CGY-0283-028_zoom1.webp" alt="">
                    <p class="product-name">Bicicleta Esportiva</p>
                    <p class="condition">Estado: Médio</p>
                    <a href="produtodesc.html?produto=Bicicleta Esportiva" class="btn-trocar">Trocar</a>
                </div>
            </div>
            <a href="todos_os_itens.php" class="ver-mais">Ver mais →</a>
        </section>
    </main>

<?php
include("models/footer.php");
?>

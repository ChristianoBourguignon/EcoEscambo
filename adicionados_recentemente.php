<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todos os Itens</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/globals.css">
</head>
<body>
  <div id="navbar-container"></div>
  <script>
    fetch("navbar.html")
      .then(res => res.text())
      .then(data => document.getElementById("navbar-container").innerHTML = data)
      .catch(err => console.error("Erro ao carregar a navbar:", err));
  </script>

<main class="container my-5">
    <!-- Trocas Populares -->
    <section class="produtos-categoria">
        <h3>Adicionados Recentemente</h3>
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
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            <div class="product">
                <img src="img/39d5a9649a.webp" alt="">
                <p class="product-name">Camiseta Ecológica</p>
                <p class="condition">Estado: Ruim</p>
                <a href="produtodesc.html?produto=Camiseta Ecológica" class="btn-trocar">Trocar</a>
            </div>
            
        </div>
    </div>
    </section>
  </main>
</body>
</html>

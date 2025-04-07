<?php
require_once 'backend/db.php';
$pdo = Db::getConnection();
include("models/header.php");
?>
    <header>
        <div class="header-content">
            <h2 class="fade-in">Troque seus itens e renove sua vida!</h2>
            <div class="line"></div>
            <h1 class="fade-in">O <span class="Futuro">EcoEscambo</span> é o Futuro do Consumo Consciente</h1>
            <p class="fade-in delay">Dê uma nova vida aos seus produtos e descubra novos tesouros sem gastar nada!</p>
            <a href="produtos.php" class="vrit fade-in delay">Ver Itens</a>
        </div>
    </header>
<?php
include("models/footer.php");
?>
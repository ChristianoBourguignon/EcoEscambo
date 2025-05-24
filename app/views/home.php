<?php $this->layout("master", [
    'title' => "Bem vindo",
    'description' => "Bem vindo a EcoEscambo, onde você pode oferecer um produto que você deseja jogar fora em um outro produto totalmente útil!"
]); ?>

<?php $this->start('body'); ?>

<header>
    <div class="header-content">
        <h2 class="fade-in">Troque seus itens e renove sua vida!</h2>
        <div class="line"></div>
        <h1 class="fade-in">O <span class="Futuro">EcoEscambo</span> é o Futuro do Consumo Consciente</h1>
        <p class="fade-in delay">Dê uma nova vida aos seus produtos e descubra novos tesouros sem gastar nada!</p>
        <a href="<?= BASE ?>/produtos" class="vrit fade-in delay">Ver Itens</a>
    </div>
</header>

<?php $this->stop(); ?>

<?php $this->layout("master", [
    'title' => "Página Principal",
    'description' => "Index da Pagina da Principal"
]); ?>

<?php $this->start('body'); ?>
    <link rel="stylesheet" href="../static/css/sobre.css">
    <!-- CONTEÚDO -->
    <main>
        <section class="content-box fade-in">
            <h1>Sobre o EcoEscambo</h1>
            <div class="line"></div>
            <p>
                O EcoEscambo é uma plataforma que promove o consumo consciente e sustentável através da troca de itens entre usuários — sem o uso de dinheiro.
                Nosso objetivo é criar uma comunidade colaborativa onde todos possam renovar seus bens e ajudar o meio ambiente ao mesmo tempo.
                Ao invés de descartar, troque!
            </p>

            <div class="contact-info px-5">
                <!--            <img src="img/gmail.png" alt="Gmail" width="50" height="25">-->
                <svg fill="#cc00a7" width="64px" height="64px" viewBox="0 0 24.00 24.00" id="email-open" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="icon flat-line" stroke="#cc00a7"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g><g id="SVGRepo_iconCarrier"><path id="secondary" d="M12.55,14.63,19.45,10a1,1,0,0,1,1.55.83V20a1,1,0,0,1-1,1H4a1,1,0,0,1-1-1V10.87A1,1,0,0,1,4.55,10l6.9,4.59A1,1,0,0,0,12.55,14.63Z" style="fill: #60db2f; stroke-width:1.7280000000000002;"></path><path id="primary" d="M6,11V3H18v8l-5.45,3.63a1,1,0,0,1-1.1,0Zm5.45,3.63L4.55,10A1,1,0,0,0,3,10.87V20a1,1,0,0,0,1,1H20a1,1,0,0,0,1-1V10.87A1,1,0,0,0,19.45,10l-6.9,4.59A1,1,0,0,1,11.45,14.63Z" style="fill: none; stroke: #96079b; stroke-linecap: round; stroke-linejoin: round; stroke-width:1.7280000000000002;"></path></g></svg>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=ecoescambo@gmail.com" target="_blank">ecoescambo@gmail.com</a>
            </div>
        </section>
    </main>
<?php $this->stop(); ?>
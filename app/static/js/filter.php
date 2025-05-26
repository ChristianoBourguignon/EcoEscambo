<?php
//$limite = 1;
//$totalProdutos = \app\controllers\ProductsController::contarProduts();
//$totalPaginas = ceil($limite / $totalProdutos);
//
//if($totalProdutos > 0){
//    ?>
<!--    <div class="d-flex justify-content-center my-4">-->
<!--        <button class="btn btn-primary" id="maisProdutos">Mostrar mais --><?php //var_dump($totalPaginas) ?><!--</button>-->
<!--    </div>-->
<!--    --><?php
//}
?>
<script>
$("#filter").on("submit", function(e) {
    e.preventDefault();

    let busca = $("#busca").val().toLowerCase().trim();
    let categoria = $("#categoria").val() ? $("#categoria").val().toLowerCase().trim() : ""

    if (busca === "" && categoria === "") {
        $(".product").show();
        return;
    }
    $(".product").each(function() {
        let texto = $(this).text().toLowerCase();
        let correspondeBusca = busca === "" || texto.indexOf(busca) > -1;
        let correspondeCategoria = categoria === "" || texto.indexOf(categoria) > -1;

        $(this).toggle(correspondeBusca && correspondeCategoria);
    });
});

//$("#maisProdutos").on("click",function(){
//    $.ajax({url: <?php //= BASE ?>//"/buscarProdutos",
//        success: function(result){
//            <?php //$produtos = result ?>
//        }
//        error: function (result){
//
//        }});
//})

</script>
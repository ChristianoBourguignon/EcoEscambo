<script>
$(document).ready(function() {
    $("#filter").on("submit", function (e) {
        e.preventDefault();

        let busca = $("#busca").val().toLowerCase().trim();
        let categoria = $("#categoria").val() ? $("#categoria").val().toLowerCase().trim() : ""

        if (busca === "" && categoria === "") {
            $(".product").show();
            return;
        }
        $(".product").each(function () {
            let texto = $(this).text().toLowerCase();
            let correspondeBusca = busca === "" || texto.indexOf(busca) > -1;
            let correspondeCategoria = categoria === "" || texto.indexOf(categoria) > -1;

            $(this).toggle(correspondeBusca && correspondeCategoria);
        });
    });
});
</script>
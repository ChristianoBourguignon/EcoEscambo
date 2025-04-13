<?php
require_once ("db.php");
$pdo = Db::getConnection();
$idProd = $_POST['produtoDesejadoId'];
$idProdOferecido = $_POST['meuProdutoId'];

// Ter o dono do produto IDPROD
// Ter o dono do Produto IDPRODOFERECIDO

try {
    // Inserção na tabela troca
    $stmt = $pdo->prepare("
    INSERT INTO troca (idUserDesejado, idUser, idProdDesejado, idProdUser)
    SELECT 
        p1.idUser AS idUserDesejado,
        p2.idUser AS idUser,
        p1.id AS idProdDesejado,
        p2.id AS idProdUser
    FROM 
        produtos p1, produtos p2
    WHERE 
        p1.id = :idProd AND
        p2.id = :idProdOferecido;
    ");
    $stmt->bindParam(':idProd', $idProd);
    $stmt->bindParam(':idProdOferecido', $idProdOferecido);
    $stmt->execute();

    // Inserção na tabela trocaded (mesmos dados)
    $stmtTrocaded = $pdo->prepare("
    INSERT INTO trocaded (idUserDesejado, idUser, idProdDesejado, idProdUser)
    SELECT 
        p1.idUser AS idUserDesejado,
        p2.idUser AS idUser,
        p1.id AS idProdDesejado,
        p2.id AS idProdUser
    FROM 
        produtos p1, produtos p2
    WHERE 
        p1.id = :idProd AND
        p2.id = :idProdOferecido;
    ");
    $stmtTrocaded->bindParam(':idProd', $idProd);
    $stmtTrocaded->bindParam(':idProdOferecido', $idProdOferecido);
    $stmtTrocaded->execute();

    // Redirecionamento após sucesso
    header("location: ../trocas.php");

} catch (PDOException $e) {
    echo "Erro ao trocar os produtos: " . $e->getMessage();
    exit;
}
?>

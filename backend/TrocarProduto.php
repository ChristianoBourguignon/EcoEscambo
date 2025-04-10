<?php
require_once ("db.php");
$pdo = Db::getConnection();
$idProd = $_POST['produtoDesejadoId'];
$idProdOferecido = $_POST['meuProdutoId'];

//Ter o dono do produto IDPROD
//Ter o dono do Produto IdPRODOFERECIDO

//INSERT INTO troca (idUserDesejado, idUser, idProdDesejado, idProdUser) VALUES (select idUser from produtos where id in (:idProd, :idProdOferecido),$idProd, $idProdOferecido)


try {
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
    header("location: ../trocas.php");


} catch (PDOException $e) {
    echo "Erro ao trocar os produtos: " . $e->getMessage();
    exit;
}


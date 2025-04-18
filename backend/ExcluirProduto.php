<?php
session_start();
require_once ("db.php");
$pdo = Db::getConnection();
$idProd = $_POST['id'];

try {
//    $stmt = $pdo->prepare("SELECT img FROM produtos WHERE id = :idProd");
//    $stmt->bindParam(':idProd', $idProd);
//    $stmt->execute();
//    $prod = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $caminho = __DIR__ . '/../' . $prod['img'];
//    unlink($caminho);
    $stmt = $pdo->prepare("DELETE FROM troca WHERE idProdDesejado = :idProd or idProdUser = :idProd");
    $stmt->bindParam(':idProd', $idProd);
    $stmt->execute();
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :idProd");
    $stmt->bindParam(':idProd', $idProd);
    $stmt->execute();

    header("Location: ../dashboard.php");

} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}



<?php
session_start();
require_once 'backend/db.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}
$pdo = Db::getConnection();
$idUser = $_SESSION['usuario_id'];
$prodUser = $_POST['meuProd'];
$prodDesejado = $_POST['prodOferecido'];
$btn = NULL;
if (isset($_POST['confirmar'])) {
    $btn = true;
} elseif (isset($_POST['rejeitar'])) {
    $btn = false;
}

try {
    if($btn){
        $stmt = $pdo->prepare("SELECT * FROM troca t JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) WHERE t.idUserDesejado = :idUser;");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM troca t JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) WHERE t.idUserDesejado = :idUser;");
    }
    $stmt->bindParam(':idUser', $idUser);
    $stmt->execute();
    $troca = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Location: ../trocas.php');

} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
}
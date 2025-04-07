<?php
require_once ("db.php");
$pdo = Db::getConnection();
$idProd = $_POST['id'];
$prodName = $_POST['nome'] ;
$prodDesc = $_POST['descricao'];
$image = $_FILES['imagem']['tmp_name'];// Só para evitar o erro de array

try {
    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    echo $idProd;
    if ($image != null) {
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imgTempPath = $_FILES['imagem']['tmp_name'];
            $nameImg = basename($_FILES['imagem']['name']);
            $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
            $newNameImg = uniqid('img_', true) . '.' . $extension;
            $imgPath = $uploadDir . $newNameImg;

            if (move_uploaded_file($imgTempPath, $imgPath)) {
                $image = "uploads/" . $newNameImg;

            }
            $stmt = $pdo->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, img = :img WHERE id = :idProd");
            $stmt->bindParam(':nome', $prodName);
            $stmt->bindParam(':descricao', $prodDesc);
            $stmt->bindParam(':img', $image);
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();
            echo $image;

        }
    } else {
        $stmt = $pdo->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao WHERE id = :idProd");
        $stmt->bindParam(':nome', $prodName);
        $stmt->bindParam(':descricao', $prodDesc);
        $stmt->bindParam(':idProd', $idProd);
        $stmt->execute();
    }
    header("Location: ../dashboard.php");


} catch (PDOException $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}
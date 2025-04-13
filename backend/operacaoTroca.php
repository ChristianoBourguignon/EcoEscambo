<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

$pdo = Db::getConnection();
$idUser = $_SESSION['usuario_id'];
$prodUser = $_POST['meuProd'];            // Produto do usuário atual (quem está logado)
$prodDesejado = $_POST['prodOferecido'];  // Produto que o outro usuário ofereceu

$btn = null;
if (isset($_POST['confirmar'])) {
    $btn = true;
} elseif (isset($_POST['rejeitar'])) {
    $btn = false;
}

try {
    if ($btn === true) {
        // Inicia transação
        $pdo->beginTransaction();

        // Atualiza o status da troca para 1 (confirmada)
        $stmtTroca = $pdo->prepare("
            UPDATE troca 
            SET status = 1 
            WHERE idProdDesejado = :prodDesejado 
              AND idProdUser = :prodUser
        ");
        $stmtTroca->execute([
            ':prodDesejado' => $prodDesejado,
            ':prodUser' => $prodUser
        ]);

        // Buscar os donos originais dos produtos
        $stmtProd1 = $pdo->prepare("SELECT idUser FROM produtos WHERE id = :id");
        $stmtProd1->execute([':id' => $prodUser]);
        $donoProdUser = $stmtProd1->fetchColumn();

        $stmtProd2 = $pdo->prepare("SELECT idUser FROM produtos WHERE id = :id");
        $stmtProd2->execute([':id' => $prodDesejado]);
        $donoProdDesejado = $stmtProd2->fetchColumn();

        // Trocar os donos dos produtos
        $stmtUpdate1 = $pdo->prepare("UPDATE produtos SET idUser = :novoDono WHERE id = :idProduto");
        $stmtUpdate1->execute([
            ':novoDono' => $donoProdDesejado,
            ':idProduto' => $prodUser
        ]);

        $stmtUpdate2 = $pdo->prepare("UPDATE produtos SET idUser = :novoDono WHERE id = :idProduto");
        $stmtUpdate2->execute([
            ':novoDono' => $donoProdUser,
            ':idProduto' => $prodDesejado
        ]);

        // Finaliza a transação
        $pdo->commit();

        header('Location: ../trocas.php');
        exit;
    } elseif ($btn === false) {
        // Rejeitar a troca (status -1)
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            UPDATE troca 
            SET status = -1 
            WHERE idProdDesejado = :prodDesejado 
              AND idProdUser = :prodUser
        ");
        $stmt->execute([
            ':prodDesejado' => $prodDesejado,
            ':prodUser' => $prodUser
        ]);

        $pdo->commit();

        header('Location: ../trocas.php');
        exit;
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Erro ao processar a troca: " . $e->getMessage();
}
?>

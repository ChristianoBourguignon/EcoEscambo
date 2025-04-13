<?php
require_once 'db.php';
session_start();
$pdo = Db::getConnection();

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$idUser = $_SESSION['usuario_id'];
$image = NULL;

// Lógica para upload da imagem
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $imgTempPath = $_FILES['imagem']['tmp_name'];
    $nameImg = basename($_FILES['imagem']['name']);
    $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
    $newNameImg = uniqid('img_', true) . '.' . $extension;
    $imgPath = $uploadDir . $newNameImg;

    if (move_uploaded_file($imgTempPath, $imgPath)) {
        $image = "uploads/" . $newNameImg;

    }
}

try {
    $sql = "INSERT INTO produtos (nome, descricao, img, idUser) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $descricao, $image, $idUser]);

    $_SESSION['sucesso'] = true;
    header("location: ../dashboard.php");
} catch (PDOException $e) {
    ?>
    <!-- Modal de erro -->
    <div class="modal fade" id="modalErro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-danger text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Erro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Erro ao cadastrar o Produto + <?= $e->getMessage() ?>
                </div>
            </div>
        </div>
    </div>
<?php
    header("location: ../dashboard.php");
}

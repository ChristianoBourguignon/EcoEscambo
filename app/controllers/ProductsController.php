<?php
namespace app\controllers;
use app\controllers\Controller;
use PDO;

class ProductsController
{
    public function index()
    {
        Controller::view("produtos");
    }
    public function buscarProdutos($idUser){
        dbController::getConnection();

        try {
            if(!$idUser){
                $stmt = dbController::getPdo()->prepare("
                    SELECT * FROM produtos p
                        WHERE (
                            EXISTS (
                                SELECT 1 
                                FROM troca t 
                                WHERE (t.idProdDesejado = p.id OR t.idProdUser = p.id)
                                  AND t.Status IN (0, -1)
                            )
                            OR NOT EXISTS (
                                SELECT 1 
                                FROM troca t 
                                WHERE t.idProdDesejado = p.id OR t.idProdUser = p.id
                            )
                        )
                        AND NOT EXISTS (
                            SELECT 1 
                            FROM troca t2 
                            WHERE (t2.idProdDesejado = p.id OR t2.idProdUser = p.id)
                              AND t2.Status = 1
                        );
                    ");
            } else {
                $stmt = dbController::getPdo()->prepare("
                    SELECT * FROM produtos p
                        WHERE p.idUser != :idUser AND
                              (
                            EXISTS (
                                SELECT 1 
                                FROM troca t 
                                WHERE (t.idProdDesejado = p.id OR t.idProdUser = p.id)
                                  AND t.Status IN (0, -1)
                            )
                            OR NOT EXISTS (
                                SELECT 1 
                                FROM troca t 
                                WHERE t.idProdDesejado = p.id OR t.idProdUser = p.id
                            )
                        )
                        AND NOT EXISTS (
                            SELECT 1 
                            FROM troca t2 
                            WHERE (t2.idProdDesejado = p.id OR t2.idProdUser = p.id)
                              AND t2.Status = 1
                        );
                ");
                $stmt->bindParam(':idUser',$idUser);
            }
            $stmt->execute();
            $produtos = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);
            return $produtos;
        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }
    public function cadastrarProdutos(){
        dbController::getConnection();
        session_start();
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
                $image = "../uploads/" . $newNameImg;

            }
        }

        try {
            $sql = "INSERT INTO produtos (nome, descricao, img, idUser) VALUES (?, ?, ?, ?)";
            $stmt = dbController::getPdo()->prepare($sql);
            $stmt->execute([$nome, $descricao, $image, $idUser]);

            $_SESSION['sucesso'] = true;
            header("location:" . BASE . "/dashboard");
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
            header("location:" . BASE . "/dashboard");
        }
    }
    public function alterarProduto(){

        //Criar lógica de apenas alterar quando tiver alteração

        dbController::getConnection();
        $idProd = $_POST['id'];
        $prodName = $_POST['nome'];
        $prodDesc = $_POST['descricao'];
        $image = $_FILES['imagem']['tmp_name'];// Só para evitar o erro de array

        try {
            $uploadDir = "../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if ($image != null) {
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    $imgTempPath = $_FILES['imagem']['tmp_name'];
                    $nameImg = basename($_FILES['imagem']['name']);
                    $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
                    $newNameImg = uniqid('img_', true) . '.' . $extension;
                    $imgPath = $uploadDir . $newNameImg;

                    if (move_uploaded_file($imgTempPath, $imgPath)) {
                        $image = "../uploads/" . $newNameImg;

                    }
                    $stmt = dbController::getPdo()->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, img = :img WHERE id = :idProd");
                    $stmt->bindParam(':nome', $prodName);
                    $stmt->bindParam(':descricao', $prodDesc);
                    $stmt->bindParam(':img', $image);
                    $stmt->bindParam(':idProd', $idProd);
                    $stmt->execute();
                    echo $image;

                }
            } else {

                $stmt = dbController::getPdo()->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao WHERE id = :idProd");
                $stmt->bindParam(':nome', $prodName);
                $stmt->bindParam(':descricao', $prodDesc);
                $stmt->bindParam(':idProd', $idProd);
                $stmt->execute();
                var_dump($stmt);
            }
            header("Location:" . BASE . "/dashboard");


        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }
    public function excluirProduto(){
        dbController::getConnection();
        session_start();
        if($_SESSION['usuario_id'] == NULL){
            http_response_code(401);
            header("location:". BASE);
            exit;
        }
        $idProd = $_POST['id'];

        try {
            $stmt = dbController::getPdo()->prepare("SELECT img FROM produtos WHERE id = :idProd");
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();
            $prod = $stmt->fetch();
            $caminhoRelativo = str_replace('/', DIRECTORY_SEPARATOR, $prod['img']);
            $caminhoAbsoluto = dirname(__DIR__) . DIRECTORY_SEPARATOR . $caminhoRelativo;
            if (file_exists($caminhoAbsoluto)) {
                unlink($caminhoAbsoluto);
            }
            $stmt = dbController::getPdo()->prepare("DELETE FROM troca WHERE idProdDesejado = :idProd or idProdUser = :idProd");
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();
            $stmt = dbController::getPdo()->prepare("DELETE FROM produtos WHERE id = :idProd");
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();

            header("Location:" . BASE . "/dashboard.php");

        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }

}
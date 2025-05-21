<?php
namespace app\controllers;
use app\controllers\Controller;
use PDO;
session_start();

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
            $_SESSION['modal'] = [
                'msg' => 'Erro ao buscar os produtos: '. $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }
    public function cadastrarProdutos(){
        dbController::getConnection();
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

            $_SESSION['modal'] = [
                'msg' => 'Produto: '. $nome . ' cadastrado com sucesso!',
                'statuscode' => 200
            ];
            header("location:" . BASE . "/dashboard");
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao cadastrar o Produto '. $nome . ': ' . $e->getMessage(),
                'statuscode' => 404
            ];
            header("location:" . BASE . "/dashboard");
            exit;
        }
    }
    public function alterarProduto(){
        if($_SESSION['usuario_id'] == NULL){
            $_SESSION['modal'] = [
                'msg' =>'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("location: ". BASE . "/produtos");
            exit;
        }
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
            }
            $_SESSION['modal'] = [
                'msg' =>'Produto: '. $prodName .' alterado com sucesso',
                'statuscode' => 200
            ];
            header("Location:" . BASE . "/dashboard");
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' =>'Erro ao alterar o produto: ' . $e->getMessage(),
                'statuscode' => 404
            ];
            header("Location:" . BASE . "/dashboard");
        }
    }
    public function excluirProduto(){
        dbController::getConnection();
        if($_SESSION['usuario_id'] == NULL){
            http_response_code(401);
            $_SESSION['modal'] = [
                'msg' =>'Você não tem acesso a essa área',
                'statuscode' => 401
            ];
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
            $_SESSION['modal'] = [
                'msg' =>'Produto Excluído com sucesso',
                'statuscode' => 200
            ];
            header("Location:" . BASE . "/dashboard");


        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao excluir o produto: '. $e->getMessage(),
                'statuscode' => 404
            ];
            header("location:". BASE . "/dashboard");
        }
    }

}
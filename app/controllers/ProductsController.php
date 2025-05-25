<?php
namespace app\controllers;
use app\controllers\Controller;
use PDO;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProductsController
{
    private array $allowedExtensionImg = ['png', 'jpg','jpeg'];
    private string $uploadDir = "app/static/uploads/";
    private array $allowedCat;

    public function __construct()
    {
        $this->allowedCat = array_column(self::getCategorias(), 'nome');
    }

    public function index()
    {
        Controller::view("produtos");
    }
    public static function getCategorias(){
        dbController::getConnection();
        $stmt = dbController::getPdo()->prepare("SELECT nome FROM categorias");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getProduto($idProd)
    {
        if((new userController)->buscarUser($_SESSION['usuario_id'])){
            dbController::getConnection();
            $stmt = dbController::getPdo()->prepare("SELECT img FROM produtos WHERE id = :idProd");
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            return $prod;
        } else {
            return 0;
        }

    }
    public function cadastrarProdutos()
    {
        if(!(new userController())->buscarUser($_SESSION['usuario_id'])){
            $_SESSION['modal'] = [
                'msg' =>'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("location: ". BASE . "/produtos");
            exit;
        }
        dbController::getConnection();
        $prodName = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prodDesc = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prodCat = $_POST['categoria'];
        if (!in_array($prodCat, $this->allowedCat)) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao cadastrar o Produto ' . $prodName . ': Categoria não permitida ou inválida.',
                'statuscode' => 404
            ];
            header("location: " . BASE . "/produtos");
            exit;
        }
        $idUser = $_SESSION['usuario_id'];
        $image = NULL;

        // Lógica para upload da imagem
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imgTempPath = $_FILES['imagem']['tmp_name'];
            $nameImg = basename($_FILES['imagem']['name']);
            $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
            if (!in_array($extension, $this->allowedExtensionImg)) {
                $_SESSION['modal'] = [
                    'msg' => 'Erro ao cadastrar o Produto ' . $prodName . ': Imagem não permitida. Envie apenas JPG, PNG e JPEG',
                    'statuscode' => 404
                ];
                header("location: " . BASE . "/produtos");
                exit;
            }
            $newNameImg = uniqid('img_', true) . '.' . $extension;
            $imgPath = $this->uploadDir . $newNameImg;
            if (move_uploaded_file($imgTempPath, $imgPath)) {
                $image = $this->uploadDir . $newNameImg;
            }
            try {
                $sql = "INSERT INTO produtos (nome, descricao, fk_categoria, img, idUser) VALUES (?, ?, ?, ?, ?)";
                $stmt = dbController::getPdo()->prepare($sql);
                $stmt->execute([$prodName, $prodDesc,$prodCat, $image, $idUser]);

                $_SESSION['modal'] = [
                    'msg' => 'Produto: ' . $prodName . ' cadastrado com sucesso!',
                    'statuscode' => 200
                ];
                header("location:" . BASE . "/dashboard");
            } catch (PDOException $e) {
                $_SESSION['modal'] = [
                    'msg' => 'Erro ao cadastrar o Produto ' . $prodName . ': ' . $e->getMessage(),
                    'statuscode' => 404
                ];
                header("location:" . BASE . "/dashboard");
                exit;
            }
        }
    }
    public function alterarProduto(){
        if(!(new userController())->buscarUser($_SESSION['usuario_id'])){
            $_SESSION['modal'] = [
                'msg' =>'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("location: ". BASE . "/produtos");
            exit;
        }

        //Criar lógica de apenas alterar quando tiver alteração

        dbController::getConnection();
        $idProd = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $prodName = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prodDesc = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prodCat = filter_input(INPUT_POST, 'categoria');
        if (!in_array($prodCat, $this->allowedCat)) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao alterar o Produto ' . $prodName . ': Categoria não permitida ou inválida.',
                'statuscode' => 404
            ];
            header("location: " . BASE . "/produtos");
            exit;
        }
        $image = $_FILES['imagem']['tmp_name'];// Só para evitar o erro de array

        try {
            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0755, true);
            }
            if ($image != null) {
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                    $imgTempPath = $_FILES['imagem']['tmp_name'];
                    $nameImg = basename($_FILES['imagem']['name']);
                    $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
                    if (!in_array($extension, $this->allowedExtensionImg)) {
                        $_SESSION['modal'] = [
                            'msg' => 'Erro ao cadastrar o Produto ' . $prodName . ': Imagem não permitida. Envie apenas JPG, PNG e JPEG',
                            'statuscode' => 404
                        ];
                        header("location: " . BASE . "/produtos");
                        exit;
                    }

                    $newNameImg = uniqid('img_', true) . '.' . $extension;
                    $imgPath = $this->uploadDir . $newNameImg;

                    if (move_uploaded_file($imgTempPath, $imgPath)) {
                        $image = $this->uploadDir . $newNameImg;
                    }
                    try {
                        $prod = $this->getProduto($idProd);
                        $caminhoRelativo = str_replace('/', DIRECTORY_SEPARATOR, $prod['img']);
                        $caminhoAbsoluto = dirname(__DIR__,2) . DIRECTORY_SEPARATOR . $caminhoRelativo;
                        if (file_exists($caminhoAbsoluto)) {
                            unlink($caminhoAbsoluto);
                        }
                        $stmt = dbController::getPdo()->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, fk_categoria = :categoria, img = :img WHERE id = :idProd");
                        $stmt->bindParam(':nome', $prodName);
                        $stmt->bindParam(':descricao', $prodDesc);
                        $stmt->bindParam(':categoria', $prodCat);
                        $stmt->bindParam(':img', $image);
                        $stmt->bindParam(':idProd', $idProd);
                        $stmt->execute();
                    } catch (\PDOException $e) {
                        $_SESSION['modal'] = [
                            'msg' => 'Erro ao cadastrar o Produto ' . $prodName . ': ' . $e->getMessage(),
                            'statuscode' => 404
                        ];
                        header("location:" . BASE . "/dashboard");
                        exit;
                    }
                }
            } else {
                $stmt = dbController::getPdo()->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao,fk_categoria = :categoria WHERE id = :idProd");
                $stmt->bindParam(':nome', $prodName);
                $stmt->bindParam(':descricao', $prodDesc);
                $stmt->bindParam(':categoria', $prodCat);
                $stmt->bindParam(':idProd', $idProd);
                $stmt->execute();
            }
            $_SESSION['modal'] = [
                'msg' =>'Produto: '. $prodName .' alterado com sucesso',
                'statuscode' => 200
            ];
            header("Location:" . BASE . "/dashboard");
        } catch (\PDOException $e) {
            $_SESSION['modal'] = [
                'msg' =>'Erro ao alterar o produto: ' . $e->getMessage(),
                'statuscode' => 404
            ];
            header("Location:" . BASE . "/dashboard");
        }
    }
    public function excluirProduto(){
        if(!(new userController())->buscarUser($_SESSION['usuario_id'])){
            $_SESSION['modal'] = [
                'msg' =>'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("location: ". BASE . "/produtos");
            exit;
        }
        dbController::getConnection();
        $idProd = $_POST['id'];

        try {
            $stmt = dbController::getPdo()->prepare("SELECT img FROM produtos WHERE id = :idProd");
            $stmt->bindParam(':idProd', $idProd);
            $stmt->execute();
            $prod = $stmt->fetch();
            $caminhoRelativo = str_replace('/', DIRECTORY_SEPARATOR, $prod['img']);
            $caminhoAbsoluto = dirname(__DIR__,2) . DIRECTORY_SEPARATOR . $caminhoRelativo;
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
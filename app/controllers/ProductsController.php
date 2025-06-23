<?php
namespace app\controllers;
use app\controllers\Controller;
use PDO;
use PDOException;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ProductsController
{
    /** @var array<string> */
    private array $allowedExtensionImg = ['png', 'jpg', 'jpeg'];
    private string $uploadDir = "app/static/uploads/";

    /** @var array<int|string, mixed> */
    private array $allowedCat;

    /** @var array<int> */
    private array $limitsProducts = [10, 10];

    public function __construct()
    {
        $this->allowedCat = array_column(self::getCategorias(), 'nome');
    }

    public function index(): void
    {
        Controller::view("produtos");
    }

    public function getLimit(): int
    {
        return $this->limitsProducts[0];
    }

    public static function contarProdutos(?int $idUser): int
    {
        if (!$idUser) {
            $stmt = dbController::getPdo()->prepare("
                SELECT count(*) FROM produtos p
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
                    )
                ORDER BY id DESC
            ");
        } else {
            $stmt = dbController::getPdo()->prepare("
                SELECT count(*) FROM produtos p
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
                    )
            ");
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function getCategorias(): array
    {
        dbController::getConnection();
        $stmt = dbController::getPdo()->prepare("SELECT nome FROM categorias");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buscarProdutos(?int $idUser = null, int $offset = 0): array
    {
        dbController::getConnection();
        try {
            $limit = $this->limitsProducts[1];
            if (!$idUser) {
                $stmt = dbController::getPdo()->prepare("
                    SELECT id, nome, descricao, img, fk_categoria FROM produtos p
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
                        )
                    ORDER BY id DESC
                    LIMIT $limit OFFSET $offset
                ");
            } else {
                $stmt = dbController::getPdo()->prepare("
                    SELECT id, img, nome, descricao, fk_categoria FROM produtos p
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
                        )
                    ORDER BY id DESC
                    LIMIT $limit OFFSET $offset
                ");
                $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao buscar os produtos: ' . $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }

    /**
     * @return array<string, string>|false
     */
    public function getProduto(int $idProd): array|false
    {
        if (!(new userController())->buscarUser((int)$_SESSION['usuario_id'])) {
            return false;
        }
        dbController::getConnection();
        $stmt = dbController::getPdo()->prepare("SELECT img FROM produtos WHERE id = :idProd");
        $stmt->bindParam(':idProd', $idProd, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrarProdutos(): void
    {
        if (!(new userController())->buscarUser((int)$_SESSION['usuario_id'])) {
            $_SESSION['modal'] = [
                'msg' => 'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("Location: " . BASE . "/produtos");
            exit;
        }
        dbController::getConnection();
        $prodName = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
        $prodDesc = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
        $prodCat = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
        if (!in_array($prodCat, $this->allowedCat)) {
            $_SESSION['modal'] = [
                'msg' => "Erro ao cadastrar o Produto $prodName: Categoria não permitida ou inválida.",
                'statuscode' => 404
            ];
            header("Location: " . BASE . "/produtos");
            exit;
        }
        $idUser = (int)$_SESSION['usuario_id'];
        $image = null;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imgTempPath = $_FILES['imagem']['tmp_name'];
            $nameImg = $_FILES['imagem']['name'];
            $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
            if (!in_array($extension, $this->allowedExtensionImg)) {
                $_SESSION['modal'] = [
                    'msg' => "Erro ao cadastrar o Produto $prodName: Imagem não permitida. Envie apenas JPG, PNG e JPEG",
                    'statuscode' => 404
                ];
                header("Location: " . BASE . "/produtos");
                exit;
            }
            $newNameImg = uniqid('img_', true) . '.' . $extension;
            $imgPath = $this->uploadDir . $newNameImg;
            if (move_uploaded_file($imgTempPath, $imgPath)) {
                $image = $this->uploadDir . $newNameImg;
            }
        }

        try {
            $sql = "INSERT INTO produtos (nome, descricao, fk_categoria, img, idUser) VALUES (?, ?, ?, ?, ?)";
            $stmt = dbController::getPdo()->prepare($sql);
            $stmt->execute([$prodName, $prodDesc, $prodCat, $image, $idUser]);

            $_SESSION['modal'] = [
                'msg' => "Produto: $prodName cadastrado com sucesso!",
                'statuscode' => 200
            ];
            header("Location: " . BASE . "/dashboard");
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => "Erro ao cadastrar o Produto $prodName: " . $e->getMessage(),
                'statuscode' => 404
            ];
            header("Location: " . BASE . "/dashboard");
            exit;
        }
    }

    public function alterarProduto(): void
    {
        if (!(new userController())->buscarUser((int)$_SESSION['usuario_id'])) {
            $_SESSION['modal'] = [
                'msg' => 'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("Location: " . BASE . "/produtos");
            exit;
        }

        dbController::getConnection();
        $idProd = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) ?: 0;
        $prodName = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
        $prodDesc = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
        $prodCat = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
        if (!in_array($prodCat, $this->allowedCat)) {
            $_SESSION['modal'] = [
                'msg' => "Erro ao alterar o Produto $prodName: Categoria não permitida ou inválida.",
                'statuscode' => 404
            ];
            header("Location: " . BASE . "/produtos");
            exit;
        }

        try {
            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0755, true);
            }
            $image = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imgTempPath = $_FILES['imagem']['tmp_name'];
                $nameImg = $_FILES['imagem']['name'];
                $extension = strtolower(pathinfo($nameImg, PATHINFO_EXTENSION));
                if (!in_array($extension, $this->allowedExtensionImg)) {
                    $_SESSION['modal'] = [
                        'msg' => "Erro ao alterar o Produto $prodName: Imagem não permitida. Envie apenas JPG, PNG e JPEG",
                        'statuscode' => 404
                    ];
                    header("Location: " . BASE . "/produtos");
                    exit;
                }

                $newNameImg = uniqid('img_', true) . '.' . $extension;
                $imgPath = $this->uploadDir . $newNameImg;

                if (move_uploaded_file($imgTempPath, $imgPath)) {
                    $image = $this->uploadDir . $newNameImg;
                    $prod = $this->getProduto((int)$idProd);
                    if (is_array($prod) && isset($prod['img'])) {
                        $caminhoRelativo = str_replace('/', DIRECTORY_SEPARATOR, $prod['img']);
                        $caminhoAbsoluto = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $caminhoRelativo;
                        if (file_exists($caminhoAbsoluto)) {
                            unlink($caminhoAbsoluto);
                        }
                    }
                    $stmt = dbController::getPdo()->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, fk_categoria = :categoria, img = :img WHERE id = :idProd");
                    $stmt->bindParam(':nome', $prodName);
                    $stmt->bindParam(':descricao', $prodDesc);
                    $stmt->bindParam(':categoria', $prodCat);
                    $stmt->bindParam(':img', $image);
                    $stmt->bindParam(':idProd', $idProd, PDO::PARAM_INT);
                    $stmt->execute();
                }
            } else {
                $stmt = dbController::getPdo()->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, fk_categoria = :categoria WHERE id = :idProd");
                $stmt->bindParam(':nome', $prodName);
                $stmt->bindParam(':descricao', $prodDesc);
                $stmt->bindParam(':categoria', $prodCat);
                $stmt->bindParam(':idProd', $idProd, PDO::PARAM_INT);
                $stmt->execute();
            }
            $_SESSION['modal'] = [
                'msg' => "Produto: $prodName alterado com sucesso",
                'statuscode' => 200
            ];
            header("Location: " . BASE . "/dashboard");
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => "Erro ao alterar o produto: " . $e->getMessage(),
                'statuscode' => 404
            ];
            header("Location: " . BASE . "/dashboard");
        }
    }

    public function excluirProduto(): void
    {
        if (!(new userController())->buscarUser((int)$_SESSION['usuario_id'])) {
            $_SESSION['modal'] = [
                'msg' => 'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("Location: " . BASE . "/produtos");
            exit;
        }
        dbController::getConnection();
        $idProd = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) ?: 0;

        try {
            $stmt = dbController::getPdo()->prepare("SELECT img FROM produtos WHERE id = :idProd");
            $stmt->bindParam(':idProd', $idProd, PDO::PARAM_INT);
            $stmt->execute();
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($prod) && isset($prod['img'])) {
                $caminhoRelativo = str_replace('/', DIRECTORY_SEPARATOR, $prod['img']);
                $caminhoAbsoluto = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $caminhoRelativo;
                if (file_exists($caminhoAbsoluto)) {
                    unlink($caminhoAbsoluto);
                }
            }
            $stmt = dbController::getPdo()->prepare("DELETE FROM troca WHERE idProdDesejado = :idProd OR idProdUser = :idProd");
            $stmt->bindParam(':idProd', $idProd, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = dbController::getPdo()->prepare("DELETE FROM produtos WHERE id = :idProd");
            $stmt->bindParam(':idProd', $idProd, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['modal'] = [
                'msg' => 'Produto Excluído com sucesso',
                'statuscode' => 200
            ];
            header("Location: " . BASE . "/dashboard");
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao excluir o produto: ' . $e->getMessage(),
                'statuscode' => 404
            ];
            header("Location: " . BASE . "/dashboard");
        }
    }
}
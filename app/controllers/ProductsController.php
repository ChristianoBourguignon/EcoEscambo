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
        require_once 'backend/db.php';
        $pdo = dbController::getConnection();

        try {
            if(!$idUser){
                $stmt = dbController::getPdo()->prepare("
                    SELECT *
                    FROM produtos p
                    WHERE EXISTS (
                            SELECT 1 
                            FROM troca t 
                            WHERE (t.idProdDesejado = p.id OR t.idProdUser = p.id)
                              AND t.Status IN (0, -1)
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
                    SELECT *
                    FROM produtos p
                    WHERE p.idUser != :idUser
                      AND EXISTS (
                        SELECT 1 
                        FROM troca t 
                        WHERE (t.idProdDesejado = p.id OR t.idProdUser = p.id)
                          AND t.Status IN (0, -1)
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
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $produtos;
        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }
    public function cadastrarProdutos(){

    }

}
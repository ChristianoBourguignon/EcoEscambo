<?php
namespace app\controllers;
use app\controllers\Controller;

session_start();
require_once 'dbController.php';


class userController
{
    public function index()
    {
        Controller::view("dashboard");
    }
    public function logar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                echo "Preencha todos os campos!";
                exit;
            }

            try {
                $pdo = dbController::getConnection();

                $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM users WHERE email = :email LIMIT 1");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $usuario = $stmt->fetch($pdo::FETCH_ASSOC);

                // Sem criptografia, comparação direta
                if ($usuario && $usuario['senha'] === $senha) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];

                } else {
                    echo "Email ou senha incorretos.";
                    exit;
                }
            } catch (PDOException $e) {
                echo "Erro de conexão: " . $e->getMessage();
                exit;
            } catch (\Exception $e) {
                echo "Erro na view: " . $e->getMessage();
                exit;
            }
        } else {
            echo "Requisição inválida.";
            exit;
        }
        header("location:" .BASE. "/dashboard");
    }
    public function meusProdutos($idUser){
        try {
            dbController::getConnection();
            $stmt = dbController::getPdo()->prepare("
                SELECT produtos.*, users.nome
                FROM produtos
                INNER JOIN users ON produtos.idUser = users.id
                WHERE produtos.idUser = :idUser;
                "
            );
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $produtos = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

//            $stmt = $pdo->prepare("SELECT nome FROM users WHERE id = :id");
//            $stmt->bindParam(':id', $idUser);
//            $stmt->execute();
//            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $produtos;
        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }
}
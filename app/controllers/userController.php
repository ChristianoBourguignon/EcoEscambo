<?php
namespace app\controllers;
use app\controllers\Controller;
use PDO;

session_start();
require_once 'dbController.php';
$idUser = $_SESSION['usuario_id'];


class userController
{
    public function index()
    {
        Controller::view("dashboard");
    }
    public function trocas()
    {
        Controller::view("trocas");
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
                dbController::getConnection();

                $stmt = dbController::getPdo()->prepare("SELECT id, nome, email, senha FROM users WHERE email = :email LIMIT 1");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Sem criptografia, comparação direta
                if ($usuario && $usuario['senha'] === $senha) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    var_dump($_SESSION['usuario_id']);
                    var_dump($_SESSION['usuario_nome']);

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
    public function deslogar(){
        // Limpa todas as variáveis de sessão
        $_SESSION = array();

        // Se estiver usando cookies de sessão, destrói também
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destrói a sessão
        session_destroy();

        // Redireciona para a tela de login ou início
        header("location:" . BASE);
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

            return $produtos;
        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }
    public function consultarTrocas(){

        if (!isset($_SESSION['usuario_id'])) {
            header("Location:" . BASE);
            exit;
        }
        dbController::getConnection();
        $idUser = $_SESSION['usuario_id'];
        $nomeUsuario = $_SESSION['usuario_nome'];

        try {
            // Consulta todos os produtos em propostas de troca feitas ao usuário logado
            $stmt = dbController::getPdo()->prepare("
               SELECT * 
                FROM troca t 
                JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
                WHERE t.idUserDesejado = :idUser 
                AND t.status = 0;
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $solicitacao = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

            $stmt = dbController::getPdo()->prepare("
               SELECT * 
                FROM troca t 
                JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
                WHERE t.idUser = :idUser 
                AND t.status = 0;
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $pendente = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

            $stmt = dbController::getPdo()->prepare("
               SELECT * 
                FROM troca t 
                JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
                WHERE t.idUser = :idUser 
                AND (t.status = 1 or t.status = -1);
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $trocados = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

            $resultadosSql = [$nomeUsuario,$solicitacao,$pendente,$trocados];
            return $resultadosSql;

        } catch (PDOException $e) {
            echo "Erro ao buscar produtos: " . $e->getMessage();
            exit;
        }
    }
    public function realizarTroca($prodOferecido, $meuProd){

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ../index.php");
            exit;
        }

        dbController::getConnection();
        $prodUser = $meuProd;
        $prodDesejado = $prodOferecido;

        $btn = null;
        if (isset($_POST['confirmar'])) {
            $btn = true;
        } elseif (isset($_POST['rejeitar'])) {
            $btn = false;
        }

        try {
            if ($btn === true) {
                // Inicia transação
                dbController::getPdo()->beginTransaction();

                // Atualiza o status da troca para 1 (confirmada)
                $stmtTroca = dbController::getPdo()->prepare("
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
                $stmtProd1 = dbController::getPdo()->prepare("SELECT idUser FROM produtos WHERE id = :id");
                $stmtProd1->execute([':id' => $prodUser]);
                $donoProdUser = $stmtProd1->fetchColumn();

                $stmtProd2 = dbController::getPdo()->prepare("SELECT idUser FROM produtos WHERE id = :id");
                $stmtProd2->execute([':id' => $prodDesejado]);
                $donoProdDesejado = $stmtProd2->fetchColumn();

                // Trocar os donos dos produtos
                $stmtUpdate1 = dbController::getPdo()->prepare("UPDATE produtos SET idUser = :novoDono WHERE id = :idProduto");
                $stmtUpdate1->execute([
                    ':novoDono' => $donoProdDesejado,
                    ':idProduto' => $prodUser
                ]);

                $stmtUpdate2 = dbController::getPdo()->prepare("UPDATE produtos SET idUser = :novoDono WHERE id = :idProduto");
                $stmtUpdate2->execute([
                    ':novoDono' => $donoProdUser,
                    ':idProduto' => $prodDesejado
                ]);

                // Finaliza a transação
                dbController::getPdo()->commit();

                header('Location:' . BASE . '/trocas');
                exit;
            } else {
                // Rejeitar a troca (status -1)
                dbController::getPdo()->beginTransaction();

                $stmt = dbController::getPdo()->prepare("
                    UPDATE troca 
                    SET status = -1 
                    WHERE idProdUser = :prodUser 
                        AND idProdDesejado = :prodDesejado
                        AND status = 0;
                ");
                $stmt->execute([
                    ':prodDesejado' => $prodDesejado,
                    ':prodUser' => $prodUser
                ]);

                dbController::getPdo()->commit();

                header('Location:' . BASE . '/trocas');
                exit;
            }

        } catch (Exception $e) {
            if (dbController::getPdo()->inTransaction()) {
                dbController::getPdo()->rollBack();
            }
            echo "Erro ao processar a troca: " . $e->getMessage();
        }
    }
}
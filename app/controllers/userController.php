<?php
namespace app\controllers;
use app\controllers\Controller;
use http\Header;
use PDO;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    public function buscarUser($idUser): bool
    {
        dbController::getConnection();
        $stmt = dbController::getPdo()->prepare("SELECT id,nome FROM users WHERE id = :idUser");
        $stmt->bindParam(':idUser', $idUser);
        $stmt->execute();
        $user = $stmt->fetch();
        if(count($user) > 0){
            if($_SESSION['usuario_nome'] == $user['nome']){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function getUser($idBusca, $colunaBusca = "id"){
        try {
            $colunasValidas = ["email","id"];
            if(!array($colunaBusca,$colunasValidas)){
                throw new \Exception("Coluna inválida ou inexistente: {$colunaBusca}");
            }
            $stmt = dbController::getPdo()->prepare("SELECT id,nome,email FROM users where {$colunaBusca} = :email LIMIT 1");
            $stmt->bindParam(":email", $idBusca);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e){
            $_SESSION['modal'] = [
                'msg' => "Erro ao buscar o usuario:".$e->getMessage(),
                'statuscode' => 404
            ];
            header("location: ". BASE . '/404');
            exit;
        }
    }

    public function criarConta(){
        $nome = filter_input(INPUT_POST, 'nome',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);
        $senha = filter_input(INPUT_POST, 'senha');
        if(empty($nome) || empty($email) || empty($senha)){
            $_SESSION['modal'] = [
                'msg' => 'É necessário o preenchimento de todos os campos',
                'statuscode' => 404
            ];
            header("location: ". BASE);
            exit;
        }
        $senha = password_hash($senha, PASSWORD_DEFAULT);
        dbController::getConnection();
        try{
            $stmt = dbController::getPdo()->prepare("SELECT email FROM users where email = :email LIMIT 1");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch();
            if(!empty($user)){
                $_SESSION['modal'] = [
                    'msg' => 'Já existe uma conta cadastrada neste e-mail!',
                    'statuscode' => 404
                ];
                header("location: ". BASE);
                exit;
            }
            $stmt = dbController::getPdo()->prepare("INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senha);
            $stmt->execute();
            $user = userController::getUser($email,"email");
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['modal'] = [
                'msg' => "Parabens, conta criada com sucesso! Você foi logado automaticamente.",
                'statuscode' => 200
            ];
            header("location: ". BASE . "/dashboard");
            exit;
        } catch (\Exception $e){
            $_SESSION['modal'] = [
                'msg' => "Erro ao criar uma conta",
                'statuscode' => 404
            ];
            header("location: ". BASE);
            exit;
        }
    }

    public function logar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $senha = filter_input(INPUT_POST,'senha');

            if (empty($email) || empty($senha)) {
                echo "Preencha todos os campos!";
                exit;
            }

            try {
                dbController::getConnection();

                $stmt = dbController::getPdo()->prepare("SELECT id, nome, email, senha FROM users WHERE email = :email LIMIT 1");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Sem criptografia, comparação direta
                // password_verify e password_hash -> pesquisar na documentação como se usa posteriormente
                if ($user && password_verify($senha,$user['senha'])) {
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['usuario_nome'] = $user['nome'];

                    $_SESSION['modal'] = [
                        'msg' => "Seja bem-vindo ". $user['nome'] . '!',
                        'statuscode' => 200
                    ];
                    header("location:" . BASE . "/dashboard");
                } else {
                    $_SESSION['modal'] = [
                        'msg' =>'Usuario ou senha incorreta',
                        'statuscode' => 404
                    ];
                    header("location:" . BASE);
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
            $_SESSION['modal'] = [
                'msg' =>'Você precisa logar para acessar esse conteúdo',
                'statuscode' => 401
            ];
            header("location: ". BASE . '/404');
        }
    }

    public function deslogar()
    {
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

    public function meusProdutos($idUser)
    {
        try {
            if(!userController::buscarUser($idUser)){
                $_SESSION['modal']= [
                    'msg'=>"Usuario não encontrado",
                    'statuscode'=>401
                ];
                header("location: ". BASE . "/dashboard");
                exit;
            }
            dbController::getConnection();
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
                        )
                     AND idUser = :idUser;
                "
            );
            $stmt->bindParam(':idUser', $idUser);
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

    public function consultarTrocas()
    {
        if(!userController::buscarUser($_SESSION['usuario_id'])){
            $_SESSION['modal']= [
                'msg'=>"Usuario não encontrado",
                'statuscode'=>401
            ];
            header("location: ". BASE . "/dashboard");
            exit;
        }
        dbController::getConnection();
        $idUser = $_SESSION['usuario_id'];
        $nomeUsuario = $_SESSION['usuario_nome'];

        try {
            // Consulta todos os produtos em propostas de troca feitas ao usuário logado
            $stmt = dbController::getPdo()->prepare("
               SELECT t.idProdDesejado,t.idProdUser,t.status,t.idUserDesejado 
                FROM troca t 
                JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
                WHERE t.idUserDesejado = :idUser 
                AND t.status = 0;
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $solicitacao = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

            $stmt = dbController::getPdo()->prepare("
               SELECT t.idProdDesejado,t.idProdUser,t.status,t.idUser 
                FROM troca t 
                JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
                WHERE t.idUser = :idUser 
                AND t.status = 0;
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $pendente = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

            $stmt = dbController::getPdo()->prepare("
               SELECT t.idProdDesejado,t.idProdUser,t.Status,t.idUser,p.img,p.nome,p.descricao 
                FROM troca t 
                JOIN produtos p ON p.id IN (t.idProdDesejado, t.idProdUser) 
                WHERE (t.idUser = :idUser or t.idUserDesejado = :idUser)
                AND (t.Status = 1 or t.Status = -1);
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            $trocados = $stmt->fetchAll(dbController::getPdo()::FETCH_ASSOC);

            foreach (['solicitacao', 'pendente', 'trocados'] as $variavel) {
//                $$variavel => É uma variável da váriavel.
//                 (Exemplo: $$variavel pode ser $trocados, isso evita setar na mesma váriavel)
                if (count($$variavel) <= 0) {
                    $$variavel = NULL;
                }
            }

            $resultadosSql = [$nomeUsuario, $solicitacao, $pendente, $trocados];
            return $resultadosSql;

        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' =>'Erro ao consultar trocas: '. $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }

    public function realizarTroca()
    {

        if (!isset($_SESSION['usuario_id'])) {
            header("Location:" . BASE);
            exit;
        }

        dbController::getConnection();
        $prodUser = filter_input(INPUT_POST, 'meuProd', FILTER_SANITIZE_NUMBER_INT);;
        $prodDesejado = filter_input(INPUT_POST, 'prodOferecido', FILTER_SANITIZE_NUMBER_INT);;

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

                $_SESSION['modal'] = [
                    'msg' =>'Produto trocado com sucesso',
                    'statuscode' => 200
                ];

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
                $_SESSION['modal'] = [
                    'msg' =>'Produto rejeitado com sucesso',
                    'statuscode' => 200
                ];

                header('Location:' . BASE . '/trocas');
                exit;
            }

        } catch (Exception $e) {
            if (dbController::getPdo()->inTransaction()) {
                dbController::getPdo()->rollBack();
            }
            $_SESSION['modal'] = [
                'msg' =>'Erro ao realizar a troca' . $e->getMessage(),
                'statuscode' => 404
            ];
        }
    }

    public function solicitarTroca()
    {
        dbController::getConnection();
        $idProd = filter_input(INPUT_POST, 'produtoDesejadoId', FILTER_SANITIZE_NUMBER_INT);
        $idProdOferecido = filter_input(INPUT_POST, 'meuProdutoId', FILTER_SANITIZE_NUMBER_INT);

        // Ter o dono do produto IDPROD
        // Ter o dono do Produto IDPRODOFERECIDO

        try {
            // Inserção na tabela troca
            $stmt = dbController::getPdo()->prepare("
                INSERT INTO troca (idUserDesejado, idUser, idProdDesejado, idProdUser)
                SELECT 
                    p1.idUser AS idUserDesejado,
                    p2.idUser AS idUser,
                    p1.id AS idProdDesejado,
                    p2.id AS idProdUser
                FROM 
                    produtos p1, produtos p2
                WHERE 
                    p1.id = :idProd AND
                    p2.id = :idProdOferecido AND
                    NOT EXISTS (
                            SELECT 1 FROM troca 
                            WHERE idUserDesejado = p1.idUser 
                              AND idUser = p2.idUser 
                              AND idProdDesejado = p1.id 
                              AND idProdUser = p2.id
                    );
                ");
            $stmt->bindParam(':idProd', $idProd);
            $stmt->bindParam(':idProdOferecido', $idProdOferecido);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                header("location:" . BASE . "/trocas");
            } else {
                $_SESSION['modal'] = [
                    'msg' =>'Já existe uma troca pendente com esses produtos!',
                    'statuscode' => 404
                ];
            }
            header("location:" . BASE . "/trocas");

        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' =>'Erro ao realizar a solicitação de troca: '. $e->getMessage(),
                'statuscode' => 401
            ];
            exit;
        }
    }
}
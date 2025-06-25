<?php
namespace app\controllers;
use Exception;
use PDO;
use PDOException;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class userController
{
    public function index(): void
    {
        Controller::view("dashboard");
    }

    public function trocas(): void
    {
        Controller::view("trocas");
    }
    public static function getNome(int $idUser): String {
        if(($idUser != NULL) && (is_string($_SESSION['usuario_nome']))){
            return $_SESSION['usuario_nome'];
        } else {
            return "";
        }
    }
    public function buscarUser(int $idUser): bool
    {
        dbController::getConnection();
        $stmt = dbController::getPdo()->prepare("SELECT id,nome FROM users WHERE id = :idUser");
        $stmt->bindParam(':idUser', $idUser);
        $stmt->execute();
        $user = $stmt->fetch();
        /** @var array{id: int,nome: string} $user */
        if($user != NULL){
            if($_SESSION['usuario_nome'] == $user['nome']){
                return true;
            } else {
                $_SESSION['modal'] = [
                    'msg' => 'Você não tem acesso à esse conteúdo',
                    'statuscode' => 401
                ];
                return false;
            }
        } else {
            $_SESSION['modal'] = [
                'msg' => 'Não encontrado o usuario especificado',
                'statuscode' => 404
            ];
            header("location:" .BASE. "/404");
            return false;
        }
    }
    /**
     * @return array{id: int, nome: string, email: string}|false
     */
    public function getUser(string $idBusca, string $colunaBusca = "id"): array|false {
        try {
            $colunasValidas = ["email", "id"];
            if (!in_array($colunaBusca, $colunasValidas, true)) {
                $_SESSION['modal'] = [
                    'msg' => "Coluna inválida",
                    'statuscode'=>404
                ];
                http_response_code(404);
                exit;
            }
            $stmt = dbController::getPdo()->prepare(
                "SELECT id, nome, email FROM users WHERE {$colunaBusca} = :valor LIMIT 1"
            );
            $stmt->bindParam(":valor", $idBusca);
            $stmt->execute();
            /** @var array{id: int, nome: string, email: string}|false $result */
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;

        } catch (Exception $e){
            $_SESSION['modal'] = [
                'msg' => "Erro ao buscar o usuário: " . $e->getMessage(),
                'statuscode' => 404
            ];
            header("location: " . BASE . '/404');
            exit;
        }
    }


    public function criarConta(): void{
        $nome = filter_input(INPUT_POST, 'nome',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        /** @var string $email */
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
            if (!is_string($email)) {
                throw new Exception("Email inválido ou não fornecido.");
            }
            $user = userController::getUser($email,"email");
            if ($user === false) {
                throw new Exception("Usuário não encontrado.");
            }
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['modal'] = [
                'msg' => "Parabens, conta criada com sucesso! Você foi logado automaticamente.",
                'statuscode' => 200
            ];
            header("location: ". BASE . "/dashboard");
            exit;
        } catch (Exception $e){
            $_SESSION['modal'] = [
                'msg' => "Erro ao criar uma conta",
                'statuscode' => 404
            ];
            header("location: ". BASE);
            exit;
        }
    }

    public function logar():void
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
                if ($user === false) {
                    throw new Exception("Usuário não encontrado.");
                }
                /** @var array{id: int, nome: string, email: string, senha: string}|false $user */

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
                throw new PDOException("Erro de conexão: ". $e->getMessage());
            } catch (Exception $e) {
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

    public function deslogar():void
    {
        // Limpa todas as variáveis de sessão
        $_SESSION = array();

        // Se estiver usando cookies de sessão, destrói também
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie("UserLogged", '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destrói a sessão
        session_destroy();

        // Redireciona para a tela de login ou início
        header("location:" . BASE);
    }
    /**
     * @return array<int, array{id: int, img: string, nome: string, descricao: string, fk_categoria: string}>|false
     */
    public function meusProdutos(int $idUser): array|false
    {
        try {
            userController::buscarUser($idUser);
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
            /** @var array<int, array{id: int, img: string, nome: string, descricao: string, fk_categoria: string}>|false $result */
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' => 'Erro ao buscar os produtos: '. $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }

    /**
     * @return array<int, array{
     *      idProdDesejado: int,
     *      idProdUser: int,
     *      status: string,
     *      nomeProdutoDesejado: string,
     *      nomeProdutoUser: string,
     *      descricaoProdutoDesejado: string,
     *      descricaoProdutoUser: string,
     *      imgProdutoDesejado: string,
     *      imgProdutoUser: string,
     *      categoriaProdutoDesejado: string,
     *      categoriaProdutoUser: string
     *  }>|false
     */
    public function getTrocasSolicitadas(int $idUser): array|false
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
            // Consulta todos os produtos em propostas de troca feitas ao usuário logado
            dbController::getConnection();
            $stmt = dbController::getPdo()->prepare("
               SELECT 
                    t.idProdDesejado,
                    t.idProdUser,
                    t.status,
                    p1.nome AS nomeProdutoDesejado,
                    p2.nome AS nomeProdutoUser,
                    p1.descricao AS descricaoProdutoDesejado,
                    p2.descricao AS descricaoProdutoUser,
                    p1.img AS imgProdutoDesejado,
                    p2.img AS imgProdutoUser,
                    p1.fk_categoria AS categoriaProdutoDesejado,
                    p2.fk_categoria AS categoriaProdutoUser
                FROM troca t
                INNER JOIN produtos p1 ON p1.id = t.idProdDesejado
                INNER JOIN produtos p2 ON p2.id = t.idProdUser
                WHERE t.idUserDesejado = :idUser
                  AND t.status = 0;
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            /**
             * @var array<int, array{
             *     idProdDesejado: int,
             *     idProdUser: int,
             *     status: string,
             *     nomeProdutoDesejado: string,
             *     nomeProdutoUser: string,
             *     descricaoProdutoDesejado: string,
             *     descricaoProdutoUser: string,
             *     imgProdutoDesejado: string,
             *     imgProdutoUser: string,
             *     categoriaProdutoDesejado: string,
             *     categoriaProdutoUser: string
             * }>|false $result
             */
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch (\PDOException $e){
            $_SESSION['modal'] = [
                'msg' =>'Erro ao consultar trocas: '. $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }

    /**
     * @return array<int, array{
     *      idProdDesejado: int,
     *      idProdUser: int,
     *      status: string,
     *      nomeProdutoDesejado: string,
     *      nomeProdutoUser: string,
     *      descricaoProdutoDesejado: string,
     *      descricaoProdutoUser: string,
     *      imgProdutoDesejado: string,
     *      imgProdutoUser: string,
     *      categoriaProdutoDesejado: string,
     *      categoriaProdutoUser: string
     *  }>|false
     */
    public function getTrocasPendentes(int $idUser): array|false
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
            // Consulta todos os produtos em propostas de troca feitas ao usuário logado
            $stmt = dbController::getPdo()->prepare("
               SELECT 
                    t.idProdDesejado,
                    t.idProdUser,
                    t.status,
                    p1.nome AS nomeProdutoDesejado,
                    p2.nome AS nomeProdutoUser,
                    p1.descricao AS descricaoProdutoDesejado,
                    p2.descricao AS descricaoProdutoUser,
                    p1.img AS imgProdutoDesejado,
                    p2.img AS imgProdutoUser,
                    p1.fk_categoria AS categoriaProdutoDesejado,
                    p2.fk_categoria AS categoriaProdutoUser
                FROM troca t
                INNER JOIN produtos p1 ON p1.id = t.idProdDesejado
                INNER JOIN produtos p2 ON p2.id = t.idProdUser
                WHERE t.idUser = :idUser
                  AND t.status = 0;
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            /**
             * @var array<int, array{
             *     idProdDesejado: int,
             *     idProdUser: int,
             *     status: string,
             *     nomeProdutoDesejado: string,
             *     nomeProdutoUser: string,
             *     descricaoProdutoDesejado: string,
             *     descricaoProdutoUser: string,
             *     imgProdutoDesejado: string,
             *     imgProdutoUser: string,
             *     categoriaProdutoDesejado: string,
             *     categoriaProdutoUser: string
             * }>|false $result
             */
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch (\PDOException $e){
            $_SESSION['modal'] = [
                'msg' =>'Erro ao consultar trocas: '. $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }

    /**
     * @return array<int, array{
     *      idProdDesejado: int,
     *      idProdUser: int,
     *      status: string,
     *      nomeProdutoDesejado: string,
     *      nomeProdutoUser: string,
     *      descricaoProdutoDesejado: string,
     *      descricaoProdutoUser: string,
     *      imgProdutoDesejado: string,
     *      imgProdutoUser: string,
     *      categoriaProdutoDesejado: string,
     *      categoriaProdutoUser: string
     *  }>|false
     */
    public function getHistoricoTrocas(int $idUser): array|false
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

            // Consulta todos os produtos em propostas de troca feitas ao usuário logado
            $stmt = dbController::getPdo()->prepare("
               SELECT 
                    t.idProdDesejado,
                    t.idProdUser,
                    t.Status,
                    pd.img AS imgProdutoDesejado,
                    pd.nome AS nomeProdutoDesejado,
                    pd.descricao AS descricaoProdutoDesejado,
                    pu.img AS imgProdutoUser,
                    pu.nome AS nomeProdutoUser,
                    pu.descricao AS descricaoProdutoUser
                FROM troca t
                JOIN produtos pd ON pd.id = t.idProdDesejado
                JOIN produtos pu ON pu.id = t.idProdUser
                WHERE (t.idUser = :idUser OR t.idUserDesejado = :idUser)
                AND (t.Status = 1 OR t.Status = -1);
            ");
            $stmt->bindParam(':idUser', $idUser);
            $stmt->execute();
            /**
             * @var array<int, array{
             *     idProdDesejado: int,
             *     idProdUser: int,
             *     status: string,
             *     nomeProdutoDesejado: string,
             *     nomeProdutoUser: string,
             *     descricaoProdutoDesejado: string,
             *     descricaoProdutoUser: string,
             *     imgProdutoDesejado: string,
             *     imgProdutoUser: string,
             *     categoriaProdutoDesejado: string,
             *     categoriaProdutoUser: string
             * }>|false $result
             */
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $_SESSION['modal'] = [
                'msg' =>'Erro ao consultar trocas: '. $e->getMessage(),
                'statuscode' => 404
            ];
            exit;
        }
    }

    public function realizarTroca():void
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

    public function solicitarTroca():void
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
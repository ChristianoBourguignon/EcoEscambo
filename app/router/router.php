<?php

use app\controllers\ProductsController;

function load(string $controller, string $action):mixed
{
    try {
        // se controller existe
        $controllerNamespace = "app\\controllers\\{$controller}";

        if (!class_exists($controllerNamespace)) {
            throw new Exception("O controller {$controller} não existe");
        }

        $controllerInstance = new $controllerNamespace();

        if (!method_exists($controllerInstance, $action)) {
            throw new Exception(
                "O método {$action} não existe no controller {$controller}"
            );
        }

        return $controllerInstance->$action((object) $_REQUEST);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return NULL;
}

$router = [
        "GET" =>[
        "/EcoEscambo/" => function () {
            return load("HomeController", "index");
        },
        "/EcoEscambo/produtos" => function () {
            return load("ProductsController", "index");
        },
        "/EcoEscambo/sobre" => function () {
            return load("AboutController", "index");
        },
        "/produtos" => function () {
            return load("ProductsController", "index");
        },
        "/EcoEscambo/dashboard" => function() {
            return load("userController", "index");
        },
        "/EcoEscambo/deslogar" => function () {
            return load("userController", "deslogar");
        },
        "/EcoEscambo/trocas" => function (){
            return load("userController","trocas");
        },
        "/EcoEscambo/404" => function (){
            return load("HomeController","notFound");
        },
        "/EcoEscambo/icon" => function(){
            $file = dirname(__DIR__,2) . "/app/static/img/Logo.ico";
            header("Content-Type: image/x-icon");
            readfile($file);
            http_response_code(200);
            exit;
        },
        "/EcoEscambo/buscarProdutos" => function(){
            if (
                !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
            ) {
                http_response_code(403);
                $_SESSION['modal'] = [
                    'msg' => "Sem acesso a esse conteúdo",
                    'statuscode' => 403
                ];
                header("location:" . BASE . "/404");
                exit;
            }
            header('Content-Type: application/json');
            $offset = filter_input(INPUT_GET,'offset',FILTER_SANITIZE_NUMBER_INT);
            $idUser = filter_input(INPUT_GET,'idUser',FILTER_SANITIZE_NUMBER_INT);
            if(!$idUser){
                $idUser = NULL;
            }
            $produtos = (new ProductsController)->buscarProdutos($idUser, $offset);
            echo json_encode($produtos);
            exit;
        }
    ],
    "POST" => [
        "/EcoEscambo/logar" => function () {
            return load("userController", "logar");
        },
        "/EcoEscambo/criarConta" => function (){
            return load("userController","criarConta");
        },
        "/EcoEscambo/solicitarTroca" => function (){
            return load("userController","solicitarTroca");
        },
        "/EcoEscambo/realizarTroca" => function (){
            return load("userController", "realizarTroca");
        },
        "/EcoEscambo/cadastrarProduto" => function (){
            return load("ProductsController", "cadastrarProdutos");
        },
        "/EcoEscambo/alterarProduto" => function (){
            return load("ProductsController", "alterarProduto");
        },
        "/EcoEscambo/excluirProduto" => function (){
            return load("ProductsController", "excluirProduto");
        },
    ],
];
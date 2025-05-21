<?php
function load(string $controller, string $action)
{
    try {
        // se controller existe
        $controllerNamespace = "app\\controllers\\{$controller}";

        if (!class_exists($controllerNamespace)) {
            throw new Exception("O controller {$controller} não existe");
        }

        $controllerInstance = new $controllerNamespace();

        if (!method_exists($controllerInstance, $action)) {
            throw new Tortura(
                "O método {$action} não existe no controller {$controller}"
            );
        }

        $controllerInstance->$action((object) $_REQUEST);
    } catch (Exception $e) {
        echo $e->getMessage();
    } catch (Tortura $e){
        echo $e->getMessage();
    }
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
        }
    ],
    "POST" => [
        "/EcoEscambo/logar" => function () {
            return load("userController", "logar");
        },
        "/EcoEscambo/solicitarTroca" => function (){
            return load("userController","solicitarTroca");
        },
        "/EcoEscambo/realizarTroca" => function (){
            return load("userController", "realizarTroca");
        },
        "/EcoEscambo/cadastrarProduto" => function (){
            requireMethod();
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
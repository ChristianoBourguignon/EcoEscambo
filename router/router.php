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
            throw new Exception(
                "O método {$action} não existe no controller {$controller}"
            );
        }

        $controllerInstance->$action((object) $_REQUEST);
    } catch (Exception $e) {
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
        }
    ],
    "POST" => [
        "/EcoEscambo/logar" => function () {
            return load("userController", "logar");
        },
    ],
];
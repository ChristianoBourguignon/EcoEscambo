<?php
require_once "config.php";
require_once "vendor/autoload.php";
require_once "app/router/router.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
    $request = $_SERVER["REQUEST_METHOD"];

    if (!isset($router[$request])) {
        throw new Exception("A rota não existe");
    }

    if (!array_key_exists($uri, $router[$request])) {
        throw new Exception("A rota não existe");
    }

    $controller = $router[$request][$uri];
    $controller();
} catch (Exception $e) {
    $_SESSION['modal'] = [
        'msg' => "Página não encontrada",
        'statuscode' => 404
    ];
    header("location: ". BASE . '/404');
    exit;
}
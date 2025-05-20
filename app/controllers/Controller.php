<?php
namespace app\controllers;

use League\Plates\Engine;

class Controller
{
    public static function view(string $view, array $data = [])
    {
        try {
            $viewsPath = dirname(__FILE__, 2) . "/views";

            if (!file_exists($viewsPath . DIRECTORY_SEPARATOR . $view . ".php")) {
                throw new \Exception("A view {$view} não existe");
            }

            $templates = new Engine($viewsPath);
            echo $templates->render($view, $data);
        } catch (\Exception $e) {
            $_SESSION['modal'] = [
                'msg' => $e->getMessage(),
                'statuscode' => 404
            ];
            header("Location: " . BASE);
            exit;
        }
    }
}
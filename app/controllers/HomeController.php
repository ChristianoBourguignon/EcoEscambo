<?php
namespace app\controllers;
use app\controllers\Controller;

class HomeController
{
    public function index()
    {
        Controller::view("home");
    }
    public function notFound()
    {
        Controller::view("404");
    }
}
<?php
namespace app\controllers;
use app\controllers\Controller;

class HomeController
{
    public function index():void
    {
        Controller::view("home");
    }
    public function notFound():void
    {
        Controller::view("404");
    }
}
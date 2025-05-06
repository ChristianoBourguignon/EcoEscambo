<?php
namespace app\controllers;
use app\controllers\Controller;

class ProductsController
{
    public function index()
    {
        Controller::view("produtos");
    }
}
<?php
namespace app\controllers;
use app\controllers\Controller;


class DashboardController
{
    public function index()
    {

        Controller::view("dashboard");
    }
}
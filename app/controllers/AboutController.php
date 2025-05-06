<?php
namespace app\controllers;
use app\controllers\Controller;


class AboutController
{
    public function index()
    {

        Controller::view("sobre");
    }
}
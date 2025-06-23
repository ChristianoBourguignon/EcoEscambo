<?php
namespace app\controllers;
use app\controllers\Controller;


class AboutController
{
    public function index(): void
    {

        Controller::view("sobre");
    }
}
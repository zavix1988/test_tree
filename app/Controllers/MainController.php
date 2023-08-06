<?php

namespace App\Controllers;

use Core\Base\AbstractController;

class MainController extends AbstractController
{
    public function indexAction()
    {
        $this->getView();
    }
}
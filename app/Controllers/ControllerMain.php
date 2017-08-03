<?php
namespace app\Controllers;

use app\core\Controller;

class ControllerMain extends Controller
{
    public function actionIndex()
    {
        $this->view->generate('main.php', 'template.php');
    }
}

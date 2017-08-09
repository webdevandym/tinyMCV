<?php

namespace app\Controllers;

use app\core\Controller;

class Controller404 extends Controller
{
    public function actionIndex(\app\Helper\Http\Request $request)
    {
        $this->view->generate('', '404.php');
    }
}

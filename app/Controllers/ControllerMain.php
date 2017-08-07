<?php

namespace app\Controllers;

use app\core\Controller;
use app\core\View;
use app\Models\ModelMain;

class ControllerMain extends Controller
{
    public function __construct()
    {
        $this->model = new ModelMain();
        $this->view = new View();
    }

    public function actionIndex()
    {
        $data = $this->model->get_data();
        $this->{$data->page}($data);
    }

    private function main(\stdClass $data)
    {
        $this->view->generate('main.php', 'maintemplate.php', $data);
    }

    private function view(\stdClass $data)
    {
        $this->view->generate('view.php', 'viewtemplate.php', $data);
    }
}

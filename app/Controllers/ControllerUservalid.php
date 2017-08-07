<?php

namespace app\Controllers;

use app\core\Controller;
use app\Models\ModelUservalid;

class ControllerUservalid extends Controller
{
    public function __construct()
    {
        $this->model = new ModelUservalid();
    }

    public function actionValidate()
    {
        // $result = new \StdClass();
        // $result = $this->model->get_data($this->getParams());
        $p = $this->getParams();
        print_r($p);
    }
}
